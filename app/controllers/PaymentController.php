<?php
class PaymentController extends Controller {
    private string $apiUrl;

    public function __construct(private CourseModel $courseModel, ?string $apiUrl = null) {
        $this->apiUrl = $apiUrl ?? (PAYPAL_MODE === 'sandbox'
            ? 'https://api-m.sandbox.paypal.com'
            : 'https://api-m.paypal.com');
    }

    public function createOrder(): void {
        $request = $this->checkoutRequest();
        $courseId = $request['course_id'];
        $studentId = (int) $_SESSION['user_id'];

        try {
            $pending = &$_SESSION['paypal_orders'][PAYPAL_CURRENCY][$courseId];
            if (($pending['student'] ?? null) === $studentId && !empty($pending['capture_started'])) {
                $this->finishOrder($courseId, $pending);
            }
            if ($this->courseModel->isEnrolled($studentId, $courseId)) {
                $this->confirmed();
            }
            $course = $this->courseModel->getCourseById($courseId);
            if (!$course || (int) $course['activo'] !== 1 || (float) $course['costo'] <= 0) {
                $this->reply(['error' => 'El curso no está disponible para pagar con PayPal.'], 422);
            }
            $amount = number_format((float) $course['costo'], 2, '.', '');
            if (($pending['student'] ?? null) !== $studentId || ($pending['amount'] ?? null) !== $amount) {
                $pending = [
                    'student' => $studentId,
                    'amount' => $amount,
                    'create_key' => bin2hex(random_bytes(16)),
                    'capture_key' => bin2hex(random_bytes(16)),
                ];
            }
            if (empty($pending['id'])) {
                $token = $this->accessToken();
                [$code, $order] = $this->orderRequest('/v2/checkout/orders', $token, [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [[
                        'reference_id' => 'curso-' . $courseId,
                        'description' => mb_substr('Curso: ' . $course['titulo'], 0, 127),
                        'amount' => ['currency_code' => PAYPAL_CURRENCY, 'value' => $amount],
                    ]],
                ], $pending['create_key']);
                if ($code < 200 || $code >= 300 || !$this->validOrderId($order['id'] ?? null)) {
                    throw $this->paypalError('crear la orden', $code, $order);
                }
                $pending['id'] = $order['id'];
            }
            $this->reply(['id' => $pending['id']]);
        } catch (Throwable $e) {
            error_log('EduWeb PayPal: ' . $e->getMessage());
            $this->reply(['error' => 'No pudimos preparar o verificar tu pago. Intenta de nuevo.'], 502);
        }
    }

    public function capture(): void {
        $request = $this->checkoutRequest();
        $courseId = $request['course_id'];
        $orderId = $request['order_id'] ?? null;
        $pending = &$_SESSION['paypal_orders'][PAYPAL_CURRENCY][$courseId];
        if (!$this->validOrderId($orderId) || ($pending['id'] ?? null) !== $orderId
            || ($pending['student'] ?? null) !== (int) $_SESSION['user_id']) {
            $this->reply(['error' => 'La orden no corresponde a esta compra.'], 400);
        }

        try {
            $this->finishOrder($courseId, $pending);
        } catch (Throwable $e) {
            error_log('EduWeb PayPal: ' . $e->getMessage());
            $this->reply(['error' => 'No pudimos verificar tu pago. Vuelve a abrir PayPal para consultar el intento.'], 502);
        }
    }

    private function finishOrder(int $courseId, array &$pending): never {
        $retried = !empty($pending['capture_started']);
        if (!empty($pending['credited']) || (!$retried && $this->courseModel->isEnrolled($pending['student'], $courseId))) {
            $this->confirmed();
        }
        $pending['capture_started'] = true;
        $token = $this->accessToken();
        $path = '/v2/checkout/orders/' . rawurlencode($pending['id']);

        $order = [];
        if ($retried) {
            [$code, $order] = $this->orderRequest($path, $token);
            if ($code !== 200) {
                throw $this->paypalError('consultar la orden', $code, $order);
            }
            if (in_array($order['status'] ?? '', ['CREATED', 'PAYER_ACTION_REQUIRED'], true)) {
                $pending['capture_started'] = false;
                $this->reply(['id' => $pending['id']]);
            }
        }
        if (($order['status'] ?? '') !== 'COMPLETED') {
            [$code, $order] = $this->orderRequest($path . '/capture', $token, [], $pending['capture_key']);
            $issue = $order['details'][0]['issue'] ?? null;
            if ($issue === 'INSTRUMENT_DECLINED') {
                $pending['capture_started'] = false;
                $pending['capture_key'] = bin2hex(random_bytes(16));
                $this->reply(['restart' => true, 'error' => 'PayPal rechazó ese método de pago.'], 422);
            }
            if ($issue === 'ORDER_ALREADY_CAPTURED') {
                [$code, $order] = $this->orderRequest($path, $token);
            }
            if ($code < 200 || $code >= 300) {
                throw $this->paypalError('capturar la orden', $code, $order);
            }
        }

        $unit = $order['purchase_units'][0] ?? [];
        $payment = $unit['payments']['captures'][0] ?? [];
        $value = $payment['amount']['value'] ?? null;
        if (($order['id'] ?? null) !== $pending['id'] || ($unit['reference_id'] ?? null) !== 'curso-' . $courseId
            || ($payment['amount']['currency_code'] ?? null) !== PAYPAL_CURRENCY
            || !is_string($value) || !preg_match('/^\d{1,10}(?:\.\d{1,2})?$/D', $value)
            || number_format((float) $value, 2, '.', '') !== $pending['amount']) {
            throw new RuntimeException('La captura no coincide con el curso, la moneda o el importe de la orden.');
        }
        if (($payment['status'] ?? '') === 'PENDING') {
            $this->reply(['error' => 'PayPal todavía está procesando el pago. Abre PayPal de nuevo para consultar esta orden.'], 409);
        }
        if (($payment['status'] ?? '') !== 'COMPLETED') {
            $this->reply(['error' => 'PayPal todavía no confirmó el pago.'], 422);
        }

        $this->courseModel->registerPayPalPurchase($pending['student'], $courseId, $pending['amount']);
        $pending['credited'] = true;
        $this->confirmed();
    }

