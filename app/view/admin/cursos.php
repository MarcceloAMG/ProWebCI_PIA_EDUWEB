<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gestión de Cursos</title>
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
        <h2 class="section-title">Gestión Global de Cursos</h2>

        <table class="report-table">
            <thead>
                <tr>
                    <th>Curso</th>
                    <th>Instructor</th>
                    <th>Precio</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($cursos)): ?>
                    <tr><td colspan="5" style="text-align:center;">No hay cursos en la plataforma.</td></tr>
                <?php else: ?>
                    <?php foreach($cursos as $curso): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($curso['titulo']) ?></strong></td>
                            <td><?= htmlspecialchars($curso['instructor']) ?></td>
                            <td>$<?= number_format($curso['costo'], 2) ?></td>
                            <td>
                                <?php if($curso['activo'] == 1): ?>
                                    <span class="btn-status active">Activo</span>
                                <?php else: ?>
                                    <span class="btn-status blocked">Dado de Baja</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($curso['activo'] == 1): ?>
                                    <a href="<?= BASE_URL ?>index.php?controller=Admin&action=toggleCourseStatus&id=<?= $curso['id'] ?>&status=0" 
                                       class="btn-status blocked" style="text-decoration:none;" 
                                       onclick="return confirm('¿Dar de baja este curso del portal?');">Dar de Baja</a>
                                <?php else: ?>
                                    <a href="<?= BASE_URL ?>index.php?controller=Admin&action=toggleCourseStatus&id=<?= $curso['id'] ?>&status=1" 
                                       class="btn-status active" style="text-decoration:none;">Reactivar</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script src="<?= BASE_URL ?>public/js/profile-menu.js"></script>
</body>
</html>