<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis valoraciones - Kronet</title>

    <link rel="stylesheet" href="/kronet/public/assets/css/kronet.css">
</head>
<body>

<div class="page-wrap">

    <a class="back-link" href="/kronet/public/perfil">
        <i class="fas fa-arrow-left"></i>
        Volver a mi perfil
    </a>

    <div class="page-header">
        <h1><i class="fas fa-star"></i> Mis valoraciones</h1>
        <p>Consulta las opiniones y experiencias de otros usuarios contigo</p>
    </div>

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

            <p style="color:var(--gris-suave);">
                <?= $media['total'] ?> valoracion<?= $media['total'] != 1 ? 'es' : '' ?>
                recibida<?= $media['total'] != 1 ? 's' : '' ?>
            </p>

        <?php else: ?>

            <div style="font-size:48px; font-weight:700; color:var(--gris-suave);">
                —
            </div>

            <p style="color:var(--gris-suave);">
                Todavía no tienes valoraciones
            </p>

        <?php endif; ?>

    </div>

    <?php if (empty($valoraciones)): ?>

        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-star-half-alt"></i>
            </div>

            <p>
                Cuando completes intercambios, otros usuarios podrán valorarte aquí.
            </p>
        </div>

    <?php else: ?>

        <div class="profile-main-col">

            <?php foreach ($valoraciones as $v): ?>

                <div class="valoracion-card">

                    <div class="vc-header">

                        <div>
                            <div class="vc-autor">
                                <?= htmlspecialchars($v['nombre_autor']) ?>
                            </div>

                            <div class="stars-display">
                                <?php
                                for ($i = 1; $i <= 5; $i++) {
                                    echo $i <= $v['puntuacion']
                                        ? '★'
                                        : '<span class="empty">★</span>';
                                }
                                ?>

                                <span style="font-size:14px; color:var(--gris-suave); margin-left:6px;">
                                    (<?= $v['puntuacion'] ?>/5)
                                </span>
                            </div>
                        </div>

                        <div class="vc-fecha">
                            <?= htmlspecialchars($v['fecha']) ?>
                        </div>

                    </div>

                    <?php if (!empty($v['comentario'])): ?>

                        <div class="vc-comentario">
                            “<?= htmlspecialchars($v['comentario']) ?>”
                        </div>

                    <?php endif; ?>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</div>

</body>
</html>