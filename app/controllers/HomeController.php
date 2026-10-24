<?php
class HomeController extends Controller {
    public function index() {
        // Esto carga el archivo app/views/public/home.php
        $this->view('public/home');
    }
}