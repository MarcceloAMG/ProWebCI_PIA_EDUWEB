<?php
class CourseController extends Controller {
    
    public function detail() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $courseModel = new CourseModel();
        
        $curso = $courseModel->getCourseById($id);
        if (!$curso) {
            header("Location: " . BASE_URL . "index.php?controller=Home&action=index");
            exit();
        }

        $niveles = $courseModel->getLevelsByCourse($id);
        
        // Verificamos si el usuario está logueado y es estudiante para ver si está inscrito
        $isEnrolled = false;
        if (isset($_SESSION['user_id']) && $_SESSION['rol'] === 'estudiante') {
            $isEnrolled = $courseModel->isEnrolled($_SESSION['user_id'], $id);
        }

        $this->view('public/course-detail', [
            'curso' => $curso,
            'niveles' => $niveles,
            'isEnrolled' => $isEnrolled
        ]);
    }

    public function enroll() {
        // Solo estudiantes logueados pueden inscribirse
        if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'estudiante') {
            header("Location: " . BASE_URL . "index.php?controller=Auth&action=showLogin");
            exit();
        }

        $course_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $student_id = $_SESSION['user_id'];

        $courseModel = new CourseModel();
        if (!$courseModel->isEnrolled($student_id, $course_id)) {
            $courseModel->enroll($student_id, $course_id);
        }

        // Recargamos la misma página de detalle, pero ya aparecerá como inscrito
        header("Location: " . BASE_URL . "index.php?controller=Course&action=detail&id=" . $course_id);
        exit();
    }
}