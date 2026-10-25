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
                    <input type="text" id="fullname" name="fullname" placeholder="Escribe tu nombre" required>
                </div>
                
                <div class="form-group">
                    <label for="gender">Género</label>
                    <select id="gender" name="gender" required>
                        <option value="">Selecciona...</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                        <option value="O">Otro</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="birthdate">Fecha de Nacimiento</label>
                    <input type="date" id="birthdate" name="birthdate" required>
                </div>
                
                <div class="form-group">
                    <label for="avatar">Foto de perfil (Avatar)</label>
                    <input type="file" id="avatar" name="avatar" accept="image/*" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" placeholder="Correo@ejemplo.com" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="Mínimo 8 caracteres" required>
                </div>

                <div class="form-group">
                    <label for="rol">Quiero registrarme como:</label>
                    <select id="rol" name="rol" required>
                        <option value="estudiante">Estudiante (Quiero tomar cursos)</option>
                        <option value="instructor">Instructor (Quiero ofrecer cursos)</option>
                    </select>
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