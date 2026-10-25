<?php
class UserModel extends Model {
    
    public function getUserByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Ahora recibe el nombre de la imagen
    public function createUser($nombre, $email, $password, $rol, $avatar, $genero, $fecha_nacimiento) {
        $stmt = $this->db->prepare("INSERT INTO usuarios (nombre_completo, email, password, rol, avatar, genero, fecha_nacimiento) VALUES (:nombre, :email, :password, :rol, :avatar, :genero, :fecha)");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':rol', $rol);
        $stmt->bindParam(':avatar', $avatar);
        $stmt->bindParam(':genero', $genero);
        $stmt->bindParam(':fecha', $fecha_nacimiento);
        return $stmt->execute();
    }

    public function updateUser($id, $nombre, $genero, $fecha, $email, $password, $avatar) {
        // Si hay contraseña nueva, la encriptamos
        if (!empty($password)) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("UPDATE usuarios SET nombre_completo=:nombre, genero=:genero, fecha_nacimiento=:fecha, email=:email, password=:pass, avatar=:avatar WHERE id=:id");
            $stmt->bindParam(':pass', $hash);
        } else {
            // Si no hay contraseña, no la actualizamos
            $stmt = $this->db->prepare("UPDATE usuarios SET nombre_completo=:nombre, genero=:genero, fecha_nacimiento=:fecha, email=:email, avatar=:avatar WHERE id=:id");
        }
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':genero', $genero);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':avatar', $avatar);
        $stmt->bindParam(':id', $id);
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