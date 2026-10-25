document.addEventListener("DOMContentLoaded", () => {
    const courseGrid = document.getElementById("course-grid-container");
    const listaCategorias = document.getElementById("lista-categorias");
    const selectCategorias = document.getElementById("search-categoria");
    const btnBuscar = document.getElementById("btn-buscar");

    // 1. CARGAR CATEGORÍAS DINÁMICAS
    fetch(BASE_URL + 'index.php?controller=Api&action=getCategorias')
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                data.data.forEach(cat => {
                    // Llenamos el menú lateral con enlaces para filtrar
                    listaCategorias.innerHTML += `<li><a href="#" onclick="filtrarPorCategoria(${cat.id}); return false;">${cat.nombre}</a></li>`;
                    // Llenamos el select del buscador
                    selectCategorias.innerHTML += `<option value="${cat.id}">${cat.nombre}</option>`;
                });
            }
        })
        .catch(err => console.error("Error categorías:", err));

    // 2. FUNCIÓN PARA CARGAR CURSOS (Con filtros opcionales)
    function cargarCursos(titulo = '', categoria_id = 0) {
        courseGrid.innerHTML = '<p>Cargando...</p>';
        
        fetch(`${BASE_URL}index.php?controller=Api&action=getCursos&titulo=${titulo}&categoria_id=${categoria_id}`)
            .then(res => res.json())
            .then(data => {
                courseGrid.innerHTML = '';
                if (data.data.length > 0) {
                    data.data.forEach(curso => {
                        courseGrid.innerHTML += `
                            <a href="${BASE_URL}index.php?controller=Course&action=detail&id=${curso.id}" class="course-card">
                                <div class="course-img">Imagen del Curso</div>
                                <div class="course-info">
                                    <h4>${curso.titulo}</h4>
                                    <p style="font-size:0.85rem; color:#64748b;">${curso.descripcion}</p>
                                    <div class="course-price">$${parseFloat(curso.costo).toFixed(2)}</div>
                                </div>
                            </a>
                        `;
                    });
                } else {
                    courseGrid.innerHTML = '<p style="width:100%; text-align:center;">No se encontraron cursos.</p>';
                }
            })
            .catch(err => {
                courseGrid.innerHTML = '<p style="color:red; width:100%;">Error al cargar cursos.</p>';
            });
    }

    // 3. EVENTO DEL BOTÓN BUSCAR
    btnBuscar.addEventListener("click", () => {
        const txt = document.getElementById("search-titulo").value;
        const catId = document.getElementById("search-categoria").value;
        cargarCursos(txt, catId);
    });

    // 4. FUNCIÓN GLOBAL (Para los clics del menú lateral)
    window.filtrarPorCategoria = (id) => {
        selectCategorias.value = id; // Sincroniza el select
        document.getElementById("search-titulo").value = ''; // Limpia el buscador de texto
        cargarCursos('', id);
    };

    // 5. CARGA INICIAL (Al entrar a la página)
    cargarCursos();
});