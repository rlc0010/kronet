<?php
$pageTitle = 'Destacar anuncio';
$navActive = '';
require __DIR__ . '/../partials/head.php';
require __DIR__ . '/../partials/navbar.php';
?>

<section class="hero-section hero-compact">
    <h1>Destacar anuncio</h1>
    <p>Aumenta la visibilidad de tu publicación durante 7 días</p>
</section>

<div class="page-wrap">

    <a class="back-link" href="/kronet/public/anuncios/mis-anuncios"><i class="fas fa-arrow-left"></i> Mis anuncios</a>

    <div id="msg"></div>

    <div class="pricing-grid">

        <!-- Opción 1: destacado gratis (suscritos) -->
        <div class="pricing-card <?= $info['puede'] ? 'featured' : '' ?>">
            <div class="pc-name"><i class="fas fa-crown" style="color:#F59E0B;"></i> Plan Premium</div>
            <div class="pc-price">Gratis <small>(con suscripción)</small></div>
            <ul>
                <li><i class="fas fa-check"></i> 1 destacado gratis cada semana</li>
                <li><i class="fas fa-check"></i> 3 anuncios al día</li>
                <li><i class="fas fa-check"></i> Mayor visibilidad permanente</li>
            </ul>
            <?php if ($info['puede']): ?>
                <p style="font-size:13px; color:var(--gris-texto); margin-bottom:14px;">
                    Te quedan <strong><?= $info['restantes'] ?></strong> destacado<?= $info['restantes'] != 1 ? 's' : '' ?> gratis esta semana.
                </p>
                <button class="btn btn-primary btn-block" onclick="destacarGratis()">
                    <i class="fas fa-star"></i> Usar destacado gratuito
                </button>
            <?php elseif ($info['sus']): ?>
                <p style="font-size:13px; color:#b91c1c; margin-bottom:14px;">
                    Ya has usado tu destacado gratis esta semana. El contador se reinicia el lunes.
                </p>
                <span class="btn btn-ghost btn-block" style="cursor:not-allowed;">Agotado esta semana</span>
            <?php else: ?>
                <p style="font-size:13px; color:var(--gris-texto); margin-bottom:14px;">
                    Necesitas la suscripción Premium activa.
                </p>
                <a href="/kronet/public/suscripcion" class="btn btn-secondary btn-block">
                    <i class="fas fa-crown"></i> Hazte Premium
                </a>
            <?php endif; ?>
        </div>

        <!-- Opción 2: pago puntual -->
        <div class="pricing-card">
            <div class="pc-name"><i class="fas fa-bolt" style="color:#00B3B3;"></i> Destacado puntual</div>
            <div class="pc-price">2,50€ <small>/ 7 días</small></div>
            <ul>
                <li><i class="fas fa-check"></i> Pago único sin suscripción</li>
                <li><i class="fas fa-check"></i> 7 días en primeros puestos</li>
                <li><i class="fas fa-check"></i> Sello "Destacado"</li>
            </ul>

            <p style="font-size:13px; color:var(--gris-texto); margin-bottom:10px;">Elige método de pago:</p>
            <div class="method-list">
                <?php foreach ($metodos as $m):
                    $iconos = ['tarjeta'=>'fa-credit-card','paypal'=>'fa-brands fa-paypal','bizum'=>'fa-mobile-alt'];
                ?>
                    <div class="method-card" data-metodo="<?= $m ?>" onclick="seleccionarMetodo(this)">
                        <i class="fas <?= $iconos[$m] ?? 'fa-money-bill' ?>"></i>
                        <div class="method-name"><?= ucfirst($m) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <button class="btn btn-secondary btn-block" id="btnPago" disabled onclick="pagarDestacado()">
                <i class="fas fa-lock"></i> Pagar 2,50€
            </button>
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

async function destacarGratis() {
    if (!confirmar('¿Usar tu destacado gratis de esta semana?')) return;
    const data = await apiPost(window.location.pathname, { tipo: 'gratis' });
    showToast(data.msg, data.ok ? 'ok' : 'error');
    if (data.ok) setTimeout(() => { window.location.href = '/kronet/public/anuncios/mis-anuncios'; }, 1200);
}

async function pagarDestacado() {
    if (!metodoSeleccionado) { showToast('Selecciona un método de pago', 'error'); return; }
    if (!confirmar('Vas a pagar 2,50€ para destacar este anuncio 7 días. ¿Confirmar?')) return;
    const data = await apiPost(window.location.pathname, { tipo: 'pago', metodo_pago: metodoSeleccionado });
    showToast(data.msg, data.ok ? 'ok' : 'error');
    if (data.ok) setTimeout(() => { window.location.href = '/kronet/public/anuncios/mis-anuncios'; }, 1200);
}
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
