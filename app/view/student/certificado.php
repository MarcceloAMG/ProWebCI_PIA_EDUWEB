<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado - <?= htmlspecialchars($datos['curso']) ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/styles.css">
        <style>
        /* 1. Configuración de la hoja */
        @page {
            size: landscape; /* Hoja horizontal */
            margin: 0; /* Quita los márgenes del navegador */
        }

        /* 2. Estilos para la pantalla normal */
        body {
            background-color: #e2e8f0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 2rem;
        }

        .certificate-container {
            background-color: #fff;
            width: 100%;
            max-width: 1000px;
            padding: 3rem;
            border: 20px solid #0f172a;
            border-radius: 10px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
            text-align: center;
            position: relative;
            background-image: radial-gradient(#f1f5f9 1px, transparent 1px);
            background-size: 20px 20px;
            box-sizing: border-box; /* Evita que el borde haga más grande la caja */
        }

        .certificate-header {
            font-size: 3rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 2px;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
        }

        .certificate-subtitle {
            font-size: 1.2rem;
            color: #64748b;
            margin-bottom: 2rem;
        }

        .certificate-text {
            font-size: 1.1rem;
            color: #334155;
            margin-bottom: 1rem;
        }

        .certificate-name {
            font-size: 3.5rem;
            font-weight: bold;
            color: #38bdf8;
            margin: 1rem 0;
            font-family: 'Georgia', serif;
            border-bottom: 2px solid #cbd5e1;
            display: inline-block;
            padding-bottom: 5px;
            padding: 0 2rem;
        }

        .certificate-course {
            font-size: 2rem;
            font-weight: bold;
            color: #0f172a;
            margin: 1rem 0 2rem 0;
        }

        .certificate-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 3rem;
        }

        .signature-box {
            text-align: center;
            width: 250px;
        }

        .signature-line {
            border-top: 2px solid #0f172a;
            margin-top: 1.5rem;
            padding-top: 0.5rem;
            font-weight: bold;
            color: #0f172a;
            font-size: 1rem;
        }

        .date-box {
            text-align: center;
            font-size: 1rem;
            color: #64748b;
        }

        /* 3. ESTILOS EXCLUSIVOS PARA IMPRIMIR */
        @media print {
            body { 
                display: block !important; /* Quita el flexbox que rompe la impresión */
                background-color: white !important; 
                padding: 0 !important; 
                margin: 0 !important;
            }
            .certificate-container { 
                box-shadow: none !important; 
                border-radius: 0 !important;
                width: 100vw !important;
                height: 100vh !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 3rem !important;
                border: 15px solid #0f172a !important; /* Borde un poco más delgado para que quepa */
                
                /* Forzamos a que el contenido se centre verticalmente */
                display: flex !important;
                flex-direction: column !important;
                justify-content: center !important;
                
                /* Obliga al navegador a imprimir los colores de fondo y bordes */
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .no-print { 
                display: none !important; 
            }
        }
    </style>
</head>
<body>

    <div class="no-print" style="position: fixed; top: 20px; right: 20px;">
        <button onclick="window.print()" style="background-color: #0f172a; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; font-size: 1rem;">🖨️ Descargar / Imprimir PDF</button>
    </div>

    <div class="certificate-container">
        <div class="certificate-header">Certificado de Finalización</div>
        <div class="certificate-subtitle">EduWeb - Portal de Cursos</div>
        
        <p class="certificate-text">Se otorga el presente certificado a:</p>
        <div class="certificate-name"><?= htmlspecialchars($datos['alumno']) ?></div>
        
        <p class="certificate-text">Por haber completado satisfactoriamente el curso:</p>
        <div class="certificate-course">"<?= htmlspecialchars($datos['curso']) ?>"</div>
        
        <div class="certificate-footer">
            <div class="date-box">
                <strong>Fecha de Terminación:</strong><br>
                <?= date('d \d\e F \d\e Y', strtotime($datos['fecha_terminacion'])) ?>
            </div>
            
            <div class="signature-box">
                <div style="font-size: 2.5rem; font-family: 'Brush Script MT', cursive; color: #0f172a; margin-bottom: -15px;">
                    <?= htmlspecialchars($datos['instructor']) ?>
                </div>
                <div class="signature-line">
                    <?= htmlspecialchars($datos['instructor']) ?><br>
                    <span style="font-weight: normal; font-size: 1rem; color: #64748b;">Instructor Certificador</span>
                </div>
            </div>
        </div>
    </div>

    <div class="no-print" style="position: fixed; bottom: 20px; left: 20px;">
        <a href="<?= BASE_URL ?>index.php?controller=Student&action=kardex" style="color: #0f172a; text-decoration: none; font-weight: bold; background: white; padding: 10px 20px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">← Volver al Kardex</a>
    </div>

</body>
</html>