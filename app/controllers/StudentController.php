<?php
class StudentController extends Controller {
    
    private $courseModel;
    private $userModel;
    private $messageModel;
    private $commentModel;
    private $categoryModel; // <-- Nueva propiedad

    // Agrega $categoryModel al constructor
    public function __construct($courseModel, $userModel, $messageModel, $commentModel, $categoryModel) {
        $this->courseModel = $courseModel;
        $this->userModel = $userModel;
        $this->messageModel = $messageModel;
        $this->commentModel = $commentModel;
        $this->categoryModel = $categoryModel; // <-- Asignación
    }


    public function kardex() { 
        AuthMiddleware::checkAccess(['estudiante']); 
        
        // Capturamos los filtros de la URL (si no vienen, están vacíos)
        $fecha_inicio = $_GET['fecha_inicio'] ?? '';
        $fecha_fin = $_GET['fecha_fin'] ?? '';
        $categoria_id = isset($_GET['categoria_id']) ? (int)$_GET['categoria_id'] : 0;
        $completado = $_GET['completado'] ?? '';
        $estatus_curso = $_GET['estatus_curso'] ?? '';

        // Buscamos los cursos aplicando los filtros
        $misCursos = $this->courseModel->getEnrolledCourses(
            $_SESSION['user_id'], 
            $fecha_inicio, 
            $fecha_fin, 
            $categoria_id, 
            $completado, 
            $estatus_curso
        );
        
        // Traemos todas las categorías para el select
        $categorias = $this->categoryModel->getAllCategories();
        
        $this->view('student/kardex', [
            'cursos' => $misCursos,
            'categorias' => $categorias
        ]); 
    }

    public function fase() { 
        AuthMiddleware::checkAccess(['estudiante']); 
        
        $curso_id = isset($_GET['curso_id']) ? (int)$_GET['curso_id'] : 0;
        $nivel_id = isset($_GET['nivel_id']) ? (int)$_GET['nivel_id'] : 0;
        
        if ($curso_id > 0 && $nivel_id == 0) {
            $niveles = $this->courseModel->getLevelsByCourse($curso_id);
            if (empty($niveles)) {
                echo "<script>
                        alert('⚠️ Este curso aún no tiene niveles ni clases registradas.');
                        window.location.href = '" . BASE_URL . "index.php?controller=Student&action=kardex';
                      </script>";
                exit();
            }
            $nivel_id = $niveles[0]['id'];
        }

        $nivel = $this->courseModel->getLevelById($nivel_id);
        if (!$nivel) {
            echo "<script>
                    alert('⚠️ Error: El nivel solicitado no existe.');
                    window.location.href = '" . BASE_URL . "index.php?controller=Student&action=kardex';
                  </script>";
            exit();
        }

        $inscripcion = $this->courseModel->getInscription($nivel['curso_id'], $_SESSION['user_id']);
        $mensajes = $this->messageModel->getChatMessages($nivel['curso_id'], $_SESSION['user_id']);

        $this->view('student/fase', [
            'nivel' => $nivel,
            'inscripcion' => $inscripcion ? $inscripcion : ['id' => 0, 'porcentaje_avance' => 0],
            'mensajes' => $mensajes
        ]); 
    }

    public function completeLevel() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inscripcion_id'])) {
            $inscripcion_id = $_POST['inscripcion_id'];
            $nivel_orden = (int)$_POST['nivel_orden']; 
            
            $curso_id = $this->courseModel->getCourseIdByInscription($inscripcion_id);
            $niveles = $this->courseModel->getLevelsByCourse($curso_id);
            $total_niveles = count($niveles);
            
            $nuevo_porcentaje = ($total_niveles > 0) ? ($nivel_orden / $total_niveles) * 100 : 100;
            if ($nuevo_porcentaje > 100) $nuevo_porcentaje = 100;
            
            $siguiente_nivel = $nivel_orden + 1;
            $this->courseModel->updateProgress($inscripcion_id, $siguiente_nivel, $nuevo_porcentaje);
            
            header("Location: " . BASE_URL . "index.php?controller=Student&action=kardex");
            exit();
        }
    }

    public function sendMessage() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->messageModel->sendMessage(
                $_SESSION['user_id'], 
                $_POST['receptor_id'], 
                $_POST['curso_id'], 
                $_POST['mensaje']
            );
            header("Location: " . BASE_URL . "index.php?controller=Student&action=fase&nivel_id=" . $_POST['nivel_id']);
            exit();
        }
    }

    public function comentarios() { 
        AuthMiddleware::checkAccess(['estudiante']); 
        $this->view('student/comentarios'); 
    }

    public function processComment() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "index.php?controller=Auth&action=showLogin");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $curso_id = $_POST['curso_id'] ?? 0;
            $calificacion = $_POST['calificacion'] ?? 5;
            $comentario = $_POST['review'] ?? '';

            if ($curso_id > 0 && !empty($comentario)) {
                $this->commentModel->createComment($curso_id, $_SESSION['user_id'], $calificacion, $comentario);
            }

            header("Location: " . BASE_URL . "index.php?controller=Student&action=kardex");
            exit();
        }
    }
    
    public function perfil() { 
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "index.php?controller=Auth&action=showLogin");
            exit();
        }
        
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        $this->view('student/perfil', ['user' => $user]); 
    }

    public function updateProfile() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "index.php?controller=Auth&action=showLogin");
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_SESSION['user_id'];
            $nombre = $_POST['fullname'] ?? '';
            $genero = $_POST['gender'] ?? '';
            $fecha = $_POST['birthdate'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $avatar_nombre = $_SESSION['avatar'] ?? 'default.png';

            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
                $avatar_nombre = uniqid('user_') . '.' . $ext;
                $uploadDir = 'public/uploads/avatars/'; 
                
                if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
                
                if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadDir . $avatar_nombre)) {
                    die("Error: No se pudo guardar la imagen. Revisa los permisos de la carpeta.");
                }
            }

            $this->userModel->updateUser($id, $nombre, $genero, $fecha, $email, $password, $avatar_nombre);

            $_SESSION['nombre'] = $nombre;
            $_SESSION['avatar'] = $avatar_nombre;

            header("Location: " . BASE_URL . "index.php?controller=Student&action=perfil");
            exit();
        }
    }
    public function certificado() {
        AuthMiddleware::checkAccess(['estudiante']);
        
        $curso_id = isset($_GET['curso_id']) ? (int)$_GET['curso_id'] : 0;
        
        $datos = $this->courseModel->getCertificateData($_SESSION['user_id'], $curso_id);
        
        if (!$datos || empty($datos['fecha_terminacion'])) {
            header("Location: " . BASE_URL . "index.php?controller=Student&action=kardex");
            exit();
        }
        
        $this->view('student/certificado', ['datos' => $datos]);
    }
}