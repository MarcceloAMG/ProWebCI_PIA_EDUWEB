<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($curso['titulo']) ?> - EduWeb</title>
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

        <div class="hero-course">
        <div class="hero-img" style="background-image: url('<?= BASE_URL ?>public/uploads/<?= htmlspecialchars($curso['imagen'] ?? 'default.jpg') ?>'); background-size: cover; background-position: center;">
            <?php if(empty($curso['imagen'])): ?> Imagen del Curso <?php endif; ?>
        </div>
        
        <div class="hero-text" style="display: flex; flex-wrap: wrap; gap: 2rem; align-items: flex-start;">
            
            <!-- Lado Izquierdo: Info y Pagos -->
            <div style="flex: 1; min-width: 300px;">
                <h2 style="margin-top:0;"><?= htmlspecialchars($curso['titulo']) ?></h2>
                <p style="margin-bottom: 1rem;"><?= nl2br(htmlspecialchars($curso['descripcion'])) ?></p>
                <div class="price-tag" style="margin-bottom: 1.5rem;">Costo Completo: $<?= number_format($curso['costo'], 2) ?></div>
                
                <?php if(isset($_SESSION['user_id'])): ?>
                    <?php if($_SESSION['rol'] !== 'estudiante'): ?>
                        <p style="color: #fbbf24;">Solo los estudiantes pueden inscribirse a los cursos.</p>
                    <?php elseif($isEnrolled): ?>
                        <a href="<?= BASE_URL ?>index.php?controller=Student&action=kardex" class="btn-action" style="display:block; text-align:center;">Ir a mi Kardex</a>
                    <?php else: ?>
                        
                        <!-- Contenedor de Botones de Pago -->
                        <div style="display: flex; flex-direction: column; gap: 10px; width: 100%; max-width: 350px;">
                            <!-- Botón de PayPal -->
                            <div id="paypal-button-container" style="width: 100%;"></div>
                            
                            <!-- Opción 2: Transferencia -->
                            <a href="<?= BASE_URL ?>index.php?controller=Course&action=enroll&id=<?= $curso['id'] ?>&metodo=transferencia" 
                               class="btn-action" 
                               style="display:flex; justify-content:center; align-items:center; background-color:#475569; height: 45px; border-radius: 4px; text-decoration: none; color: white; font-weight: bold; width: 100%; box-sizing: border-box;">
                                Pagar por Transferencia (Simulado)
                            </a>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>index.php?controller=Auth&action=showLogin" class="btn-action">Inicia Sesión para Inscribirte</a>
                <?php endif; ?>
            </div>

            <!-- Lado Derecho: Video Trailer -->
            <div style="flex: 1; min-width: 300px;">
                <?php 
                    $videoUrl = null;
                    if (!empty($niveles) && !empty($niveles[0]['video_url'])) {
                        $videoUrl = $niveles[0]['video_url'];
                        if (strpos($videoUrl, 'youtube.com/watch?v=') !== false) {
                            $videoId = explode('v=', $videoUrl)[1];
                            $videoUrl = "https://www.youtube.com/embed/" . $videoId;
                        } elseif (strpos($videoUrl, 'youtu.be/') !== false) {
                            $videoId = explode('youtu.be/', $videoUrl)[1];
                            $videoUrl = "https://www.youtube.com/embed/" . $videoId;
                        }
                    }
                ?>

                <?php if ($videoUrl): ?>
                    <div style="background-color: #1e293b; border-radius: 8px; overflow: hidden; position: relative; padding-bottom: 56.25%;">
                        <iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;" 
                                src="<?= htmlspecialchars($videoUrl) ?>" 
                                allowfullscreen>
                        </iframe>
                    </div>
                    <p style="text-align: center; color: #94a3b8; font-size: 0.85rem; margin-top: 0.5rem;">Video de presentación</p>
                <?php else: ?>
                    <div style="background-color: #1e293b; border-radius: 8px; overflow: hidden; position: relative; padding-bottom: 56.25%;">
                        <iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;" 
                                src="https://www.youtube.com/embed/dQw4w9WgXcQ?autoplay=0" 
                                allowfullscreen>
                        </iframe>
                    </div>
                    <p style="text-align: center; color: #eab308; font-size: 0.85rem; margin-top: 0.5rem; font-weight: bold;">
                        ⚠️ El instructor no ha subido un video. Disfruta de este clásico.
                    </p>
                <?php endif; ?>
            </div>

        </div>
    </div>
    <!-- AQUÍ SE CIERRA EL HERO COURSE (Fondo oscuro) -->

    <!-- Contenedor de Descripción (Fondo claro) -->
    <div class="detail-container">
        <div class="course-body" style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <h3 style="color: #0f172a; margin-bottom: 1rem;">Descripción del Contenido</h3>
            <p style="color: #475569; line-height: 1.6; margin-bottom: 2rem;"><?= nl2br(htmlspecialchars($curso['descripcion'])) ?></p>
            
            <h3 style="color: #0f172a; margin-bottom: 1rem; border-top: 1px solid #e2e8f0; padding-top: 1.5rem;">Estructura de Niveles</h3>
            <ul class="level-list" style="list-style: none; padding: 0;">
                <?php if(empty($niveles)): ?>
                    <li class="level-item" style="background: #f8fafc; padding: 1rem; border-radius: 6px; color: #64748b; text-align: center;">
                        Este curso aún no tiene niveles registrados.
                    </li>
                <?php else: ?>
                    <?php foreach($niveles as $nivel): ?>
                        <li class="level-item" style="background: #f8fafc; padding: 1rem; margin-bottom: 0.5rem; border-radius: 6px; display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-weight: 500; color: #0f172a;">Nivel <?= $nivel['orden'] ?>: <?= htmlspecialchars($nivel['titulo']) ?></span>
                            
                            <?php if($isEnrolled): ?>
                                <a href="<?= BASE_URL ?>index.php?controller=Student&action=fase&nivel_id=<?= $nivel['id'] ?>" class="btn-action" style="padding: 0.3rem 0.8rem; font-size: 0.85rem;">Ver Clase</a>
                            <?php else: ?>
                                <?php if($nivel['costo'] == 0): ?>
                                    <strong style="color: #059669;">Gratis - <a href="<?= BASE_URL ?>index.php?controller=Student&action=fase&nivel_id=<?= $nivel['id'] ?>" style="color: #059669;">Ver Muestra</a></strong>
                                <?php else: ?>
                                    <span style="color: #ef4444; font-weight: bold;">🔒 Requiere Inscripción</span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>

        <aside class="course-meta-box">
            <h4>Información General</h4>
            <p>⭐ <strong>Calificación:</strong> <?= ($promedio > 0) ? number_format($promedio, 1) . ' / 5.0' : 'Sin calificaciones aún' ?></p>
            <p>📂 <strong>Categoría:</strong> <?= empty($categorias) ? 'Sin categoría' : htmlspecialchars(implode(', ', $categorias)) ?></p>
            <p>👤 <strong>Instructor:</strong> <?= htmlspecialchars($curso['instructor']) ?></p>
        </aside>
    </div>

    <!-- SDK de PayPal -->
    <script src="https://www.paypal.com/sdk/js?client-id=<?= PAYPAL_CLIENT_ID ?>&currency=USD"></script>
    <script>
        if (document.getElementById('paypal-button-container')) {
            paypal.Buttons({
                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: { value: '<?= number_format($curso['costo'], 2, '.', '') ?>' },
                            description: 'Curso: <?= htmlspecialchars($curso['titulo']) ?>'
                        }]
                    });
                },
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(details) {
                        window.location.href = '<?= BASE_URL ?>index.php?controller=Payment&action=capture&course_id=<?= $curso['id'] ?>&order_id=' + data.orderID;
                    });
                },
                onError: function (err) {
                    alert('Hubo un error con el pago. Intenta de nuevo.');
                    console.error(err);
                }
            }).render('#paypal-button-container');
        }
    </script>
</body>
</html>