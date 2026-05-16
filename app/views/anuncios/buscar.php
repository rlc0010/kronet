<?php
$pageTitle = 'Explorar servicios';
$navActive = 'explorar';
require __DIR__ . '/../partials/head.php';
require __DIR__ . '/../partials/navbar.php';

$busquedaVal = $_GET['busqueda']     ?? '';
$tipoVal     = $_GET['tipo_anuncio'] ?? '';
$catVal      = $_GET['categoria']    ?? '';
$pageNum     = max(1, (int)($_GET['p'] ?? 1));
$miId        = $_SESSION['id_usuario'] ?? 0;
?>

<section class="hero-section hero-compact">
    <h1>Explora servicios</h1>
    <p>Descubre las habilidades y servicios que ofrece nuestra comunidad</p>
</section>

<div class="page-wrap-wide">

    <?php if (!$miId): ?>
        <div class="flash flash-info" style="margin-bottom:24px;">
            <i class="fas fa-info-circle"></i>
            Estás navegando como invitado. <a href="/kronet/public/login" style="font-weight:600;">Inicia sesión</a> o
            <a href="/kronet/public/register" style="font-weight:600;">crea una cuenta gratis</a> para apuntarte a un servicio o contactar con su autor.
        </div>
    <?php endif; ?>

    <form method="GET" action="/kronet/public/anuncios/buscar" class="search-bar">
        <input type="text" name="busqueda" value="<?= htmlspecialchars($busquedaVal) ?>" placeholder="Buscar por palabra clave...">

        <select name="tipo_anuncio">
            <option value="">Todos los tipos</option>
            <option value="oferta"  <?= $tipoVal === 'oferta'  ? 'selected' : '' ?>>Ofertas</option>
            <option value="demanda" <?= $tipoVal === 'demanda' ? 'selected' : '' ?>>Demandas</option>
        </select>

        <select name="categoria">
            <option value="">Todas las categorías</option>
            <?php foreach ($categorias as $slug => $cat): ?>
                <option value="<?= $slug ?>" <?= $catVal === $slug ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="btn btn-secondary">
            <i class="fas fa-search"></i> Buscar
        </button>
    </form>

    <p style="font-size:14px; color:var(--gris-texto); margin-bottom:20px;">
        <?= (int)$total ?> servicio<?= $total != 1 ? 's' : '' ?> encontrado<?= $total != 1 ? 's' : '' ?>
    </p>

    <?php if (empty($anuncios)): ?>
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-search"></i></div>
            <p>No se han encontrado anuncios con esos filtros.</p>
            <a href="/kronet/public/anuncios/buscar" class="btn btn-ghost">Limpiar filtros</a>
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
                $esMio = $miId && $anuncio['id_usuario'] == $miId;
            ?>
                <div class="service-card" id="anuncio-<?= $anuncio['id_anuncio'] ?>">
                    <div class="card-body">
                        <div class="tags-row">
                            <span class="badge <?= $anuncio['tipo_anuncio'] === 'oferta' ? 'badge-oferta' : 'badge-demanda' ?>">
                                <i class="fas <?= $anuncio['tipo_anuncio'] === 'oferta' ? 'fa-hand-holding-heart' : 'fa-hands-helping' ?>"></i>
                                <?= ucfirst($anuncio['tipo_anuncio']) ?>
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

                    <span class="time-tag">
                        <i class="fas fa-coins"></i>
                        <?= (int)$anuncio['precio_creditos'] ?> créd.
                    </span>

                    <div class="card-footer">
                        <div class="service-author">
                            <i class="fas fa-user"></i>
                            <?php if ($miId): ?>
                                <a href="/kronet/public/perfil/<?= $anuncio['id_usuario'] ?>">
                                    <?= htmlspecialchars($anuncio['nombre_usuario'] ?? 'Usuario') ?>
                                </a>
                            <?php else: ?>
                                <span><?= htmlspecialchars($anuncio['nombre_usuario'] ?? 'Usuario') ?></span>
                            <?php endif; ?>
                        </div>
                        <a class="btn btn-primary btn-sm" href="/kronet/public/anuncios/<?= $anuncio['id_anuncio'] ?>">
                            <?= $esMio ? '<i class="fas fa-eye"></i> Ver' : '<i class="fas fa-handshake"></i> Apuntarme' ?>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Paginación -->
        <?php if ($totalPages > 1):
            $qs = $_GET; unset($qs['p']);
            $base = '/kronet/public/anuncios/buscar?' . http_build_query($qs);
            $sep = $base === '/kronet/public/anuncios/buscar?' ? '' : '&';
        ?>
            <div class="pagination">
                <?php if ($pageNum > 1): ?>
                    <a href="<?= $base . $sep ?>p=<?= $pageNum - 1 ?>"><i class="fas fa-chevron-left"></i></a>
                <?php else: ?>
                    <span class="disabled"><i class="fas fa-chevron-left"></i></span>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php if ($i == $pageNum): ?>
                        <span class="current"><?= $i ?></span>
                    <?php else: ?>
                        <a href="<?= $base . $sep ?>p=<?= $i ?>"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($pageNum < $totalPages): ?>
                    <a href="<?= $base . $sep ?>p=<?= $pageNum + 1 ?>"><i class="fas fa-chevron-right"></i></a>
                <?php else: ?>
                    <span class="disabled"><i class="fas fa-chevron-right"></i></span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
