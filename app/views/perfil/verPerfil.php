<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi perfil - Kronet</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 750px; margin: 40px auto; padding: 0 20px; }
        h1 { color: #333; }
        .perfil-card { background: #f9f9f9; border-radius: 10px; padding: 24px; margin-bottom: 30px; border: 1px solid #e0e0e0; }
        .perfil-card .nombre { font-size: 22px; font-weight: bold; color: #222; }
        .campo { margin: 8px 0; color: #555; }
        .campo strong { color: #333; }
        .saldo { display: inline-block; background: #4a90e2; color: white; border-radius: 20px; padding: 5px 16px; font-weight: bold; margin-top: 10px; }
        .estrellas { color: #f5a623; font-size: 20px; }
        h2 { color: #444; border-bottom: 2px solid #eee; padding-bottom: 8px; margin-top: 30px; }
        .intercambio { border: 1px solid #ddd; border-radius: 8px; padding: 14px; margin-bottom: 12px; }
        .estado { display: inline-block; padding: 3px 12px; border-radius: 12px; font-size: 12px; font-weight: bold; }
        .estado-pendiente  { background: #fff3e0; color: #e65100; }
        .estado-confirmado { background: #e8f5e9; color: #2e7d32; }
        .estado-cancelado  { background: #fce4ec; color: #c62828; }
        .detalle { color: #666; font-size: 13px; margin-top: 6px; }
        .btn { display: inline-block; padding: 8px 16px; border-radius: 6px; font-size: 13px; text-decoration: none; cursor: pointer; border: none; font-family: Arial, sans-serif; }
        .btn-valorar { background: #f5a623; color: white; margin-top: 8px; }
        .btn-valorar:hover { background: #d4891e; }
        .vacio { color: #999; font-style: italic; }
        .nav-links { margin-bottom: 20px; }
        .nav-links a { color: #4a90e2; text-decoration: none; margin-right: 14px; }
    </style>
</head>
<body>

    <h1>👤 Mi perfil</h1>

    <div class="nav-links">
        <a href="/kronet/public/">← Inicio</a>
        <a href="/kronet/public/intercambios/ofertas-recibidas">Ofertas recibidas</a>
        <a href="/kronet/public/intercambios/mis-intercambios">Mis intercambios</a>
        <a href="/kronet/public/valoraciones/mis-valoraciones">Mis valoraciones</a>
        <a href="/kronet/public/logout">Cerrar sesión</a>
    </div>

    <?php if (isset($error)): ?>
        <p style="color:red"><?= htmlspecialchars($error) ?></p>
    <?php else: ?>

        <div class="perfil-card">
            <div class="nombre"><?= htmlspecialchars($usuario['nombre'] ?? 'Sin nombre') ?></div>

            <div class="campo"><strong>Email:</strong> <?= htmlspecialchars($usuario['email'] ?? 'No disponible') ?></div>
            <div class="campo"><strong>Descripción:</strong> <?= htmlspecialchars($usuario['descripcion'] ?? 'Sin descripción') ?></div>

            <div class="saldo">🪙 <?= htmlspecialchars($usuario['saldo_monedas'] ?? 0) ?> créditos</div>

            <?php if (isset($media) && $media['total'] > 0): ?>
                <div style="margin-top: 14px;">
                    <span class="estrellas">
                        <?php $m = round($media['media']); for ($i = 1; $i <= 5; $i++) echo $i <= $m ? '★' : '☆'; ?>
                    </span>
                    <strong><?= number_format($media['media'], 1) ?>/5</strong>
                    <span style="color:#888; font-size:13px;">(<?= $media['total'] ?> valoraciones)</span>
                </div>
            <?php endif; ?>
        </div>

        <h2>Historial de intercambios</h2>

        <?php if (empty($historial)): ?>
            <p class="vacio">Todavía no has participado en ningún intercambio.</p>
        <?php else: ?>
            <?php foreach ($historial as $i): ?>
                <?php
                $esOfertante   = ($i['id_usuario_ofertante'] == $_SESSION['id_usuario']);
                $idOtroUsuario = $esOfertante ? $i['id_usuario_solicitante'] : $i['id_usuario_ofertante'];
                $rolLabel      = $esOfertante ? 'Ofertante' : 'Solicitante';
                ?>
                <div class="intercambio">
                    <strong><?= htmlspecialchars($i['titulo_anuncio']) ?></strong>
                    <span class="estado estado-<?= $i['estado'] ?>"><?= ucfirst($i['estado']) ?></span>
                    <div class="detalle">
                        🪙 <?= htmlspecialchars($i['monedas_intercambio']) ?> créditos
                        &nbsp;|&nbsp; 📅 <?= htmlspecialchars($i['fecha_inicio']) ?>
                        &nbsp;|&nbsp; Rol: <?= $rolLabel ?>
                    </div>
                    <?php if ($i['estado'] === 'confirmado'): ?>
                        <a class="btn btn-valorar" href="/kronet/public/valoraciones/crear?id_usuario=<?= $idOtroUsuario ?>">
                            ⭐ Valorar usuario
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    <?php endif; ?>

</body>
</html>
