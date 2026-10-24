<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Moderación de Comentarios</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/styles.css">
</head>
<body>
    <header>
        <h1><a href="<?= BASE_URL ?>index.php?controller=Admin&action=comentarios">EduWeb Central (Administrador)</a></h1>
        <div class="auth-buttons"><a href="<?= BASE_URL ?>index.php?controller=Home&action=index" style="border-color:#ef4444; color:#ef4444;">Salir</a></div>
    </header>

    <div class="student-layout">
        <h2 class="section-title">Moderación de Comentarios en Foros de Cursos</h2>
        
        <div class="course-viewer" style="grid-template-columns: 1fr 1fr;">
            <div class="tabs-container">
                <span style="font-size:0.8rem; color:#64748b;">Curso: PHP Avanzado | Alumno: user_toxico23</span>
                <p style="margin: 0.5rem 0; font-weight: 500; color:#ef4444;">"Este curso no sirve para nada, el maestro es un incompetente total."</p>
                
                <div style="display:flex; gap:10px; align-items:center; margin-top:1rem; border-top:1px solid #f1f5f9; padding-top:0.5rem;">
                    <select style="padding:0.4rem; font-size:0.85rem; border:1px solid #cbd5e1; border-radius:4px; flex:1;">
                        <option value="">Selecciona Causa Obligatoria...</option>
                        <option value="Ataque personal o falta de respeto">Ataque personal / Falta de respeto</option>
                        <option value="Lenguaje vulgar u ofensivo">Lenguaje vulgar u ofensivo</option>
                        <option value="Información falsa o SPAM">Información falsa o SPAM</option>
                    </select>
                    <button class="btn-action danger" style="font-size:0.85rem; padding:0.4rem 1rem;" onclick="alert('Comentario borrado del portal. Se guardó la causa en la base de datos.')">Eliminar</button>
                </div>
            </div>

            <div class="tabs-container" style="opacity: 0.75; display:flex; flex-direction:column; justify-content:center;">
                <span style="font-size:0.8rem; color:#64748b;">Curso: Master en MySQL | Alumno: spam_bot</span>
                <div class="comment-deleted-legend">
                    🚫 El mensaje fue borrado por el Administrador. <br>
                    <span style="font-size: 0.75rem; font-weight: bold; color: #475569;">Causa: Información falsa o SPAM (Eliminado el 19-Sep-2026).</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>