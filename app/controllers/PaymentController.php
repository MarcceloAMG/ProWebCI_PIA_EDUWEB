<?php
class PaymentController extends Controller {
    
    private $courseModel;

    public function __construct($courseModel) {
        $this->courseModel = $courseModel;
    }

    public function capture() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "index.php?controller=Auth&action=showLogin");
            exit();
        }

        $course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;
        $order_id = $_GET['order_id'] ?? '';

        if (empty($course_id) || empty($order_id)) {
            die("Error: Faltan datos de la transacción.");
        }

        $curso = $this->courseModel->getCourseById($course_id);

        if (!$curso) {
            die("Error: El curso no existe.");
        }

        if ($this->verifyPayPalOrder($order_id, $curso['costo'])) {
            
            $student_id = $_SESSION['user_id'];

            if (!$this->courseModel->isEnrolled($student_id, $course_id)) {
                $inscripcion_id = $this->courseModel->enroll($student_id, $course_id);
                
                if ($inscripcion_id) {
                    $this->courseModel->savePayment($inscripcion_id, $curso['costo'], 'PayPal');
                }
            }

            header("Location: " . BASE_URL . "index.php?controller=Student&action=kardex&pago=exitoso");
            exit();
        } else {
            header("Location: " . BASE_URL . "index.php?controller=Course&action=detail&id=" . $course_id . "&error=pago_fallido");
            exit();
        }
    }

    private function verifyPayPalOrder($order_id, $expected_amount) {
        $mode = PAYPAL_MODE === 'sandbox' ? 'sandbox' : 'live';
        $url = "https://api-m.$mode.paypal.com/v2/checkout/orders/$order_id/capture";
        
        $client_id = PAYPAL_CLIENT_ID;
        $secret = PAYPAL_SECRET;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{}"); 
        curl_setopt($ch, CURLOPT_USERPWD, "$client_id:$secret");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code == 200 || $http_code == 201) {
            $data = json_decode($response, true);
            if (isset($data['status']) && $data['status'] === 'COMPLETED') {
                return true;
            }
        }
        return false;
    }
}