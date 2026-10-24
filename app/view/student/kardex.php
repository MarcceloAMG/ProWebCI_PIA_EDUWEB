<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Kardex - EduWeb</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/styles.css">
</head>
<body>
    <header>
        <h1><a href="<?= BASE_URL ?>index.php?controller=Student&action=kardex">EduWeb (Estudiante)</a></h1>
        <div class="auth-buttons">
            <a href="<?= BASE_URL ?>index.php?controller=Home&action=index" style="border-color: #ef4444; color: #ef4444;">Cerrar Sesión</a>
        </div>
    </header>

    <div class="student-layout">
        <h2 class="section-title">Mi Kardex de Cursos</h2>

        <form class="filter-bar" action="#" method="GET">
            <div class="filter-group"><label>Fecha Inscripción (Desde)</label><input type="date"></div>
            <div class="filter-group"><label>Fecha Inscripción (Hasta)</label><input type="date"></div>
            <div class="filter-group">
                <label>Categoría</label>
                <select>
                    <option value="">Todas</option>
                    <option value="1">IT & Software</option>
                    <option value="2">Marketing</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Estatus Completado</label>
                <select>
                    <option value="">Todos</option>
                    <option value="terminados">Solo terminados</option>
                    <option value="incompletos">Solo en curso</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Estatus Curso</label>
                <select>
                    <option value="">Todos</option>
                    <option value="activos">Solo activos</option>
                    <option value="baja">Dados de baja</option>
                </select>
            </div>
            <button type="button" class="btn-submit" style="width: auto; padding: 0.75rem 1.5rem;">Filtrar</button>
        </form>

        <div class="course-grid">
            <div class="course-card">
                <div class="course-img">Desarrollo Web PHP</div>
                <div class="course-info">
                    <h4>PHP Avanzado y POO</h4>
                    <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 0.5rem;">Inscrito: 15-Ago-2026</p>
                    <div class="progress-text">
                        <span>Progreso</span>
                        <span>50%</span>
                    </div>
                    <div class="progress-container">
                        <div class="progress-bar" style="width: 50%;"></div>
                    </div>
                    <a href="<?= BASE_URL ?>index.php?controller=Student&action=fase" class="btn-action" style="text-align: center; margin-top: 1rem; font-size: 0.9rem; padding: 0.5rem;">Continuar Curso</a>
                </div>
            </div>

            <div class="course-card">
                <div class="course-img">JavaScript Moderno</div>
                <div class="course-info">
                    <h4>Consumo de APIs con Fetch</h4>
                    <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 0.5rem;">Inscrito: 01-Ago-2026</p>
                    <div class="progress-text">
                        <span>Completado</span>
                        <span>100%</span>
                    </div>
                    <div class="progress-container">
                        <div class="progress-bar" style="width: 100%; background-color: #10b981;"></div>
                    </div>
                    <div style="display: flex; gap: 0.5rem; margin-top: 1rem;">
                        <a href="<?= BASE_URL ?>index.php?controller=Student&action=fase" class="btn-action" style="flex: 1; text-align: center; font-size: 0.85rem; padding: 0.5rem; background-color: #475569;">Ver Clases</a>
                        <a href="<?= BASE_URL ?>index.php?controller=Student&action=comentarios" class="btn-action" style="flex: 1; text-align: center; font-size: 0.85rem; padding: 0.5rem; background-color: #eab308;">Calificar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>