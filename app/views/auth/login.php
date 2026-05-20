<?php $pageTitle = 'Iniciar sesión'; require __DIR__ . '/../partials/head.php'; ?>

<main class="auth-main">
    <div class="auth-card">

        <div class="auth-logo-div-icon">
            <img src="/kronet/public/assets/images/logo.png" alt="Kronet" class="auth-logo-icon">
        </div>
        <h1 class="auth-title">Bienvenido a Kronet</h1>

        <div id="errorMsg" class="flash flash-error" style="display:none;"></div>

        <form id="loginForm" class="auth-form" method="post" novalidate>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="tu@email.com" autocomplete="email" required>
                <div id="err-email" class="field-error" style="display:none;"></div>
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" placeholder="••••••••" autocomplete="current-password" required>
                <div id="err-password" class="field-error" style="display:none;"></div>
            </div>

            <div class="auth-actions">
                <button type="submit" class="btn btn-primary btn-block" id="submitBtn">
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

<div class="toast-container" id="toastContainer"></div>
<script src="/kronet/public/assets/js/kronet.js"></script>
<script src="/kronet/public/assets/js/login.js"></script>
</body>
</html>
