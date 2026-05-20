<?php
$pageTitle = 'Notificaciones';
$navActive = '';
require __DIR__ . '/../partials/head.php';
require __DIR__ . '/../partials/navbar.php';
?>

<section class="hero-section hero-compact">
    <h1>Notificaciones</h1>
    <p>Actividad reciente de tu cuenta</p>
</section>

<div class="page-wrap">

    <a class="back-link" href="/kronet/public/"><i class="fas fa-arrow-left"></i> Inicio</a>

    <?php if (!empty($notificaciones)): ?>
        <div style="text-align:right; margin-bottom:14px;">
            <button class="btn btn-ghost btn-sm" onclick="marcarTodas()">
                <i class="fas fa-check-double"></i> Marcar todas como leídas
            </button>
        </div>
    <?php endif; ?>

    <?php if (empty($notificaciones)): ?>
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-bell-slash"></i></div>
            <p>No tienes notificaciones todavía.</p>
        </div>
    <?php else: ?>
        <div class="intercambios-list">
            <?php
            $iconos = [
                'mensaje'      => 'fa-envelope',
                'oferta'       => 'fa-inbox',
                'intercambio'  => 'fa-exchange-alt',
                'valoracion'   => 'fa-star',
                'sistema'      => 'fa-info-circle',
            ];
            foreach ($notificaciones as $n):
                $icono = $iconos[$n['tipo']] ?? 'fa-bell';
            ?>
                <div class="mensaje-card <?= !$n['leida'] ? 'unread' : '' ?>">
                    <div class="mc-head">
                        <span class="mc-autor">
                            <i class="fas <?= $icono ?>"></i> <?= htmlspecialchars($n['titulo']) ?>
                        </span>
                        <span class="mc-fecha"><i class="fas fa-calendar"></i> <?= htmlspecialchars($n['fecha']) ?></span>
                    </div>
                    <?php if (!empty($n['contenido'])): ?>
                        <p class="mc-content"><?= htmlspecialchars($n['contenido']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($n['enlace'])): ?>
                        <a class="btn btn-outline btn-sm" href="<?= htmlspecialchars($n['enlace']) ?>">
                            <i class="fas fa-arrow-right"></i> Ver
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<style>
.mensaje-card { background:white; border-radius:var(--radio-lg); box-shadow:var(--sombra); padding:20px 24px; }
.mensaje-card.unread { background:#f0f9ff; border-left:4px solid var(--azul-verdoso); }
.mensaje-card .mc-head { display:flex; justify-content:space-between; align-items:center; margin-bottom:10px; flex-wrap:wrap; gap:8px; }
.mensaje-card .mc-autor { font-weight:600; font-size:15px; }
.mensaje-card .mc-fecha { font-size:12px; color:var(--gris-texto); }
.mensaje-card .mc-content { font-size:14px; color:rgba(15,28,63,0.75); line-height:1.5; margin-bottom:12px; }
</style>

<script>
async function marcarTodas() {
    const data = await apiPost('/kronet/public/notificaciones/marcar-leidas', {});
    showToast(data.msg, data.ok ? 'ok' : 'error');
    if (data.ok) setTimeout(() => window.location.reload(), 500);
}
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
