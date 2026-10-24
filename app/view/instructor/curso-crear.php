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
            <h2>Definición del Contenido y Fases Autodidactas</h2>
            <br>
            <form action="<?= BASE_URL ?>index.php?controller=Instructor&action=processCurso" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Título del Curso</label>
                    <input type="text" id="title" name="titulo" required placeholder="Ej. Desarrollo Web con PHP Puro">
                </div>
                <div class="form-group">
                    <label for="desc">Descripción General</label>
                    <textarea id="desc" name="descripcion" required placeholder="Describe los objetivos y de qué trata el curso en general..." style="width:100%; height:80px; padding:0.75rem; border:1px solid #cbd5e1; border-radius:4px; font-size:1rem; resize:none;"></textarea>
                </div>
                <div class="form-group">
                    <label for="categories">Categorías Asignadas (Puede pertenecer a más de una)</label>
                    <select id="categories" name="categorias[]" multiple style="height: 80px;">
                        <option value="1">IT & Software</option>
                        <option value="2">Marketing</option>
                        <option value="3">Design / UI-UX</option>
                    </select>
                    <span style="font-size: 0.8rem; color: #64748b;">Mantén presionado Ctrl (Cmd en Mac) para seleccionar varias de la lista.</span>
                </div>
                <div class="form-group">
                    <label for="cover">Imagen de Portada del Curso</label>
                    <input type="file" id="cover" name="cover" accept="image/*" required>
                </div>
                <div class="form-group">
                    <label for="cost_type">Esquema de Costo</label>
                    <select id="cost_type" name="cost_type" required>
                        <option value="completo">Costo Único por Curso Completo</option>
                        <option value="niveles">Costo Individual por cada Nivel / Fase</option>
                        <option value="gratis">Curso Totalmente Gratis</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="price">Precio Global o Base (Símbolo de Moneda)</label>
                    <input type="number" id="price" name="price" min="0" step="0.01" placeholder="599.00">
                </div>

                <h3 class="section-title" style="margin-top: 2rem;">Configuración de Contenido por Fases</h3>
                
                <div class="phase-block">
                    <h4>Fase o Nivel 1</h4>
                    <div class="form-group">
                        <label>Título del Nivel</label>
                        <input type="text" name="nivel1_titulo" required placeholder="Ej. Introducción y Entorno">
                    </div>
                    <div class="form-group">
                        <label>Contenido en Texto</label>
                        <textarea name="nivel1_texto" placeholder="Texto explicativo para esta fase específica..." style="width:100%; height:60px; padding:0.75rem; border:1px solid #cbd5e1; border-radius:4px; resize:none;"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Video Explicativo de la Fase (Obligatorio por nivel)</label>
                        <input type="file" name="nivel1_video" accept="video/*" required>
                    </div>
                    <div class="form-group">
                        <label>Archivos Adjuntos (PDF)</label>
                        <input type="file" name="nivel1_pdf" accept=".pdf">
                    </div>
                </div>

                <div class="phase-block">
                    <h4>Fase o Nivel 2</h4>
                    <div class="form-group">
                        <label>Título del Nivel</label>
                        <input type="text" name="nivel2_titulo" placeholder="Ej. Implementación del Modelo Vista Controlador">
                    </div>
                    <div class="form-group">
                        <label>Video Explicativo de la Fase (Obligatorio por nivel)</label>
                        <input type="file" name="nivel2_video" accept="video/*">
                    </div>
                </div>

                <button type="submit" class="btn-submit accent">Publicar Curso e Iniciar Oferta</button>
            </form>
        </div>
    </div>
</body>
</html>