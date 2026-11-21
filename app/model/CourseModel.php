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

    // Obtener las categorías de un curso específico
    public function getCategoriesByCourse($course_id) {
        $stmt = $this->db->prepare("SELECT cat.nombre FROM categorias cat JOIN curso_categoria cc ON cat.id = cc.categoria_id WHERE cc.curso_id = :id");
        $stmt->bindParam(':id', $course_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN); 
    }

    // Obtener el promedio real de calificación
    public function getAverageRating($course_id) {
        $stmt = $this->db->prepare("SELECT AVG(calificacion) FROM comentarios WHERE curso_id = :id AND eliminado = 0");
        $stmt->bindParam(':id', $course_id);
        $stmt->execute();
        $promedio = $stmt->fetchColumn();
        return $promedio ? $promedio : '0.0'; 
    }

    // Obtener los cursos de un instructor específico
    public function getCoursesByInstructor($instructor_id) {
        $stmt = $this->db->prepare("SELECT * FROM cursos WHERE instructor_id = :inst ORDER BY fecha_creacion DESC");
        $stmt->bindParam(':inst', $instructor_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Crear curso
    public function createCourse($instructor_id, $titulo, $descripcion, $costo, $imagen, $video_trailer) {
        $stmt = $this->db->prepare("INSERT INTO cursos (instructor_id, titulo, descripcion, costo, imagen, video_trailer) VALUES (:inst, :titulo, :desc, :costo, :img, :trailer)");
        $stmt->bindParam(':inst', $instructor_id);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':desc', $descripcion);
        $stmt->bindParam(':costo', $costo);
        $stmt->bindParam(':img', $imagen);
        $stmt->bindParam(':trailer', $video_trailer);
        $stmt->execute();
        return $this->db->lastInsertId(); 
    }

    // Guardar nivel
    public function createLevel($curso_id, $titulo, $video_url, $contenido_texto, $archivo_pdf, $orden, $costo) {
        $stmt = $this->db->prepare("INSERT INTO niveles (curso_id, titulo, video_url, contenido_texto, archivo_pdf, orden, costo) VALUES (:curso, :titulo, :video, :texto, :pdf, :orden, :costo)");
        $stmt->bindParam(':curso', $curso_id);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':video', $video_url);
        $stmt->bindParam(':texto', $contenido_texto);
        $stmt->bindParam(':pdf', $archivo_pdf);
        $stmt->bindParam(':orden', $orden);
        $stmt->bindParam(':costo', $costo);
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

    // Una confirmación de PayPal registra la inscripción y el pago como una sola operación.
    public function registerPayPalPurchase(int $studentId, int $courseId, string $amount): void {
        $this->db->beginTransaction();
        try {
            $lock = $this->db->prepare('SELECT id FROM usuarios WHERE id = ? FOR UPDATE');
            $lock->execute([$studentId]);
            if (!$lock->fetchColumn()) {
                throw new RuntimeException('El estudiante ya no existe.');
            }
            $query = $this->db->prepare('SELECT id FROM inscripciones WHERE estudiante_id = ? AND curso_id = ? ORDER BY id LIMIT 1');
            $query->execute([$studentId, $courseId]);
            $enrollmentId = $query->fetchColumn();
            if ($enrollmentId === false) {
                $this->enroll($studentId, $courseId);
                $enrollmentId = $this->db->lastInsertId();
            }

            $query = $this->db->prepare("SELECT id FROM pagos WHERE inscripcion_id = ? AND metodo_pago = 'PayPal'");
            $query->execute([$enrollmentId]);
            if ($query->fetchColumn() === false) {
                $payment = $this->db->prepare("INSERT INTO pagos (inscripcion_id, monto, metodo_pago) VALUES (?, ?, 'PayPal')");
                $payment->execute([$enrollmentId, $amount]);
            }
            $this->db->commit();
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }

    // Obtener los datos de un nivel específico
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

    // Actualizar progreso
    public function updateProgress($inscripcion_id, $nivel_actual, $porcentaje) {
        if ($porcentaje >= 100) {
            $stmt = $this->db->prepare("
                UPDATE inscripciones 
                SET nivel_actual = :nivel, porcentaje_avance = :porcentaje, fecha_ultimo_acceso = CURRENT_TIMESTAMP, fecha_terminacion = CURRENT_TIMESTAMP 
                WHERE id = :id
            ");
        } else {
            $stmt = $this->db->prepare("
                UPDATE inscripciones 
                SET nivel_actual = :nivel, porcentaje_avance = :porcentaje, fecha_ultimo_acceso = CURRENT_TIMESTAMP 
                WHERE id = :id
            ");
        }
        $stmt->bindParam(':nivel', $nivel_actual);
        $stmt->bindParam(':porcentaje', $porcentaje);
        $stmt->bindParam(':id', $inscripcion_id);
        return $stmt->execute();
    }

    // Obtener todos los datos para armar el certificado
    public function getCertificateData($student_id, $course_id) {
        $stmt = $this->db->prepare("
            SELECT 
                u_est.nombre_completo AS alumno,
                c.titulo AS curso,
                u_inst.nombre_completo AS instructor,
                i.fecha_terminacion
            FROM inscripciones i
            JOIN usuarios u_est ON i.estudiante_id = u_est.id
            JOIN cursos c ON i.curso_id = c.id
            JOIN usuarios u_inst ON c.instructor_id = u_inst.id
            WHERE i.estudiante_id = :student AND i.curso_id = :course
        ");
        $stmt->bindParam(':student', $student_id);
        $stmt->bindParam(':course', $course_id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Obtener los cursos en los que el estudiante está inscrito
       public function getEnrolledCourses($student_id, $fecha_inicio = '', $fecha_fin = '', $categoria_id = 0, $completado = '', $estatus_curso = '') {
        $sql = "SELECT c.*, i.fecha_inscripcion, i.porcentaje_avance, i.fecha_terminacion, i.id as inscripcion_id
                FROM cursos c 
                JOIN inscripciones i ON c.id = i.curso_id 
                WHERE i.estudiante_id = :student_id";
        
        $params = [':student_id' => $student_id];

        // Filtro por fechas
        if (!empty($fecha_inicio)) {
            $sql .= " AND DATE(i.fecha_inscripcion) >= :fecha_inicio";
            $params[':fecha_inicio'] = $fecha_inicio;
        }
        if (!empty($fecha_fin)) {
            $sql .= " AND DATE(i.fecha_inscripcion) <= :fecha_fin";
            $params[':fecha_fin'] = $fecha_fin;
        }

        // Filtro por categoría
        if ($categoria_id > 0) {
            $sql .= " AND c.id IN (SELECT curso_id FROM curso_categoria WHERE categoria_id = :cat_id)";
            $params[':cat_id'] = $categoria_id;
        }

        // Filtro por completado
        if ($completado === 'terminados') {
            $sql .= " AND i.porcentaje_avance >= 100";
        } elseif ($completado === 'incompletos') {
            $sql .= " AND i.porcentaje_avance < 100";
        }

        // Filtro por estatus del curso (activo/baja)
        if ($estatus_curso === 'activos') {
            $sql .= " AND c.activo = 1";
        } elseif ($estatus_curso === 'baja') {
            $sql .= " AND c.activo = 0";
        }

        $sql .= " ORDER BY i.fecha_inscripcion DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // Resumen de ventas
    public function getSalesSummaryByInstructor($instructor_id) {
        $stmt = $this->db->prepare("
            SELECT c.id, c.titulo, c.activo,
                   (SELECT COUNT(id) FROM inscripciones WHERE curso_id = c.id) as total_alumnos,
                   (SELECT IFNULL(AVG(porcentaje_avance), 0) FROM inscripciones WHERE curso_id = c.id) as promedio_avance,
                   (SELECT IFNULL(SUM(monto), 0) FROM pagos p JOIN inscripciones i ON p.inscripcion_id = i.id WHERE i.curso_id = c.id) as total_ingresos
            FROM cursos c 
            WHERE c.instructor_id = :instructor_id
            ORDER BY c.fecha_creacion DESC
        ");
        $stmt->bindParam(':instructor_id', $instructor_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Detalle de ventas por curso
    public function getSalesDetailByCourse($course_id) {
        $stmt = $this->db->prepare("
            SELECT u.nombre_completo as alumno, 
                   u.id as estudiante_id,
                   i.fecha_inscripcion, 
                   i.porcentaje_avance,
                   IFNULL(p.monto, 0) as monto, 
                   IFNULL(p.metodo_pago, 'Ninguno') as metodo_pago
            FROM inscripciones i
            JOIN usuarios u ON i.estudiante_id = u.id
            LEFT JOIN pagos p ON i.id = p.inscripcion_id
            WHERE i.curso_id = :course_id
            ORDER BY i.fecha_inscripcion DESC
        ");
        $stmt->bindParam(':course_id', $course_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Guardar relación Curso-Categoría
    public function assignCategoryToCourse($curso_id, $categoria_id) {
        $stmt = $this->db->prepare("INSERT INTO curso_categoria (curso_id, categoria_id) VALUES (:curso, :categoria)");
        $stmt->bindParam(':curso', $curso_id);
        $stmt->bindParam(':categoria', $categoria_id);
        return $stmt->execute();
    }

    // --- NUEVOS MÉTODOS PARA INYECCIÓN DE DEPENDENCIAS ---
    
    // Obtener la inscripción de un alumno en un curso
    public function getInscription($curso_id, $student_id) {
        $stmt = $this->db->prepare("SELECT id, porcentaje_avance FROM inscripciones WHERE estudiante_id = :student AND curso_id = :curso");
        $stmt->bindParam(':student', $student_id);
        $stmt->bindParam(':curso', $curso_id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Obtener el ID del curso a partir del ID de la inscripción
    public function getCourseIdByInscription($inscripcion_id) {
        $stmt = $this->db->prepare("SELECT curso_id FROM inscripciones WHERE id = :id");
        $stmt->bindParam(':id', $inscripcion_id);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
        // Obtener los comentarios de un curso que NO hayan sido eliminados
    public function getCommentsByCourse($course_id) {
        $stmt = $this->db->prepare("
            SELECT c.calificacion, c.comentario, c.fecha_creacion, u.nombre_completo, u.avatar 
            FROM comentarios c
            JOIN usuarios u ON c.usuario_id = u.id
            WHERE c.curso_id = :curso_id AND c.eliminado = 0
            ORDER BY c.fecha_creacion DESC
        ");
        $stmt->bindParam(':curso_id', $course_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }
        // Cambiar el estado de un curso (Activo / Inactivo)
    public function toggleCourseStatus($course_id, $status, $instructor_id = null) {
        $sql = "UPDATE cursos SET activo = :status WHERE id = :id";
        $params = ['status' => $status, 'id' => $course_id];
        if ($instructor_id !== null) {
            $sql .= " AND instructor_id = :instructor_id";
            $params['instructor_id'] = $instructor_id;
        }
        return $this->db->prepare($sql)->execute($params);
    }

    // Obtener TODOS los cursos para el panel del Administrador
    public function getAllCoursesForAdmin() {
        $stmt = $this->db->prepare("
            SELECT c.*, u.nombre_completo as instructor 
            FROM cursos c 
            JOIN usuarios u ON c.instructor_id = u.id 
            ORDER BY c.fecha_creacion DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}