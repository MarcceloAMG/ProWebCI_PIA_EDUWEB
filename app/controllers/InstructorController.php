<?php
class InstructorController extends Controller {
    public function ventasResumen() { 
        AuthMiddleware::checkAccess(['instructor']); 
        $this->view('instructor/ventas-resumen'); 
    }
    public function ventasDetalle() { 
        AuthMiddleware::checkAccess(['instructor']); 
        $this->view('instructor/ventas-detalle'); 
    }
    public function cursoCrear() { 
        AuthMiddleware::checkAccess(['instructor']); 
        $this->view('instructor/curso-crear'); 
    }
}