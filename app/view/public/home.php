<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduWeb - Portal de Cursos</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/styles.css">
</head>
<body>
    <header style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 2rem; background-color: #0f172a; color: white;">
        <h1 style="margin: 0;"><a href="<?= BASE_URL ?>index.php?controller=Home&action=index" style="color: white; text-decoration: none;">EduWeb</a></h1>
        
        <!-- Contenedor de botones y perfil alineados a la derecha -->
        <div class="auth-buttons" style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap; justify-content: flex-end;">
            <?php if(isset($_SESSION['user_id'])): ?>
                
                <!-- Bloque de Perfil Clickable con Menú Desplegable -->
                <div class="profile-menu-container">
                    <div class="profile-trigger">
                        <?php 
                            $avatarFile = $_SESSION['avatar'] ?? '';
                            $rutaFisica = 'public/uploads/avatars/' . $avatarFile;
                            
                            if (!empty($avatarFile) && file_exists($rutaFisica)): 
                        ?>
                            <img src="<?= BASE_URL ?>public/uploads/avatars/<?= htmlspecialchars($avatarFile) ?>" alt="Avatar" class="profile-avatar-img">
                        <?php else: ?>
                            <div class="profile-avatar-fallback">
                                <?= strtoupper(substr($_SESSION['nombre'] ?? 'U', 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Menú que se despliega -->
                    <div class="profile-dropdown">
                        <div class="dropdown-header">
                            <strong><?= htmlspecialchars($_SESSION['nombre']) ?></strong>
                            <span><?= ucfirst($_SESSION['rol']) ?></span>
                        </div>
                        
                        <?php if($_SESSION['rol'] === 'estudiante'): ?>
                            <a href="<?= BASE_URL ?>index.php?controller=Student&action=kardex">Mi Kardex</a>
                        <?php elseif($_SESSION['rol'] === 'instructor'): ?>
                            <a href="<?= BASE_URL ?>index.php?controller=Instructor&action=ventasResumen">Panel Instructor</a>
                        <?php elseif($_SESSION['rol'] === 'administrador'): ?>
                            <a href="<?= BASE_URL ?>index.php?controller=Admin&action=usuarios">Panel Admin</a>
                        <?php endif; ?>
                        
                        <a href="<?= BASE_URL ?>index.php?controller=Student&action=perfil">Editar Perfil</a>
                        <a href="<?= BASE_URL ?>index.php?controller=Auth&action=logout" style="color: #ef4444;">Cerrar Sesión</a>
                    </div>
                </div>
            
            <?php else: ?>
                <!-- Si NO hay sesión, mostramos estos dos botones -->
                <a href="<?= BASE_URL ?>index.php?controller=Auth&action=showLogin" style="color: white; text-decoration: none; padding: 0.5rem 1rem; border-radius: 4px; border: 1px solid #38bdf8; white-space: nowrap;">Iniciar Sesión</a>
                <a href="<?= BASE_URL ?>index.php?controller=Auth&action=showRegister" style="background-color: #38bdf8; color: #0f172a; text-decoration: none; padding: 0.5rem 1rem; border-radius: 4px; font-weight: bold; border: none; white-space: nowrap;">Registrarse</a>
            <?php endif; ?>
        </div>
    </header>

    <div class="search-container">
        <input type="text" id="search-titulo" placeholder="Buscar por título o palabra clave...">
        <select id="search-categoria">
            <option value="0">Todas las categorías</option>
            <!-- JS llenará las opciones aquí -->
        </select>
        
        <!-- Selector de ordenamiento -->
        <select id="orden-cursos">
            <option value="recientes">Más Recientes</option>
            <option value="mejor_calificados">Mejor Calificados</option>
            <option value="mas_vendidos">Más Vendidos</option>
        </select>
        
        <button type="button" id="btn-buscar">Buscar</button>
    </div>

    <div class="main-layout">
        <aside class="sidebar">
            <h3>Categorías</h3>
            <ul id="lista-categorias">
                <!-- JS llenará las categorías aquí -->
            </ul>
        </aside>

        <main class="content">
            <h2 class="section-title">Cursos Disponibles</h2>
            <div class="course-grid" id="course-grid-container">
                <!-- JS inyectará los cursos aquí -->
            </div>
        </main>
    </div>

    <script>const BASE_URL = "<?= BASE_URL ?>";</script>
    <script src="<?= BASE_URL ?>public/js/home.js"></script>
        <!-- NUEVO: Llamada al script del menú de perfil -->
    <script src="<?= BASE_URL ?>public/js/profile-menu.js"></script>
</body>
</html>
</body>
</html>