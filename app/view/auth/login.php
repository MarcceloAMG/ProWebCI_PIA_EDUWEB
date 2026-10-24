<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - EduWeb</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/styles.css">
</head>
<body>
    <div class="form-wrapper">
        <div class="form-card">
            <h2>Iniciar Sesión</h2>
            <?php if (isset($error)): ?>
    <div style="background-color: #fee2e2; color: #b91c1c; padding: 10px; border-radius: 5px; margin-bottom: 1rem; text-align: center; font-size: 0.9rem;">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>
            <form action="<?= BASE_URL ?>index.php?controller=Auth&action=processLogin" method="POST">
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" required placeholder="ejemplo@correo.com">
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required placeholder="••••••••">
                </div>
                <button type="submit" class="btn-submit">Ingresar</button>
            </form>
            <div class="switch-route">
                ¿No tienes cuenta? <a href="<?= BASE_URL ?>index.php?controller=Auth&action=showRegister">Regístrate aquí</a>
            </div>
            <a href="<?= BASE_URL ?>index.php?controller=Home&action=index" class="back-home">← Volver al inicio</a>
        </div>
    </div>
</body>
</html>