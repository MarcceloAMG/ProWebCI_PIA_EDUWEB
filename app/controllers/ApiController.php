<?php
class ApiController extends Controller {
    
    public function __construct() {
        // Ejecutamos el middleware de CORS antes que cualquier acción
        CorsMiddleware::handle();
        // Declaramos que la respuesta siempre será JSON
        header("Content-Type: application/json; charset=UTF-8");
    }

      // Endpoint 1: GET - Obtener cursos (con filtros)
    public function getCursos() {
        $titulo = $_GET['titulo'] ?? '';
        $categoria_id = isset($_GET['categoria_id']) ? (int)$_GET['categoria_id'] : 0;

        $courseModel = new CourseModel();
        $cursos = $courseModel->getAllActiveCourses($titulo, $categoria_id);

        // Siempre devolvemos 200, aunque esté vacío, para que JS lo maneje fácil
        $this->sendResponse(200, ["status" => "success", "data" => $cursos]);
    }

    // Endpoint 3: GET - Obtener categorías
    public function getCategorias() {
        $categoryModel = new CategoryModel();
        $cats = $categoryModel->getAllCategories();
        $this->sendResponse(200, ["status" => "success", "data" => $cats]);
    }

    // Endpoint 2: POST - Crear un nuevo curso
    public function crearCurso() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(405, ["error" => "Método no permitido. Usa POST."]);
            return;
        }

        // Recibimos los datos (pueden venir como JSON o como Form-Data)
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Validación en el servidor (Requisito de rúbrica)
        $titulo = $input['titulo'] ?? null;
        $descripcion = $input['descripcion'] ?? null;
        $costo = $input['costo'] ?? 0;
        $instructor_id = 1; // Simulamos que el instructor es el ID 1

        if (empty($titulo) || empty($descripcion)) {
            $this->sendResponse(400, ["error" => "Datos incompletos. 'titulo' y 'descripcion' son obligatorios."]);
            return;
        }

        $courseModel = new CourseModel();
        $creado = $courseModel->createCourse($instructor_id, $titulo, $descripcion, $costo);

        if ($creado) {
            $this->sendResponse(201, ["status" => "success", "message" => "Curso creado correctamente."]);
        } else {
            $this->sendResponse(500, ["error" => "Error interno al guardar el curso."]);
        }
    }

    // Función auxiliar para enviar respuestas JSON con código HTTP
    private function sendResponse($statusCode, $data) {
        http_response_code($statusCode);
        echo json_encode($data);
        exit();
    }
}