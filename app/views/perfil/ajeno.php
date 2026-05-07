<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de <?= htmlspecialchars($usuario['nombre'] ?? 'Usuario') ?> - Kronet</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 750px; margin: 40px auto; padding: 0 20px; }
        h1 { color: #333; }
        .perfil-card { background: #f9f9f9; border-radius: 10px; padding: 24px; margin-bottom: 30px; border: 1px solid #e0e0e0; }
        .perfil-card .nombre { font-size: 24px; font-weight: bold; color: #222; }
        .perfil-card .descripcion { color: #555; margin-top: 8px; }
        .perfil-card .meta { color: #888; font-size: 13px; margin-top: 10px; }
        .estrellas { color: #f5a623; font-size: 20px; }
        .media-badge { display: inline-block; background: #4a90e2; color: white; border-radius: 20px; padding: 4px 14px; font-size: 14px; font-weight: bold; margin-top: 8px; }
        .btn { display: inline-block; padding: 10px 20px; border-radius: 6px; font-size: 14px; cursor: pointer; text-decoration: none; border: none; font-family: Arial, sans-serif; }
        .btn-primary { background: #4a90e2; color: white; }
        .btn-primary:hover { background: #357abd; }
        .btn-secondary { background: #e8f0fe; color: #4a90e2; border: 1px solid #c5d8f8; }
        .btn-secondary:hover { background: #c5d8f8; }
        .btn-valorar { background: #f5a623; color: white; }
        .btn-valorar:hover { background: #d4891e; }
        .acciones { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 16px; }
        h2 { color: #444; border-bottom: 2px solid #eee; padding-bottom: 8px; }
        .anuncio-card { border: 1px solid #ddd; border-radius: 8px; padding: 16px; margin-bottom: 14px; }
        .anuncio-card h3 { margin: 0 0 8px; color: #333; }
        .anuncio-card .tipo { display: inline-block; padding: 2px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; }
        .tipo-oferta { background: #e8f8e8; color: #2a7a2a; }
        .tipo-demanda { background: #fff0e8; color: #c04000; }
        .anuncio-card .detalle { color: #666; font-size: 13px; margin: 6px 0; }
        .valoracion { border: 1px solid #e0e0e0; border-radius: 8px; padding: 14px; margin-bottom: 14px; }
        .valoracion .autor { font-weight: bold; }
        .valoracion .comentario { color: #555; margin-top: 6px; font-style: italic; }
        .valoracion .fecha { color: #aaa; font-size: 12px; margin-top: 6px; }
        .vacio { color: #999; font-style: italic; }
        #msg-solicitar { margin-top: 10px; font-weight: bold; }
        .back { color: #4a90e2; text-decoration: none; }
    </style>
</head>
<body>

    <p><a class="back" href="javascript:history.back()">← Volver</a> | <a class="back" href="/kronet/public/">Inicio</a></p>

    <?php if (isset($error)): ?>
        <p style="color:red"><?= htmlspecialchars($error) ?></p>
    <?php else: ?>

    <div class="perfil-card">
        <div class="nombre"><?= htmlspecialchars($usuario['nombre']) ?></div>
        <div class="descripcion"><?= htmlspecialchars($usuario['descripcion'] ?? 'Sin descripción') ?></div>

        <?php if ($media['total'] > 0): ?>
            <div style="margin-top: 10px;">
                <span class="estrellas">
                    <?php $m = round($media['media']); for ($i = 1; $i <= 5; $i++) echo $i <= $m ? '★' : '☆'; ?>
                </span>
                <span class="media-badge"><?= number_format($media['media'], 1) ?> / 5</span>
                <span style="color:#888; font-size:13px;"> (<?= $media['total'] ?> valoraciones)</span>
            </div>
        <?php else: ?>
            <div class="meta" style="margin-top:10px;">Sin valoraciones aún</div>
        <?php endif; ?>

        <div class="acciones">
            <a class="btn btn-secondary" href="/kronet/public/mensajes/nuevo?id_receptor=<?= $usuario['id_usuario'] ?>">✉ Enviar mensaje</a>
            <?php if (!$yaValorado): ?>
                <a class="btn btn-valorar" href="/kronet/public/valoraciones/crear?id_usuario=<?= $usuario['id_usuario'] ?>">⭐ Dejar valoración</a>
            <?php else: ?>
                <span class="btn" style="background:#eee;color:#888;cursor:default;">✓ Ya valorado</span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Anuncios activos del usuario -->
    <h2>Anuncios de <?= htmlspecialchars($usuario['nombre']) ?></h2>

    <?php if (empty($anuncios)): ?>
        <p class="vacio">Este usuario no tiene anuncios activos.</p>
    <?php else: ?>
        <?php foreach ($anuncios as $anuncio): ?>
            <div class="anuncio-card" id="anuncio-<?= $anuncio['id_anuncio'] ?>">
                <h3><?= htmlspecialchars($anuncio['titulo']) ?></h3>
                <span class="tipo tipo-<?= $anuncio['tipo_anuncio'] === 'oferta' ? 'oferta' : 'demanda' ?>">
                    <?= ucfirst(htmlspecialchars($anuncio['tipo_anuncio'])) ?>
                </span>
                <div class="detalle"><?= htmlspecialchars($anuncio['descripcion']) ?></div>
                <div class="detalle">📂 <?= htmlspecialchars($anuncio['categoria']) ?> &nbsp;|&nbsp; ⏱ <?= htmlspecialchars($anuncio['duracion_estimada']) ?> h</div>

                <div style="margin-top:12px;">
                    <button class="btn btn-primary" onclick="solicitarAnuncio(<?= $anuncio['id_anuncio'] ?>, this)">
                        ✉ Solicitar este anuncio
                    </button>
                    <div id="msg-solicitar-<?= $anuncio['id_anuncio'] ?>" style="margin-top:8px; font-weight:bold;"></div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Valoraciones recibidas -->
    <h2>Valoraciones recibidas</h2>

    <?php if (empty($valoraciones)): ?>
        <p class="vacio">Este usuario todavía no tiene valoraciones.</p>
    <?php else: ?>
        <?php foreach ($valoraciones as $v): ?>
            <div class="valoracion">
                <div class="autor"><?= htmlspecialchars($v['nombre_autor']) ?></div>
                <div class="estrellas">
                    <?php for ($i = 1; $i <= 5; $i++) echo $i <= $v['puntuacion'] ? '★' : '☆'; ?>
                </div>
                <?php if (!empty($v['comentario'])): ?>
                    <div class="comentario">"<?= htmlspecialchars($v['comentario']) ?>"</div>
                <?php endif; ?>
                <div class="fecha"><?= htmlspecialchars($v['fecha']) ?></div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php endif; ?>

    <script>
        function solicitarAnuncio(idAnuncio, btn) {
            if (!confirm('¿Quieres solicitar este anuncio? Se descontarán los créditos correspondientes.')) return;

            btn.disabled = true;
            btn.textContent = 'Procesando...';

            const formData = new FormData();
            formData.append('id_anuncio', idAnuncio);

            fetch('/kronet/public/intercambios/solicitar', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                const msgDiv = document.getElementById('msg-solicitar-' + idAnuncio);
                msgDiv.style.color = data.ok ? 'green' : 'red';
                msgDiv.textContent = data.ok ? '✓ Solicitud enviada correctamente' : '✗ ' + data.msg;
                if (data.ok) {
                    btn.textContent = '✓ Solicitado';
                } else {
                    btn.disabled = false;
                    btn.textContent = '✉ Solicitar este anuncio';
                }
            })
            .catch(() => {
                const msgDiv = document.getElementById('msg-solicitar-' + idAnuncio);
                msgDiv.style.color = 'red';
                msgDiv.textContent = 'Error de conexión.';
                btn.disabled = false;
                btn.textContent = '✉ Solicitar este anuncio';
            });
        }
    </script>

</body>
</html>
