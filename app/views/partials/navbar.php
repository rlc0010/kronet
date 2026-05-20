<?php
/**
 * Navbar común. Activable con $navActive (string):
 *   'inicio' | 'explorar' | 'publicar' | 'servicios' | 'mensajes' | 'perfil'
 *
 * Muestra dos menús distintos:
 *   - Si NO hay sesión: solo "Inicio" y "Explorar", más botones "Entrar" y
 *     "Registrarse" en el lado derecho.
 *   - Si SÍ hay sesión: navegación completa con badges de no leídos,
 *     créditos, avatar.
 */
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Mensaje.php';
require_once __DIR__ . '/../../models/Notificacion.php';

$navActive = $navActive ?? '';
$navUserId = $_SESSION['id_usuario'] ?? null;
$navUser   = $navUserId ? User::findById($navUserId) : null;
$navUnreadMsg = $navUserId ? Mensaje::contarNoLeidos($navUserId) : 0;
$navUnreadNot = $navUserId ? Notificacion::contarNoLeidas($navUserId) : 0;

if (!function_exists('navItem')) {
    function navItem($active, $current, $href, $icon, $label, $badge = 0) {
        $cls = $active === $current ? ' class="active"' : '';
        $badgeHtml = $badge > 0 ? '<span class="nav-badge">' . ($badge > 9 ? '9+' : $badge) . '</span>' : '';
        echo '<li><a href="' . $href . '"' . $cls . '><i class="' . $icon . '"></i> ' . $label . $badgeHtml . '</a></li>';
    }
}
?>
<nav class="navbar">
    <a href="/kronet/public/" class="logo-container">
        <div class="logo-icon">
            <img src="/kronet/public/assets/images/logo.png" alt="Kronet">
        </div>
        <span class="logo-text">Kronet</span>
    </a>

    <button class="nav-toggle" onclick="document.getElementById('navMenu').classList.toggle('open')" aria-label="Menú">
        <i class="fas fa-bars"></i>
    </button>

    <ul class="nav-menu" id="navMenu">
        <?php navItem($navActive, 'inicio',    '/kronet/public/',                'fas fa-home',         'Inicio'); ?>
        <?php navItem($navActive, 'explorar',  '/kronet/public/anuncios/buscar', 'fas fa-search',       'Explorar'); ?>
        <?php if ($navUser): ?>
            <?php navItem($navActive, 'publicar',  '/kronet/public/anuncios/crear',                'fas fa-plus-circle',  'Publicar'); ?>
            <?php navItem($navActive, 'servicios', '/kronet/public/intercambios/mis-intercambios', 'fas fa-exchange-alt', 'Mis Servicios'); ?>
            <?php navItem($navActive, 'mensajes',  '/kronet/public/mensajes',  'fas fa-envelope',      'Mensajes', $navUnreadMsg); ?>
            <?php navItem($navActive, 'contactos', '/kronet/public/contactos', 'fas fa-user-friends',  'Contactos'); ?>
        <?php endif; ?>
    </ul>

    <div class="nav-user">
        <?php if ($navUser): ?>
            <div class="credits-badge" title="Tu saldo de créditos de tiempo">
                <i class="fas fa-clock"></i>
                <strong><?= (int)$navUser['saldo_monedas'] ?>h</strong>
            </div>
            <a href="/kronet/public/perfil" class="avatar-nav" title="Mi perfil">
                <?= strtoupper(substr($navUser['nombre'] ?? 'U', 0, 1)) ?>
            </a>
        <?php else: ?>
            <a href="/kronet/public/login" class="btn btn-ghost btn-sm" style="color:white; border-color:rgba(255,255,255,0.4);">
                <i class="fas fa-sign-in-alt"></i> Entrar
            </a>
            <a href="/kronet/public/register" class="btn btn-secondary btn-sm">
                <i class="fas fa-user-plus"></i> Registrarse
            </a>
        <?php endif; ?>
    </div>
</nav>
