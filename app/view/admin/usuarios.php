<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Usuarios</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/styles.css">
</head>
<body>
    <header>
        <h1><a href="<?= BASE_URL ?>index.php?controller=Admin&action=usuarios">EduWeb Central (Administrador)</a></h1>
        <div class="auth-buttons"><a href="<?= BASE_URL ?>index.php?controller=Home&action=index" style="border-color:#ef4444; color:#ef4444;">Salir</a></div>
    </header>

    <div class="student-layout">
        <h2 class="section-title">Lista de Usuarios Registrados</h2>

        <div class="search-container" style="background-color: #ffffff; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-radius: 8px; margin-bottom: 2rem; padding: 1rem;">
            <input type="text" placeholder="Buscar por username o nombre real..." style="border: 1px solid #cbd5e1; width: 60%;">
            <button type="button">Buscar Usuario</button>
        </div>

        <table class="report-table">
            <thead>
                <tr>
                    <th>Usuario (Username)</th>
                    <th>Nombre Real</th>
                    <th>Rol</th>
                    <th>Estado de Cuenta</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>mario_dev99</strong></td>
                    <td>Mario Fuentes Ordaz</td>
                    <td>Estudiante</td>
                    <td><span class="btn-status blocked">Deshabilitado (3 intentos fallidos)</span></td>
                    <td><button class="btn-status active" onclick="alert('Cuenta desbloqueada y reactivada')">Reactivar / Desbloquear</button></td>
                </tr>
                <tr>
                    <td><strong>juan.perez</strong></td>
                    <td>Juan Pérez López</td>
                    <td>Estudiante</td>
                    <td><span class="btn-status active">Activo</span></td>
                    <td><button class="btn-status blocked" onclick="alert('Usuario bloqueado manualmente')">Bloquear Cuenta</button></td>
                </tr>
                <tr>
                    <td><strong>fausto.islas</strong></td>
                    <td>Fausto Alejandro Islas Amador</td>
                    <td>Instructor</td>
                    <td><span class="btn-status active">Activo</span></td>
                    <td><button class="btn-status blocked" onclick="alert('Instructor deshabilitado')">Bloquear Cuenta</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>