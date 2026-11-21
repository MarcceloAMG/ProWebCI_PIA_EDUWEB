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
        <h2 style="margin: 0;"><a href="<?= BASE_URL ?>index.php?controller=Student&action=kardex" style="color: white; text-decoration: none;">← Volver al Kardex</a></h2>
    </header>

    <div class="form-wrapper">
        <div class="form-card" style="max-width: 550px;">
            <h2>Calificar Curso Finalizado</h2>
            <p style="font-size: 0.9rem; color: #64748b; text-align: center; margin-bottom: 1.5rem;">
                Tu opinión ayuda a mantener la calidad del portal.
            </p>

            <form action="<?= BASE_URL ?>index.php?controller=Student&action=processComment" method="POST">
                <!-- Recibimos el ID del curso desde la URL del Kardex -->
                <input type="hidden" name="curso_id" value="<?= $_GET['curso_id'] ?? 0 ?>">

                <div class="form-group" style="text-align: center;">
                    <label style="font-weight: bold;">¿Qué puntuación le das al curso?</label>
                    <select name="calificacion" required style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 4px; margin-top: 0.5rem;">
                        <option value="5">⭐⭐⭐⭐⭐ (5 - Excelente)</option>
                        <option value="4">⭐⭐⭐⭐ (4 - Muy bueno)</option>
                        <option value="3">⭐⭐⭐ (3 - Bueno)</option>
                        <option value="2">⭐⭐ (2 - Regular)</option>
                        <option value="1">⭐ (1 - Malo)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="review">Reseña Escrita</label>
                    <textarea id="review" name="review" required placeholder="Escribe tu comentario sobre el curso..." style="width:100%; height:120px; padding:0.75rem; border:1px solid #cbd5e1; border-radius:4px; font-size:1rem; resize:none;"></textarea>
                </div>

                <button type="submit" class="btn-submit accent">Publicar Reseña</button>
            </form>
        </div>
    </div>
</body>
</html>