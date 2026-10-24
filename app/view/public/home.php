<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduWeb - Portal de Cursos</title>
    <!-- AQUÍ ESTABA EL ERROR DE TU CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>public/styles.css">
</head>
<body>
       <header>
        <h1><a href="<?= BASE_URL ?>index.php?controller=Home&action=index">EduWeb</a></h1>
        <div class="auth-buttons">
            <?php if(isset($_SESSION['user_id'])): ?>
                <span style="color: white; margin-right: 15px; font-weight: bold;">
                    Hola, <?= htmlspecialchars($_SESSION['nombre']) ?>
                </span>
                
                <?php if($_SESSION['rol'] === 'estudiante'): ?>
                    <a href="<?= BASE_URL ?>index.php?controller=Student&action=kardex">Mi Kardex</a>
                <?php elseif($_SESSION['rol'] === 'instructor'): ?>
                    <a href="<?= BASE_URL ?>index.php?controller=Instructor&action=ventasResumen">Panel Instructor</a>
                <?php elseif($_SESSION['rol'] === 'administrador'): ?>
                    <a href="<?= BASE_URL ?>index.php?controller=Admin&action=usuarios">Panel Admin</a>
                <?php endif; ?>
                
                <a href="<?= BASE_URL ?>index.php?controller=Auth&action=logout" class="btn-primary">Cerrar Sesión</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>index.php?controller=Auth&action=showLogin">Iniciar Sesión</a>
                <a href="<?= BASE_URL ?>index.php?controller=Auth&action=showRegister" class="btn-primary">Registrarse</a>
            <?php endif; ?>
        </div>
    </header>

    <div class="search-container">
        <input type="text" placeholder="Buscar por título o palabra clave...">
        <select>
            <option value="">Todas las categorías</option>
            <option value="1">IT & Software</option>
            <option value="2">Marketing</option>
            <option value="3">Diseño</option>
        </select>
        <input type="date">
        <button type="button">Buscar</button>
    </div>

    <div class="main-layout">
        <aside class="sidebar">
            <h3>Categorías</h3>
            <ul>
                <li><a href="#">IT & Software</a></li>
                <li><a href="#">Marketing Digital</a></li>
                <li><a href="#">Diseño Gráfico & UI</a></li>
                <li><a href="#">Negocios & Finanzas</a></li>
            </ul>
        </aside>

        <main class="content">
            <h2 class="section-title">Cursos Mejor Calificados</h2>
            <div class="course-grid">
                <!-- AQUÍ TAMBIÉN ESTABA EL ERROR DE LA RUTA -->
                <a href="<?= BASE_URL ?>index.php?controller=Course&action=detail&id=1" class="course-card">
                    <div class="course-img">Desarrollo Web PHP</div>
                    <div class="course-info">
                        <h4>PHP Avanzado y Programación Orientada a Objetos</h4>
                        <div class="course-price">$599.00</div>
                    </div>
                </a>
            </div>
        </main>
    </div>
</body>
</html>