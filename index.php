<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// 1. Cargamos configuración
require_once 'config/config.php';

// 2. Cargamos el núcleo (Core) - ¡El orden aquí es vital!
require_once 'core/Database.php';
require_once 'core/Model.php';      
require_once 'core/Controller.php';
require_once 'core/Router.php';


// 3. Cargamos los middlewares
require_once 'app/middleware/AuthMiddleware.php';
require_once 'app/middleware/CorsMiddleware.php';

// 4. Cargamos los modelos
require_once 'app/model/UserModel.php'; 
require_once 'app/model/CourseModel.php';
require_once 'app/model/CategoryModel.php'; 

// 5. Ejecutamos el enrutador
$router = new Router();
$router->run();