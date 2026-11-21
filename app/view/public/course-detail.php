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
        
        <div class="auth-buttons" style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap; justify-content: flex-end;">
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
                <div class="price-tag" style="margin-bottom: 1.5rem;">Costo Completo: $<?= number_format($curso['costo'], 2) ?> <?= htmlspecialchars(PAYPAL_CURRENCY, ENT_QUOTES, 'UTF-8') ?></div>
                
                <?php if(isset($_SESSION['user_id'])): ?>
                    <?php if($_SESSION['rol'] !== 'estudiante'): ?>
                        <p style="color: #fbbf24;">Solo los estudiantes pueden inscribirse a los cursos.</p>
                    <?php elseif($isEnrolled): ?>
                        <a href="<?= BASE_URL ?>index.php?controller=Student&action=kardex" class="btn-action" style="display:block; text-align:center;">Ir a mi Kardex</a>
                    <?php else: ?>
                        
                        <!-- Contenedor de Botones de Pago -->
                        <div style="display: flex; flex-direction: column; gap: 10px; width: 100%; max-width: 350px;">
                            <?php $_SESSION['paypal_csrf'] ??= bin2hex(random_bytes(32)); ?>
                            <div id="paypal-button-container" style="width: 100%;" data-csrf="<?= htmlspecialchars($_SESSION['paypal_csrf'], ENT_QUOTES, 'UTF-8') ?>"></div>
                            <p id="paypal-status" role="status" aria-live="polite" hidden></p>
                            
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
                    $trailer = $curso['video_trailer'] ?? ''; 
                    $esLinkTrailer = (strpos($trailer, 'http') === 0);
                ?>
                <div style="background-color: #000; border-radius: 8px; overflow: hidden; position: relative; padding-bottom: 56.25%;">
                    <?php if ($esLinkTrailer): ?>
                        <?php 
                            $videoId = '';
                            if (strpos($trailer, 'youtube.com/watch?v=') !== false) {
                                $videoId = explode('v=', $trailer)[1];
                            } elseif (strpos($trailer, 'youtu.be/') !== false) {
                                $videoId = explode('youtu.be/', $trailer)[1];
                            }
                            $embedUrl = $videoId ? "https://www.youtube.com/embed/" . $videoId : $trailer;
                        ?>
                        <iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;" 
                                src="<?= htmlspecialchars($embedUrl) ?>" allowfullscreen></iframe>
                    <?php else: ?>
                        <video style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" controls>
                            <source src="<?= BASE_URL ?>public/uploads/videos/<?= htmlspecialchars($trailer) ?>" type="video/mp4">
                        </video>
                    <?php endif; ?>
                </div>
                <p style="text-align: center; color: #94a3b8; font-size: 0.85rem; margin-top: 0.5rem;">Video de presentación del curso</p>
            </div>
        </div>
    </div>

    <!-- Contenedor de Descripción -->
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

        <!-- ========================================== -->
        <!-- NUEVA SECCIÓN: COMENTARIOS Y RESEÑAS       -->
        <!-- ========================================== -->
        <div class="course-body" style="grid-column: 1 / -1; margin-top: 2rem; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <h3 style="color: #0f172a; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.5rem; margin-bottom: 1.5rem;">
                Reseñas de Alumnos (<?= count($comentarios ?? []) ?>)
            </h3>
            
            <?php if(empty($comentarios)): ?>
                <p style="color: #64748b; font-style: italic;">Este curso aún no tiene reseñas. Los alumnos pueden calificarlo una vez que lo terminen.</p>
            <?php else: ?>
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <?php foreach($comentarios as $c): ?>
                        <div style="border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem;">
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 0.5rem;">
                                <?php $rutaAv = 'public/uploads/avatars/' . $c['avatar']; ?>
                                <?php if(!empty($c['avatar']) && file_exists($rutaAv)): ?>
                                    <img src="<?= BASE_URL . $rutaAv ?>" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                                <?php else: ?>
                                    <div style="width: 40px; height: 40px; border-radius: 50%; background: #cbd5e1; display:flex; align-items:center; justify-content:center; font-weight:bold; color:#475569;">
                                        <?= strtoupper(substr($c['nombre_completo'], 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div>
                                    <strong style="color: #0f172a; display: block;"><?= htmlspecialchars($c['nombre_completo']) ?></strong>
                                    <span style="font-size: 0.8rem; color: #94a3b8;"><?= date('d/m/Y', strtotime($c['fecha_creacion'])) ?></span>
                                </div>
                                
                                <div style="margin-left: auto; color: #eab308; font-size: 1.2rem;">
                                    <?= str_repeat('★', $c['calificacion']) . str_repeat('☆', 5 - $c['calificacion']) ?>
                                </div>
                            </div>
                            <p style="color: #334155; margin: 0; padding-left: 50px;"><?= nl2br(htmlspecialchars($c['comentario'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <!-- SDK de PayPal -->
    <script src="https://www.paypal.com/sdk/js?client-id=<?= rawurlencode(PAYPAL_CLIENT_ID) ?>&amp;currency=<?= rawurlencode(PAYPAL_CURRENCY) ?>"></script>
    <script>
        const paypalContainer = document.getElementById('paypal-button-container');
        if (paypalContainer) {
            const paypalStatus = document.getElementById('paypal-status');
            const paymentUrl = <?= json_encode(BASE_URL . 'index.php?controller=Payment&action=', JSON_HEX_TAG) ?>;
            const courseId = <?= (int) $curso['id'] ?>;
            let navigating = false;

            function showPayPalStatus(message) {
                paypalStatus.textContent = message;
                paypalStatus.hidden = message === '';
            }

            function goToKardex(redirect) {
                navigating = true;
                window.location.assign(redirect);
            }

            async function requestPayPal(action, payload = {}) {
                const response = await fetch(paymentUrl + action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': paypalContainer.dataset.csrf
                    },
                    body: JSON.stringify({course_id: courseId, ...payload})
                });
                const result = await response.json();
                if (!response.ok && !result.restart) {
                    throw new Error(result.error || 'Hubo un error con PayPal.');
                }
                return result;
            }

            if (window.paypal) {
                paypal.Buttons({
                    async createOrder() {
                        showPayPalStatus('Preparando pago…');
                        const order = await requestPayPal('createOrder');
                        showPayPalStatus('');
                        if (order.redirect) {
                            goToKardex(order.redirect);
                            return;
                        }
                        return order.id;
                    },
                    async onApprove(data, actions) {
                        showPayPalStatus('Confirmando el pago…');
                        const result = await requestPayPal('capture', {order_id: data.orderID});
                        if (result.restart) {
                            showPayPalStatus(result.error);
                            return actions.restart();
                        }
                        if (!result.redirect) throw new Error('PayPal necesita que apruebes la orden de nuevo.');
                        goToKardex(result.redirect);
                    },
                    onCancel() {
                        showPayPalStatus('');
                    },
                    onError(error) {
                        if (!navigating) {
                            showPayPalStatus(error?.message || 'Hubo un error con PayPal. Intenta de nuevo.');
                        }
                    }
                }).render('#paypal-button-container').catch(() => {
                    showPayPalStatus('No se pudo abrir el formulario de PayPal.');
                });
            } else {
                showPayPalStatus('No se pudo cargar PayPal.');
            }
        }
    </script>
    <script src="<?= BASE_URL ?>public/js/profile-menu.js"></script>
</body>
</html>