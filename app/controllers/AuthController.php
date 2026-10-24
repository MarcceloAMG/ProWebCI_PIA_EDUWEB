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

            // Contraseña cifrada (Rúbrica)
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $userModel = new UserModel();
            $registroExitoso = $userModel->createUser($nombre, $email, $hashed_password, 'estudiante');

            if ($registroExitoso) {
                // Si se registra, lo mandamos al login
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
                // Validar si está bloqueado
                if ($user['estado'] === 'bloqueado') {
                    $this->view('auth/login', ['error' => 'Tu cuenta está bloqueada. Contacta al administrador.']);
                    return;
                }

                // Validar contraseña
                if (password_verify($password, $user['password'])) {
                    $userModel->updateFailedAttempts($user['id'], 0);
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['rol'] = $user['rol'];
                    $_SESSION['nombre'] = $user['nombre_completo'];
                    
                    header("Location: " . BASE_URL . "index.php?controller=Home&action=index");
                    exit();
                } else {
                    // Sumar intento fallido
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
}