<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen de Ventas - Instructor</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/styles.css">
</head>
<body>
    <header>
        <h1><a href="<?= BASE_URL ?>index.php?controller=Instructor&action=ventasResumen">EduWeb (Panel Instructor)</a></h1>
        <div class="auth-buttons">
            <a href="<?= BASE_URL ?>index.php?controller=Instructor&action=cursoCrear" class="btn-primary">Crear Nuevo Curso</a>
            <a href="<?= BASE_URL ?>index.php?controller=Auth&action=logout" style="border-color: #ef4444; color: #ef4444;">Cerrar Sesión</a>
        </div>
    </header>

    <div class="student-layout">
        <h2 class="section-title">Mis Cursos y Ventas</h2>
        
        <table class="report-table">
            <thead>
                <tr>
                    <th>Curso</th>
                    <th>Alumnos Inscritos</th>
                    <th>Nivel Promedio</th>
                    <th>Total Ingresos</th>
                    <th>Acciones</th>
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
                            <td>0</td> <!-- Dato dummy por ahora, hasta la 3ra entrega -->
                            <td>Nivel 0</td> <!-- Dato dummy -->
                            <td style="color: #059669; font-weight: bold;">$0.00</td>
                            <td>
                                <a href="<?= BASE_URL ?>index.php?controller=Instructor&action=ventasDetalle" class="btn-action" style="font-size: 0.8rem; padding: 0.4rem 0.8rem;">Ver Detalle</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        
        <div class="summary-card">
            <div>TOTAL DE INGRESOS GLOBAL: $0.00</div>
        </div>
    </div>
</body>
</html>