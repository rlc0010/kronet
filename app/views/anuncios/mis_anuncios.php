<?php
$pageTitle = 'Mis anuncios';
$navActive = '';
require __DIR__ . '/../partials/head.php';
require __DIR__ . '/../partials/navbar.php';
require_once __DIR__ . '/../../helpers/Categorias.php';
?>

<section class="hero-section hero-compact">
    <h1>Mis anuncios</h1>
    <p>Gestiona tus publicaciones activas</p>
</section>

<div class="page-wrap-wide">

    <a class="back-link" href="/kronet/public/"><i class="fas fa-arrow-left"></i> Inicio</a>

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; flex-wrap:wrap; gap:12px;">
        <p style="color:var(--gris-texto);">Total: <strong><?= count($anuncios) ?></strong> anuncio<?= count($anuncios) != 1 ? 's' : '' ?></p>
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
            <?php foreach ($anuncios as $anuncio):
                $catSlug = $anuncio['categoria'];
                $catNombre = Categorias::nombre($catSlug);
                $catIcon = Categorias::icono($catSlug);
                $plazasFill = $anuncio['plazas_totales'] > 0
                    ? (int)($anuncio['plazas_ocupadas'] / $anuncio['plazas_totales'] * 100)
                    : 0;
            ?>
                <div class="service-card" id="anuncio-<?= $anuncio['id_anuncio'] ?>">
                    <div class="card-body">
                        <div class="tags-row">
                            <span class="badge <?= $anuncio['tipo_anuncio'] === 'oferta' ? 'badge-oferta' : 'badge-demanda' ?>">
                                <?= ucfirst($anuncio['tipo_anuncio']) ?>
                            </span>
                            <span class="badge badge-<?= $anuncio['estado'] ?>">
                                <?= ucfirst($anuncio['estado']) ?>
                            </span>
                            <?php if ($anuncio['destacado']): ?>
                                <span class="badge badge-destacado"><i class="fas fa-star"></i> Destacado</span>
                            <?php endif; ?>
                        </div>
                        <h3>
                            <a href="/kronet/public/anuncios/<?= $anuncio['id_anuncio'] ?>" style="color:inherit;">
                                <?= htmlspecialchars($anuncio['titulo']) ?>
                            </a>
                        </h3>
                        <p><?= htmlspecialchars(mb_substr($anuncio['descripcion'], 0, 140)) ?><?= mb_strlen($anuncio['descripcion']) > 140 ? '…' : '' ?></p>
                        <div class="tags-row">
                            <span class="tag tag-cat"><i class="<?= $catIcon ?>"></i> <?= htmlspecialchars($catNombre) ?></span>
                            <span class="tag"><i class="fas fa-clock"></i> <?= (int)$anuncio['duracion_estimada'] ?>h</span>
                        </div>
                        <div class="plazas-bar">
                            <span><i class="fas fa-users"></i> <?= (int)$anuncio['plazas_ocupadas'] ?>/<?= (int)$anuncio['plazas_totales'] ?> plazas</span>
                            <div class="bar"><div class="fill" style="width: <?= $plazasFill ?>%"></div></div>
                        </div>
                    </div>
                    <span class="time-tag"><i class="fas fa-coins"></i> <?= (int)$anuncio['precio_creditos'] ?> créd.</span>
                    <div class="card-footer">
                        <a class="btn btn-outline btn-sm" href="/kronet/public/anuncios/<?= $anuncio['id_anuncio'] ?>/editar">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a class="btn btn-valorar btn-sm" href="/kronet/public/anuncios/<?= $anuncio['id_anuncio'] ?>/destacar">
                            <i class="fas fa-star"></i> Destacar
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
async function eliminar(id) {
    if (!confirmar('¿Seguro que quieres eliminar este anuncio?')) return;
    const data = await apiPost('/kronet/public/anuncios/' + id + '/eliminar', {});
    if (data.ok) {
        document.getElementById('anuncio-' + id).remove();
        showToast('Anuncio eliminado', 'ok');
    } else {
        showToast(data.msg || 'No se pudo eliminar', 'error');
    }
}
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
