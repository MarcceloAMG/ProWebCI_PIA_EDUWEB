<?php
class InstructorController extends Controller {
    
    public function ventasResumen() { 
        AuthMiddleware::checkAccess(['instructor']); 
        
        // Obtenemos los cursos del instructor logueado
        $instructor_id = $_SESSION['user_id'];
        $courseModel = new CourseModel();
        $cursos = $courseModel->getCoursesByInstructor($instructor_id);

        // Le pasamos los cursos a la vista
        $this->view('instructor/ventas-resumen', ['cursos' => $cursos]); 
    }

    public function ventasDetalle() { 
        AuthMiddleware::checkAccess(['instructor']); 
        $this->view('instructor/ventas-detalle'); 
    }

    public function cursoCrear() { 
        AuthMiddleware::checkAccess(['instructor']); 
        
        // Obtenemos las categorías para el select del formulario
        $categoryModel = new CategoryModel();
        $categorias = $categoryModel->getAllCategories();
        
        $this->view('instructor/curso-crear', ['categorias' => $categorias]); 
    }

    public function processCurso() {
        AuthMiddleware::checkAccess(['instructor']); 
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $instructor_id = $_SESSION['user_id'];
            $titulo = $_POST['titulo'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $costo = $_POST['price'] ?? 0;

            // Manejo de la imagen de portada
            $imagen_nombre = 'default.jpg';
            if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION);
                $imagen_nombre = uniqid('course_') . '.' . $ext;
                // Asegúrate de tener creada la carpeta public/uploads/
                move_uploaded_file($_FILES['cover']['tmp_name'], '../public/uploads/' . $imagen_nombre);
            }

            $courseModel = new CourseModel();
            $creado = $courseModel->createCourse($instructor_id, $titulo, $descripcion, $costo, $imagen_nombre);

            if ($creado) {
                // Si se crea, recargamos el panel de ventas
                header("Location: " . BASE_URL . "index.php?controller=Instructor&action=ventasResumen");
                exit();
            }
        }
    }
}