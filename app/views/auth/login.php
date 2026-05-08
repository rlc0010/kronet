<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/kronet/public/assets/images/logo.png">
    <title>Iniciar sesión - Kronet</title>
    <link rel="stylesheet" href="/kronet/public/assets/css/kronet.css">
</head>
<body>

<main class="auth-main">
    <div class="auth-card">

        <div class="auth-logo-div-icon">
            <img src="/kronet/public/assets/images/logo.png" alt="KRONET" class="auth-logo-icon">
        </div>
        <h1 class="auth-title">Bienvenido a Kronet</h1>

        <p id="errorMsg" class="flash flash-error" style="display:none;"></p>

        <form id="loginForm" class="auth-form">

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
                    <i class="fas fa-sign-in-alt"></i> Entrar
                </button>
            </div>
        </form>

        <p class="auth-switch-link">
            ¿No tienes cuenta aún?
            <a href="/kronet/public/register">Regístrate</a>
        </p>

    </div>
</main>

<script src="/kronet/public/assets/js/login.js"></script>
</body>
</html>