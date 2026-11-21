<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Kardex - EduWeb</title>
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

        <div class="student-layout">
        
        <!-- ALERTA DE ERROR: CURSO SIN NIVELES -->
        <?php if(isset($_GET['error']) && $_GET['error'] === 'no_niveles'): ?>
            <div style="background-color: #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #f87171; text-align: center; font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                ⚠️ El curso al que intentaste acceder aún no tiene niveles ni clases registradas.<br>
                <span style="font-weight: normal; font-size: 0.9rem;">Por favor, contacta al instructor para más información.</span>
            </div>
        <?php elseif(isset($_GET['error']) && $_GET['error'] === 'error_nivel'): ?>
            <div style="background-color: #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #f87171; text-align: center; font-weight: bold;">
                ⚠️ Ocurrió un error al intentar cargar el nivel del curso.
            </div>
        <?php endif; ?>

        <h2 class="section-title">Mi Kardex de Cursos</h2>
        <!-- El resto de tu código sigue igual... -->

        <!-- Filtros visuales (se pueden programar a futuro) -->
                <form class="filter-bar" action="<?= BASE_URL ?>index.php" method="GET">
            <!-- Parámetros ocultos para el Router -->
            <input type="hidden" name="controller" value="Student">
            <input type="hidden" name="action" value="kardex">

            <div class="filter-group">
                <label>Fecha Inscripción (Desde)</label>
                <input type="date" name="fecha_inicio" value="<?= htmlspecialchars($_GET['fecha_inicio'] ?? '') ?>">
            </div>
            
            <div class="filter-group">
                <label>Fecha Inscripción (Hasta)</label>
                <input type="date" name="fecha_fin" value="<?= htmlspecialchars($_GET['fecha_fin'] ?? '') ?>">
            </div>
            
            <div class="filter-group">
                <label>Categoría</label>
                <select name="categoria_id">
                    <option value="0">Todas</option>
                    <?php if(!empty($categorias)): ?>
                        <?php foreach($categorias as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= (isset($_GET['categoria_id']) && $_GET['categoria_id'] == $cat['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="filter-group">
                <label>Estatus Completado</label>
                <select name="completado">
                    <option value="">Todos</option>
                    <option value="terminados" <?= (isset($_GET['completado']) && $_GET['completado'] === 'terminados') ? 'selected' : '' ?>>Solo terminados</option>
                    <option value="incompletos" <?= (isset($_GET['completado']) && $_GET['completado'] === 'incompletos') ? 'selected' : '' ?>>Solo en curso</option>
                </select>
            </div>

            <div class="filter-group">
                <label>Estatus Curso</label>
                <select name="estatus_curso">
                    <option value="">Todos</option>
                    <option value="activos" <?= (isset($_GET['estatus_curso']) && $_GET['estatus_curso'] === 'activos') ? 'selected' : '' ?>>Solo activos</option>
                    <option value="baja" <?= (isset($_GET['estatus_curso']) && $_GET['estatus_curso'] === 'baja') ? 'selected' : '' ?>>Dados de baja</option>
                </select>
            </div>

            <div style="display: flex; gap: 10px; width: 100%; margin-top: 10px;">
                <button type="submit" class="btn-submit" style="width: auto; padding: 0.75rem 1.5rem;">Aplicar Filtros</button>
                <!-- Botón para limpiar filtros -->
                <a href="<?= BASE_URL ?>index.php?controller=Student&action=kardex" class="btn-action" style="padding: 0.75rem 1.5rem; background-color: #64748b; text-decoration: none;">Limpiar Filtros</a>
            </div>
        </form>

        <div class="course-grid">
            <?php if(empty($cursos)): ?>
                <p style="grid-column: 1 / -1; text-align: center; color: #64748b; padding: 2rem;">
                    Aún no estás inscrito en ningún curso. <a href="<?= BASE_URL ?>index.php?controller=Home&action=index" style="color: #38bdf8;">¡Explora el catálogo!</a>
                </p>
            <?php else: ?>
                <?php foreach($cursos as $curso): ?>
                    <div class="course-card">
                        <?php 
                            $imgUrl = $curso['imagen'] ? BASE_URL . 'public/uploads/' . $curso['imagen'] : BASE_URL . 'public/uploads/default.jpg'; 
                            $porcentaje = number_format($curso['porcentaje_avance'], 0);
                        ?>
                        <div class="course-img" style="background-image: url('<?= $imgUrl ?>'); background-size: cover; background-position: center; height: 150px;"></div>
                        
                        <div class="course-info">
<h4>
    <?= htmlspecialchars($curso['titulo']) ?>
    <?php if(isset($curso['activo']) && $curso['activo'] == 0): ?>
        <span style="font-size: 0.65rem; background: #ef4444; color: white; padding: 2px 5px; border-radius: 4px; margin-left: 5px; vertical-align: middle;">Inactivo</span>
    <?php endif; ?>
</h4>
                            <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 0.5rem;">
                                Inscrito: <?= date('d-M-Y', strtotime($curso['fecha_inscripcion'])) ?>
                            </p>
                            
                            <div class="progress-text">
                                <span><?= $porcentaje >= 100 ? 'Completado' : 'Progreso' ?></span>
                                <span><?= $porcentaje ?>%</span>
                            </div>
                            <div class="progress-container">
                                <div class="progress-bar" style="width: <?= $porcentaje ?>%; <?= $porcentaje >= 100 ? 'background-color: #10b981;' : '' ?>"></div>
                            </div>
                            
                            <div style="display: flex; gap: 0.5rem; margin-top: 1rem;">
                                <a href="<?= BASE_URL ?>index.php?controller=Student&action=fase&curso_id=<?= $curso['id'] ?>" class="btn-action" style="flex: 1; text-align: center; font-size: 0.85rem; padding: 0.5rem; background-color: #475569;">Ver Clases</a>
    
                                <?php if($porcentaje >= 100): ?>
                                    <!-- Botón de Certificado (Nuevo) -->
                                    <a href="<?= BASE_URL ?>index.php?controller=Student&action=certificado&curso_id=<?= $curso['id'] ?>" class="btn-action" style="flex: 1; text-align: center; font-size: 0.85rem; padding: 0.5rem; background-color: #8b5cf6;">Ver Diploma</a>
        
                                    <a href="<?= BASE_URL ?>index.php?controller=Student&action=comentarios&curso_id=<?= $curso['id'] ?>" class="btn-action" style="flex: 1; text-align: center; font-size: 0.85rem; padding: 0.5rem; background-color: #eab308;">Calificar</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <script src="<?= BASE_URL ?>public/js/profile-menu.js"></script>
</body>
</html>