<?php
class MessageModel extends Model {
    
    public function getChatMessages($curso_id, $user_id) {
        // Si el instructor consulta, $user_id es el alumno. Si el alumno consulta, es su propio ID.
        // La consulta extrae todos los mensajes del curso donde el usuario sea emisor O receptor.
        $stmt = $this->db->prepare("
            SELECT m.*, u.nombre_completo, u.avatar, u.rol 
            FROM mensajes m 
            JOIN usuarios u ON m.emisor_id = u.id 
            WHERE m.curso_id = :curso_id 
              AND (m.emisor_id = :user_id OR m.receptor_id = :user_id)
            ORDER BY m.fecha_envio ASC
        ");
        $stmt->bindParam(':curso_id', $curso_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function sendMessage($emisor_id, $receptor_id, $curso_id, $mensaje) {
        $stmt = $this->db->prepare("INSERT INTO mensajes (emisor_id, receptor_id, curso_id, mensaje) VALUES (:emisor, :receptor, :curso, :mensaje)");
        $stmt->bindParam(':emisor', $emisor_id);
        $stmt->bindParam(':receptor', $receptor_id);
        $stmt->bindParam(':curso', $curso_id);
        $stmt->bindParam(':mensaje', $mensaje);
        return $stmt->execute();
    }
}