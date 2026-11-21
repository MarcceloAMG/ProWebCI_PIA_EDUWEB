<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Alumnos - Instructor</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/styles.css">
</head>
<body>
    <header style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 2rem; background-color: #0f172a; color: white;">
        <h1 style="margin: 0;"><a href="<?= BASE_URL ?>index.php?controller=Instructor&action=ventasResumen" style="color: white; text-decoration: none;">← Volver al Resumen</a></h1>
        
        <div class="auth-buttons" style="display: flex; align-items: center; gap: 15px;">
            <a href="<?= BASE_URL ?>index.php?controller=Instructor&action=cursoCrear" class="btn-primary" style="color: white; text-decoration: none; padding: 0.5rem 1rem; border-radius: 4px; border: 1px solid #38bdf8;">Crear Nuevo Curso</a>
            
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
        <h2 class="section-title">Detalle del Curso: <?= htmlspecialchars($curso['titulo'] ?? 'Desconocido') ?></h2>

        <table class="report-table">
            <thead>
                <tr>
                    <th>Nombre del Alumno</th>
                    <th>Fecha de Inscripción</th>
                    <th>Nivel de Avance</th>
                    <th>Precio Pagado</th>
                    <th>Forma de Pago</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($alumnos)): ?>
                    <tr>
                        <td colspan="6" style="text-align:center;">Aún no hay alumnos inscritos en este curso.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($alumnos as $alumno): ?>
                        <tr>
                            <td><?= htmlspecialchars($alumno['alumno']) ?></td>
                            <td><?= date('d-M-Y', strtotime($alumno['fecha_inscripcion'])) ?></td>
                            <td><?= number_format($alumno['porcentaje_avance'], 0) ?>%</td>
                            <td style="color: #059669;">$<?= number_format($alumno['monto'], 2) ?></td>
                            <td><?= htmlspecialchars($alumno['metodo_pago']) ?></td>
                            <td>
                                <a href="<?= BASE_URL ?>index.php?controller=Instructor&action=chat&curso_id=<?= $curso['id'] ?>&alumno_id=<?= $alumno['estudiante_id'] ?>" 
                                   class="btn-action" 
                                   style="background-color: #eab308; color: #0f172a; font-size: 0.8rem; padding: 0.4rem 0.8rem; text-decoration: none;">
                                   💬 Mensaje
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="summary-card" style="background-color: #0f172a; color: white; text-align: right;">
            Total Generado por este Curso: $<?= number_format($total_curso, 2) ?>
        </div>
    </div>
    
    <script src="<?= BASE_URL ?>public/js/profile-menu.js"></script>
</body>
</html>