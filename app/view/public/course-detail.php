<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($curso['titulo']) ?> - EduWeb</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/styles.css">
</head>
<body>
    <header>
        <h2><a href="<?= BASE_URL ?>index.php?controller=Home&action=index">← EduWeb</a></h2>
    </header>

    <div class="hero-course">
        <!-- Si el curso tiene imagen, la mostramos, si no, un texto por defecto -->
        <div class="hero-img" style="background-image: url('<?= BASE_URL ?>public/uploads/<?= htmlspecialchars($curso['imagen'] ?? 'default.jpg') ?>'); background-size: cover; background-position: center;">
            <?php if(empty($curso['imagen'])): ?> Imagen del Curso <?php endif; ?>
        </div>
        <div class="hero-text">
            <h2><?= htmlspecialchars($curso['titulo']) ?></h2>
            <p><?= nl2br(htmlspecialchars($curso['descripcion'])) ?></p>
            <div class="price-tag">Costo Completo: $<?= number_format($curso['costo'], 2) ?></div>
            
            <?php if(isset($_SESSION['user_id'])): ?>
                <?php if($_SESSION['rol'] !== 'estudiante'): ?>
                    <p style="color: #fbbf24;">Solo los estudiantes pueden inscribirse a los cursos.</p>
                <?php elseif($isEnrolled): ?>
                    <a href="<?= BASE_URL ?>index.php?controller=Student&action=kardex" class="btn-action">Ir a mi Kardex</a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>index.php?controller=Course&action=enroll&id=<?= $curso['id'] ?>" class="btn-action">Inscribirse Ahora</a>
                <?php endif; ?>
            <?php else: ?>
                <a href="<?= BASE_URL ?>index.php?controller=Auth&action=showLogin" class="btn-action">Inicia Sesión para Inscribirte</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="detail-container">
        <div class="course-body">
            <h3>Descripción del Contenido</h3>
            <p>Este curso está dividido en múltiples fases diseñadas estratégicamente para el aprendizaje autodidacta. Incluye archivos descargables, lecturas complementarias y videos estructurados por cada nivel de avance.</p>
            
            <h3>Estructura de Niveles</h3>
            <ul class="level-list">
                <?php if(empty($niveles)): ?>
                    <li class="level-item"><span>Este curso aún no tiene niveles registrados.</span></li>
                <?php else: ?>
                    <?php foreach($niveles as $nivel): ?>
                        <li class="level-item">
                            <span>Nivel <?= $nivel['orden'] ?>: <?= htmlspecialchars($nivel['titulo']) ?></span>
                            
                            <?php if($isEnrolled): ?>
                                <!-- Si está inscrito, puede ver todo -->
                                <a href="<?= BASE_URL ?>index.php?controller=Student&action=fase&nivel_id=<?= $nivel['id'] ?>" class="btn-action" style="padding: 0.3rem 0.8rem; font-size: 0.85rem;">Ver Clase</a>
                            <?php else: ?>
                                <!-- Si NO está inscrito, los niveles gratis se pueden ver, los de pago no -->
                                <?php if($nivel['costo'] == 0): ?>
                                    <strong style="color: #059669;">Gratis (Muestra) - <a href="<?= BASE_URL ?>index.php?controller=Student&action=fase&nivel_id=<?= $nivel['id'] ?>" style="color: #059669;">Ver Muestra</a></strong>
                                <?php else: ?>
                                    <span style="color: #ef4444; font-weight: bold;">🔒 Requiere Inscripción</span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>

        <aside class="course-meta-box">
            <h4>Información General</h4>
            <p>⭐ <strong>Calificación:</strong> 4.8 / 5.0</p>
            <p>📂 <strong>Categoría:</strong> IT & Software</p>
            <p>👤 <strong>Instructor:</strong> <?= htmlspecialchars($curso['instructor']) ?></p>
        </aside>
    </div>
</body>
</html>