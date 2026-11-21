<?php
class StudentController extends Controller {
    
    public function kardex() { 
        AuthMiddleware::checkAccess(['estudiante']); 
        
        $courseModel = new CourseModel();
        $misCursos = $courseModel->getEnrolledCourses($_SESSION['user_id']);
        
        $this->view('student/kardex', ['cursos' => $misCursos]); 
    }

    public function fase() { 
        AuthMiddleware::checkAccess(['estudiante']); 
        
        $courseModel = new CourseModel();
        $curso_id = isset($_GET['curso_id']) ? (int)$_GET['curso_id'] : 0;
        $nivel_id = isset($_GET['nivel_id']) ? (int)$_GET['nivel_id'] : 0;
        
        // Si venimos del Kardex (solo con curso_id)
        if ($curso_id > 0 && $nivel_id == 0) {
            $niveles = $courseModel->getLevelsByCourse($curso_id);
            
            // Si no hay niveles, lanzamos una alerta de JS y lo regresamos al Kardex
            if (empty($niveles)) {
                echo "<script>
                        alert('⚠️ Este curso aún no tiene niveles ni clases registradas. Por favor, contacta al instructor.');
                        window.location.href = '" . BASE_URL . "index.php?controller=Student&action=kardex';
                      </script>";
                exit();
            }
            
            // Si sí hay niveles, tomamos el ID del primero
            $nivel_id = $niveles[0]['id'];
        }

        // Buscamos la info del nivel
        $nivel = $courseModel->getLevelById($nivel_id);
        
        // Si el nivel no existe (porque le pasaron un ID falso por URL)
        if (!$nivel) {
            echo "<script>
                    alert('⚠️ Error: El nivel solicitado no existe.');
                    window.location.href = '" . BASE_URL . "index.php?controller=Student&action=kardex';
                  </script>";
            exit();
        }

        // Obtenemos el ID de la inscripción
        $stmt = (new Database())->getConnection()->prepare("SELECT id, porcentaje_avance FROM inscripciones WHERE estudiante_id = :student AND curso_id = :curso");
        $stmt->execute([':student' => $_SESSION['user_id'], ':curso' => $nivel['curso_id']]);
        $inscripcion = $stmt->fetch();

        // Cargamos los mensajes (Chat)
        $messageModel = new MessageModel();
        $mensajes = $messageModel->getChatMessages($nivel['curso_id'], $_SESSION['user_id']);

        // Si todo está bien, cargamos la vista
        $this->view('student/fase', [
            'nivel' => $nivel,
            'inscripcion' => $inscripcion ? $inscripcion : ['id' => 0, 'porcentaje_avance' => 0],
            'mensajes' => $mensajes
        ]); 
    }

    public function completeLevel() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inscripcion_id'])) {
            $courseModel = new CourseModel();
            
            // Calculamos el nuevo porcentaje basado en el nivel actual
            $porcentaje_actual = (float)$_POST['porcentaje_actual'];
            $nuevo_porcentaje = $porcentaje_actual + 34; // Sumamos aprox 33.3% por nivel
            
            if ($nuevo_porcentaje > 100) $nuevo_porcentaje = 100;
            
            $courseModel->updateProgress($_POST['inscripcion_id'], $_POST['nivel_orden'], $nuevo_porcentaje);
            header("Location: " . BASE_URL . "index.php?controller=Student&action=kardex");
            exit();
        }
    }

    public function sendMessage() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $messageModel = new MessageModel();
            $messageModel->sendMessage(
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
                $commentModel = new CommentModel();
                $commentModel->createComment($curso_id, $_SESSION['user_id'], $calificacion, $comentario);
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
        
        $userModel = new UserModel();
        $user = $userModel->getUserById($_SESSION['user_id']);
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

            $userModel = new UserModel();
            $userModel->updateUser($id, $nombre, $genero, $fecha, $email, $password, $avatar_nombre);

            $_SESSION['nombre'] = $nombre;
            $_SESSION['avatar'] = $avatar_nombre;

            header("Location: " . BASE_URL . "index.php?controller=Student&action=perfil");
            exit();
        }
    }
}