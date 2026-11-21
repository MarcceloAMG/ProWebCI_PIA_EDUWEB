<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clase - <?= htmlspecialchars($nivel['curso_titulo'] ?? 'Curso') ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/styles.css">
</head>
<body>
    <header style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 2rem; background-color: #0f172a; color: white;">
        <h1 style="margin: 0;"><a href="<?= BASE_URL ?>index.php?controller=Home&action=index" style="color: white; text-decoration: none;">EduWeb</a></h1>
        
        <!-- Contenedor de botones y perfil alineados a la derecha -->
        <div class="auth-buttons" style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap; justify-content: flex-end;">
            <?php if(isset($_SESSION['user_id'])): ?>
                
                <!-- Bloque de Perfil Clickable con Menú Desplegable -->
                <div class="profile-menu-container">
                    <div class="profile-trigger">
                        <?php 
                            $avatarFile = $_SESSION['avatar'] ?? '';
                            $rutaFisica = 'public/uploads/avatars/' . $avatarFile;
                            
                            if (!empty($avatarFile) && file_exists($rutaFisica)): 
                        ?>
                            <img src="<?= BASE_URL ?>public/uploads/avatars/<?= htmlspecialchars($avatarFile) ?>" alt="Avatar" class="profile-avatar-img">
                        <?php else: ?>
                            <div class="profile-avatar-fallback">
                                <?= strtoupper(substr($_SESSION['nombre'] ?? 'U', 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Menú que se despliega -->
                    <div class="profile-dropdown">
                        <div class="dropdown-header">
                            <strong><?= htmlspecialchars($_SESSION['nombre']) ?></strong>
                            <span><?= ucfirst($_SESSION['rol']) ?></span>
                        </div>
                        
                        <?php if($_SESSION['rol'] === 'estudiante'): ?>
                            <a href="<?= BASE_URL ?>index.php?controller=Student&action=kardex">Mi Kardex</a>
                        <?php elseif($_SESSION['rol'] === 'instructor'): ?>
                            <a href="<?= BASE_URL ?>index.php?controller=Instructor&action=ventasResumen">Panel Instructor</a>
                        <?php elseif($_SESSION['rol'] === 'administrador'): ?>
                            <a href="<?= BASE_URL ?>index.php?controller=Admin&action=usuarios">Panel Admin</a>
                        <?php endif; ?>
                        
                        <a href="<?= BASE_URL ?>index.php?controller=Student&action=perfil">Editar Perfil</a>
                        <a href="<?= BASE_URL ?>index.php?controller=Auth&action=logout" style="color: #ef4444;">Cerrar Sesión</a>
                    </div>
                </div>
            
            <?php else: ?>
                <!-- Si NO hay sesión, mostramos estos dos botones -->
                <a href="<?= BASE_URL ?>index.php?controller=Auth&action=showLogin" style="color: white; text-decoration: none; padding: 0.5rem 1rem; border-radius: 4px; border: 1px solid #38bdf8; white-space: nowrap;">Iniciar Sesión</a>
                <a href="<?= BASE_URL ?>index.php?controller=Auth&action=showRegister" style="background-color: #38bdf8; color: #0f172a; text-decoration: none; padding: 0.5rem 1rem; border-radius: 4px; font-weight: bold; border: none; white-space: nowrap;">Registrarse</a>
            <?php endif; ?>
        </div>
    </header>

    <div class="student-layout">
        <div class="course-viewer">
            <div>
                <!-- VIDEO DINÁMICO -->
                <div style="background-color: #1e293b; border-radius: 8px; overflow: hidden; position: relative; padding-bottom: 56.25%; margin-bottom: 1.5rem;">
                    <?php 
                        $videoUrl = $nivel['video_url'] ?? '';
                        if (!empty($videoUrl)) {
                            if (strpos($videoUrl, 'youtube.com/watch?v=') !== false) {
                                $videoId = explode('v=', $videoUrl)[1];
                                $videoUrl = "https://www.youtube.com/embed/" . $videoId;
                            } elseif (strpos($videoUrl, 'youtu.be/') !== false) {
                                $videoId = explode('youtu.be/', $videoUrl)[1];
                                $videoUrl = "https://www.youtube.com/embed/" . $videoId;
                            }
                        } else {
                            $videoUrl = "https://www.youtube.com/embed/dQw4w9WgXcQ"; // Rickroll si no hay video
                        }
                    ?>
                    <iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;" 
                            src="<?= htmlspecialchars($videoUrl) ?>?autoplay=0" allowfullscreen></iframe>
                </div>

                <div class="tabs-container">
                    <h3>Nivel <?= $nivel['orden'] ?>: <?= htmlspecialchars($nivel['titulo']) ?></h3>
                    
                    <!-- RECURSOS DINÁMICOS -->
                    <ul class="resource-list">
                        <?php if(!empty($nivel['archivo_pdf'])): ?>
                            <li class="resource-item">
                                📄 <a href="<?= BASE_URL ?>public/uploads/<?= htmlspecialchars($nivel['archivo_pdf']) ?>" target="_blank">Descargar Material PDF</a>
                            </li>
                        <?php else: ?>
                            <li class="resource-item" style="color: #64748b;">📄 Sin archivos adjuntos</li>
                        <?php endif; ?>
                    </ul>
                    
                    <!-- TEXTO DINÁMICO -->
                    <p style="margin-top: 1.5rem; color: #475569; font-size: 0.95rem; line-height: 1.5;">
                        <?= !empty($nivel['contenido_texto']) ? nl2br(htmlspecialchars($nivel['contenido_texto'])) : 'Sin descripción de texto para este nivel.' ?>
                    </p>

                                        <!-- BOTÓN PARA COMPLETAR NIVEL -->
                    <form action="<?= BASE_URL ?>index.php?controller=Student&action=completeLevel" method="POST" style="margin-top: 2rem; text-align: right;">
                        <input type="hidden" name="inscripcion_id" value="<?= $inscripcion['id'] ?>">
                        <input type="hidden" name="nivel_orden" value="<?= $nivel['orden'] ?>">
                        <!-- Aquí pasamos el porcentaje REAL que el alumno tiene en la BD -->
                        <input type="hidden" name="porcentaje_actual" value="<?= $inscripcion['porcentaje_avance'] ?>"> 
                        
                        <button type="submit" class="btn-action" style="background-color: #38bdf8; color: #0f172a;">Marcar Nivel como Completado</button>
                    </form>
                </div>
            </div>

            <!-- CHAT DINÁMICO -->
            <aside class="tabs-container" style="height: fit-content;">
                <h3>Dudas sobre este Nivel</h3>
                <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 1rem;">Canal directo con el Instructor</p>
                
                <div class="chat-box">
                    <?php if(empty($mensajes)): ?>
                        <p style="text-align:center; color:#94a3b8; font-size:0.9rem;">No hay mensajes aún.</p>
                    <?php else: ?>
                        <?php foreach($mensajes as $msg): ?>
                            <?php $esMio = ($msg['emisor_id'] == $_SESSION['user_id']); ?>
                            <div class="chat-message" style="<?= $esMio ? 'background-color: #f0fdf4; margin-left: 20px;' : 'margin-right: 20px;' ?>">
                                <div class="chat-meta" style="<?= $esMio ? 'color: #16a34a;' : '' ?>">
                                    <strong><?= $esMio ? 'Tú' : htmlspecialchars($msg['nombre_completo']) ?></strong> - <?= date('d/m/Y H:i', strtotime($msg['fecha_envio'])) ?>
                                </div>
                                <p style="font-size: 0.9rem;"><?= htmlspecialchars($msg['mensaje']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <form action="<?= BASE_URL ?>index.php?controller=Student&action=sendMessage" method="POST">
                    <input type="hidden" name="curso_id" value="<?= $nivel['curso_id'] ?>">
                    <input type="hidden" name="receptor_id" value="<?= $nivel['instructor_id'] ?>">
                    <input type="hidden" name="nivel_id" value="<?= $nivel['id'] ?>">
                    
                    <div class="form-group" style="margin-bottom: 0.75rem;">
                        <input type="text" name="mensaje" placeholder="Escribe tu duda aquí..." required>
                    </div>
                    <button type="submit" class="btn-submit" style="font-size: 0.9rem; padding: 0.5rem;">Enviar Mensaje</button>
                </form>
            </aside>
        </div>
    </div>
    
    <script src="<?= BASE_URL ?>public/js/profile-menu.js"></script>
</body>
</html>