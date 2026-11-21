document.addEventListener('DOMContentLoaded', function() {
    const trigger = document.querySelector('.profile-trigger');
    const dropdown = document.querySelector('.profile-dropdown');

    // Solo ejecutamos si los elementos existen en la página
    if (trigger && dropdown) {
        
        // 1. Abrir/Cerrar al dar clic en el avatar
        trigger.addEventListener('click', function(e) {
            e.stopPropagation(); // Evita que el clic se propague al documento
            dropdown.classList.toggle('show');
        });

        // 2. Cerrar si se hace clic fuera del menú
        document.addEventListener('click', function(e) {
            if (!dropdown.contains(e.target) && !trigger.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });
    }
});