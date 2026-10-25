<?php
class StudentController extends Controller {
    public function kardex() { 
        AuthMiddleware::checkAccess(['estudiante']); 
        $this->view('student/kardex'); 
    }
    public function fase() { 
        AuthMiddleware::checkAccess(['estudiante']); 
        $this->view('student/fase'); 
    }
    public function comentarios() { 
        AuthMiddleware::checkAccess(['estudiante']); 
        $this->view('student/comentarios'); 
    }
    
    public function perfil() { 
        // Solo validamos que haya una sesión iniciada, no importa el rol
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

            // Mantenemos el avatar actual por defecto
            $avatar_nombre = $_SESSION['avatar'] ?? 'default.png';

            // Si subió una foto nueva
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
                $avatar_nombre = uniqid('user_') . '.' . $ext;
                
                // 👇 ESTA ES LA LÍNEA QUE DEBEMOS CORREGIR 👇
                $uploadDir = 'public/uploads/avatars/'; 
                
                if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
                
                // Intentamos mover el archivo y si falla, mostramos un error
                if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadDir . $avatar_nombre)) {
                    die("Error: No se pudo guardar la imagen. Revisa los permisos de la carpeta.");
                }
            }

            $userModel = new UserModel();
            $userModel->updateUser($id, $nombre, $genero, $fecha, $email, $password, $avatar_nombre);

            // Actualizamos la sesión
            $_SESSION['nombre'] = $nombre;
            $_SESSION['avatar'] = $avatar_nombre;

            // Recargamos la página de perfil
            header("Location: " . BASE_URL . "index.php?controller=Student&action=perfil");
            exit();
        }
    }
}