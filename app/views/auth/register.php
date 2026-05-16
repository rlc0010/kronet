<?php $pageTitle = 'Crear cuenta'; require __DIR__ . '/../partials/head.php'; ?>

<main class="auth-main">
    <div class="auth-card">

        <div class="auth-logo-div-icon">
            <img src="/kronet/public/assets/images/logo.png" alt="Kronet" class="auth-logo-icon">
        </div>
        <h1 class="auth-title">Crear cuenta</h1>

        <div id="errorMsg" class="flash flash-error" style="display:none;"></div>

        <form id="registerForm" class="auth-form" novalidate>

            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Tu nombre" required>
                <div id="err-nombre" class="field-error" style="display:none;"></div>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="tu@email.com" autocomplete="email" required>
                <div id="err-email" class="field-error" style="display:none;"></div>
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" placeholder="••••••••" autocomplete="new-password" required>
                <div class="password-rules" id="passwordRules">
                    <div class="rule" data-rule="len"><i class="fas fa-circle-notch"></i> Al menos 8 caracteres</div>
                    <div class="rule" data-rule="upper"><i class="fas fa-circle-notch"></i> Una letra mayúscula</div>
                    <div class="rule" data-rule="lower"><i class="fas fa-circle-notch"></i> Una letra minúscula</div>
                    <div class="rule" data-rule="num"><i class="fas fa-circle-notch"></i> Un número</div>
                </div>
            </div>

            <div class="form-group">
                <label for="password2">Confirmar contraseña</label>
                <input type="password" name="password2" id="password2" placeholder="••••••••" autocomplete="new-password" required>
                <div id="err-password2" class="field-error" style="display:none;"></div>
            </div>

            <div class="auth-actions">
                <button type="submit" class="btn btn-primary btn-block" id="submitBtn">
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

<div class="toast-container" id="toastContainer"></div>
<script src="/kronet/public/assets/js/kronet.js"></script>
<script src="/kronet/public/assets/js/register.js"></script>
</body>
</html>
