<?php
class CorsMiddleware {
    public static function handle() {
        // Permitir cualquier origen (en producción se pone el dominio exacto)
        header("Access-Control-Allow-Origin: *");
        // Permitir los métodos HTTP de la API
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        // Permitir cabeceras personalizadas
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

        // Si el navegador hace una petición de comprobación (OPTIONS), respondemos OK y terminamos.
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }
    }
}