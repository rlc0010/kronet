<?php
$pageTitle = 'Suscripción Premium';
$navActive = '';
require __DIR__ . '/../partials/head.php';
require __DIR__ . '/../partials/navbar.php';
require_once __DIR__ . '/../../models/Suscripcion.php';
require_once __DIR__ . '/../../models/Pago.php';
?>

<section class="hero-section hero-compact">
    <h1>Kronet Premium</h1>
    <p>Más visibilidad, más anuncios, mismo banco de tiempo</p>
</section>

<div class="page-wrap">

    <a class="back-link" href="/kronet/public/perfil"><i class="fas fa-arrow-left"></i> Mi perfil</a>

    <div id="msg"></div>

    <?php if ($suscripcion): ?>
        <div class="card" style="margin-bottom:24px; border-left:4px solid var(--verde-azulado);">
            <h3 style="margin-bottom:8px;"><i class="fas fa-crown" style="color:#F59E0B;"></i> Tu suscripción está activa</h3>
            <p>Premium hasta <strong><?= date('d/m/Y', strtotime($suscripcion['fecha_fin'])) ?></strong>.</p>
            <p style="font-size:13px; color:var(--gris-texto); margin-top:6px;">
                Destacados gratis usados esta semana: <?= (int)$suscripcion['destacados_usados_semana'] ?> / <?= Suscripcion::DESTACADOS_GRATIS_SEMANA ?>
            </p>
            <div style="margin-top:14px;">
                <button class="btn btn-danger btn-sm" onclick="cancelar()">
                    <i class="fas fa-times"></i> Cancelar suscripción
                </button>
            </div>
        </div>
    <?php endif; ?>

    <div class="pricing-grid">
        <!-- Plan Gratis -->
        <div class="pricing-card">
            <div class="pc-name">Plan Gratis</div>
            <div class="pc-price">0€ <small>/ siempre</small></div>
            <ul>
                <li><i class="fas fa-check"></i> Publica anuncios sin límite mensual</li>
                <li><i class="fas fa-check"></i> Intercambia tiempo con la comunidad</li>
                <li><i class="fas fa-check"></i> Mensajería completa</li>
                <li><i class="fas fa-times" style="color:#dc2626;"></i> Sin destacados gratuitos</li>
            </ul>
            <span class="btn btn-ghost btn-block" style="cursor:default;">Tu plan actual</span>
        </div>

        <!-- Premium -->
        <div class="pricing-card featured">
            <div class="pc-name"><i class="fas fa-crown" style="color:#F59E0B;"></i> Plan Premium</div>
            <div class="pc-price"><?= number_format(Suscripcion::PRECIO_MENSUAL, 2, ',', '.') ?>€ <small>/ mes</small></div>
            <ul>
                <li><i class="fas fa-check"></i> 1 destacado gratis cada semana</li>
                <li><i class="fas fa-check"></i> Insignia Premium en tu perfil</li>
                <li><i class="fas fa-check"></i> Mayor visibilidad de tus anuncios</li>
                <li><i class="fas fa-check"></i> Soporte prioritario</li>
            </ul>

            <?php if (!$suscripcion): ?>
                <p style="font-size:13px; color:var(--gris-texto); margin-bottom:10px;">Elige método de pago:</p>
                <div class="method-list">
                    <?php
                    $iconos = ['tarjeta'=>'fa-credit-card','paypal'=>'fa-brands fa-paypal','bizum'=>'fa-mobile-alt'];
                    foreach (Pago::METODOS as $m): ?>
                        <div class="method-card" data-metodo="<?= $m ?>" onclick="seleccionarMetodo(this)">
                            <i class="fas <?= $iconos[$m] ?? 'fa-money-bill' ?>"></i>
                            <div class="method-name"><?= ucfirst($m) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <button class="btn btn-primary btn-block" id="btnPago" disabled onclick="suscribir()">
                    <i class="fas fa-crown"></i> Activar Premium
                </button>
            <?php else: ?>
                <span class="btn btn-ghost btn-block" style="cursor:default;">Ya estás suscrito</span>
            <?php endif; ?>
        </div>
    </div>

</div>

<script>
let metodoSeleccionado = null;
function seleccionarMetodo(el) {
    document.querySelectorAll('.method-card').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
    metodoSeleccionado = el.dataset.metodo;
    document.getElementById('btnPago').disabled = false;
}

async function suscribir() {
    if (!metodoSeleccionado) { showToast('Selecciona un método de pago', 'error'); return; }
    if (!confirmar('Vas a activar Premium por <?= number_format(Suscripcion::PRECIO_MENSUAL, 2, ',', '.') ?>€/mes. ¿Confirmar?')) return;

    const data = await apiPost('/kronet/public/suscripcion/activar', { metodo_pago: metodoSeleccionado });
    showToast(data.msg, data.ok ? 'ok' : 'error');
    if (data.ok) setTimeout(() => window.location.reload(), 1000);
}

async function cancelar() {
    if (!confirmar('¿Cancelar tu suscripción Premium? Mantendrás los beneficios hasta la fecha de fin.')) return;
    const data = await apiPost('/kronet/public/suscripcion/cancelar', {});
    showToast(data.msg, data.ok ? 'ok' : 'error');
    if (data.ok) setTimeout(() => window.location.reload(), 800);
}
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
