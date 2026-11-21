<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen de Ventas - Instructor</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/styles.css">
</head>
<body>
    <header style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 2rem; background-color: #0f172a; color: white;">
        <h1 style="margin: 0;"><a href="<?= BASE_URL ?>index.php?controller=Home&action=index" style="color: white; text-decoration: none;">← EduWeb (Panel Instructor)</a></h1>
        
        <div class="auth-buttons" style="display: flex; align-items: center; gap: 15px;">
            <!-- Botón de Crear Curso -->
            <a href="<?= BASE_URL ?>index.php?controller=Instructor&action=cursoCrear" class="btn-primary" style="color: white; text-decoration: none; padding: 0.5rem 1rem; border-radius: 4px; border: 1px solid #38bdf8;">Crear Nuevo Curso</a>
            
            <!-- Menú de Perfil -->
            <div class="profile-menu-container">
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
                    <a href="<?= BASE_URL ?>index.php?controller=Instructor&action=ventasResumen">Mis Ventas</a>
                    <a href="<?= BASE_URL ?>index.php?controller=Student&action=perfil">Editar Perfil</a>
                    <a href="<?= BASE_URL ?>index.php?controller=Auth&action=logout" style="color: #ef4444;">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </header>

    <div class="student-layout">
        <h2 class="section-title">Mis Cursos y Ventas</h2>
        
                <table class="report-table">
            <thead>
                <tr>
                    <th>Curso</th>
                    <th>Alumnos Inscritos</th>
                    <th>Progreso Promedio</th>
                    <th>Total Ingresos</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($cursos)): ?>
                    <tr>
                        <td colspan="5" style="text-align:center;">Aún no tienes cursos creados. ¡Crea tu primer curso!</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($cursos as $curso): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($curso['titulo']) ?></strong></td>
                            <td><?= $curso['total_alumnos'] ?></td>
                            <td><?= number_format($curso['promedio_avance'], 0) ?>%</td>
                            <td style="color: #059669; font-weight: bold;">$<?= number_format($curso['total_ingresos'], 2) ?></td>
                            <td>
                                <?php $activo = (int) $curso['activo'] === 1; ?>
                                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                    <a href="<?= BASE_URL ?>index.php?controller=Instructor&amp;action=ventasDetalle&amp;id=<?= (int) $curso['id'] ?>" class="btn-action" style="font-size: 0.8rem; padding: 0.4rem 0.8rem;">Ver Detalle</a>
                                    <form method="post" action="<?= BASE_URL ?>index.php?controller=Instructor&amp;action=toggleCourse" <?php if ($activo): ?>onsubmit="return confirm('¿Seguro que quieres dar de baja este curso? Ya no aparecerá en el catálogo público.');"<?php endif; ?>>
                                        <input type="hidden" name="id" value="<?= (int) $curso['id'] ?>">
                                        <input type="hidden" name="status" value="<?= $activo ? 0 : 1 ?>">
                                        <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf_curso_estado, ENT_QUOTES, 'UTF-8') ?>">
                                        <button type="submit" class="btn-action" style="font-size: 0.8rem; padding: 0.4rem 0.8rem; border: 0; cursor: pointer; background-color: <?= $activo ? '#ef4444' : '#10b981' ?>;">
                                            <?= $activo ? 'Dar de Baja' : 'Reactivar Curso' ?>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        
        <div class="summary-card">
            <div>TOTAL DE INGRESOS GLOBAL: $<?= number_format($total_global, 2) ?></div>
        </div>
    </div>
    
    <script src="<?= BASE_URL ?>public/js/profile-menu.js"></script>
</body>
</html>