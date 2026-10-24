<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Reportes de Usuarios</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/styles.css">
</head>
<body>
    <header>
        <h1><a href="<?= BASE_URL ?>index.php?controller=Admin&action=reportes">EduWeb Central (Administrador)</a></h1>
        <div class="auth-buttons"><a href="<?= BASE_URL ?>index.php?controller=Home&action=index" style="border-color:#ef4444; color:#ef4444;">Salir</a></div>
    </header>

    <div class="student-layout">
        <h2 class="section-title">Reportes de Rendimiento de la Comunidad</h2>

        <div class="filter-bar" style="margin-bottom: 2rem;">
            <div class="filter-group">
                <label>Seleccionar Tipo de Usuario</label>
                <select onchange="alert('Simulación: Cambiando columnas del reporte activo.')">
                    <option value="instructores">Reporte de Instructores</option>
                    <option value="estudiantes">Reporte de Estudiantes</option>
                </select>
            </div>
            <button type="button" class="btn-submit" style="width: auto; padding: 0.75rem 1.5rem;">Generar Reporte Activos</button>
        </div>

        <h3 style="color:#0f172a; margin-bottom: 0.5rem;">📊 Vista Instructores</h3>
        <table class="report-table">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Nombre</th>
                    <th>Fecha Ingreso</th>
                    <th>Cantidad de Cursos Ofrecidos</th>
                    <th>Ganancias Totales</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>fausto.islas</td>
                    <td>Fausto Alejandro Islas Amador</td>
                    <td>01-Ene-2026</td>
                    <td>3 cursos</td>
                    <td style="color:#059669; font-weight:bold;">$17,964.00</td>
                </tr>
            </tbody>
        </table>

        <h3 style="color:#0f172a; margin-top:2rem; margin-bottom: 0.5rem;">📊 Vista Estudiantes</h3>
        <table class="report-table">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Nombre</th>
                    <th>Fecha de Ingreso</th>
                    <th>Cantidad de Cursos Inscritos</th>
                    <th>% de Avance Global</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>juan.perez</td>
                    <td>Juan Pérez López</td>
                    <td>15-Ago-2026</td>
                    <td>2 cursos</td>
                    <td><strong>75%</strong></td>
                </tr>
                <tr>
                    <td>mario_dev99</td>
                    <td>Mario Fuentes Ordaz</td>
                    <td>18-Ago-2026</td>
                    <td>1 curso</td>
                    <td><strong>0%</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>