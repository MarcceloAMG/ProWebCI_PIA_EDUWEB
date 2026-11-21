<?php
class Router {
    public function run() {
        $controllerName = isset($_GET['controller']) ? ucfirst($_GET['controller']) . 'Controller' : 'HomeController';
        $actionName = isset($_GET['action']) ? $_GET['action'] : 'index';

        $controllerFile = __DIR__ . '/../app/controllers/' . $controllerName . '.php';

        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            
            // --- INYECCIÓN DE DEPENDENCIAS ---
            // Instanciamos los modelos
            $userModel = new UserModel();
            $courseModel = new CourseModel();
            $categoryModel = new CategoryModel();
            $messageModel = new MessageModel();
            $commentModel = new CommentModel();

            // Inyectamos los modelos dependiendo del controlador
            switch ($controllerName) {
                case 'StudentController':
                    // Agrega $categoryModel al final
                    $controller = new StudentController($courseModel, $userModel, $messageModel, $commentModel, $categoryModel);
                    break;
                case 'InstructorController':
                    $controller = new InstructorController($courseModel, $categoryModel, $userModel, $messageModel);
                    break;
                case 'AdminController':
                    $controller = new AdminController($userModel, $categoryModel, $commentModel, $courseModel);
                    break;
                case 'AuthController':
                    $controller = new AuthController($userModel);
                    break;
                case 'ApiController':
                    $controller = new ApiController($courseModel, $categoryModel);
                    break;
                case 'CourseController':
                    $controller = new CourseController($courseModel);
                    break;
                case 'PaymentController':
                    $controller = new PaymentController($courseModel);
                    break;
                case 'HomeController':
                    $controller = new HomeController();
                    break;

                default:
                    $controller = new $controllerName();
                    break;
            }

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