<?php
class AuthMiddleware {
    
    // Recibe los roles permitidos (ej. ['estudiante', 'instructor'])
    public static function checkAccess($allowedRoles = []) {
        // 1. Verificar si hay sesión iniciada
        if (!isset($_SESSION['user_id'])) {
            // Si no hay sesión, lo pateamos al login
            header("Location: " . BASE_URL . "index.php?controller=Auth&action=showLogin");
            exit();
        }

        // 2. Verificar si el rol del usuario está permitido
        if (!empty($allowedRoles) && !in_array($_SESSION['rol'], $allowedRoles)) {
            // Si su rol no está permitido (ej. un estudiante quiere entrar a admin), lo mandamos al home
            header("Location: " . BASE_URL . "index.php?controller=Home&action=index");
            exit();
        }
    }
}