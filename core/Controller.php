<?php
class Controller {
    public function view($view, $data = []) {
        extract($data);
        
        $viewFile = __DIR__ . '/../app/view/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("Error: La vista '$view' no existe.");
        }
    }
}