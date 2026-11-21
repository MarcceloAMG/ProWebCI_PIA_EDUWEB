<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat con <?= htmlspecialchars($alumno['nombre_completo']) ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/styles.css">
</head>
<body>
    <header style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 2rem; background-color: #0f172a; color: white;">
        <h1 style="margin: 0;"><a href="<?= BASE_URL ?>index.php?controller=Instructor&action=ventasDetalle&id=<?= $curso['id'] ?>" style="color: white; text-decoration: none;">← Volver al Detalle</a></h1>
        
        <div class="auth-buttons" style="display: flex; align-items: center; gap: 15px;">
            <!-- Botón de Crear Curso -->
            <a href="<?= BASE_URL ?>index.php?controller=Instructor&action=cursoCrear" class="btn-primary" style="color: white; text-decoration: none; padding: 0.5rem 1rem; border-radius: 4px; border: 1px solid #38bdf8;">Crear Nuevo Curso</a>
            
            <!-- Menú de Perfil -->
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

    <div class="student-layout" style="max-width: 800px; margin-top: 2rem;">
        <div class="form-card" style="max-width: 100%; padding: 2rem;">
            <div style="text-align: center; margin-bottom: 2rem;">
                <h2 style="color: #0f172a; margin-bottom: 0.5rem;">Chat con <?= htmlspecialchars($alumno['nombre_completo']) ?></h2>
                <p style="color: #64748b; font-size: 0.9rem;">Curso: <?= htmlspecialchars($curso['titulo']) ?></p>
            </div>
            
            <!-- Historial del Chat -->
            <div class="chat-box" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; height: 400px; overflow-y: auto; padding: 1.5rem; margin-bottom: 1.5rem; display: flex; flex-direction: column;">
                <?php if(empty($mensajes)): ?>
                    <p style="text-align:center; color:#94a3b8; margin-top: auto; margin-bottom: auto;">No hay mensajes aún. Envía el primero para iniciar la conversación.</p>
                <?php else: ?>
                    <?php foreach($mensajes as $msg): ?>
                        <?php $esMio = ($msg['emisor_id'] == $_SESSION['user_id']); ?>
                        <div class="chat-message" style="margin-bottom: 1rem; <?= $esMio ? 'text-align: right;' : 'text-align: left;' ?>">
                            <div class="chat-meta" style="font-size: 0.75rem; color: #64748b; margin-bottom: 0.25rem;">
                                <strong><?= $esMio ? 'Tú (Instructor)' : htmlspecialchars($msg['nombre_completo']) ?></strong> - <?= date('d/m/Y H:i', strtotime($msg['fecha_envio'])) ?>
                            </div>
                            <div style="display: inline-block; padding: 0.75rem 1rem; border-radius: 8px; max-width: 80%; 
                                        <?= $esMio ? 'background-color: #dcfce7; color: #166534; border-bottom-right-radius: 0;' : 'background-color: #e2e8f0; color: #1e293b; border-bottom-left-radius: 0;' ?>">
                                <?= htmlspecialchars($msg['mensaje']) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Formulario para responder -->
            <form action="<?= BASE_URL ?>index.php?controller=Instructor&action=sendChatMessage" method="POST" style="display: flex; gap: 10px;">
                <input type="hidden" name="curso_id" value="<?= $curso['id'] ?>">
                <input type="hidden" name="alumno_id" value="<?= $alumno['id'] ?>">
                
                <input type="text" name="mensaje" placeholder="Escribe tu respuesta al alumno aquí..." required style="flex: 1; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 1rem;">
                <button type="submit" class="btn-submit accent" style="width: auto; padding: 0.75rem 1.5rem;">Enviar</button>
            </form>
        </div>
    </div>
    
    <!-- Script para el menú de perfil -->
    <script src="<?= BASE_URL ?>public/js/profile-menu.js"></script>
    
    <!-- Script para que el chat siempre haga scroll hasta el último mensaje -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chatBox = document.querySelector('.chat-box');
            if(chatBox) {
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        });
    </script>
</body>
</html>