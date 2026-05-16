<?php
$pageTitle = 'Ofertas recibidas';
$navActive = 'servicios';
require __DIR__ . '/../partials/head.php';
require __DIR__ . '/../partials/navbar.php';
?>

<section class="hero-section hero-compact">
    <h1>Ofertas recibidas</h1>
    <p>Solicitudes pendientes de tu respuesta</p>
</section>

<div class="page-wrap">

    <a class="back-link" href="/kronet/public/"><i class="fas fa-arrow-left"></i> Inicio</a>

    <div class="tabs-container">
        <a href="/kronet/public/intercambios/mis-intercambios" class="tab-btn">
            <i class="fas fa-exchange-alt"></i> Mis intercambios
        </a>
        <a href="/kronet/public/intercambios/ofertas-recibidas" class="tab-btn active">
            <i class="fas fa-inbox"></i> Ofertas recibidas
        </a>
    </div>

    <?php if (empty($intercambios)): ?>
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-inbox"></i></div>
            <p>No tienes ofertas pendientes.</p>
        </div>
    <?php else: ?>
        <div class="intercambios-list">
            <?php foreach ($intercambios as $i):
                $tipoAnuncio = $i['tipo_anuncio'] ?? 'oferta';
            ?>
                <div class="oferta-card" id="intercambio-<?= (int)$i['id_intercambio'] ?>">
                    <div class="of-head">
                        <div>
                            <h3>
                                <?php if ($i['id_anuncio']): ?>
                                    <a href="/kronet/public/anuncios/<?= (int)$i['id_anuncio'] ?>" style="color:inherit;">
                                        <?= htmlspecialchars($i['titulo_anuncio']) ?>
                                    </a>
                                <?php else: ?>
                                    <?= htmlspecialchars($i['titulo_anuncio']) ?>
                                <?php endif; ?>
                            </h3>
                            <div class="of-info">
                                <span><i class="fas fa-user"></i>
                                    <a href="/kronet/public/perfil/<?= (int)$i['id_usuario_solicitante'] ?>"><?= htmlspecialchars($i['nombre_solicitante']) ?></a>
                                </span>
                                <span><i class="fas fa-coins"></i> <?= (int)$i['monedas_intercambio'] ?> créditos</span>
                                <span><i class="fas fa-calendar"></i> <?= htmlspecialchars($i['fecha_inicio']) ?></span>
                                <span class="badge <?= $tipoAnuncio === 'oferta' ? 'badge-oferta' : 'badge-demanda' ?>"><?= ucfirst($tipoAnuncio) ?></span>
                            </div>
                        </div>
                        <span class="badge badge-pendiente">Pendiente</span>
                    </div>
                    <div class="of-actions">
                        <button class="btn btn-primary btn-sm" onclick="gestionar(<?= (int)$i['id_intercambio'] ?>, 'aceptar')">
                            <i class="fas fa-check"></i> Aceptar
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="gestionar(<?= (int)$i['id_intercambio'] ?>, 'rechazar')">
                            <i class="fas fa-times"></i> Rechazar
                        </button>
                        <?php if ($i['id_anuncio']): ?>
                            <a class="btn btn-outline btn-sm" href="/kronet/public/mensajes?anuncio=<?= (int)$i['id_anuncio'] ?>&usuario=<?= (int)$i['id_usuario_solicitante'] ?>">
                                <i class="fas fa-comments"></i> Chat
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<script>
async function gestionar(id, accion) {
    if (accion === 'aceptar') {
        if (!confirmar('Al aceptar se transferirán los créditos. ¿Confirmar?')) return;
    } else {
        if (!confirmar('¿Rechazar esta solicitud?')) return;
    }
    const data = await apiPost('/kronet/public/intercambios/' + id + '/' + accion, {});
    showToast(data.msg, data.ok ? 'ok' : 'error');
    if (data.ok) {
        const card = document.getElementById('intercambio-' + id);
        if (card) {
            card.style.opacity = '0.5';
            setTimeout(() => card.remove(), 400);
        }
    }
}
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
