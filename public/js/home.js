document.addEventListener('DOMContentLoaded', function() {
    cargarCategorias();
    cargarCursos();

    document.getElementById('btn-buscar').addEventListener('click', function() {
        cargarCursos();
    });
    
    document.getElementById('orden-cursos').addEventListener('change', function() {
        cargarCursos();
    });
});

function cargarCategorias() {
    fetch(BASE_URL + 'index.php?controller=Api&action=getCategorias')
        .then(response => response.json())
        .then(data => {
            const lista = document.getElementById('lista-categorias');
            const select = document.getElementById('search-categoria');
            
            if(data.status === 'success') {
                data.data.forEach(cat => {
                    lista.innerHTML += `<li onclick="filtrarPorCategoria(${cat.id})" style="cursor:pointer; padding: 5px 0;">${cat.nombre}</li>`;
                    select.innerHTML += `<option value="${cat.id}">${cat.nombre}</option>`;
                });
            }
        })
        .catch(error => console.error('Error categorías:', error));
}

function cargarCursos(titulo = '', categoria_id = 0) {
    const orden = document.getElementById('orden-cursos').value;
    const url = `${BASE_URL}index.php?controller=Api&action=getCursos&titulo=${titulo}&categoria_id=${categoria_id}&orden=${orden}`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            const contenedor = document.getElementById('course-grid-container');
            contenedor.innerHTML = '';

            if (data.status === 'success' && data.data.length > 0) {
                data.data.forEach(curso => {
                    const imgUrl = curso.imagen ? `${BASE_URL}public/uploads/${curso.imagen}` : `${BASE_URL}public/uploads/default.jpg`;
                    
                    // Calculamos la calificación de forma segura
                    let textoCalificacion = 'Sin calificaciones';
                    if (curso.promedio_calificacion && parseFloat(curso.promedio_calificacion) > 0) {
                        textoCalificacion = '⭐ ' + parseFloat(curso.promedio_calificacion).toFixed(1);
                    }

                    const ventas = curso.total_ventas || 0;

                    // Armamos la tarjeta
                    contenedor.innerHTML += `
                        <div class="course-card">
                            <img src="${imgUrl}" alt="${curso.titulo}" class="course-img" onerror="this.src='${BASE_URL}public/uploads/default.jpg'">
                            <div class="course-info">
                                <h4>${curso.titulo}</h4>
                                <p style="color:#64748b; font-size:0.85rem; margin-bottom:0.5rem;">
                                    ${textoCalificacion} (${ventas} alumnos)
                                </p>
                                <p style="font-weight:bold; color:#059669; margin-bottom:1rem;">$${parseFloat(curso.costo).toFixed(2)}</p>
                                <a href="${BASE_URL}index.php?controller=Course&action=detail&id=${curso.id}" class="btn-action" style="display:block; text-align:center;">Ver Detalle</a>
                            </div>
                        </div>
                    `;
                });
            } else {
                contenedor.innerHTML = '<p>No se encontraron cursos disponibles.</p>';
            }
        })
        .catch(error => console.error('Error cursos:', error));
}

function filtrarPorCategoria(id) {
    document.getElementById('search-categoria').value = id;
    const titulo = document.getElementById('search-titulo').value;
    cargarCursos(titulo, id);
}