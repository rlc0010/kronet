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

    fetch("/kronet/public/login", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
        })
        .then(res => res.text())
        .then(data => {
            if (data.includes("correcto")) {
                errorMsg.style.color = "green";
                errorMsg.textContent = "Login correcto";

                window.location.href = "/kronet/public/";
            } else {
                errorMsg.textContent = data;
            }
    });
});