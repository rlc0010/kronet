<?php
$pageTitle = 'Inicio';
$navActive = 'inicio';
require __DIR__ . '/partials/head.php';
require __DIR__ . '/partials/navbar.php';

require_once __DIR__ . '/../models/Intercambio.php';
require_once __DIR__ . '/../models/Anuncio.php';
require_once __DIR__ . '/../models/Mensaje.php';
require_once __DIR__ . '/../models/Valoracion.php';

$logueado = isset($_SESSION['id_usuario']);

if ($logueado) {
    $miStats     = Intercambio::statsPorUsuario($_SESSION['id_usuario']);
    $miMedia     = Valoracion::mediaUsuario($_SESSION['id_usuario']);
    $misAnuncios = Anuncio::contarPorUsuario($_SESSION['id_usuario']);
    $msgsNoLeidos= Mensaje::contarNoLeidos($_SESSION['id_usuario']);
}
?>

<!-- ===== HERO ===== -->
<section class="hero-section">
    <h1>Intercambia tiempo, comparte habilidades</h1>
    <p>Kronet es tu banco de tiempo donde puedes ofrecer tus servicios y solicitar ayuda de otros. El tiempo es tu moneda.</p>
    <div class="hero-btns">
        <?php if ($logueado): ?>
            <a href="/kronet/public/anuncios/crear" class="btn btn-white btn-lg">
                <i class="fas fa-plus"></i> Publicar servicio
            </a>
            <a href="/kronet/public/anuncios/buscar" class="btn btn-white-outline btn-lg">
                Explorar servicios <i class="fas fa-arrow-right"></i>
            </a>
        <?php else: ?>
            <a href="/kronet/public/register" class="btn btn-white btn-lg">
                <i class="fas fa-user-plus"></i> Crear cuenta gratis
            </a>
            <a href="/kronet/public/anuncios/buscar" class="btn btn-white-outline btn-lg">
                Explorar servicios <i class="fas fa-arrow-right"></i>
            </a>
        <?php endif; ?>
    </div>
</section>

<?php if ($logueado): ?>
<!-- ===== DASHBOARD (solo logueados) ===== -->
<section style="padding: 50px 5% 30px; background: var(--blanco-marfil);">
    <div style="max-width:1200px; margin:0 auto;">
        <h2 style="font-size:24px; margin-bottom:18px;">Tu actividad</h2>
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-clock"></i></div>
                <div class="stat-value"><?= (int)($navUser['saldo_monedas'] ?? 0) ?></div>
                <div class="stat-label">Créditos disponibles</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg,#10B981,#059669);"><i class="fas fa-exchange-alt"></i></div>
                <div class="stat-value"><?= (int)$miStats['confirmados'] ?></div>
                <div class="stat-label">Intercambios completados</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg,#F59E0B,#D97706);"><i class="fas fa-hourglass-half"></i></div>
                <div class="stat-value"><?= (int)$miStats['pendientes'] ?></div>
                <div class="stat-label">Intercambios pendientes</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg,#6366F1,#8B5CF6);"><i class="fas fa-bullhorn"></i></div>
                <div class="stat-value"><?= (int)$misAnuncios ?></div>
                <div class="stat-label">Anuncios publicados</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg,#EC4899,#DB2777);"><i class="fas fa-star"></i></div>
                <div class="stat-value"><?= $miMedia['total'] > 0 ? number_format($miMedia['media'], 1) : '—' ?></div>
                <div class="stat-label"><?= $miMedia['total'] > 0 ? $miMedia['total'] . ' valoraciones' : 'Sin valoraciones aún' ?></div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ===== CÓMO FUNCIONA (visible siempre) ===== -->
