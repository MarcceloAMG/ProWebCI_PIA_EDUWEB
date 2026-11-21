<?php
class InstructorController extends Controller {
    
    private $courseModel;
    private $categoryModel;
    private $userModel;
    private $messageModel;

    public function __construct($courseModel, $categoryModel, $userModel, $messageModel) {
        $this->courseModel = $courseModel;
        $this->categoryModel = $categoryModel;
        $this->userModel = $userModel;
        $this->messageModel = $messageModel;
    }
    
    public function ventasResumen() { 
        AuthMiddleware::checkAccess(['instructor']); 
        
        $instructor_id = $_SESSION['user_id'];
        $cursos = $this->courseModel->getSalesSummaryByInstructor($instructor_id);
        
        $total_global = 0;
        foreach($cursos as $c) {
            $total_global += $c['total_ingresos'];
        }

        $this->view('instructor/ventas-resumen', [
            'cursos' => $cursos,
            'total_global' => $total_global,
            'csrf_curso_estado' => $_SESSION['csrf_curso_estado'] ??= bin2hex(random_bytes(32))
        ]); 
    }

    public function ventasDetalle() { 
        AuthMiddleware::checkAccess(['instructor']); 
        
        $course_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $curso = $this->courseModel->getCourseById($course_id);
        $alumnos = $this->courseModel->getSalesDetailByCourse($course_id);
        
        $total_curso = 0;
        foreach($alumnos as $a) {
            $total_curso += $a['monto'];
        }

        $this->view('instructor/ventas-detalle', [
            'curso' => $curso,
            'alumnos' => $alumnos,
            'total_curso' => $total_curso
        ]); 
    }

    public function cursoCrear() { 
        AuthMiddleware::checkAccess(['instructor']); 
        
        $categorias = $this->categoryModel->getAllCategories();
        
        $this->view('instructor/curso-crear', ['categorias' => $categorias]); 
    }

    public function processCurso() {
        AuthMiddleware::checkAccess(['instructor']); 
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $instructor_id = $_SESSION['user_id'];
            $titulo = $_POST['titulo'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $costo = $_POST['price'] ?? 0;

            $procesarVideo = function($tipo, $link, $file, $prefijo) {
                if ($tipo === 'file' && !empty($file['name'])) {
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $nombre = uniqid($prefijo . '_') . '.' . $ext;
                    $uploadDir = __DIR__ . '/../../public/uploads/videos/'; 
                    if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
                    move_uploaded_file($file['tmp_name'], $uploadDir . $nombre);
                    return $nombre;
                }
                return $link;
            };

            $trailer_file_data = $_FILES['trailer_file'] ?? null;
            $video_trailer = $procesarVideo($_POST['trailer_tipo'], $_POST['trailer_link'] ?? '', $trailer_file_data, 'trailer');

            $imagen_nombre = 'default.jpg';
            if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION);
                $imagen_nombre = uniqid('course_') . '.' . $ext;
                $uploadDir = __DIR__ . '/../../public/uploads/'; 
                move_uploaded_file($_FILES['cover']['tmp_name'], $uploadDir . $imagen_nombre);
            }

            $curso_id = $this->courseModel->createCourse($instructor_id, $titulo, $descripcion, $costo, $imagen_nombre, $video_trailer);

            if ($curso_id) {
                if (isset($_POST['categorias']) && is_array($_POST['categorias'])) {
                    foreach ($_POST['categorias'] as $cat_id) {
                        $this->courseModel->assignCategoryToCourse($curso_id, $cat_id);
                    }
                }

                $niveles_titulos = $_POST['nivel_titulo'] ?? [];
                $niveles_tipos = $_POST['nivel_video_tipo'] ?? [];
                $niveles_links = $_POST['nivel_video_link'] ?? [];
                $niveles_textos = $_POST['nivel_contenido'] ?? [];
                $niveles_pdfs = $_FILES['nivel_pdf'] ?? [];

                foreach ($niveles_titulos as $index => $n_titulo) {
                    $n_tipo = $niveles_tipos[$index];
                    $n_link = $niveles_links[$index];
                    
                    $n_file_data = null;
                    if (isset($_FILES['nivel_video_file'])) {
                        $n_file_data = [
                            'name' => $_FILES['nivel_video_file']['name'][$index],
                            'tmp_name' => $_FILES['nivel_video_file']['tmp_name'][$index],
                            'error' => $_FILES['nivel_video_file']['error'][$index]
                        ];
                    }
                    $video_url_nivel = $procesarVideo($n_tipo, $n_link, $n_file_data, 'nivel');

                    $pdf_nombre = null;
                    if (isset($niveles_pdfs['name'][$index]) && $niveles_pdfs['error'][$index] === UPLOAD_ERR_OK) {
                        $ext_pdf = pathinfo($niveles_pdfs['name'][$index], PATHINFO_EXTENSION);
                        $pdf_nombre = uniqid('pdf_') . '.' . $ext_pdf;
                        $uploadDir = __DIR__ . '/../../public/uploads/'; 
                        move_uploaded_file($niveles_pdfs['tmp_name'][$index], $uploadDir . $pdf_nombre);
                    }

                    $this->courseModel->createLevel(
                        $curso_id, 
                        $n_titulo, 
                        $video_url_nivel, 
                        $niveles_textos[$index], 
                        $pdf_nombre, 
                        $index + 1, 
                        0
                    );
                }

                header("Location: " . BASE_URL . "index.php?controller=Instructor&action=ventasResumen");
                exit();
            }
        }
    }

    public function chat() {
        AuthMiddleware::checkAccess(['instructor']);
        
        $curso_id = isset($_GET['curso_id']) ? (int)$_GET['curso_id'] : 0;
        $alumno_id = isset($_GET['alumno_id']) ? (int)$_GET['alumno_id'] : 0;

        $curso = $this->courseModel->getCourseById($curso_id);
        $alumno = $this->userModel->getUserById($alumno_id);
        $mensajes = $this->messageModel->getChatMessages($curso_id, $alumno_id);

        $this->view('instructor/chat', [
            'curso' => $curso,
            'alumno' => $alumno,
            'mensajes' => $mensajes
        ]);
    }

    public function sendChatMessage() {
        AuthMiddleware::checkAccess(['instructor']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->messageModel->sendMessage(
                $_SESSION['user_id'], 
                $_POST['alumno_id'],  
                $_POST['curso_id'], 
                $_POST['mensaje']
            );
            header("Location: " . BASE_URL . "index.php?controller=Instructor&action=chat&curso_id=" . $_POST['curso_id'] . "&alumno_id=" . $_POST['alumno_id']);
            exit();
        }
    }

    public function toggleCourse() {
        AuthMiddleware::checkAccess(['instructor']);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Allow: POST');
            http_response_code(405);
            exit();
        }
        $token = $_POST['csrf'] ?? null;
        if (!is_string($token) || empty($_SESSION['csrf_curso_estado'])
            || !hash_equals($_SESSION['csrf_curso_estado'], $token)) {
            http_response_code(403);
            exit('Recarga el panel para volver a intentarlo.');
        }
        $course_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $status = $_POST['status'] ?? null;
        if (!$course_id || !in_array($status, ['0', '1'], true)) {
            http_response_code(400);
            exit('Curso o estado inválido.');
        }

        $this->courseModel->toggleCourseStatus($course_id, (int) $status, (int) $_SESSION['user_id']);
        header("Location: " . BASE_URL . "index.php?controller=Instructor&action=ventasResumen");
        exit();
    }
}