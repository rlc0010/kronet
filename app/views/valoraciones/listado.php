<?php
$pageTitle = 'Mis valoraciones';
$navActive = '';
require __DIR__ . '/../partials/head.php';
require __DIR__ . '/../partials/navbar.php';
?>

<section class="hero-section hero-compact">
    <h1>Mis valoraciones</h1>
    <p>Consulta las opiniones que otros usuarios han dejado sobre ti</p>
</section>

<div class="page-wrap">

    <a class="back-link" href="/kronet/public/perfil"><i class="fas fa-arrow-left"></i> Volver a mi perfil</a>

    <div class="card" style="text-align:center; margin-bottom:30px;">
        <?php if ($media['total'] > 0): ?>
            <div style="font-size:48px; font-weight:700; color:var(--azul-verdoso); font-family:'Poppins', sans-serif;">
                <?= number_format($media['media'], 1) ?>
            </div>
            <div class="stars-display" style="font-size:28px; margin:10px 0;">
                <?php
                $m = round($media['media']);
                for ($i = 1; $i <= 5; $i++) {
                    echo $i <= $m ? '★' : '<span class="empty">★</span>';
                }
                ?>
            </div>
            <p style="color:var(--gris-texto);">
                <?= (int)$media['total'] ?> valoración<?= $media['total'] != 1 ? 'es' : '' ?> recibida<?= $media['total'] != 1 ? 's' : '' ?>
            </p>
        <?php else: ?>
            <div style="font-size:48px; font-weight:700; color:var(--gris-suave);">—</div>
            <p style="color:var(--gris-texto);">Todavía no tienes valoraciones</p>
        <?php endif; ?>
    </div>

    <?php if (empty($valoraciones)): ?>
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-star-half-alt"></i></div>
            <p>Cuando completes intercambios, otros usuarios podrán valorarte aquí.</p>
        </div>
    <?php else: ?>
        <div class="profile-main-col">
            <?php foreach ($valoraciones as $v): ?>
                <div class="valoracion-card">
                    <div class="vc-header">
                        <div>
                            <div class="vc-autor"><?= htmlspecialchars($v['nombre_autor']) ?></div>
                            <div class="stars-display">
                                <?php for ($i = 1; $i <= 5; $i++) echo $i <= $v['puntuacion'] ? '★' : '<span class="empty">★</span>'; ?>
                                <span style="font-size:14px; color:var(--gris-texto); margin-left:6px;">(<?= (int)$v['puntuacion'] ?>/5)</span>
                            </div>
                        </div>
                        <div class="vc-fecha"><?= htmlspecialchars($v['fecha']) ?></div>
                    </div>
                    <?php if (!empty($v['comentario'])): ?>
                        <div class="vc-comentario">"<?= htmlspecialchars($v['comentario']) ?>"</div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
