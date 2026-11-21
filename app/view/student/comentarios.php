<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calificar Curso - EduWeb</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/styles.css">
</head>
<body>
        <header style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 2rem; background-color: #0f172a; color: white;">
        <h1 style="margin: 0;"><a href="<?= BASE_URL ?>index.php?controller=Home&action=index" style="color: white; text-decoration: none;">← EduWeb</a></h1>
        
        <div class="auth-buttons" style="display: flex; align-items: center;">
            <?php if(isset($_SESSION['user_id'])): ?>
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
                        <a href="<?= BASE_URL ?>index.php?controller=Student&action=kardex">Mi Kardex</a>
                        <a href="<?= BASE_URL ?>index.php?controller=Student&action=perfil">Editar Perfil</a>
                        <a href="<?= BASE_URL ?>index.php?controller=Auth&action=logout" style="color: #ef4444;">Cerrar Sesión</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="<?= BASE_URL ?>index.php?controller=Auth&action=showLogin" style="color: white; text-decoration: none; padding: 0.5rem 1rem; border-radius: 4px; border: 1px solid #38bdf8;">Iniciar Sesión</a>
            <?php endif; ?>
        </div>
    </header>

    <div class="form-wrapper">
        <div class="form-card" style="max-width: 550px;">
            <h2>Calificar Curso Finalizado</h2>
            <p style="font-size: 0.9rem; color: #64748b; text-align: center; margin-bottom: 1.5rem;">
                Tu opinión ayuda a mantener la integridad y calidad del portal.
            </p>

            <form action="<?= BASE_URL ?>index.php?controller=Student&action=processComment" method="POST">
                <div class="form-group" style="text-align: center;">
                    <label style="font-weight: bold;">¿Qué puntuación le das al curso?</label>
                    <div class="star-rating">
                        <span class="active">★</span>
                        <span class="active">★</span>
                        <span class="active">★</span>
                        <span class="active">★</span>
                        <span>★</span>
                    </div>
                    <span style="font-size: 0.85rem; color: #64748b;">(Haz clic para asignar de 1 a 5 estrellas)</span>
                </div>

                <div class="form-group">
                    <label for="review">Reseña Escrita</label>
                    <textarea id="review" name="review" required placeholder="Escribe tu comentario sobre los niveles, el contenido y la atención del instructor..." style="width:100%; height:120px; padding:0.75rem; border:1px solid #cbd5e1; border-radius:4px; font-size:1rem; resize:none;"></textarea>
                </div>

                <button type="submit" class="btn-submit accent">Publicar Reseña y Finalizar</button>
            </form>
        </div>
    </div>
    <script src="<?= BASE_URL ?>public/js/profile-menu.js"></script>
</body>
</html>