<?php
class CourseModel extends Model {
    
    public function getAllActiveCourses($titulo = '', $categoria_id = 0) {
        $sql = "SELECT c.* FROM cursos c";
        $params = [];
        if ($categoria_id > 0) { $sql .= " JOIN curso_categoria cc ON c.id = cc.curso_id"; }
        $sql .= " WHERE c.activo = 1";
        if (!empty($titulo)) {
            $sql .= " AND c.titulo LIKE :titulo";
            $params[':titulo'] = "%$titulo%";
        }
        if ($categoria_id > 0) {
            $sql .= " AND cc.categoria_id = :cat_id";
            $params[':cat_id'] = $categoria_id;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // NUEVA: Obtener los cursos de un instructor específico
    public function getCoursesByInstructor($instructor_id) {
        $stmt = $this->db->prepare("SELECT * FROM cursos WHERE instructor_id = :inst ORDER BY fecha_creacion DESC");
        $stmt->bindParam(':inst', $instructor_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ACTUALIZADA: Ahora recibe la imagen
    public function createCourse($instructor_id, $titulo, $descripcion, $costo, $imagen) {
        $stmt = $this->db->prepare("INSERT INTO cursos (instructor_id, titulo, descripcion, costo, imagen) VALUES (:inst, :titulo, :desc, :costo, :img)");
        $stmt->bindParam(':inst', $instructor_id);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':desc', $descripcion);
        $stmt->bindParam(':costo', $costo);
        $stmt->bindParam(':img', $imagen);
        return $stmt->execute();
    }

        // Obtener datos de un curso por su ID
    public function getCourseById($id) {
        $stmt = $this->db->prepare("SELECT c.*, u.nombre_completo as instructor FROM cursos c JOIN usuarios u ON c.instructor_id = u.id WHERE c.id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Obtener los niveles de un curso
    public function getLevelsByCourse($course_id) {
        $stmt = $this->db->prepare("SELECT * FROM niveles WHERE curso_id = :id ORDER BY orden ASC");
        $stmt->bindParam(':id', $course_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Verificar si un estudiante ya está inscrito
    public function isEnrolled($student_id, $course_id) {
        $stmt = $this->db->prepare("SELECT id FROM inscripciones WHERE estudiante_id = :student AND curso_id = :course");
        $stmt->bindParam(':student', $student_id);
        $stmt->bindParam(':course', $course_id);
        $stmt->execute();
        return $stmt->fetch() ? true : false;
    }

    // Inscribir al estudiante
    public function enroll($student_id, $course_id) {
        $stmt = $this->db->prepare("INSERT INTO inscripciones (estudiante_id, curso_id) VALUES (:student, :course)");
        $stmt->bindParam(':student', $student_id);
        $stmt->bindParam(':course', $course_id);
        return $stmt->execute();
    }
}