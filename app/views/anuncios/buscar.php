<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar anuncios - Kronet</title>
    <link rel="stylesheet" href="/kronet/public/assets/css/kronet.css">
</head>
<body>

<div class="page-wrap-wide">

    <a class="back-link" href="/kronet/public/"><i class="fas fa-arrow-left"></i> Volver al inicio</a>

    <div class="page-header">
        <h1><i class="fas fa-search"></i> Buscar anuncios</h1>
        <p>Encuentra ofertas y demandas de otros usuarios</p>
    </div>

    <!-- Filtros -->
    <form method="GET" action="/kronet/public/anuncios/buscar" class="search-bar">
        <div>
            <input type="text" name="busqueda" value="<?= htmlspecialchars($_GET['busqueda'] ?? '') ?>" placeholder="Buscar por palabra clave...">
        </div>
        <div>
            <select name="tipo_anuncio">
                <option value="">Todos los tipos</option>
                <option value="oferta"  <?= ($_GET['tipo_anuncio'] ?? '') === 'oferta'  ? 'selected' : '' ?>>Oferta</option>
                <option value="demanda" <?= ($_GET['tipo_anuncio'] ?? '') === 'demanda' ? 'selected' : '' ?>>Demanda</option>
            </select>
        </div>
        <div>
            <select name="categoria">
                <option value="">Todas las categorías</option>
                <option value="tecnologia" <?= ($_GET['categoria'] ?? '') === 'tecnologia' ? 'selected' : '' ?>>Tecnología</option>
                <option value="educacion"  <?= ($_GET['categoria'] ?? '') === 'educacion'  ? 'selected' : '' ?>>Educación</option>
                <option value="hogar"      <?= ($_GET['categoria'] ?? '') === 'hogar'      ? 'selected' : '' ?>>Hogar</option>
                <option value="deporte"    <?= ($_GET['categoria'] ?? '') === 'deporte'    ? 'selected' : '' ?>>Deporte</option>
                <option value="arte"       <?= ($_GET['categoria'] ?? '') === 'arte'       ? 'selected' : '' ?>>Arte</option>
                <option value="otros"      <?= ($_GET['categoria'] ?? '') === 'otros'      ? 'selected' : '' ?>>Otros</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary">
            <i class="fas fa-search"></i> Buscar
        </button>
    </form>

    <p style="font-size:14px; color:var(--gris-suave); margin-bottom:20px;">
        <?= count($anuncios) ?> anuncio<?= count($anuncios) != 1 ? 's' : '' ?> encontrado<?= count($anuncios) != 1 ? 's' : '' ?>
    </p>

    <?php if (empty($anuncios)): ?>
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-search"></i></div>
            <p>No se han encontrado anuncios con esos filtros.</p>
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
                            <?php if ($anuncio['destacado']): ?>
                                <span class="badge badge-destacado"><i class="fas fa-star"></i> Destacado</span>
                            <?php endif; ?>
                        </div>
                        <h3><?= htmlspecialchars($anuncio['titulo']) ?></h3>
                        <p><?= htmlspecialchars($anuncio['descripcion']) ?></p>
                        <div class="tags-row">
                            <span class="tag"><i class="fas fa-folder"></i> <?= htmlspecialchars($anuncio['categoria']) ?></span>
                        </div>
                    </div>

                    <span class="time-tag"><i class="fas fa-clock"></i> <?= htmlspecialchars($anuncio['duracion_estimada']) ?>h</span>

                    <div class="card-footer">
                        <?php if ($anuncio['id_usuario'] == $_SESSION['id_usuario']): ?>
                            <a class="btn btn-outline btn-sm" href="/kronet/public/anuncios/<?= $anuncio['id_anuncio'] ?>/editar">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                        <?php else: ?>
                            <button class="btn btn-primary btn-sm" onclick="solicitarAnuncio(<?= $anuncio['id_anuncio'] ?>, this)">
                                <i class="fas fa-envelope"></i> Solicitar
                            </button>
                            <a class="btn btn-ghost btn-sm" href="/kronet/public/perfil/<?= $anuncio['id_usuario'] ?>">
                                <i class="fas fa-user"></i> Ver perfil
                            </a>
                        <?php endif; ?>
                    </div>

                    <div id="msg-solicitar-<?= $anuncio['id_anuncio'] ?>" style="padding:0 20px 12px; font-size:13px; font-weight:600;"></div>

                    <?php if ($anuncio['id_usuario'] != $_SESSION['id_usuario']): ?>
                        <div class="form-mensaje" id="form-msg-<?= $anuncio['id_anuncio'] ?>" style="padding:0 20px 16px; border-top:1px solid rgba(191,197,210,0.3); margin-top:4px;">
                            <p style="font-size:13px; font-weight:600; margin-bottom:8px;">Enviar mensaje al anunciante:</p>
                            <textarea class="kro-input" rows="2" id="texto-msg-<?= $anuncio['id_anuncio'] ?>" placeholder="Escribe un mensaje..."></textarea>
                            <div style="margin-top:8px; display:flex; align-items:center; gap:10px;">
                                <button class="btn btn-ghost btn-sm" onclick="enviarMensaje(<?= $anuncio['id_anuncio'] ?>, <?= $anuncio['id_usuario'] ?>)">
                                    <i class="fas fa-paper-plane"></i> Enviar
                                </button>
                                <span id="msg-msj-<?= $anuncio['id_anuncio'] ?>" style="font-size:12px;"></span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
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
                msgDiv.textContent = data.ok ? '✓ Solicitud enviada correctamente' : '✗ ' + data.msg;
                if (data.ok) { btn.innerHTML = '<i class="fas fa-check"></i> Solicitado'; }
                else { btn.disabled = false; btn.innerHTML = '<i class="fas fa-envelope"></i> Solicitar'; }
            })
            .catch(() => {
                document.getElementById('msg-solicitar-' + idAnuncio).textContent = 'Error de conexión.';
                btn.disabled = false; btn.innerHTML = '<i class="fas fa-envelope"></i> Solicitar';
            });
    }

    function enviarMensaje(idAnuncio, idReceptor) {
        const texto = document.getElementById('texto-msg-' + idAnuncio).value.trim();
        const msgDiv = document.getElementById('msg-msj-' + idAnuncio);
        if (!texto) { msgDiv.style.color = '#b91c1c'; msgDiv.textContent = 'Escribe un mensaje.'; return; }
        const formData = new FormData();
        formData.append('id_receptor', idReceptor);
        formData.append('mensaje', texto);
        fetch('/kronet/public/mensaje/enviar', { method: 'POST', body: formData })
            .then(() => {
                msgDiv.style.color = 'var(--verde-azulado)';
                msgDiv.textContent = '✓ Mensaje enviado';
                document.getElementById('texto-msg-' + idAnuncio).value = '';
            })
            .catch(() => { msgDiv.style.color = '#b91c1c'; msgDiv.textContent = 'Error al enviar.'; });
    }
</script>

</body>
</html>