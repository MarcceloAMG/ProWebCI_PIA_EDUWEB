<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Moderación de Comentarios</title>
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
        <h2 class="section-title">Moderación de Comentarios</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); gap: 1.5rem;">
            <?php if(empty($comentarios)): ?>
                <p style="grid-column: 1 / -1; text-align:center; color:#64748b;">No hay comentarios para moderar.</p>
            <?php else: ?>
                <?php foreach($comentarios as $comentario): ?>
                    <div class="tabs-container" style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-top: 4px solid #ef4444;">
                        <span style="font-size:0.8rem; color:#64748b;">Curso: <?= htmlspecialchars($comentario['curso_titulo']) ?> | Alumno: <?= htmlspecialchars($comentario['nombre_completo']) ?></span>
                        <p style="margin: 0.5rem 0; font-weight: 500;">"<?= htmlspecialchars($comentario['comentario']) ?>"</p>
                        <p style="font-size: 0.8rem; color: #94a3b8;">Fecha: <?= date('d/m/Y', strtotime($comentario['fecha_creacion'])) ?></p>
                        
                        <form action="<?= BASE_URL ?>index.php?controller=Admin&action=deleteComment" method="POST" style="display:flex; gap:10px; align-items:center; margin-top:1rem; border-top:1px solid #f1f5f9; padding-top:0.5rem;">
                            <input type="hidden" name="comentario_id" value="<?= $comentario['id'] ?>">
                            <select name="causa" style="padding:0.4rem; font-size:0.85rem; border:1px solid #cbd5e1; border-radius:4px; flex:1;" required>
                                <option value="">Selecciona Causa Obligatoria...</option>
                                <option value="Ataque personal o falta de respeto">Ataque personal / Falta de respeto</option>
                                <option value="Lenguaje vulgar u ofensivo">Lenguaje vulgar u ofensivo</option>
                                <option value="Información falsa o SPAM">Información falsa o SPAM</option>
                            </select>
                            <button type="submit" class="btn-action" style="font-size:0.85rem; padding:0.4rem 1rem; background-color: #ef4444;">Eliminar</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <script src="<?= BASE_URL ?>public/js/profile-menu.js"></script>
</body>
</html>