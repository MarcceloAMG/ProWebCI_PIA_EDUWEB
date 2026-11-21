<?php
class CourseController extends Controller {
    
    private $courseModel;

    public function __construct($courseModel) {
        $this->courseModel = $courseModel;
    }

    public function detail() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        $curso = $this->courseModel->getCourseById($id);
        if (!$curso) {
            header("Location: " . BASE_URL . "index.php?controller=Home&action=index");
            exit();
        }

        $niveles = $this->courseModel->getLevelsByCourse($id);
        
        $isEnrolled = false;
        if (isset($_SESSION['user_id']) && $_SESSION['rol'] === 'estudiante') {
            $isEnrolled = $this->courseModel->isEnrolled($_SESSION['user_id'], $id);
        }
        
        $categorias = $this->courseModel->getCategoriesByCourse($id);
        $promedio = $this->courseModel->getAverageRating($id);
        // NUEVO: Traemos todos los comentarios
        $comentarios = $this->courseModel->getCommentsByCourse($id);

        $this->view('public/course-detail', [
            'curso' => $curso,
            'niveles' => $niveles,
            'isEnrolled' => $isEnrolled,
            'categorias' => $categorias,
            'promedio' => $promedio,
            'comentarios' => $comentarios // <-- Lo pasamos a la vista
        ]);
    }

    public function enroll() {
        if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'estudiante') {
            header("Location: " . BASE_URL . "index.php?controller=Auth&action=showLogin");
            exit();
        }

        $course_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $student_id = $_SESSION['user_id'];

        if (!$this->courseModel->isEnrolled($student_id, $course_id)) {
            $this->courseModel->enroll($student_id, $course_id);
        }

        header("Location: " . BASE_URL . "index.php?controller=Course&action=detail&id=" . $course_id);
        exit();
    }
}