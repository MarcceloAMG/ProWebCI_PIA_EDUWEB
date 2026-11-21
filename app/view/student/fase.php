<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($nivel['titulo']) ?> - EduWeb</title>
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

    <div class="student-layout" style="max-width: 1300px; width: 95%;">
        
        <div class="course-viewer">
            
            <!-- COLUMNA IZQUIERDA: VIDEO Y CONTENIDO -->
            <div>
                <!-- VIDEO DINÁMICO -->
                <?php 
                    $videoUrl = $nivel['video_url'] ?? ''; 
                    $esLink = (strpos($videoUrl, 'http') === 0);
                ?>
                <div style="background-color: #000; border-radius: 8px; overflow: hidden; position: relative; padding-bottom: 56.25%; margin-bottom: 2rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <?php if ($esLink): ?>
                        <?php 
                            $videoId = '';
                            if (strpos($videoUrl, 'youtube.com/watch?v=') !== false) {
                                $videoId = explode('v=', $videoUrl)[1];
                            } elseif (strpos($videoUrl, 'youtu.be/') !== false) {
                                $videoId = explode('youtu.be/', $videoUrl)[1];
                            }
                            // Agregamos enablejsapi=1 para poder controlarlo con JS
                            $embedUrl = $videoId ? "https://www.youtube.com/embed/" . $videoId . "?enablejsapi=1&rel=0" : $videoUrl;
                        ?>
                        <iframe id="video-player" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;" 
                                src="<?= htmlspecialchars($embedUrl) ?>" allowfullscreen></iframe>
                    <?php else: ?>
                        <video id="video-player" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" controls>
                            <source src="<?= BASE_URL ?>public/uploads/videos/<?= htmlspecialchars($videoUrl) ?>" type="video/mp4">
                            Tu navegador no soporta videos HTML5.
                        </video>
                    <?php endif; ?>
                </div>

                <!-- INFORMACIÓN DEL NIVEL -->
                <div class="tabs-container" style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #e2e8f0; padding-bottom: 1rem; margin-bottom: 1.5rem;">
                        <h2 style="color: #0f172a; margin: 0;">Nivel <?= $nivel['orden'] ?>: <?= htmlspecialchars($nivel['titulo']) ?></h2>
                    </div>
                    
                    <h4 style="color: #475569; margin-bottom: 0.5rem;">📚 Recursos Complementarios</h4>
                    <ul class="resource-list" style="margin-bottom: 1.5rem; padding-left: 0; list-style: none;">
                        <?php if(!empty($nivel['archivo_pdf'])): ?>
                            <li style="margin-bottom: 0.5rem;">
                                <a href="<?= BASE_URL ?>public/uploads/<?= htmlspecialchars($nivel['archivo_pdf']) ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 8px; background: #f1f5f9; padding: 0.75rem 1rem; border-radius: 6px; color: #0284c7; text-decoration: none; font-weight: bold; width: fit-content;">
                                    📄 Descargar Material de Apoyo (PDF)
                                </a>
                            </li>
                        <?php else: ?>
                            <li style="color: #94a3b8; font-size: 0.9rem; font-style: italic;">El instructor no adjuntó archivos PDF para este nivel.</li>
                        <?php endif; ?>
                    </ul>
                    
                    <h4 style="color: #475569; margin-bottom: 0.5rem;">📝 Descripción de la Clase</h4>
                    <p style="color: #334155; line-height: 1.7; font-size: 1rem; background: #f8fafc; padding: 1rem; border-radius: 6px; border-left: 4px solid #38bdf8;">
                        <?= !empty($nivel['contenido_texto']) ? nl2br(htmlspecialchars($nivel['contenido_texto'])) : 'Sin descripción de texto para este nivel.' ?>
                    </p>

                    <!-- BOTÓN PARA COMPLETAR NIVEL (INICIALMENTE DESHABILITADO) -->
                    <form action="<?= BASE_URL ?>index.php?controller=Student&action=completeLevel" method="POST" style="margin-top: 2rem; text-align: right;">
                        <input type="hidden" name="inscripcion_id" value="<?= $inscripcion['id'] ?>">
                        <input type="hidden" name="nivel_orden" value="<?= $nivel['orden'] ?>">
                        <input type="hidden" name="porcentaje_actual" value="<?= $inscripcion['porcentaje_avance'] ?>"> 
                        
                        <!-- Botón deshabilitado por defecto -->
                        <button type="submit" id="btn-completar" class="btn-action" disabled 
                                style="background-color: #94a3b8; color: white; padding: 0.8rem 1.5rem; font-size: 1rem; cursor: not-allowed; border: none;">
                            ⏳ Mira el video completo para avanzar
                        </button>
                    </form>
                </div>
            </div>

            <!-- COLUMNA DERECHA: CHAT (TABS) -->
            <aside class="tabs-container" style="height: fit-content; background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <h3 style="color: #0f172a; margin-top: 0;">Dudas sobre este Nivel</h3>
                <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 1rem;">Canal directo con el Instructor</p>
                
                <div class="chat-box" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; height: 350px; overflow-y: auto; padding: 1rem; margin-bottom: 1rem;">
                    <?php if(empty($mensajes)): ?>
                        <p style="text-align:center; color:#94a3b8; font-size:0.9rem; margin-top: 2rem;">No hay mensajes aún.<br>¡Sé el primero en preguntar!</p>
                    <?php else: ?>
                        <?php foreach($mensajes as $msg): ?>
                            <?php $esMio = ($msg['emisor_id'] == $_SESSION['user_id']); ?>
                            <div class="chat-message" style="<?= $esMio ? 'background-color: #dcfce7; margin-left: 20px;' : 'margin-right: 20px;' ?> padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                                <div class="chat-meta" style="<?= $esMio ? 'color: #16a34a;' : '' ?> font-size: 0.75rem; color: #64748b; margin-bottom: 0.25rem;">
                                    <strong><?= $esMio ? 'Tú' : htmlspecialchars($msg['nombre_completo']) ?></strong> - <?= date('d/m/Y H:i', strtotime($msg['fecha_envio'])) ?>
                                </div>
                                <p style="font-size: 0.9rem; margin: 0;"><?= htmlspecialchars($msg['mensaje']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <form action="<?= BASE_URL ?>index.php?controller=Student&action=sendMessage" method="POST">
                    <input type="hidden" name="curso_id" value="<?= $nivel['curso_id'] ?>">
                    <input type="hidden" name="receptor_id" value="<?= $nivel['instructor_id'] ?>">
                    <input type="hidden" name="nivel_id" value="<?= $nivel['id'] ?>">
                    
                    <div class="form-group" style="margin-bottom: 0.75rem;">
                        <input type="text" name="mensaje" placeholder="Escribe tu duda aquí..." required style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 4px;">
                    </div>
                    <button type="submit" class="btn-submit" style="font-size: 0.9rem; padding: 0.75rem;">Enviar Mensaje</button>
                </form>
            </aside>
            
        </div>
    </div>
    
    <script src="<?= BASE_URL ?>public/js/profile-menu.js"></script>

    <!-- SCRIPT DE CONTROL DE VIDEO -->
    <script>
        const btnCompletar = document.getElementById('btn-completar');
        const esYoutube = <?= $esLink ? 'true' : 'false' ?>;

        function habilitarBoton() {
            btnCompletar.disabled = false;
            btnCompletar.style.backgroundColor = '#10b981';
            btnCompletar.style.cursor = 'pointer';
            btnCompletar.innerHTML = '✅ Marcar Nivel como Completado';
        }

        if (esYoutube) {
            // === LÓGICA PARA YOUTUBE ===
            var tag = document.createElement('script');
            tag.src = "https://www.youtube.com/iframe_api";
            var firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

            var player;
            function onYouTubeIframeAPIReady() {
                player = new YT.Player('video-player', {
                    events: {
                        'onStateChange': onPlayerStateChange
                    }
                });
            }

            function onPlayerStateChange(event) {
                // YT.PlayerState.ENDED = 0 (El video terminó)
                if (event.data === 0) {
                    habilitarBoton();
                }
            }
        } else {
            // === LÓGICA PARA VIDEO LOCAL (HTML5) ===
            const video = document.getElementById('video-player');
            
            if (video) {
                // 1. Habilitar botón al terminar
                video.addEventListener('ended', function() {
                    habilitarBoton();
                });

                // 2. ANTI-SKIP: Bloquear adelantar la barra
                let maxTime = 0;
                video.addEventListener('timeupdate', function() {
                    if (video.currentTime > maxTime) {
                        maxTime = video.currentTime;
                    }
                });

                video.addEventListener('seeking', function() {
                    // Si intentan adelantar más de 1 segundo
                    if (video.currentTime > maxTime + 1) {
                        video.currentTime = maxTime; // Los regresamos
                        alert("⚠️ No puedes saltarte partes del video. Por favor, míralo completo para completar el nivel.");
                    }
                });
            }
        }
    </script>
</body>
</html>