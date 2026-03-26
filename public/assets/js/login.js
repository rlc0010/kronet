document.getElementById("loginForm").addEventListener("submit", function(e) {
    e.preventDefault();

    let email = document.getElementById("email").value.trim();
    let password = document.getElementById("password").value.trim();
    let errorMsg = document.getElementById("errorMsg");

    // Reset mensaje
    errorMsg.textContent = "";
    errorMsg.style.color = "red";

    // Validación básica
    if (!email || !password) {
        errorMsg.textContent = "Todos los campos son obligatorios";
        return;
    }

    //Validación formato email
    if (!email.includes("@")) {
        errorMsg.textContent = "El email no es válido";
        return;
    }

    // Simulación backend (TEMPORAL)
    if (email !== "test@test.com") {
        // Alt2: usuario no registrado
        errorMsg.innerHTML = 'No existe una cuenta con ese email. <a href="/register">Regístrate</a>';
        return;
    }

    if (password !== "1234") {
        // Alt1: credenciales incorrectas
        errorMsg.textContent = "Email o contraseña incorrectos";
        return;
    }

    // Login correcto (flujo principal)
    errorMsg.style.color = "green";
    errorMsg.textContent = "Login correcto";

});