document.addEventListener("DOMContentLoaded", () => {
    const registrationForm = document.getElementById("registerForm");

    if (registrationForm) {
        registrationForm.addEventListener("submit", (event) => {
            const fullname = document.getElementById("fullname").value.trim();
            const gender = document.getElementById("gender").value;
            const birthdate = document.getElementById("birthdate").value;
            const avatar = document.getElementById("avatar").files[0];
            const email = document.getElementById("email").value.trim();
            const password = document.getElementById("password").value;

            // Validaciones
            if (!fullname || !gender || !birthdate || !avatar || !email || !password) {
                alert("❌ Todos los campos son obligatorios.");
                event.preventDefault(); return;
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert("❌ Correo electrónico inválido.");
                event.preventDefault(); return;
            }

            const errors = [];
            if (password.length < 8) errors.push("Mínimo 8 caracteres.");
            if (!/[A-Z]/.test(password)) errors.push("Al menos una mayúscula.");
            if (!/[0-9]/.test(password)) errors.push("Al menos un número.");
            const specialCharRegex = /[¡"#\$%&/='?¿:;,.\-_+*{}\[\]]/;
            if (!specialCharRegex.test(password)) errors.push("Al menos un carácter especial.");

            if (errors.length > 0) {
                let msg = "❌ La contraseña no cumple:\n\n";
                errors.forEach(e => msg += `• ${e}\n`);
                alert(msg);
                event.preventDefault(); return;
            }
            
            // ¡OJO AQUÍ! Si todo está bien, NO usamos preventDefault, dejamos que PHP reciba los datos.
        });
    }
});