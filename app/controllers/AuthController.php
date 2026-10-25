<?php
class AuthController extends Controller {
    
    public function showLogin() { 
        $this->view('auth/login'); 
    }

    public function showRegister() { 
        $this->view('auth/register'); 
    }

    public function processRegister() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
           $nombre = $_POST['fullname'] ?? '';
           $email = $_POST['email'] ?? '';
           $password = $_POST['password'] ?? '';
           $rol = $_POST['rol'] ?? 'estudiante'; 
           
           // 👇 AGREGAMOS ESTAS DOS LÍNEAS 👇
           $genero = $_POST['gender'] ?? 'O'; 
           $fecha_nacimiento = $_POST['birthdate'] ?? null;
           
           // Manejo de la imagen de perfil
           $avatar_nombre = 'default.png';
           if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
               $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
               $avatar_nombre = uniqid('user_') . '.' . $ext;
               $uploadDir = 'public/uploads/avatars/';
               if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
               move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadDir . $avatar_nombre);
           }

           $userModel = new UserModel();
           $hashed_password = password_hash($password, PASSWORD_DEFAULT);
           
           // 👇 ACTUALIZAMOS LA LLAMADA PARA ENVIAR LOS DATOS 👇
           $registroExitoso = $userModel->createUser($nombre, $email, $hashed_password, $rol, $avatar_nombre, $genero, $fecha_nacimiento); 

            if ($registroExitoso) {
                header("Location: " . BASE_URL . "index.php?controller=Auth&action=showLogin");
                exit();
            } else {
                $this->view('auth/register', ['error' => 'Error al registrar el usuario.']);
            }
        }
    }

    public function processLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $userModel = new UserModel();
            $user = $userModel->getUserByEmail($email);

            if ($user) {
                if ($user['estado'] === 'bloqueado') {
                    $this->view('auth/login', ['error' => 'Tu cuenta está bloqueada. Contacta al administrador.']);
                    return;
                }

                if (password_verify($password, $user['password'])) {
                    $userModel->updateFailedAttempts($user['id'], 0);
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['rol'] = $user['rol'];
                    $_SESSION['nombre'] = $user['nombre_completo'];
                    // NUEVO: Guardamos el avatar en la sesión
                    $_SESSION['avatar'] = $user['avatar']; 
                    
                    header("Location: " . BASE_URL . "index.php?controller=Home&action=index");
                    exit();
                } else {
                    $intentos = $user['intentos_fallidos'] + 1;
                    $userModel->updateFailedAttempts($user['id'], $intentos);
                    
                    if ($intentos >= 3) {
                        $userModel->blockUser($user['id']);
                        $this->view('auth/login', ['error' => 'Contraseña incorrecta. Has alcanzado 3 intentos. Cuenta BLOQUEADA.']);
                    } else {
                        $this->view('auth/login', ['error' => 'Contraseña incorrecta. Intento ' . $intentos . ' de 3.']);
                    }
                }
            } else {
                $this->view('auth/login', ['error' => 'El correo no está registrado.']);
            }
        }
    }

    // --- NUEVA FUNCIÓN PARA CERRAR SESIÓN ---
    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header("Location: " . BASE_URL . "index.php?controller=Home&action=index");
        exit();
    }
}