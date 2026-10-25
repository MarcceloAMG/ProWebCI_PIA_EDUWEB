<?php
class CategoryModel extends Model {
    
    // Trae TODOS los datos de las categorías (incluyendo la descripción)
    public function getAllCategories() {
        $stmt = $this->db->prepare("SELECT * FROM categorias");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function createCategory($nombre, $descripcion, $creado_por) {
        $stmt = $this->db->prepare("INSERT INTO categorias (nombre, descripcion, creado_por) VALUES (:nombre, :desc, :creador)");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':desc', $descripcion);
        $stmt->bindParam(':creador', $creado_por);
        return $stmt->execute();
    }
}