<?php
class MessageModel extends Model {
    
    // Traer los mensajes entre el alumno y el instructor de un curso
    public function getChatMessages($curso_id, $student_id) {
        $stmt = $this->db->prepare("
            SELECT m.*, u.nombre_completo, u.avatar, u.rol 
            FROM mensajes m 
            JOIN usuarios u ON m.emisor_id = u.id 
            WHERE m.curso_id = :curso_id 
              AND (m.emisor_id = :student_id OR m.receptor_id = :student_id)
            ORDER BY m.fecha_envio ASC
        ");
        $stmt->bindParam(':curso_id', $curso_id);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Enviar un mensaje nuevo
    public function sendMessage($emisor_id, $receptor_id, $curso_id, $mensaje) {
        $stmt = $this->db->prepare("INSERT INTO mensajes (emisor_id, receptor_id, curso_id, mensaje) VALUES (:emisor, :receptor, :curso, :mensaje)");
        $stmt->bindParam(':emisor', $emisor_id);
        $stmt->bindParam(':receptor', $receptor_id);
        $stmt->bindParam(':curso', $curso_id);
        $stmt->bindParam(':mensaje', $mensaje);
        return $stmt->execute();
    }
}