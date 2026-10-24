<?php
class CourseController extends Controller {
    
    public function detail() {
        // Carga la vista app/view/public/course-detail.php
        $this->view('public/course-detail');
    }
}