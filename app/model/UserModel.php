<?php
class UserModel extends Model {
    
    public function getUserByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function createUser($nombre, $email, $password, $rol) {
        $stmt = $this->db->prepare("INSERT INTO usuarios (nombre_completo, email, password, rol) VALUES (:nombre, :email, :password, :rol)");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':rol', $rol);
        return $stmt->execute();
    }

    public function updateFailedAttempts($userId, $attempts) {
        $stmt = $this->db->prepare("UPDATE usuarios SET intentos_fallidos = :attempts WHERE id = :id");
        $stmt->bindParam(':attempts', $attempts);
        $stmt->bindParam(':id', $userId);
        return $stmt->execute();
    }

    public function blockUser($userId) {
        $stmt = $this->db->prepare("UPDATE usuarios SET estado = 'bloqueado' WHERE id = :id");
        $stmt->bindParam(':id', $userId);
        return $stmt->execute();
    }
}