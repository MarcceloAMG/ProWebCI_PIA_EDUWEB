<?php
class Controller {
    public function view($view, $data = []) {
        extract($data);
        
        // __DIR__ busca en 'core' y sube a 'app/view'
        $viewFile = __DIR__ . '/../app/view/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("Error: La vista '$view' no existe.");
        }
    }

        public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header("Location: " . BASE_URL . "index.php?controller=Home&action=index");
        exit();
    }
}