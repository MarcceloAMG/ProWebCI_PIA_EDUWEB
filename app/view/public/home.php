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
                
                                <!-- Bloque de Perfil Clickable -->
                <a href="<?= BASE_URL ?>index.php?controller=Student&action=perfil" style="display: flex; align-items: center; gap: 10px; text-decoration: none; padding: 5px 10px; border-radius: 8px; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.1)'" onmouseout="this.style.backgroundColor='transparent'">
                    
                    <?php 
                        $avatarFile = $_SESSION['avatar'] ?? '';
                        $rutaFisica = 'public/uploads/avatars/' . $avatarFile;
                        
                        // Si el archivo existe en la carpeta, mostramos la foto
                        if (!empty($avatarFile) && file_exists($rutaFisica)): 
                    ?>
                        <img src="<?= BASE_URL ?>public/uploads/avatars/<?= htmlspecialchars($avatarFile) ?>" 
                             alt="Avatar" 
                             style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover; border: 2px solid #38bdf8;">
                    <?php else: ?>
                        <!-- Si no hay foto, mostramos un círculo con la primera letra del nombre -->
                        <div style="width: 35px; height: 35px; border-radius: 50%; background-color: #38bdf8; color: #0f172a; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.2rem; border: 2px solid #38bdf8;">
                            <?= strtoupper(substr($_SESSION['nombre'] ?? 'U', 0, 1)) ?>
                        </div>
                    <?php endif; ?>

                    <span style="color: white; font-weight: bold; font-size: 0.9rem; white-space: nowrap;">
                        Hola, <?= htmlspecialchars($_SESSION['nombre']) ?>
                    </span>
                </a>

                <!-- Botón de Panel según el rol -->
                <?php if($_SESSION['rol'] === 'estudiante'): ?>
                    <a href="<?= BASE_URL ?>index.php?controller=Student&action=kardex" style="color: white; text-decoration: none; padding: 0.5rem 1rem; border-radius: 4px; border: 1px solid #38bdf8; white-space: nowrap;">Mi Kardex</a>
                <?php elseif($_SESSION['rol'] === 'instructor'): ?>
                    <a href="<?= BASE_URL ?>index.php?controller=Instructor&action=ventasResumen" style="color: white; text-decoration: none; padding: 0.5rem 1rem; border-radius: 4px; border: 1px solid #38bdf8; white-space: nowrap;">Panel Instructor</a>
                <?php elseif($_SESSION['rol'] === 'administrador'): ?>
                    <a href="<?= BASE_URL ?>index.php?controller=Admin&action=usuarios" style="color: white; text-decoration: none; padding: 0.5rem 1rem; border-radius: 4px; border: 1px solid #38bdf8; white-space: nowrap;">Panel Admin</a>
                <?php endif; ?>
                
                <!-- Botón de Cerrar Sesión -->
                <a href="<?= BASE_URL ?>index.php?controller=Auth&action=logout" style="background-color: #38bdf8; color: #0f172a; text-decoration: none; padding: 0.5rem 1rem; border-radius: 4px; font-weight: bold; border: none; white-space: nowrap;">Cerrar Sesión</a>
            
            <?php else: ?>
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
</body>
</html>