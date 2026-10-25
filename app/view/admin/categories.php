<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Categorías</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/styles.css">
</head>
<body>
    <header>
        <h1><a href="<?= BASE_URL ?>index.php?controller=Admin&action=categorias">EduWeb Central (Administrador)</a></h1>
        <div class="auth-buttons"><a href="<?= BASE_URL ?>index.php?controller=Auth&action=logout" style="border-color:#ef4444; color:#ef4444;">Salir</a></div>
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
                            <tr>
                                <td colspan="3" style="text-align:center;">No hay categorías registradas aún.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($categorias as $cat): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($cat['nombre'] ?? '') ?></strong></td>
                                    <!-- AQUÍ ESTÁ EL CAMBIO: Agregamos ?? '' por si está vacío -->
                                    <td><?= htmlspecialchars($cat['descripcion'] ?? '') ?></td>
                                    <td>
                                        <button class="btn-status blocked" style="padding:2px 6px;" onclick="confirm('¿Dar de baja lógica a esta categoría?')">Eliminar</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>