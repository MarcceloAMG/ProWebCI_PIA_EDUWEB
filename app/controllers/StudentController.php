<?php
class StudentController extends Controller {
    public function kardex() { 
        AuthMiddleware::checkAccess(['estudiante']); 
        $this->view('student/kardex'); 
    }
    public function fase() { 
        AuthMiddleware::checkAccess(['estudiante']); 
        $this->view('student/fase'); 
    }
    public function comentarios() { 
        AuthMiddleware::checkAccess(['estudiante']); 
        $this->view('student/comentarios'); 
    }
    public function perfil() { 
        AuthMiddleware::checkAccess(['estudiante']); 
        $this->view('student/perfil'); 
    }
}