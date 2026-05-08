<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/kronet/public/assets/images/logo.png">
    <title>Registro - Kronet</title>
    <link rel="stylesheet" href="/kronet/public/assets/css/kronet.css">
</head>
<body>

<main class="auth-main">
    <div class="auth-card">

        <div class="auth-logo-div-icon">
            <img src="/kronet/public/assets/images/logo.png" alt="KRONET" class="auth-logo-icon">
        </div>
        <h1 class="auth-title">Crear cuenta</h1>

        <p id="errorMsg" class="flash flash-error" style="display:none;"></p>

        <form id="registerForm" class="auth-form">

            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Tu nombre" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="tu@email.com" required>
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" placeholder="••••••••" required>
            </div>

            <div class="auth-actions">
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-user-plus"></i> Registrarse
                </button>
            </div>
        </form>

        <p class="auth-switch-link">
            ¿Ya tienes cuenta?
            <a href="/kronet/public/login">Inicia sesión</a>
        </p>

    </div>
</main>

<script src="/kronet/public/assets/js/register.js"></script>
</body>
</html>