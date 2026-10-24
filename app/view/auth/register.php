<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - EduWeb</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/styles.css">
</head>
<body>
    <div class="form-wrapper">
        <div class="form-card">
            <h2>Crear Cuenta</h2>
            <form id="registerForm" action="<?= BASE_URL ?>index.php?controller=Auth&action=processRegister" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="fullname">Nombre Completo</label>
                    <input type="text" id="fullname" name="fullname" placeholder="Juan Pérez">
                </div>
                <div class="form-group">
                    <label for="gender">Género</label>
                    <select id="gender" name="gender">
                        <option value="">Selecciona...</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                        <option value="O">Otro</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="birthdate">Fecha de Nacimiento</label>
                    <input type="date" id="birthdate" name="birthdate">
                </div>
                <div class="form-group">
                    <label for="avatar">Foto de perfil (Avatar)</label>
                    <input type="file" id="avatar" name="avatar" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="text" id="email" name="email" placeholder="juan@ejemplo.com">
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="Mínimo 8 caracteres">
                </div>
                <button type="submit" class="btn-submit accent">Registrarme</button>
            </form>
            <div class="switch-route">
                ¿Ya tienes cuenta? <a href="<?= BASE_URL ?>index.php?controller=Auth&action=showLogin">Inicia sesión</a>
            </div>
            <a href="<?= BASE_URL ?>index.php?controller=Home&action=index" class="back-home">← Volver al inicio</a>
        </div>
    </div>
    <script src="<?= BASE_URL ?>public/validaciones.js"></script>
</body>
</html>