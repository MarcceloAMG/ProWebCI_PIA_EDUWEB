<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Reportes</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/styles.css">
</head>
<body>
       <header style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 2rem; background-color: #0f172a; color: white;">
        <h1 style="margin: 0;"><a href="<?= BASE_URL ?>index.php?controller=Admin&action=usuarios" style="color: white; text-decoration: none;">EduWeb Central (Admin)</a></h1>
        
        <div class="auth-buttons" style="display: flex; align-items: center; gap: 15px;">
            <!-- Enlaces de Navegación del Admin -->
            <a href="<?= BASE_URL ?>index.php?controller=Admin&action=usuarios" style="border: none; padding: 0.5rem; font-size: 0.95rem;">Usuarios</a>
            <a href="<?= BASE_URL ?>index.php?controller=Admin&action=categorias" style="border: none; padding: 0.5rem; font-size: 0.95rem;">Categorías</a>
            <a href="<?= BASE_URL ?>index.php?controller=Admin&action=reportes" style="border: none; padding: 0.5rem; font-size: 0.95rem;">Reportes</a>
            <a href="<?= BASE_URL ?>index.php?controller=Admin&action=comentarios" style="border: none; padding: 0.5rem; font-size: 0.95rem;">Comentarios</a>
            <a href="<?= BASE_URL ?>index.php?controller=Admin&action=cursos">Cursos</a>
            <!-- Botón de Ir al Inicio -->
            <a href="<?= BASE_URL ?>index.php?controller=Home&action=index" style="color: #38bdf8; border: 1px solid #38bdf8; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; font-size: 0.95rem; margin-left: 10px;">Ir al Inicio</a>
            
            <!-- Menú de Perfil Desplegable -->
            <div class="profile-menu-container" style="margin-left: 10px;">
                <div class="profile-trigger">
                    <?php 
                        $avatarFile = $_SESSION['avatar'] ?? '';
                        $rutaFisica = 'public/uploads/avatars/' . $avatarFile;
                        if (!empty($avatarFile) && file_exists($rutaFisica)): 
                    ?>
                        <img src="<?= BASE_URL ?>public/uploads/avatars/<?= htmlspecialchars($avatarFile) ?>" alt="Avatar" class="profile-avatar-img">
                    <?php else: ?>
                        <div class="profile-avatar-fallback"><?= strtoupper(substr($_SESSION['nombre'] ?? 'U', 0, 1)) ?></div>
                    <?php endif; ?>
                </div>
                <div class="profile-dropdown">
                    <div class="dropdown-header">
                        <strong><?= htmlspecialchars($_SESSION['nombre']) ?></strong>
                        <span><?= ucfirst($_SESSION['rol']) ?></span>
                    </div>
                    <a href="<?= BASE_URL ?>index.php?controller=Student&action=perfil">Editar Perfil</a>
                    <a href="<?= BASE_URL ?>index.php?controller=Auth&action=logout" style="color: #ef4444;">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </header>

    <div class="student-layout">
        <h2 class="section-title">Reportes de la Comunidad (Usuarios Activos)</h2>

        <form action="<?= BASE_URL ?>index.php" method="GET" class="filter-bar" style="margin-bottom: 2rem;">
            <input type="hidden" name="controller" value="Admin">
            <input type="hidden" name="action" value="reportes">
            <div class="filter-group">
                <label>Tipo de Reporte</label>
                <select name="tipo" onchange="this.form.submit()">
                    <option value="instructores" <?= $tipo === 'instructores' ? 'selected' : '' ?>>Reporte de Instructores</option>
                    <option value="estudiantes" <?= $tipo === 'estudiantes' ? 'selected' : '' ?>>Reporte de Estudiantes</option>
                </select>
            </div>
        </form>

        <?php if($tipo === 'instructores'): ?>
            <h3 style="color:#0f172a; margin-bottom: 0.5rem;">📊 Rendimiento de Instructores</h3>
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Nombre</th>
                        <th>Fecha Ingreso</th>
                        <th>Cursos Ofrecidos</th>
                        <th>Ganancias Totales</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($datos)): ?>
                        <tr><td colspan="5" style="text-align:center;">No hay instructores activos.</td></tr>
                    <?php else: ?>
                        <?php foreach($datos as $d): ?>
                            <tr>
                                <td><?= htmlspecialchars($d['usuario']) ?></td>
                                <td><?= htmlspecialchars($d['nombre_completo']) ?></td>
                                <td><?= date('d-M-Y', strtotime($d['fecha_registro'])) ?></td>
                                <td><?= $d['total_cursos'] ?> cursos</td>
                                <td style="color:#059669; font-weight:bold;">$<?= number_format($d['total_ganancias'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php else: ?>
            <h3 style="color:#0f172a; margin-bottom: 0.5rem;">📊 Rendimiento de Estudiantes</h3>
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Nombre</th>
                        <th>Fecha de Ingreso</th>
                        <th>Cursos Inscritos</th>
                        <th>% Avance Global</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($datos)): ?>
                        <tr><td colspan="5" style="text-align:center;">No hay estudiantes activos.</td></tr>
                    <?php else: ?>
                        <?php foreach($datos as $d): ?>
                            <tr>
                                <td><?= htmlspecialchars($d['usuario']) ?></td>
                                <td><?= htmlspecialchars($d['nombre_completo']) ?></td>
                                <td><?= date('d-M-Y', strtotime($d['fecha_registro'])) ?></td>
                                <td><?= $d['total_cursos'] ?> cursos</td>
                                <td><strong><?= number_format($d['promedio_avance'], 0) ?>%</strong></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <script src="<?= BASE_URL ?>public/js/profile-menu.js"></script>
</body>
</html>