<?php
class CourseModel extends Model {
    
    // Obtener todos los cursos activos
    public function getAllActiveCourses() {
        $stmt = $this->db->prepare("SELECT id, titulo, descripcion, costo FROM cursos WHERE activo = 1");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Crear un nuevo curso (POST)
    public function createCourse($instructor_id, $titulo, $descripcion, $costo) {
        $stmt = $this->db->prepare("INSERT INTO cursos (instructor_id, titulo, descripcion, costo) VALUES (:inst, :titulo, :desc, :costo)");
        $stmt->bindParam(':inst', $instructor_id);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':desc', $descripcion);
        $stmt->bindParam(':costo', $costo);
        return $stmt->execute();
    }
}