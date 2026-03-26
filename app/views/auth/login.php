<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
</head>
<body>

<h2>Iniciar sesión</h2>

<form id="loginForm">
    <label>Email</label><br>
    <input type="email" name="email" id="email" required><br><br>

    <label>Contraseña</label><br>
    <input type="password" name="password" id="password" required><br><br>

    <button type="submit">Entrar</button>
</form>

<p id="errorMsg" style="color:red;"></p>
<script src="/kronet/public/assets/js/login.js"></script>
</body>
</html>