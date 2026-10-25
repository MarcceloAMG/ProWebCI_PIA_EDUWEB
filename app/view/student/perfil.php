<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - EduWeb</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/styles.css">
</head>
<body>
    <header>
        <h2><a href="<?= BASE_URL ?>index.php?controller=Student&action=kardex">← Volver a mi Panel</a></h2>
        <h1>Configuración de Cuenta</h1>
    </header>

    <div class="form-wrapper">
        <div class="form-card" style="max-width: 550px;">
            <h2>Actualizar Datos Personales</h2>
            <p style="font-size: 0.9rem; color: #64748b; text-align: center; margin-bottom: 1.5rem;">
                Modifica tu información de registro. Ten cuidado con la llave de acceso.
            </p>

            <form action="<?= BASE_URL ?>index.php?controller=Student&action=updateProfile" method="POST" enctype="multipart/form-data">
                
                <div class="form-group">
                    <label for="fullname">Nombre Completo</label>
                    <input type="text" id="fullname" name="fullname" value="<?= htmlspecialchars($user['nombre_completo'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="gender">Género</label>
                    <select id="gender" name="gender" required>
                        <option value="M" <?= ($user['genero'] ?? '') === 'M' ? 'selected' : '' ?>>Masculino</option>
                        <option value="F" <?= ($user['genero'] ?? '') === 'F' ? 'selected' : '' ?>>Femenino</option>
                        <option value="O" <?= ($user['genero'] ?? '') === 'O' ? 'selected' : '' ?>>Otro</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="birthdate">Fecha de Nacimiento</label>
                    <input type="date" id="birthdate" name="birthdate" value="<?= htmlspecialchars($user['fecha_nacimiento'] ?? '') ?>" required>
                </div>

                               <div class="form-group">
                    <label>Foto de Perfil (Avatar)</label>
                    <div class="avatar-preview-container" style="margin-bottom: 0.5rem;">
                        <?php 
                            $avatarFile = $user['avatar'] ?? '';
                            $rutaFisica = 'public/uploads/avatars/' . $avatarFile;
                            
                            if (!empty($avatarFile) && file_exists($rutaFisica)): 
                        ?>
                            <img src="<?= BASE_URL ?>public/uploads/avatars/<?= htmlspecialchars($avatarFile) ?>" 
                                 alt="Avatar" 
                                 style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
                        <?php else: ?>
                            <div class="avatar-current" style="width: 60px; height: 60px; border-radius: 50%; background-color: #cbd5e1; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold; color: #475569;">
                                <?= strtoupper(substr($user['nombre_completo'] ?? 'U', 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                        <span style="font-size: 0.85rem; color: #475569;">Foto actual cargada en el sistema.</span>
                    </div>
                    <input type="file" id="avatar" name="avatar" accept="image/*">
                    <span style="font-size: 0.8rem; color: #64748b;">Selecciona un nuevo archivo solo si deseas cambiar tu avatar.</span>
                </div>
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                </div>

                <div class="form-group" style="border-top: 1px solid #f1f5f9; padding-top: 1rem; margin-top: 1.5rem;">
                    <label for="password">Nueva Contraseña (Opcional)</label>
                    <input type="password" id="password" name="password" placeholder="Dejar en blanco para mantener la actual">
                    <span style="font-size: 0.8rem; color: #64748b; display: block; margin-top: 0.25rem;">
                        Si la cambias, debe cumplir las reglas: mínimo 8 caracteres, una mayúscula, un número y un carácter especial.
                    </span>
                </div>

                <button type="submit" class="btn-submit accent">Guardar Cambios</button>
            </form>
        </div>
    </div>
</body>
</html>