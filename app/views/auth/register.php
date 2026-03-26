<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
</head>
<body>

<h2>Registro</h2>

<form id="registerForm">
    <label>Nombre</label><br>
    <input type="text" name="nombre" id="nombre" required><br><br>

    <label>Email</label><br>
    <input type="email" name="email" id="email" required><br><br>

    <label>Contraseña</label><br>
    <input type="password" name="password" id="password" required><br><br>

    <button type="submit">Registrarse</button>
</form>

<p id="errorMsg" style="color:red;"></p>

<p>
    ¿Ya tienes cuenta? 
    <a href="/kronet/public/login">Inicia sesión</a>
</p>

<script src="/kronet/public/assets/js/register.js"></script>

</body>
</html>