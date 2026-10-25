<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Curso - Instructor</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/styles.css">
</head>
<body>
    <header>
        <h2><a href="<?= BASE_URL ?>index.php?controller=Instructor&action=ventasResumen">← Volver al Panel</a></h2>
        <h1>Alta de Nuevo Curso</h1>
    </header>

    <div class="student-layout" style="max-width: 800px;">
        <div class="form-card" style="max-width: 100%; padding: 2rem;">
            <h2>Definición del Curso</h2>
            <br>
            <form action="<?= BASE_URL ?>index.php?controller=Instructor&action=processCurso" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Título del Curso</label>
                    <input type="text" id="title" name="titulo" required placeholder="Ej. Desarrollo Web con PHP Puro">
                </div>
                <div class="form-group">
                    <label for="desc">Descripción General</label>
                    <textarea id="desc" name="descripcion" required placeholder="Describe los objetivos del curso..." style="width:100%; height:80px; padding:0.75rem; border:1px solid #cbd5e1; border-radius:4px; font-size:1rem; resize:none;"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="categories">Categorías Asignadas</label>
                    <select id="categories" name="categorias[]" multiple style="height: 80px;">
                        <!-- Loop dinámico de categorías desde la BD -->
                        <?php foreach($categorias as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span style="font-size: 0.8rem; color: #64748b;">Mantén presionado Ctrl para seleccionar varias.</span>
                </div>
                
                <div class="form-group">
                    <label for="cover">Imagen de Portada del Curso</label>
                    <input type="file" id="cover" name="cover" accept="image/*" required>
                </div>
                
                <div class="form-group">
                    <label for="price">Precio del Curso (MXN)</label>
                    <input type="number" id="price" name="price" min="0" step="0.01" placeholder="599.00">
                </div>

                <button type="submit" class="btn-submit accent">Publicar Curso</button>
            </form>
        </div>
    </div>
</body>
</html>