    private function checkoutRequest(): array {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            header('Allow: POST');
            $this->reply(['error' => 'Método no permitido.'], 405);
        }
        if (empty($_SESSION['user_id']) || ($_SESSION['rol'] ?? '') !== 'estudiante') {
            $this->reply(['error' => 'Inicia sesión como estudiante para comprar.'], 403);
        }
        $csrf = $_SESSION['paypal_csrf'] ?? '';
        if ($csrf === '' || !hash_equals($csrf, $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '')) {
            $this->reply(['error' => 'La sesión de compra expiró. Recarga el curso.'], 403);
        }
        $request = json_decode((string) file_get_contents('php://input'), true);
        if (!is_array($request) || !is_int($request['course_id'] ?? null) || $request['course_id'] <= 0) {
            $this->reply(['error' => 'Curso inválido.'], 400);
        }
        return $request;
    }

    private function accessToken(): string {
        [$code, $body] = $this->request('/v1/oauth2/token', ['Content-Type: application/x-www-form-urlencoded'],
            'grant_type=client_credentials', PAYPAL_CLIENT_ID . ':' . PAYPAL_SECRET);
        if ($code !== 200 || !is_string($body['access_token'] ?? null) || $body['access_token'] === '') {
            throw $this->paypalError('obtener el token OAuth', $code, $body);
        }
        return $body['access_token'];
    }

    private function orderRequest(string $path, string $token, ?array $body = null, ?string $key = null): array {
        $headers = ['Authorization: Bearer ' . $token, 'Content-Type: application/json', 'Prefer: return=representation'];
        if ($key !== null) {
            $headers[] = 'PayPal-Request-Id: ' . $key;
        }
        return $this->request($path, $headers, $body === null ? null : json_encode((object) $body, JSON_THROW_ON_ERROR));
    }

    private function request(string $path, array $headers, ?string $body, ?string $credentials = null): array {
        $curl = curl_init($this->apiUrl . $path);
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_HTTPHEADER => $headers,
        ]);
        if ($body !== null) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        }
        if ($credentials !== null) {
            curl_setopt($curl, CURLOPT_USERPWD, $credentials);
        }
        $response = curl_exec($curl);
        $code = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_errno($curl);
        curl_close($curl);
        if ($response === false) {
            throw new RuntimeException('Error de conexión cURL ' . $error . ' al contactar PayPal.');
        }
        $data = json_decode($response, true);
        return [$code, is_array($data) ? $data : []];
    }

    private function paypalError(string $action, int $code, array $body): RuntimeException {
        $issue = $body['details'][0]['issue'] ?? $body['name'] ?? $body['error'] ?? '';
        $detail = is_string($issue) && preg_match('/^[A-Za-z0-9_]{1,80}$/D', $issue) ? " $issue" : '';
        return new RuntimeException("No se pudo $action (HTTP $code).$detail");
    }

    private function validOrderId(mixed $id): bool {
        return is_string($id) && preg_match('/^[A-Za-z0-9]{5,36}$/D', $id) === 1;
    }

    private function confirmed(): never {
        $this->reply(['redirect' => BASE_URL . 'index.php?controller=Student&action=kardex&pago=exitoso']);
    }

    private function reply(array $body, int $code = 200): never {
        http_response_code($code);
        header('Content-Type: application/json; charset=UTF-8');
        header('Cache-Control: no-store');
        echo json_encode($body, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        exit();
    }
}