<section class="how-section">
    <h2>¿Cómo funciona Kronet?</h2>
    <p>Tres pasos simples para empezar a intercambiar tiempo</p>

    <div class="how-cards">
        <div class="how-card">
            <div class="how-icon"><i class="fas fa-edit"></i></div>
            <h3>1. Publica tu servicio</h3>
            <p>Comparte lo que sabes hacer. Cada hora que ofreces suma créditos a tu cuenta.</p>
        </div>
        <div class="how-card">
            <div class="how-icon"><i class="fas fa-search"></i></div>
            <h3>2. Explora y conecta</h3>
            <p>Busca servicios que necesitas y contacta con otros usuarios de la comunidad.</p>
        </div>
        <div class="how-card">
            <div class="how-icon"><i class="fas fa-handshake"></i></div>
            <h3>3. Intercambia tiempo</h3>
            <p>Realiza el intercambio y valora la experiencia. El tiempo es la única moneda.</p>
        </div>
    </div>
</section>

<?php if ($logueado): ?>
<!-- ===== ACCESOS RÁPIDOS (solo logueados) ===== -->
<section style="padding: 60px 5%; background: white;">
    <div style="max-width:1200px; margin:0 auto;">
        <h2 style="font-size:28px; margin-bottom:8px; text-align:center;">Tu panel rápido</h2>
        <p style="text-align:center; color:var(--gris-texto); margin-bottom:40px;">Accede directamente a lo que necesitas</p>

        <div class="quick-cards">
            <a href="/kronet/public/anuncios/crear" class="how-card">
                <div class="how-icon"><i class="fas fa-plus"></i></div>
                <h3>Publicar anuncio</h3>
                <p>Ofrece o solicita un servicio</p>
            </a>

            <a href="/kronet/public/anuncios/mis-anuncios" class="how-card">
                <div class="how-icon" style="background: linear-gradient(135deg,#00B3B3,#2ED573);"><i class="fas fa-list-alt"></i></div>
                <h3>Mis anuncios</h3>
                <p>Gestiona tus publicaciones</p>
            </a>

            <a href="/kronet/public/intercambios/ofertas-recibidas" class="how-card">
                <div class="how-icon" style="background: linear-gradient(135deg,#F59E0B,#EF4444);"><i class="fas fa-inbox"></i></div>
                <h3>Ofertas recibidas</h3>
                <p>Revisa propuestas de intercambio</p>
            </a>

            <a href="/kronet/public/mensajes" class="how-card">
                <div class="how-icon" style="background: linear-gradient(135deg,#6366F1,#8B5CF6);"><i class="fas fa-envelope"></i></div>
                <h3>Mensajes <?= $msgsNoLeidos > 0 ? '(' . $msgsNoLeidos . ')' : '' ?></h3>
                <p>Tu bandeja de chats</p>
            </a>

            <a href="/kronet/public/intercambios/mis-intercambios" class="how-card">
                <div class="how-icon" style="background: linear-gradient(135deg,#10B981,#059669);"><i class="fas fa-exchange-alt"></i></div>
                <h3>Mis intercambios</h3>
                <p>Historial de intercambios</p>
            </a>

            <a href="/kronet/public/suscripcion" class="how-card">
                <div class="how-icon" style="background: linear-gradient(135deg,#F59E0B,#D97706);"><i class="fas fa-crown"></i></div>
                <h3>Hazte Premium</h3>
                <p>Más visibilidad y destacados</p>
            </a>
        </div>
    </div>
</section>
<?php else: ?>
<!-- ===== CTA para anónimos ===== -->
<section style="padding: 60px 5%; background: white; text-align:center;">
    <div style="max-width:700px; margin:0 auto;">
        <h2 style="font-size:30px; margin-bottom:14px;">¿Listo para empezar?</h2>
        <p style="color:var(--gris-texto); font-size:16px; margin-bottom:30px;">
            Únete a la comunidad de Kronet en menos de un minuto. Crear cuenta es gratis y al
            registrarte recibes 5 créditos de bienvenida para tus primeros intercambios.
        </p>
        <div class="hero-btns" style="justify-content:center;">
            <a href="/kronet/public/register" class="btn btn-primary btn-lg">
                <i class="fas fa-user-plus"></i> Crear cuenta gratis
            </a>
            <a href="/kronet/public/login" class="btn btn-outline btn-lg">
                <i class="fas fa-sign-in-alt"></i> Ya tengo cuenta
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php'; ?>
