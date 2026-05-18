<?php
$pageTitle = 'Mis intercambios';
$navActive = 'servicios';
require __DIR__ . '/../partials/head.php';
require __DIR__ . '/../partials/navbar.php';
?>

<section class="hero-section hero-compact">
    <h1>Mis intercambios</h1>
    <p>Historial de todos tus intercambios realizados</p>
</section>

<div class="page-wrap">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:28px;">
        <a class="back-link" style="margin-bottom:0;" href="/kronet/public/"><i class="fas fa-arrow-left"></i> Inicio</a>
        <div class="tabs-container" style="margin-bottom:0;">
            <a href="/kronet/public/intercambios/mis-intercambios" class="tab-btn active">
                <i class="fas fa-exchange-alt"></i> Mis intercambios
            </a>
            <a href="/kronet/public/intercambios/ofertas-recibidas" class="tab-btn">
                <i class="fas fa-inbox"></i> Ofertas recibidas
            </a>
        </div>
    </div>

    <?php if (empty($intercambios)): ?>
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-exchange-alt"></i></div>
            <p>Todavía no has participado en ningún intercambio.</p>
            <a href="/kronet/public/anuncios/buscar" class="btn btn-primary">
                <i class="fas fa-search"></i> Explorar anuncios
            </a>
        </div>
    <?php else: ?>
        <div class="intercambios-list">
            <?php foreach ($intercambios as $i):
                $esOfertante  = ($i['id_usuario_ofertante']  == $_SESSION['id_usuario']);
                $idOtro       = $esOfertante ? $i['id_usuario_solicitante'] : $i['id_usuario_ofertante'];
                $rolLabel     = $esOfertante ? 'Eres el ofertante' : 'Eres el solicitante';
                $tipoAnuncio  = $i['tipo_anuncio'] ?? 'oferta';
            ?>
                <div class="intercambio-card">
                    <div class="ic-header">
                        <h3>
                            <?php if ($i['id_anuncio']): ?>
                                <a href="/kronet/public/anuncios/<?= (int)$i['id_anuncio'] ?>" style="color:inherit;">
                                    <?= htmlspecialchars($i['titulo_anuncio'] ?? '(anuncio eliminado)') ?>
                                </a>
                            <?php else: ?>
                                <?= htmlspecialchars($i['titulo_anuncio'] ?? '(anuncio eliminado)') ?>
                            <?php endif; ?>
                        </h3>
                        <span class="badge badge-<?= htmlspecialchars($i['estado']) ?>">
                            <?= ucfirst($i['estado']) ?>
                        </span>
                    </div>
                    <div class="ic-meta">
                        <span><i class="fas fa-coins"></i> <strong><?= (int)$i['monedas_intercambio'] ?></strong> créditos</span>
                        <span><i class="fas fa-calendar"></i> <?= htmlspecialchars($i['fecha_inicio']) ?></span>
                        <span><i class="fas fa-user-tag"></i> <?= $rolLabel ?></span>
                        <span class="badge <?= $tipoAnuncio === 'oferta' ? 'badge-oferta' : 'badge-demanda' ?>"><?= ucfirst($tipoAnuncio) ?></span>
                    </div>
                    <div class="ic-actions">
                        <a class="btn btn-ghost btn-sm" href="/kronet/public/perfil/<?= (int)$idOtro ?>">
                            <i class="fas fa-user"></i> Ver perfil
                        </a>
                        <?php if ($i['id_anuncio']): ?>
                            <a class="btn btn-outline btn-sm" href="/kronet/public/mensajes?anuncio=<?= (int)$i['id_anuncio'] ?>&usuario=<?= (int)$idOtro ?>">
                                <i class="fas fa-comments"></i> Abrir chat
                            </a>
                        <?php endif; ?>
                        <?php if ($i['estado'] === 'confirmado'): ?>
                            <a class="btn btn-valorar btn-sm" href="/kronet/public/valoraciones/crear?id_usuario=<?= (int)$idOtro ?>">
                                <i class="fas fa-star"></i> Valorar usuario
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
