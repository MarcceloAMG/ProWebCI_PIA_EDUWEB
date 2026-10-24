<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Curso - EduWeb</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/styles.css">
</head>
<body>
    <header>
        <h2><a href="<?= BASE_URL ?>index.php?controller=Home&action=index">← EduWeb</a></h2>
    </header>

    <div class="hero-course">
        <div class="hero-img">Imagen del Curso</div>
        <div class="hero-text">
            <h2>PHP Avanzado y Programación Orientada a Objetos</h2>
            <p>Aprende de manera autodidacta a estructurar proyectos web escalables usando patrones de diseño arquitectónicos complejos.</p>
            <div class="price-tag">Costo Completo: $599.00</div>
            
            <!-- AQUÍ ESTÁ LA LÓGICA DE SESIÓN -->
            <?php if(isset($_SESSION['user_id'])): ?>
                <!-- Si ya inició sesión, lo mandamos al Kardex -->
                <a href="<?= BASE_URL ?>index.php?controller=Student&action=kardex" class="btn-action">Inscribirse Ahora</a>
            <?php else: ?>
                <!-- Si no ha iniciado sesión, lo mandamos al login -->
                <a href="<?= BASE_URL ?>index.php?controller=Auth&action=showLogin" class="btn-action">Inicia Sesión para Comprar</a>
            <?php endif; ?>

        </div>
    </div>

    <div class="detail-container">
        <div class="course-body">
            <h3>Descripción del Contenido</h3>
            <p>Este curso está dividido en múltiples fases diseñadas estratégicamente para el aprendizaje autodidacta. Incluye archivos descargables en formato PDF, lecturas complementarias y videos estructurados por cada nivel de avance.</p>
            
            <h3>Estructura de Niveles</h3>
            <ul class="level-list">
                <li class="level-item">
                    <span>Nivel 1: Introducción a la Programación Orientada a Objetos</span>
                    <strong style="color: #059669;">Gratis (Muestra)</strong>
                </li>
                <li class="level-item">
                    <span>Nivel 2: Controladores y Enrutamiento en PHP Puro</span>
                    <span>$200.00</span>
                </li>
            </ul>
        </div>

        <aside class="course-meta-box">
            <h4>Información General</h4>
            <p>⭐ <strong>Calificación:</strong> 4.8 / 5.0</p>
            <p>📂 <strong>Categoría:</strong> IT & Software</p>
            <p>👤 <strong>Instructor:</strong> Fausto Alejandro Islas A.</p>
        </aside>
    </div>
</body>
</html>