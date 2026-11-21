<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Curso - Instructor</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/styles.css">
    <style>
        /* Estilos para las Pills de Categorías */
        .category-pill input[type="checkbox"] { display: none; }
        .category-pill span {
            display: inline-block; padding: 6px 12px; border-radius: 20px; 
            border: 1px solid #cbd5e1; background: #fff; cursor: pointer; 
            transition: all 0.2s; font-size: 0.9rem;
        }
        .category-pill input[type="checkbox"]:checked + span {
            background-color: #38bdf8; color: #0f172a; border-color: #38bdf8; font-weight: bold;
        }
        /* Estilos para el botón de eliminar nivel */
        .btn-remove-nivel {
            position: absolute; top: 15px; right: 15px; background: #ef4444; color: white;
            border: none; border-radius: 50%; width: 30px; height: 30px; cursor: pointer; font-weight: bold;
        }
    </style>
</head>
<body>
    <header style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 2rem; background-color: #0f172a; color: white;">
        <h1 style="margin: 0;"><a href="<?= BASE_URL ?>index.php?controller=Instructor&action=ventasResumen" style="color: white; text-decoration: none;">← Volver al Panel</a></h1>
        
        <div class="auth-buttons" style="display: flex; align-items: center; gap: 15px;">
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
                    <a href="<?= BASE_URL ?>index.php?controller=Instructor&action=ventasResumen">Mis Ventas</a>
                    <a href="<?= BASE_URL ?>index.php?controller=Student&action=perfil">Editar Perfil</a>
                    <a href="<?= BASE_URL ?>index.php?controller=Auth&action=logout" style="color: #ef4444;">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </header>

    <div class="student-layout" style="max-width: 800px;">
        <div class="form-card" style="max-width: 100%; padding: 2rem;">
            <h2>Alta de Nuevo Curso</h2>
            <p style="text-align:center; color:#64748b; margin-bottom:2rem;">Configura los datos del curso y su contenido inicial.</p>
            
            <form action="<?= BASE_URL ?>index.php?controller=Instructor&action=processCurso" method="POST" enctype="multipart/form-data">
                
                <!-- DATOS DEL CURSO -->
                <h3 style="color: #0f172a; margin-bottom: 1rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.5rem;">1. Datos Principales</h3>
                
                <div class="form-group">
                    <label for="title">Título del Curso *</label>
                    <input type="text" id="title" name="titulo" required placeholder="Ej. Desarrollo Web con PHP Puro">
                </div>
                
                <div class="form-group">
                    <label for="desc">Descripción General *</label>
                    <textarea id="desc" name="descripcion" required placeholder="Describe los objetivos del curso..." style="width:100%; height:80px; padding:0.75rem; border:1px solid #cbd5e1; border-radius:4px; font-size:1rem; resize:none;"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Categorías Asignadas (Haz clic para seleccionar)</label>
                    <!-- Contenedor con scroll para muchas categorías -->
                    <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 5px; max-height: 120px; overflow-y: auto; padding: 10px; border: 1px solid #e2e8f0; border-radius: 6px; background-color: #f8fafc;">
                        <?php if(empty($categorias)): ?>
                            <span style="font-size: 0.9rem; color: #64748b;">No hay categorías disponibles.</span>
                        <?php else: ?>
                            <?php foreach($categorias as $cat): ?>
                                <label class="category-pill">
                                    <input type="checkbox" name="categorias[]" value="<?= $cat['id'] ?>">
                                    <span><?= htmlspecialchars($cat['nombre']) ?></span>
                                </label>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="cover">Imagen de Portada *</label>
                    <input type="file" id="cover" name="cover" accept="image/*" required>
                </div>
                
                <div class="form-group">
                    <label for="price">Precio del Curso (MXN)</label>
                    <input type="number" id="price" name="price" min="0" step="0.01" placeholder="599.00" required>
                </div>

                <!-- VIDEO TRAILER -->
                <div class="form-group" style="background: #f8fafc; padding: 1rem; border-radius: 6px; border: 1px solid #cbd5e1;">
                    <label style="color: #0f172a; font-weight: bold;">🎬 Video de Presentación *</label>
                    <select name="trailer_tipo" onchange="toggleVideoInput(this)">
                        <option value="link">Link de YouTube</option>
                        <option value="file">Subir Archivo Local</option>
                    </select>
                    <div class="video-link" style="margin-top: 8px;">
                        <input type="url" name="trailer_link" placeholder="https://www.youtube.com/watch?v=...">
                    </div>
                    <div class="video-file" style="margin-top: 8px; display: none;">
                        <input type="file" name="trailer_file" accept="video/mp4,video/webm">
                    </div>
                </div>

                <!-- CONTENEDOR DE NIVELES -->
                <h3 style="color: #0f172a; margin-top: 2rem; margin-bottom: 1rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.5rem;">2. Contenido de los Niveles</h3>
                
                <div id="niveles-container">
                    <!-- Nivel 1 (Fijo) -->
                    <div class="nivel-block" style="background: #f8fafc; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #cbd5e1; position: relative;">
                        <h4 style="margin-top: 0;">Nivel 1 (Obligatorio)</h4>
                        
                        <div class="form-group">
                            <label>Título del Nivel *</label>
                            <input type="text" name="nivel_titulo[]" required placeholder="Ej. Introducción y Configuración">
                        </div>
                        
                        <div class="form-group">
                            <label>Video del Nivel *</label>
                            <select name="nivel_video_tipo[]" onchange="toggleVideoInput(this)">
                                <option value="link">Link de YouTube</option>
                                <option value="file">Subir Archivo Local</option>
                            </select>
                            <div class="video-link" style="margin-top: 8px;">
                                <input type="url" name="nivel_video_link[]" placeholder="https://www.youtube.com/watch?v=...">
                            </div>
                            <div class="video-file" style="margin-top: 8px; display: none;">
                                <input type="file" name="nivel_video_file[]" accept="video/mp4,video/webm">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Texto Explicativo</label>
                            <textarea name="nivel_contenido[]" style="width:100%; height:60px; padding:0.5rem; border:1px solid #cbd5e1; border-radius:4px; resize:none;" placeholder="Apuntes o explicación..."></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Material PDF (Opcional)</label>
                            <input type="file" name="nivel_pdf[]" accept=".pdf">
                        </div>
                    </div>
                </div>

                <button type="button" id="btn-add-nivel" class="btn-action" style="background-color: #475569; margin-bottom: 2rem;">+ Añadir otro nivel</button>

                <button type="submit" class="btn-submit accent" style="font-size: 1.1rem; padding: 1rem;">Publicar Curso Completo</button>
            </form>
        </div>
    </div>

    <!-- Template Oculto para Nuevos Niveles -->
    <template id="template-nivel">
        <div class="nivel-block" style="background: #f8fafc; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #cbd5e1; position: relative;">
            <button type="button" class="btn-remove-nivel" onclick="this.parentElement.remove()">X</button>
            <h4 style="margin-top: 0;">Nuevo Nivel</h4>
            
            <div class="form-group">
                <label>Título del Nivel *</label>
                <input type="text" name="nivel_titulo[]" required placeholder="Ej. Nivel 2">
            </div>
            
            <div class="form-group">
                <label>Video del Nivel *</label>
                <select name="nivel_video_tipo[]" onchange="toggleVideoInput(this)">
                    <option value="link">Link de YouTube</option>
                    <option value="file">Subir Archivo Local</option>
                </select>
                <div class="video-link" style="margin-top: 8px;">
                    <input type="url" name="nivel_video_link[]" placeholder="https://www.youtube.com/watch?v=...">
                </div>
                <div class="video-file" style="margin-top: 8px; display: none;">
                    <input type="file" name="nivel_video_file[]" accept="video/mp4,video/webm">
                </div>
            </div>
            
            <div class="form-group">
                <label>Texto Explicativo</label>
                <textarea name="nivel_contenido[]" style="width:100%; height:60px; padding:0.5rem; border:1px solid #cbd5e1; border-radius:4px; resize:none;" placeholder="Apuntes..."></textarea>
            </div>
            
            <div class="form-group">
                <label>Material PDF (Opcional)</label>
                <input type="file" name="nivel_pdf[]" accept=".pdf">
            </div>
        </div>
    </template>

    <script>
        // 1. Lógica para mostrar/ocultar inputs de video
        function toggleVideoInput(selectElement) {
            const parent = selectElement.closest('.form-group');
            const linkDiv = parent.querySelector('.video-link');
            const fileDiv = parent.querySelector('.video-file');
            const linkInput = linkDiv.querySelector('input');
            const fileInput = fileDiv.querySelector('input');

            if (selectElement.value === 'link') {
                linkDiv.style.display = 'block';
                fileDiv.style.display = 'none';
                linkInput.required = true;
                fileInput.required = false;
            } else {
                linkDiv.style.display = 'none';
                fileDiv.style.display = 'block';
                linkInput.required = false;
                fileInput.required = true;
            }
        }

        // 2. Lógica para añadir niveles
        document.getElementById('btn-add-nivel').addEventListener('click', function() {
            const container = document.getElementById('niveles-container');
            const template = document.getElementById('template-nivel');
            const clone = template.content.cloneNode(true);
            
            // Asignamos el required correcto al link del nuevo nivel
            clone.querySelector('.video-link input').required = true;
            
            container.appendChild(clone);
        });

        // Inicializar el primer nivel (obligatorio el link por defecto)
        document.querySelector('select[name="nivel_video_tipo[]"]').dispatchEvent(new Event('change'));
        document.querySelector('select[name="trailer_tipo"]').dispatchEvent(new Event('change'));
    </script>
    <script src="<?= BASE_URL ?>public/js/profile-menu.js"></script>
</body>
</html>