<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ofertas recibidas - Kronet</title>
    <link rel="stylesheet" href="/kronet/public/assets/css/kronet.css">
</head>
<body>

<div class="page-wrap">

    <a class="back-link" href="/kronet/public/"><i class="fas fa-arrow-left"></i> Inicio</a>

    <div class="page-header" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:16px;">
        <div>
            <h1><i class="fas fa-inbox"></i> Ofertas recibidas</h1>
            <p>Solicitudes pendientes de tu respuesta</p>
        </div>
        <a href="/kronet/public/intercambios/mis-intercambios" class="btn btn-outline btn-sm">
            <i class="fas fa-list"></i> Mis intercambios
        </a>
    </div>

    <?php if (empty($intercambios)): ?>
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-inbox"></i></div>
            <p>No tienes ofertas pendientes.</p>
        </div>
    <?php else: ?>
        <div class="intercambios-list">
            <?php foreach ($intercambios as $intercambio): ?>
                <div class="oferta-card" id="intercambio-<?= $intercambio['id_intercambio'] ?>">
                    <div class="of-head">
                        <div>
                            <h3><?= htmlspecialchars($intercambio['titulo_anuncio']) ?></h3>
                            <div class="of-info">
                                <span><i class="fas fa-user"></i> <?= htmlspecialchars($intercambio['nombre_ofertante']) ?></span>
                                <span><i class="fas fa-coins"></i> <?= htmlspecialchars($intercambio['monedas_intercambio']) ?> créditos</span>
                                <span><i class="fas fa-calendar"></i> <?= htmlspecialchars($intercambio['fecha_inicio']) ?></span>
                            </div>
                        </div>
                        <span class="badge badge-pendiente">Pendiente</span>
                    </div>
                    <div class="of-actions">
                        <button class="btn btn-primary btn-sm" onclick="gestionarOferta(<?= $intercambio['id_intercambio'] ?>, 'aceptar', this)">
                            <i class="fas fa-check"></i> Aceptar
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="gestionarOferta(<?= $intercambio['id_intercambio'] ?>, 'rechazar', this)">
                            <i class="fas fa-times"></i> Rechazar
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<script>
    function gestionarOferta(id, accion, btn) {
        fetch('/kronet/public/intercambios/' + id + '/' + accion, { method: 'POST' })
            .then(res => res.json())
            .then(data => {
                if (data.ok) {
                    const div = document.getElementById('intercambio-' + id);
                    div.innerHTML = '<p class="flash flash-ok"><i class="fas fa-check-circle"></i> ' + data.msg + '</p>';
                } else { alert('Error: ' + data.msg); }
            });
    }
</script>

</body>
</html>