<?php
class CommentModel extends Model {
    
    // 1. Guardar un comentario/calificación (Para el Estudiante)
    public function createComment($curso_id, $usuario_id, $calificacion, $comentario) {
        $stmt = $this->db->prepare("INSERT INTO comentarios (curso_id, usuario_id, calificacion, comentario) VALUES (:curso, :user, :calif, :comentario)");
        $stmt->bindParam(':curso', $curso_id);
        $stmt->bindParam(':user', $usuario_id);
        $stmt->bindParam(':calif', $calificacion);
        $stmt->bindParam(':comentario', $comentario);
        return $stmt->execute();
    }

    // 2. Obtener todos los comentarios activos (Para el Admin y el detalle del curso)
    public function getAllComments() {
        $stmt = $this->db->prepare("
            SELECT c.*, u.nombre_completo, cur.titulo as curso_titulo 
            FROM comentarios c 
            JOIN usuarios u ON c.usuario_id = u.id 
            JOIN cursos cur ON c.curso_id = cur.id 
            WHERE c.eliminado = 0
            ORDER BY c.fecha_creacion DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // 3. Borrado lógico con causa (Para el Admin)
    public function deleteComment($commentId, $causa) {
        $stmt = $this->db->prepare("UPDATE comentarios SET eliminado = 1, causa_eliminacion = :causa, fecha_eliminacion = CURRENT_TIMESTAMP WHERE id = :id");
        $stmt->bindParam(':causa', $causa);
        $stmt->bindParam(':id', $commentId);
        return $stmt->execute();
    }
}