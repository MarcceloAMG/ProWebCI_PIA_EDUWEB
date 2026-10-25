<?php
class AdminController extends Controller {
    
    public function usuarios() { 
        AuthMiddleware::checkAccess(['administrador']); 
        $this->view('admin/usuarios'); 
    }

    // Muestra la pantalla con las categorías
    public function categorias() { 
        AuthMiddleware::checkAccess(['administrador']); 
        
        $categoryModel = new CategoryModel();
        $categorias = $categoryModel->getAllCategories();
        
        // Le pasamos el arreglo de categorías a la vista
        $this->view('admin/categories', ['categorias' => $categorias]); 
    } 

    // Procesa el formulario de nueva categoría
    public function processCategory() {
        AuthMiddleware::checkAccess(['administrador']); 
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $creado_por = $_SESSION['user_id']; // El ID del admin que la creó

            $categoryModel = new CategoryModel();
            $categoryModel->createCategory($nombre, $descripcion, $creado_por);

            // Recargamos la página
            header("Location: " . BASE_URL . "index.php?controller=Admin&action=categorias");
            exit();
        }
    }

    public function reportes() { 
        AuthMiddleware::checkAccess(['administrador']); 
        $this->view('admin/reportes'); 
    }
    
    public function comentarios() { 
        AuthMiddleware::checkAccess(['administrador']); 
        $this->view('admin/comentarios'); 
    }
}