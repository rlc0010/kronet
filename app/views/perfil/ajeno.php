<?php
$pageTitle = 'Perfil';
$navActive = '';
require __DIR__ . '/../partials/head.php';
require __DIR__ . '/../partials/navbar.php';
require_once __DIR__ . '/../../helpers/Categorias.php';
?>

<section class="hero-section hero-compact">
    <h1><?= isset($usuario['nombre']) ? htmlspecialchars($usuario['nombre']) : 'Perfil' ?></h1>
    <p>Perfil público en Kronet</p>
</section>

<div class="page-wrap-wide">

    <a class="back-link" href="javascript:history.back()"><i class="fas fa-arrow-left"></i> Volver</a>

    <?php if (isset($error)): ?>
        <div class="flash flash-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
    <?php elseif ($meHaBloqueado): ?>
        <div class="blocked-wall">
            <div class="bw-avatar"><?= strtoupper(substr($usuario['nombre'], 0, 1)) ?></div>
            <h2><?= htmlspecialchars($usuario['nombre']) ?></h2>
            <p>Este usuario ha restringido el acceso a su perfil.<br>No puedes ver su contenido ni contactar con él.</p>
        </div>
    <?php else: ?>

    <div class="profile-layout">

        <div>
            <div class="user-card">
                <div class="avatar-wrapper">
                    <div class="main-avatar-placeholder">
                        <?= strtoupper(substr($usuario['nombre'], 0, 1)) ?>
                    </div>
                </div>
                <h2><?= htmlspecialchars($usuario['nombre']) ?></h2>
                <p class="user-desc"><?= htmlspecialchars($usuario['descripcion'] ?: 'Sin descripción') ?></p>

                <?php if ($media['total'] > 0): ?>
                    <div class="rating-badge">
                        <i class="fas fa-star"></i>
                        <?= number_format($media['media'], 1) ?> / 5
                        <span style="font-weight:500; opacity:0.85;">(<?= (int)$media['total'] ?>)</span>
                    </div>
                <?php else: ?>
                    <p style="font-size:13px; color:var(--gris-texto); margin-bottom:12px;">Sin valoraciones aún</p>
                <?php endif; ?>

                <div class="action-strip" style="flex-direction:column;">
                    <?php if (!$yaValorado): ?>
                        <a class="btn btn-valorar btn-sm" href="/kronet/public/valoraciones/crear?id_usuario=<?= (int)$usuario['id_usuario'] ?>">
                            <i class="fas fa-star"></i> Dejar valoración
                        </a>
                    <?php else: ?>
                        <span class="btn btn-ghost btn-sm" style="cursor:default;">
                            <i class="fas fa-check"></i> Ya lo has valorado
                        </span>
                    <?php endif; ?>

                    <?php if (!$esContacto): ?>
                        <button class="btn btn-outline btn-sm" onclick="agregar(<?= (int)$usuario['id_usuario'] ?>)">
                            <i class="fas fa-user-plus"></i> Agregar a contactos
                        </button>
                    <?php else: ?>
                        <button class="btn btn-ghost btn-sm" onclick="quitar(<?= (int)$usuario['id_usuario'] ?>)">
                            <i class="fas fa-user-check"></i> En tus contactos · Quitar
                        </button>
                    <?php endif; ?>

                    <?php if (!$estaBloqueado): ?>
                        <button class="btn btn-danger btn-sm" onclick="bloquear(<?= (int)$usuario['id_usuario'] ?>)">
                            <i class="fas fa-ban"></i> Bloquear usuario
                        </button>
                    <?php else: ?>
                        <button class="btn btn-ghost btn-sm" onclick="desbloquear(<?= (int)$usuario['id_usuario'] ?>)">
                            <i class="fas fa-undo"></i> Desbloquear
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="profile-main-col">

            <!-- Anuncios -->
            <div class="card">
                <div class="page-header" style="margin-bottom:18px;">
                    <h2><i class="fas fa-bullhorn"></i> Anuncios activos</h2>
                </div>
                <?php if (empty($anuncios)): ?>
                    <div class="empty-state">
                        <div class="empty-icon"><i class="fas fa-bullhorn"></i></div>
                        <p>Este usuario no tiene anuncios activos.</p>
                    </div>
                <?php else: ?>
                    <div class="services-grid">
                        <?php foreach ($anuncios as $a):
                            $catNombre = Categorias::nombre($a['categoria']);
                            $catIcon   = Categorias::icono($a['categoria']);
                        ?>
                            <div class="service-card">
                                <div class="card-body">
                                    <div class="tags-row">
                                        <span class="badge <?= $a['tipo_anuncio'] === 'oferta' ? 'badge-oferta' : 'badge-demanda' ?>"><?= ucfirst($a['tipo_anuncio']) ?></span>
                                    </div>
                                    <h3>
                                        <a href="/kronet/public/anuncios/<?= (int)$a['id_anuncio'] ?>" style="color:inherit;">
                                            <?= htmlspecialchars($a['titulo']) ?>
                                        </a>
                                    </h3>
                                    <p><?= htmlspecialchars(mb_substr($a['descripcion'],0,120)) ?>…</p>
                                    <div class="tags-row">
                                        <span class="tag tag-cat"><i class="<?= $catIcon ?>"></i> <?= htmlspecialchars($catNombre) ?></span>
                                    </div>
                                </div>
                                <span class="time-tag"><i class="fas fa-coins"></i> <?= (int)$a['precio_creditos'] ?></span>
                                <div class="card-footer">
                                    <a class="btn btn-primary btn-sm" href="/kronet/public/anuncios/<?= (int)$a['id_anuncio'] ?>">
                                        <i class="fas fa-arrow-right"></i> Ver anuncio
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Valoraciones -->
            <div class="card">
                <div class="page-header" style="margin-bottom:18px;">
                    <h2><i class="fas fa-star"></i> Valoraciones recibidas</h2>
                </div>
                <?php if (empty($valoraciones)): ?>
                    <div class="empty-state">
                        <div class="empty-icon"><i class="fas fa-star"></i></div>
                        <p>Este usuario todavía no tiene valoraciones.</p>
                    </div>
                <?php else: ?>
                    <div class="intercambios-list">
                        <?php foreach ($valoraciones as $v): ?>
                            <div class="valoracion-card">
                                <div class="vc-header">
                                    <span class="vc-autor"><?= htmlspecialchars($v['nombre_autor']) ?></span>
                                    <span class="vc-fecha"><?= htmlspecialchars($v['fecha']) ?></span>
                                </div>
                                <div class="stars-display">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star <?= $i <= $v['puntuacion'] ? '' : 'empty' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <?php if (!empty($v['comentario'])): ?>
                                    <p class="vc-comentario">"<?= htmlspecialchars($v['comentario']) ?>"</p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <?php endif; ?>
</div>

<script>
async function agregar(id) {
    const data = await apiPost('/kronet/public/usuarios/agregar', { id_usuario: id });
    showToast(data.msg, data.ok ? 'ok' : 'warn');
    if (data.ok) setTimeout(() => window.location.reload(), 700);
}
async function quitar(id) {
    if (!confirmar('¿Quitar de contactos?')) return;
    const data = await apiPost('/kronet/public/usuarios/quitar', { id_usuario: id });
    showToast(data.msg, data.ok ? 'ok' : 'error');
    if (data.ok) setTimeout(() => window.location.reload(), 700);
}
async function bloquear(id) {
    if (!confirmar('¿Bloquear a este usuario?')) return;
    const data = await apiPost('/kronet/public/usuarios/bloquear', { id_usuario: id });
    showToast(data.msg, data.ok ? 'ok' : 'warn');
    if (data.ok) setTimeout(() => window.location.reload(), 700);
}
async function desbloquear(id) {
    const data = await apiPost('/kronet/public/usuarios/desbloquear', { id_usuario: id });
    showToast(data.msg, data.ok ? 'ok' : 'error');
    if (data.ok) setTimeout(() => window.location.reload(), 700);
}
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
