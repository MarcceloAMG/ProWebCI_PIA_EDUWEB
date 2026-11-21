<?php
class AdminController extends Controller {
    
    private $userModel;
    private $categoryModel;
    private $commentModel;
    private $courseModel; // <-- Debe estar aquí

    public function __construct($userModel, $categoryModel, $commentModel, $courseModel) {
        $this->userModel = $userModel;
        $this->categoryModel = $categoryModel;
        $this->commentModel = $commentModel;
        $this->courseModel = $courseModel; // <-- Y asignado aquí
    }

    public function usuarios() { 
        AuthMiddleware::checkAccess(['administrador']); 
        $usuarios = $this->userModel->getAllUsers(); 
        $this->view('admin/usuarios', ['usuarios' => $usuarios]); 
    }

    public function processBlockUser() {
        AuthMiddleware::checkAccess(['administrador']); 
        if (isset($_GET['id']) && isset($_GET['estado'])) {
            $this->userModel->toggleUserStatus($_GET['id'], $_GET['estado']);
        }
        header("Location: " . BASE_URL . "index.php?controller=Admin&action=usuarios");
        exit();
    }

    public function categorias() { 
        AuthMiddleware::checkAccess(['administrador']); 
        $categorias = $this->categoryModel->getAllCategories();
        $this->view('admin/categories', ['categorias' => $categorias]); 
    } 

    public function processCategory() {
        AuthMiddleware::checkAccess(['administrador']); 
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->categoryModel->createCategory($_POST['nombre'], $_POST['descripcion'], $_SESSION['user_id']);
            header("Location: " . BASE_URL . "index.php?controller=Admin&action=categorias");
            exit();
        }
    }

    public function deleteCategory() {
        AuthMiddleware::checkAccess(['administrador']); 
        if (isset($_GET['id'])) {
            $this->categoryModel->deleteCategory($_GET['id']);
        }
        header("Location: " . BASE_URL . "index.php?controller=Admin&action=categorias");
        exit();
    }

    public function comentarios() { 
        AuthMiddleware::checkAccess(['administrador']); 
        $comentarios = $this->commentModel->getAllComments();
        $this->view('admin/comentarios', ['comentarios' => $comentarios]); 
    }

    public function deleteComment() {
        AuthMiddleware::checkAccess(['administrador']); 
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comentario_id'])) {
            $this->commentModel->deleteComment($_POST['comentario_id'], $_POST['causa']);
        }
        header("Location: " . BASE_URL . "index.php?controller=Admin&action=comentarios");
        exit();
    }

    public function reportes() { 
        AuthMiddleware::checkAccess(['administrador']); 
        
        // Obtenemos el tipo de reporte (por defecto instructores)
        $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'instructores';
        
        if ($tipo === 'estudiantes') {
            $datos = $this->userModel->getStudentReport();
        } else {
            $datos = $this->userModel->getInstructorReport();
        }

        $this->view('admin/reportes', [
            'tipo' => $tipo,
            'datos' => $datos
        ]); 
    }



// Agrega estas dos funciones al final
    public function cursos() {
        AuthMiddleware::checkAccess(['administrador']);
        $cursos = $this->courseModel->getAllCoursesForAdmin();
        $this->view('admin/cursos', ['cursos' => $cursos]);
    }

    public function toggleCourseStatus() {
        AuthMiddleware::checkAccess(['administrador']);
        if (isset($_GET['id']) && isset($_GET['status'])) {
            $this->courseModel->toggleCourseStatus($_GET['id'], $_GET['status']);
        }
        header("Location: " . BASE_URL . "index.php?controller=Admin&action=cursos");
        exit();
    }
}