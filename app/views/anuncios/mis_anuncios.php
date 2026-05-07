<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/kronet/public/assets/images/logo.png">
    <title>Mis anuncios - Kronet</title>
    <link rel="stylesheet" href="/kronet/public/assets/css/kronet.css">
</head>
<body>

<div class="page-wrap">

    <a class="back-link" href="/kronet/public/"><i class="fas fa-arrow-left"></i> Inicio</a>

    <div class="page-header" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:16px;">
        <div>
            <h1>Mis anuncios</h1>
            <p>Gestiona tus publicaciones activas</p>
        </div>
        <a href="/kronet/public/anuncios/crear" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo anuncio
        </a>
    </div>

    <?php if (empty($anuncios)): ?>
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-bullhorn"></i></div>
            <p>Todavía no has publicado ningún anuncio.</p>
            <a href="/kronet/public/anuncios/crear" class="btn btn-primary">
                <i class="fas fa-plus"></i> Publicar mi primer anuncio
            </a>
        </div>
    <?php else: ?>
        <div class="services-grid">
            <?php foreach ($anuncios as $anuncio): ?>
                <div class="service-card" id="anuncio-<?= $anuncio['id_anuncio'] ?>">
                    <div class="card-body">
                        <div class="tags-row">
                            <span class="badge <?= $anuncio['tipo_anuncio'] === 'oferta' ? 'badge-oferta' : 'badge-demanda' ?>">
                                <?= ucfirst(htmlspecialchars($anuncio['tipo_anuncio'])) ?>
                            </span>
                            <span class="badge <?= $anuncio['estado'] === 'activo' ? 'badge-activo' : 'badge-inactivo' ?>">
                                <?= ucfirst(htmlspecialchars($anuncio['estado'])) ?>
                            </span>
                        </div>
                        <h3><?= htmlspecialchars($anuncio['titulo']) ?></h3>
                        <p><?= htmlspecialchars($anuncio['descripcion']) ?></p>
                        <div class="tags-row">
                            <span class="tag"><i class="fas fa-folder"></i> <?= htmlspecialchars($anuncio['categoria']) ?></span>
                            <span class="tag"><i class="fas fa-clock"></i> <?= htmlspecialchars($anuncio['duracion_estimada']) ?>h</span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a class="btn btn-outline btn-sm" href="/kronet/public/anuncios/<?= $anuncio['id_anuncio'] ?>/editar">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <button class="btn btn-danger btn-sm" onclick="eliminar(<?= $anuncio['id_anuncio'] ?>)">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<script>
    function eliminar(id) {
        if (!confirm('¿Seguro que quieres eliminar este anuncio?')) return;
        fetch('/kronet/public/anuncios/' + id + '/eliminar', { method: 'POST' })
            .then(res => res.json())
            .then(data => {
                if (data.ok) { document.getElementById('anuncio-' + id).remove(); }
                else { alert('Error: ' + data.msg); }
            });
    }
</script>

</body>
</html>