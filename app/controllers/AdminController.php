<?php
class AdminController extends Controller {
    public function usuarios() { 
        AuthMiddleware::checkAccess(['administrador']); 
        $this->view('admin/usuarios'); 
    }
    public function categorias() { 
        AuthMiddleware::checkAccess(['administrador']); 
        $this->view('admin/categories'); 
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