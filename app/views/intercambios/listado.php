<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/kronet/public/assets/images/logo.png">
    <title>Mis intercambios - Kronet</title>
    <link rel="stylesheet" href="/kronet/public/assets/css/kronet.css">
</head>
<body>

<div class="page-wrap">

    <a class="back-link" href="/kronet/public/"><i class="fas fa-arrow-left"></i> Inicio</a>

    <div class="page-header" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:16px;">
        <div>
            <h1><i class="fas fa-exchange-alt"></i> Mis intercambios</h1>
            <p>Historial de todos tus intercambios realizados</p>
        </div>
        <a href="/kronet/public/intercambios/ofertas-recibidas" class="btn btn-outline btn-sm">
            <i class="fas fa-inbox"></i> Ofertas recibidas
        </a>
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
            <?php foreach ($intercambios as $i): ?>
                <?php
                $esOfertante   = ($i['id_usuario_ofertante']   == $_SESSION['id_usuario']);
                $idOtroUsuario = $esOfertante ? $i['id_usuario_solicitante'] : $i['id_usuario_ofertante'];
                $rolLabel      = $esOfertante ? 'Ofertante' : 'Solicitante';
                ?>
                <div class="intercambio-card">
                    <div class="ic-header">
                        <h3><?= htmlspecialchars($i['titulo_anuncio']) ?></h3>
                        <span class="badge badge-<?= $i['estado'] ?>">
                            <?= ucfirst($i['estado']) ?>
                        </span>
                    </div>
                    <div class="ic-meta">
                        <span><i class="fas fa-coins"></i> <strong><?= htmlspecialchars($i['monedas_intercambio']) ?></strong> créditos</span>
                        <span><i class="fas fa-calendar"></i> <?= htmlspecialchars($i['fecha_inicio']) ?></span>
                        <span><i class="fas fa-user-tag"></i> Rol: <?= $rolLabel ?></span>
                    </div>
                    <?php if ($i['estado'] === 'confirmado'): ?>
                        <div class="ic-actions">
                            <a class="btn btn-valorar btn-sm" href="/kronet/public/valoraciones/crear?id_usuario=<?= $idOtroUsuario ?>">
                                <i class="fas fa-star"></i> Valorar usuario
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

</body>
</html>