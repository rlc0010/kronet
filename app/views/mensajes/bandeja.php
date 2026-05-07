<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bandeja de mensajes - Kronet</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 700px; margin: 40px auto; padding: 0 20px; }
        h1 { color: #333; }
        .mensaje { border: 1px solid #e0e0e0; border-radius: 8px; padding: 16px; margin-bottom: 12px; background: #fafafa; }
        .mensaje.no-leido { background: #f0f7ff; border-color: #a8c8f8; }
        .mensaje .autor { font-weight: bold; color: #333; }
        .mensaje .contenido { color: #555; margin: 6px 0; }
        .mensaje .fecha { color: #aaa; font-size: 12px; }
        .mensaje .acciones { margin-top: 10px; }
        .btn { display: inline-block; padding: 6px 14px; border-radius: 6px; font-size: 12px; text-decoration: none; cursor: pointer; border: none; font-family: Arial, sans-serif; }
        .btn-responder { background: #4a90e2; color: white; }
        .btn-responder:hover { background: #357abd; }
        .vacio { color: #999; font-style: italic; }
        .back { color: #4a90e2; text-decoration: none; }
        .reply-form { margin-top: 10px; display: none; }
        .reply-form textarea { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 6px; font-size: 13px; resize: vertical; }
        .reply-form button { margin-top: 6px; padding: 7px 16px; background: #4a90e2; color: white; border: none; border-radius: 6px; cursor: pointer; }
        .reply-msg { font-size: 12px; font-weight: bold; margin-top: 4px; }
    </style>
</head>
<body>

    <h1>✉ Bandeja de mensajes</h1>
    <p><a class="back" href="/kronet/public/">← Volver al inicio</a></p>

    <?php if (empty($mensajes)): ?>
        <p class="vacio">No tienes mensajes recibidos.</p>
    <?php else: ?>
        <?php foreach ($mensajes as $m): ?>
            <div class="mensaje <?= !$m['leido'] ? 'no-leido' : '' ?>" id="msg-<?= $m['id_mensaje'] ?>">
                <div class="autor">
                    👤 <?= htmlspecialchars($m['nombre']) ?>
                    <a class="btn" style="background:#f0f0f0;color:#555;font-size:11px;margin-left:8px;" href="/kronet/public/perfil/<?= $m['id_emisor'] ?>">Ver perfil</a>
                </div>
                <div class="contenido"><?= htmlspecialchars($m['contenido']) ?></div>
                <div class="fecha">📅 <?= htmlspecialchars($m['fecha_envio']) ?></div>

                <div class="acciones">
                    <button class="btn btn-responder" onclick="toggleRespuesta(<?= $m['id_mensaje'] ?>, <?= $m['id_emisor'] ?>)">
                        ↩ Responder
                    </button>
                </div>

                <div class="reply-form" id="reply-<?= $m['id_mensaje'] ?>">
                    <textarea rows="3" id="reply-text-<?= $m['id_mensaje'] ?>" placeholder="Escribe tu respuesta..."></textarea>
                    <button onclick="enviarRespuesta(<?= $m['id_mensaje'] ?>, <?= $m['id_emisor'] ?>)">Enviar</button>
                    <div class="reply-msg" id="reply-msg-<?= $m['id_mensaje'] ?>"></div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <script>
        function toggleRespuesta(idMsg, idEmisor) {
            const form = document.getElementById('reply-' + idMsg);
            form.style.display = form.style.display === 'none' || form.style.display === '' ? 'block' : 'none';
        }

        function enviarRespuesta(idMsg, idReceptor) {
            const texto = document.getElementById('reply-text-' + idMsg).value.trim();
            const msgDiv = document.getElementById('reply-msg-' + idMsg);

            if (!texto) {
                msgDiv.style.color = 'red';
                msgDiv.textContent = 'Escribe un mensaje.';
                return;
            }

            const formData = new FormData();
            formData.append('id_receptor', idReceptor);
            formData.append('mensaje', texto);

            fetch('/kronet/public/mensaje/enviar', {
                method: 'POST',
                body: formData
            })
            .then(res => res.text())
            .then(() => {
                msgDiv.style.color = 'green';
                msgDiv.textContent = '✓ Respuesta enviada';
                document.getElementById('reply-text-' + idMsg).value = '';
                setTimeout(() => {
                    document.getElementById('reply-' + idMsg).style.display = 'none';
                    msgDiv.textContent = '';
                }, 2000);
            })
            .catch(() => {
                msgDiv.style.color = 'red';
                msgDiv.textContent = 'Error al enviar.';
            });
        }
    </script>

</body>
</html>
