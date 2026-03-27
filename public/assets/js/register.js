document.getElementById("registerForm").addEventListener("submit", function(e) {
    e.preventDefault();

    let nombre = document.getElementById("nombre").value.trim();
    let email = document.getElementById("email").value.trim();
    let password = document.getElementById("password").value.trim();
    let errorMsg = document.getElementById("errorMsg");

    errorMsg.textContent = "";
    errorMsg.style.color = "red";

    if (!nombre || !email || !password) {
        errorMsg.textContent = "Todos los campos son obligatorios";
        return;
    }

    fetch("/kronet/public/register", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `nombre=${encodeURIComponent(nombre)}&email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.ok) {
            window.location.href = "/kronet/public/login";
        } else {
            errorMsg.textContent = data.msg;
        }
    });
});