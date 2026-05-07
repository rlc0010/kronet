<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/kronet/public/assets/images/logo.png">
    <title>Perfil de <?= htmlspecialchars($usuario['nombre'] ?? 'Usuario') ?> - Kronet</title>
    <link rel="stylesheet" href="/kronet/public/assets/css/kronet.css">
</head>
<body>

<div class="page-wrap">

    <a class="back-link" href="javascript:history.back()"><i class="fas fa-arrow-left"></i> Volver</a>

    <?php if (isset($error)): ?>
        <div class="flash flash-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
    <?php else: ?>

    <div class="profile-layout">

        <!-- Sidebar -->
        <div class="profile-sidebar">
            <div class="user-card">
                <div class="avatar-wrapper">
                    <div class="main-avatar-placeholder">
                        <?= strtoupper(substr($usuario['nombre'], 0, 1)) ?>
                    </div>
                </div>
                <h2><?= htmlspecialchars($usuario['nombre']) ?></h2>
                <p class="user-desc"><?= htmlspecialchars($usuario['descripcion'] ?? 'Sin descripción') ?></p>

                <?php if ($media['total'] > 0): ?>
                    <div class="rating-badge">
                        <i class="fas fa-star"></i>
                        <?= number_format($media['media'], 1) ?> / 5
                    </div>
                    <p style="font-size:13px; color:var(--gris-suave); margin-bottom:16px;">(<?= $media['total'] ?> valoraciones)</p>
                <?php else: ?>
                    <p style="font-size:13px; color:var(--gris-suave); margin-bottom:16px;">Sin valoraciones aún</p>
                <?php endif; ?>

                <div class="action-strip">
                    <a class="btn btn-secondary btn-sm" href="/kronet/public/mensajes/nuevo?id_receptor=<?= $usuario['id_usuario'] ?>">
                        <i class="fas fa-envelope"></i> Enviar mensaje
                    </a>
                    <?php if (!$yaValorado): ?>
                        <a class="btn btn-valorar btn-sm" href="/kronet/public/valoraciones/crear?id_usuario=<?= $usuario['id_usuario'] ?>">
                            <i class="fas fa-star"></i> Dejar valoración
                        </a>
                    <?php else: ?>
                        <span class="btn btn-ghost btn-sm" style="cursor:default;">
                            <i class="fas fa-check"></i> Ya valorado
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Columna principal -->
        <div class="profile-main-col">

            <!-- Anuncios -->
            <div class="card">
                <div class="page-header">
                    <h2><i class="fas fa-bullhorn"></i> Anuncios de <?= htmlspecialchars($usuario['nombre']) ?></h2>
                </div>

                <?php if (empty($anuncios)): ?>
                    <div class="empty-state">
                        <div class="empty-icon"><i class="fas fa-bullhorn"></i></div>
                        <p>Este usuario no tiene anuncios activos.</p>
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
                                    </div>
                                    <h3><?= htmlspecialchars($anuncio['titulo']) ?></h3>
                                    <p><?= htmlspecialchars($anuncio['descripcion']) ?></p>
                                    <div class="tags-row">
                                        <span class="tag"><i class="fas fa-folder"></i> <?= htmlspecialchars($anuncio['categoria']) ?></span>
                                        <span class="tag"><i class="fas fa-clock"></i> <?= htmlspecialchars($anuncio['duracion_estimada']) ?>h</span>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button class="btn btn-primary btn-sm" onclick="solicitarAnuncio(<?= $anuncio['id_anuncio'] ?>, this)">
                                        <i class="fas fa-envelope"></i> Solicitar
                                    </button>
                                    <div id="msg-solicitar-<?= $anuncio['id_anuncio'] ?>" style="font-size:13px; font-weight:600;"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Valoraciones -->
            <div class="card">
                <div class="page-header">
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
    function solicitarAnuncio(idAnuncio, btn) {
        if (!confirm('¿Quieres solicitar este anuncio? Se descontarán los créditos correspondientes.')) return;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
        const formData = new FormData();
        formData.append('id_anuncio', idAnuncio);
        fetch('/kronet/public/intercambios/solicitar', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                const msgDiv = document.getElementById('msg-solicitar-' + idAnuncio);
                msgDiv.style.color = data.ok ? 'var(--verde-azulado)' : '#b91c1c';
                msgDiv.textContent = data.ok ? '✓ Solicitud enviada' : '✗ ' + data.msg;
                if (data.ok) { btn.innerHTML = '<i class="fas fa-check"></i> Solicitado'; }
                else { btn.disabled = false; btn.innerHTML = '<i class="fas fa-envelope"></i> Solicitar'; }
            })
            .catch(() => {
                document.getElementById('msg-solicitar-' + idAnuncio).textContent = 'Error de conexión.';
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-envelope"></i> Solicitar';
            });
    }
</script>

</body>
</html>