<?php
class ApiController extends Controller {
    
    private $courseModel;
    private $categoryModel;

    public function __construct($courseModel, $categoryModel) {
        CorsMiddleware::handle();
        header("Content-Type: application/json; charset=UTF-8");
        $this->courseModel = $courseModel;
        $this->categoryModel = $categoryModel;
    }

    public function getCursos() {
        $titulo = $_GET['titulo'] ?? '';
        $categoria_id = isset($_GET['categoria_id']) ? (int)$_GET['categoria_id'] : 0;
        $orden = $_GET['orden'] ?? 'recientes'; 

        $cursos = $this->courseModel->getAllActiveCourses($titulo, $categoria_id, $orden);

        $this->sendResponse(200, ["status" => "success", "data" => $cursos]);
    }

    public function getCategorias() {
        $cats = $this->categoryModel->getAllCategories();
        $this->sendResponse(200, ["status" => "success", "data" => $cats]);
    }

    public function crearCurso() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(405, ["error" => "Método no permitido. Usa POST."]);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        $titulo = $input['titulo'] ?? null;
        $descripcion = $input['descripcion'] ?? null;
        $costo = $input['costo'] ?? 0;
        $instructor_id = 1; 

        if (empty($titulo) || empty($descripcion)) {
            $this->sendResponse(400, ["error" => "Datos incompletos. 'titulo' y 'descripcion' son obligatorios."]);
            return;
        }

        // Como createCourse ahora pide video e imagen, ponemos valores por defecto
        $creado = $this->courseModel->createCourse($instructor_id, $titulo, $descripcion, $costo, 'default.jpg', 'https://www.youtube.com/embed/dQw4w9WgXcQ');

        if ($creado) {
            $this->sendResponse(201, ["status" => "success", "message" => "Curso creado correctamente."]);
        } else {
            $this->sendResponse(500, ["error" => "Error interno al guardar el curso."]);
        }
    }

    private function sendResponse($statusCode, $data) {
        http_response_code($statusCode);
        echo json_encode($data);
        exit();
    }
}