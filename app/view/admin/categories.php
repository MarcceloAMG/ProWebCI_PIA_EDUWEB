<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Categorías</title>
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
        <h2 class="section-title">Control de Categorías del Portal</h2>
        
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; margin-top: 1rem;">
            
            <!-- Formulario para agregar -->
            <div class="form-card" style="max-width: 100%; padding: 1.5rem; height: fit-content;">
                <h3 style="font-size:1.1rem; margin-bottom:1rem;">Agregar Nueva Categoría</h3>
                <form action="<?= BASE_URL ?>index.php?controller=Admin&action=processCategory" method="POST">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="nombre" required placeholder="Ej. Marketing de Afiliados">
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="descripcion" required placeholder="¿Qué tipo de cursos contendrá?..." style="width:100%; height:80px; padding:0.5rem; border:1px solid #cbd5e1; border-radius:4px; font-size:0.95rem; resize:none;"></textarea>
                    </div>
                    <button type="submit" class="btn-submit accent" style="padding:0.5rem;">Registrar</button>
                </form>
            </div>

            <!-- Tabla Dinámica -->
            <div>
                <!-- Solo reemplaza la tabla de la vista que ya tenías -->
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Categoría</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($categorias)): ?>
                            <tr><td colspan="3" style="text-align:center;">No hay categorías registradas aún.</td></tr>
                        <?php else: ?>
                            <?php foreach($categorias as $cat): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($cat['nombre']) ?></strong></td>
                                    <td><?= htmlspecialchars($cat['descripcion']) ?></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>index.php?controller=Admin&action=deleteCategory&id=<?= $cat['id'] ?>" class="btn-status blocked" style="text-decoration:none;" onclick="return confirm('¿Eliminar esta categoría?');">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="<?= BASE_URL ?>public/js/profile-menu.js"></script>
</body>
</html>