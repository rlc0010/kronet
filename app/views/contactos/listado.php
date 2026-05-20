<?php
$pageTitle = 'Mis contactos';
$navActive = 'contactos';
require __DIR__ . '/../partials/head.php';
require __DIR__ . '/../partials/navbar.php';
?>

<section class="hero-section hero-compact">
    <h1>Mis contactos</h1>
    <p>Personas con las que has conectado en Kronet</p>
</section>

<div class="page-wrap-wide">

    <a class="back-link" href="/kronet/public/perfil"><i class="fas fa-arrow-left"></i> Mi perfil</a>

    <?php if (empty($contactos)): ?>
        <div class="empty-state" style="margin-top:40px;">
            <div class="empty-icon"><i class="fas fa-user-friends"></i></div>
            <p>Todavía no tienes contactos.</p>
            <p style="font-size:14px; color:var(--gris-texto); margin-top:8px;">
                Visita el perfil de otro usuario y pulsa <strong>"Agregar a contactos"</strong> para empezar a conectar.
            </p>
            <a href="/kronet/public/anuncios/buscar" class="btn btn-primary" style="margin-top:18px;">
                <i class="fas fa-search"></i> Explorar servicios
            </a>
        </div>
    <?php else: ?>
        <p style="font-size:14px; color:var(--gris-texto); margin-bottom:24px;">
            <i class="fas fa-user-friends"></i> <?= count($contactos) ?> contacto<?= count($contactos) != 1 ? 's' : '' ?>
        </p>
        <div class="contactos-grid">
            <?php foreach ($contactos as $c): ?>
                <div class="contacto-card" id="ccard-<?= (int)$c['id_usuario'] ?>">
                    <div class="cc-avatar">
                        <?= strtoupper(substr($c['nombre'], 0, 1)) ?>
                        <?php if ($c['tipo_usuario'] === 'suscrito'): ?>
                            <span class="cc-premium" title="Premium"><i class="fas fa-crown"></i></span>
                        <?php endif; ?>
                    </div>
                    <div class="cc-info">
                        <h3>
                            <a href="/kronet/public/perfil/<?= (int)$c['id_usuario'] ?>">
                                <?= htmlspecialchars($c['nombre']) ?>
                            </a>
                        </h3>
                        <?php if (!empty($c['descripcion'])): ?>
                            <p class="cc-desc"><?= htmlspecialchars(mb_substr($c['descripcion'], 0, 90)) ?><?= mb_strlen($c['descripcion']) > 90 ? '…' : '' ?></p>
                        <?php else: ?>
                            <p class="cc-desc" style="color:var(--gris-texto); font-style:italic;">Sin descripción</p>
                        <?php endif; ?>
                        <div class="cc-meta">
                            <?php if ((float)$c['media_valoraciones'] > 0): ?>
                                <span title="Valoración media">
                                    <i class="fas fa-star" style="color:#F59E0B;"></i>
                                    <?= number_format((float)$c['media_valoraciones'], 1) ?>
                                    <span style="color:var(--gris-texto);">(<?= (int)$c['total_valoraciones'] ?>)</span>
                                </span>
                            <?php else: ?>
                                <span style="color:var(--gris-texto);">Sin valoraciones</span>
                            <?php endif; ?>
                            <span title="Anuncios activos">
                                <i class="fas fa-bullhorn" style="color:var(--azul-verdoso);"></i>
                                <?= (int)$c['total_anuncios'] ?> anuncio<?= $c['total_anuncios'] != 1 ? 's' : '' ?> activo<?= $c['total_anuncios'] != 1 ? 's' : '' ?>
                            </span>
                        </div>
                    </div>
                    <div class="cc-actions">
                        <a href="/kronet/public/perfil/<?= (int)$c['id_usuario'] ?>" class="btn btn-outline btn-sm">
                            <i class="fas fa-user"></i> Ver perfil
                        </a>
                        <?php if ((int)$c['total_anuncios'] > 0): ?>
                            <a href="/kronet/public/anuncios/buscar" class="btn btn-ghost btn-sm">
                                <i class="fas fa-bullhorn"></i> Sus anuncios
                            </a>
                        <?php endif; ?>
                        <button class="btn btn-danger btn-sm" onclick="quitarContacto(<?= (int)$c['id_usuario'] ?>)">
                            <i class="fas fa-user-times"></i> Quitar
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<script>
async function quitarContacto(id) {
    if (!confirmar('¿Quitar a este usuario de tus contactos?')) return;
    const data = await apiPost('/kronet/public/usuarios/quitar', { id_usuario: id });
    if (data.ok) {
        showToast('Contacto eliminado', 'ok');
        const card = document.getElementById('ccard-' + id);
        if (card) {
            card.style.transition = 'opacity 0.3s';
            card.style.opacity = '0';
            setTimeout(() => card.remove(), 320);
        }
    } else {
        showToast(data.msg, 'error');
    }
}
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
