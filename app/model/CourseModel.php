<?php
class CourseModel extends Model {
    
        // ACTUALIZADA: Ahora recibe el parámetro $orden
    public function getAllActiveCourses($titulo = '', $categoria_id = 0, $orden = 'recientes') {
        $sql = "SELECT c.*, 
                (SELECT AVG(calificacion) FROM comentarios WHERE curso_id = c.id AND eliminado = 0) as promedio_calificacion,
                (SELECT COUNT(id) FROM inscripciones WHERE curso_id = c.id) as total_ventas
                FROM cursos c";
        
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
        
        // Lógica para ordenar según lo que pida la vista
        if ($orden === 'mejor_calificados') {
            $sql .= " ORDER BY promedio_calificacion DESC, total_ventas DESC";
        } elseif ($orden === 'mas_vendidos') {
            $sql .= " ORDER BY total_ventas DESC";
        } else {
            $sql .= " ORDER BY c.fecha_creacion DESC"; // Por defecto: recientes
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // NUEVA: Obtener las categorías de un curso específico
    public function getCategoriesByCourse($course_id) {
        $stmt = $this->db->prepare("SELECT cat.nombre FROM categorias cat JOIN curso_categoria cc ON cat.id = cc.categoria_id WHERE cc.curso_id = :id");
        $stmt->bindParam(':id', $course_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN); // Devuelve un array simple con los nombres
    }

    // NUEVA: Obtener el promedio real de calificación
    public function getAverageRating($course_id) {
        $stmt = $this->db->prepare("SELECT AVG(calificacion) FROM comentarios WHERE curso_id = :id AND eliminado = 0");
        $stmt->bindParam(':id', $course_id);
        $stmt->execute();
        $promedio = $stmt->fetchColumn();
        return $promedio ? $promedio : '0.0'; // Si no hay reseñas, ponemos 5.0 por defecto
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

      // Obtener los datos de un nivel específico y saber de qué curso es
    public function getLevelById($nivel_id) {
        $stmt = $this->db->prepare("
            SELECT n.*, c.titulo as curso_titulo, c.instructor_id 
            FROM niveles n 
            JOIN cursos c ON n.curso_id = c.id 
            WHERE n.id = :id
        ");
        $stmt->bindParam(':id', $nivel_id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Actualizar el progreso del alumno
    public function updateProgress($inscripcion_id, $nivel_actual, $porcentaje) {
        $stmt = $this->db->prepare("
            UPDATE inscripciones 
            SET nivel_actual = :nivel, porcentaje_avance = :porcentaje, fecha_ultimo_acceso = CURRENT_TIMESTAMP 
            WHERE id = :id
        ");
        $stmt->bindParam(':nivel', $nivel_actual);
        $stmt->bindParam(':porcentaje', $porcentaje);
        $stmt->bindParam(':id', $inscripcion_id);
        return $stmt->execute();
    }
        // Obtener los cursos en los que el estudiante está inscrito (FALTABA)
    public function getEnrolledCourses($student_id) {
        $stmt = $this->db->prepare("
            SELECT c.*, i.fecha_inscripcion, i.porcentaje_avance, i.fecha_terminacion, i.id as inscripcion_id
            FROM cursos c 
            JOIN inscripciones i ON c.id = i.curso_id 
            WHERE i.estudiante_id = :student_id
            ORDER BY i.fecha_inscripcion DESC
        ");
        $stmt->bindParam(':student_id', $student_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}