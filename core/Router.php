<?php
class Router {
    public function run() {
        $controllerName = isset($_GET['controller']) ? ucfirst($_GET['controller']) . 'Controller' : 'HomeController';
        $actionName = isset($_GET['action']) ? $_GET['action'] : 'index';

        // __DIR__ busca siempre en la carpeta 'core' y de ahí sube a 'app'
        $controllerFile = __DIR__ . '/../app/controllers/' . $controllerName . '.php';

        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controller = new $controllerName();
            if (method_exists($controller, $actionName)) {
                $controller->$actionName();
            } else {
                http_response_code(404);
                echo "Error 404: La acción '$actionName' no existe.";
            }
        } else {
            http_response_code(404);
            echo "Error 404: El controlador '$controllerName' no existe.";
        }
    }
}