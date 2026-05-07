<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis valoraciones - Kronet</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 700px; margin: 40px auto; padding: 0 20px; }
        h1 { color: #333; }
        .resumen { background: #f0f7ff; border-radius: 10px; padding: 20px; margin-bottom: 30px; text-align: center; }
        .resumen .nota { font-size: 48px; font-weight: bold; color: #4a90e2; }
        .resumen .estrellas { font-size: 28px; color: #f5a623; }
        .resumen .total { color: #666; font-size: 14px; }
        .valoracion { border: 1px solid #e0e0e0; border-radius: 8px; padding: 16px; margin-bottom: 16px; }
        .valoracion .autor { font-weight: bold; color: #333; }
        .valoracion .estrellas { color: #f5a623; font-size: 18px; }
        .valoracion .comentario { color: #555; margin-top: 8px; }
        .valoracion .fecha { color: #999; font-size: 12px; margin-top: 8px; }
        .vacia { color: #888; text-align: center; margin-top: 40px; }
        .back { color: #4a90e2; text-decoration: none; }
    </style>
</head>
<body>

    <h1>⭐ Mis valoraciones</h1>
    <p><a class="back" href="/kronet/public/perfil">← Volver a mi perfil</a></p>

    <div class="resumen">
        <?php if ($media['total'] > 0): ?>
            <div class="nota"><?= number_format($media['media'], 1) ?></div>
            <div class="estrellas">
                <?php
                $m = round($media['media']);
                for ($i = 1; $i <= 5; $i++) echo $i <= $m ? '★' : '☆';
                ?>
            </div>
            <div class="total"><?= $media['total'] ?> valoracion<?= $media['total'] != 1 ? 'es' : '' ?> recibida<?= $media['total'] != 1 ? 's' : '' ?></div>
        <?php else: ?>
            <div class="nota">—</div>
            <div class="total">Todavía no tienes valoraciones</div>
        <?php endif; ?>
    </div>

    <?php if (empty($valoraciones)): ?>
        <p class="vacia">Cuando completes intercambios, otros usuarios podrán valorarte aquí.</p>
    <?php else: ?>
        <?php foreach ($valoraciones as $v): ?>
            <div class="valoracion">
                <div class="autor"><?= htmlspecialchars($v['nombre_autor']) ?></div>
                <div class="estrellas">
                    <?php for ($i = 1; $i <= 5; $i++) echo $i <= $v['puntuacion'] ? '★' : '☆'; ?>
                    <span style="font-size:14px; color:#555;">(<?= $v['puntuacion'] ?>/5)</span>
                </div>
                <?php if (!empty($v['comentario'])): ?>
                    <div class="comentario">"<?= htmlspecialchars($v['comentario']) ?>"</div>
                <?php endif; ?>
                <div class="fecha"><?= htmlspecialchars($v['fecha']) ?></div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</body>
</html>
