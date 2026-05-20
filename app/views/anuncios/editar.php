<?php
$pageTitle = 'Editar anuncio';
$navActive = '';
require __DIR__ . '/../partials/head.php';
require __DIR__ . '/../partials/navbar.php';
?>

<section class="hero-section hero-compact">
    <h1>Editar anuncio</h1>
    <p>Modifica los datos de tu publicación</p>
</section>

<div class="page-wrap">

    <a class="back-link" href="/kronet/public/anuncios/mis-anuncios"><i class="fas fa-arrow-left"></i> Mis anuncios</a>

    <div id="msg"></div>

    <div class="form-container">
        <form id="formEditar" novalidate>

            <div class="form-group">
                <label for="titulo">Título</label>
                <input type="text" name="titulo" id="titulo" value="<?= htmlspecialchars($anuncio['titulo']) ?>" maxlength="255" required>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea name="descripcion" id="descripcion" rows="5" maxlength="2000" required><?= htmlspecialchars($anuncio['descripcion']) ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="tipo_anuncio">Tipo de anuncio</label>
                    <select name="tipo_anuncio" id="tipo_anuncio" required>
                        <option value="oferta"  <?= $anuncio['tipo_anuncio'] === 'oferta'  ? 'selected' : '' ?>>Oferta</option>
                        <option value="demanda" <?= $anuncio['tipo_anuncio'] === 'demanda' ? 'selected' : '' ?>>Demanda</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="categoria">Categoría</label>
                    <select name="categoria" id="categoria" required>
                        <?php foreach ($categorias as $slug => $cat): ?>
                            <option value="<?= $slug ?>"
                                    data-mult="<?= htmlspecialchars($multiplicadores[$slug] ?? 1) ?>"
                                    <?= $anuncio['categoria'] === $slug ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="duracion_estimada">Duración (horas)</label>
                    <input type="number" name="duracion_estimada" id="duracion_estimada" min="1" max="100" value="<?= (int)$anuncio['duracion_estimada'] ?>" required>
                </div>

                <div class="form-group">
                    <label for="plazas_totales">Plazas totales</label>
                    <input type="number" name="plazas_totales" id="plazas_totales" min="<?= max(1,(int)$anuncio['plazas_ocupadas']) ?>" max="50" value="<?= (int)$anuncio['plazas_totales'] ?>" required>
                    <span class="help-text">Plazas ocupadas actualmente: <?= (int)$anuncio['plazas_ocupadas'] ?></span>
                </div>
            </div>

            <div class="price-preview">
                <div class="pp-left">
                    <div class="pp-icon"><i class="fas fa-coins"></i></div>
                    <div>
                        <div class="pp-title">Precio en créditos</div>
                        <div class="pp-sub" id="ppSub">—</div>
                    </div>
                </div>
                <div class="pp-amount"><span id="ppAmount"><?= (int)$anuncio['precio_creditos'] ?></span> <small>créditos</small></div>
            </div>

            <div class="form-group">
                <label>Imagen del anuncio <span style="color:var(--gris-texto); font-weight:400;">(opcional)</span></label>
                <div class="img-upload-wrap" id="imgWrap">
                    <?php if (!empty($anuncio['imagen'])): ?>
                        <img id="imgPreview" src="/kronet/public/uploads/anuncios/<?= htmlspecialchars($anuncio['imagen']) ?>"
                             alt="" style="width:100%; height:100%; object-fit:cover; border-radius:12px;">
                        <div class="img-upload-placeholder" id="imgPlaceholder" style="display:none;">
                    <?php else: ?>
                        <img id="imgPreview" src="" alt="" style="display:none; width:100%; height:100%; object-fit:cover; border-radius:12px;">
                        <div class="img-upload-placeholder" id="imgPlaceholder">
                    <?php endif; ?>
                            <i class="fas fa-image"></i>
                            <span>Clic para cambiar imagen</span>
                            <small>JPG, PNG o WebP · Máx. 2 MB</small>
                        </div>
                    <input type="file" name="imagen" id="imagen" accept="image/jpeg,image/png,image/webp" style="display:none;">
                </div>
                <span class="help-text">Si no cambias la imagen se mantiene la actual.</span>
            </div>

            <div class="form-actions">
                <a href="/kronet/public/anuncios/mis-anuncios" class="btn btn-ghost">Cancelar</a>
                <button type="submit" class="btn btn-secondary" id="submitBtn">
                    <i class="fas fa-save"></i> Guardar cambios
                </button>
            </div>
        </form>
    </div>

</div>

<script>
const ppAmount = document.getElementById('ppAmount');
const ppSub = document.getElementById('ppSub');
const selCat = document.getElementById('categoria');
const inDur  = document.getElementById('duracion_estimada');

function recalcularPrecio() {
    const opt = selCat.options[selCat.selectedIndex];
    const mult = parseFloat(opt?.dataset?.mult || '1');
    const dur = Math.max(1, parseInt(inDur.value || '1', 10));
    const creditos = Math.max(1, Math.round(dur * mult));
    ppAmount.textContent = creditos;
    const nivel = mult >= 2.0 ? 'Alta especialización'
                : mult >= 1.5 ? 'Especialización media'
                : mult >= 1.2 ? 'Cualificación ligera'
                : 'Sin cualificación específica';
    ppSub.textContent = `${dur}h × ${mult} (${nivel})`;
}
selCat.addEventListener('change', recalcularPrecio);
inDur.addEventListener('input', recalcularPrecio);
recalcularPrecio();

document.getElementById('imgWrap').addEventListener('click', () => document.getElementById('imagen').click());
document.getElementById('imagen').addEventListener('change', function() {
    if (!this.files[0]) return;
    document.getElementById('imgPreview').src = URL.createObjectURL(this.files[0]);
    document.getElementById('imgPreview').style.display = 'block';
    document.getElementById('imgPlaceholder').style.display = 'none';
});

document.getElementById('formEditar').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';

    const formData = new FormData(this);
    const data = await apiPost(window.location.href, formData);
    const msg = document.getElementById('msg');
    msg.className = data.ok ? 'flash flash-ok' : 'flash flash-error';
    msg.innerHTML = (data.ok ? '<i class="fas fa-check-circle"></i> ' : '<i class="fas fa-exclamation-circle"></i> ') + data.msg;
    msg.style.display = 'flex';
    showToast(data.msg, data.ok ? 'ok' : 'error');

    if (data.ok) {
        setTimeout(() => { window.location.href = '/kronet/public/anuncios/mis-anuncios'; }, 1000);
    } else {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Guardar cambios';
    }
});
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
