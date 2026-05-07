<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/kronet/public/assets/images/logo.png">
    <title>Bandeja de mensajes - Kronet</title>
    <link rel="stylesheet" href="/kronet/public/assets/css/kronet.css">
</head>
<body>

<div class="page-wrap">

    <a class="back-link" href="/kronet/public/"><i class="fas fa-arrow-left"></i> Volver al inicio</a>

    <div class="page-header">
        <h1><i class="fas fa-envelope"></i> Bandeja de mensajes</h1>
        <p>Mensajes recibidos de otros usuarios</p>
    </div>

    <?php if (empty($mensajes)): ?>
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-envelope-open"></i></div>
            <p>No tienes mensajes recibidos.</p>
        </div>
    <?php else: ?>
        <div class="intercambios-list">
            <?php foreach ($mensajes as $m): ?>
                <div class="mensaje-card <?= !$m['leido'] ? 'unread' : '' ?>" id="msg-<?= $m['id_mensaje'] ?>">
                    <div class="mc-head">
                        <span class="mc-autor">
                            <i class="fas fa-user"></i> <?= htmlspecialchars($m['nombre']) ?>
                        </span>
                        <span class="mc-fecha"><i class="fas fa-calendar"></i> <?= htmlspecialchars($m['fecha_envio']) ?></span>
                    </div>

                    <p class="mc-content"><?= htmlspecialchars($m['contenido']) ?></p>

                    <div style="display:flex; gap:10px; flex-wrap:wrap;">
                        <button class="btn btn-secondary btn-sm" onclick="toggleRespuesta(<?= $m['id_mensaje'] ?>, <?= $m['id_emisor'] ?>)">
                            <i class="fas fa-reply"></i> Responder
                        </button>
                        <a class="btn btn-ghost btn-sm" href="/kronet/public/perfil/<?= $m['id_emisor'] ?>">
                            <i class="fas fa-user"></i> Ver perfil
                        </a>
                    </div>

                    <div class="reply-form" id="reply-<?= $m['id_mensaje'] ?>">
                        <div class="form-group" style="margin-top:12px;">
                            <textarea class="kro-input" rows="3" id="reply-text-<?= $m['id_mensaje'] ?>" placeholder="Escribe tu respuesta..."></textarea>
                        </div>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <button class="btn btn-secondary btn-sm" onclick="enviarRespuesta(<?= $m['id_mensaje'] ?>, <?= $m['id_emisor'] ?>)">
                                <i class="fas fa-paper-plane"></i> Enviar
                            </button>
                            <span id="reply-msg-<?= $m['id_mensaje'] ?>" style="font-size:12px; font-weight:600;"></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<script>
    function toggleRespuesta(idMsg, idEmisor) {
        const form = document.getElementById('reply-' + idMsg);
        form.style.display = form.style.display === 'none' || form.style.display === '' ? 'block' : 'none';
    }

    function enviarRespuesta(idMsg, idReceptor) {
        const texto = document.getElementById('reply-text-' + idMsg).value.trim();
        const msgDiv = document.getElementById('reply-msg-' + idMsg);

        if (!texto) {
            msgDiv.style.color = '#b91c1c';
            msgDiv.textContent = 'Escribe un mensaje.';
            return;
        }

        const formData = new FormData();
        formData.append('id_receptor', idReceptor);
        formData.append('mensaje', texto);

        fetch('/kronet/public/mensaje/enviar', { method: 'POST', body: formData })
            .then(res => res.text())
            .then(() => {
                msgDiv.style.color = 'var(--verde-azulado)';
                msgDiv.textContent = '✓ Respuesta enviada';
                document.getElementById('reply-text-' + idMsg).value = '';
                setTimeout(() => {
                    document.getElementById('reply-' + idMsg).style.display = 'none';
                    msgDiv.textContent = '';
                }, 2000);
            })
            .catch(() => {
                msgDiv.style.color = '#b91c1c';
                msgDiv.textContent = 'Error al enviar.';
            });
    }
</script>

</body>
</html>