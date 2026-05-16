<?php
$pageTitle = 'Publicar servicio';
$navActive = 'publicar';
require __DIR__ . '/../partials/head.php';
require __DIR__ . '/../partials/navbar.php';
?>

<section class="hero-section hero-compact">
    <h1>Publicar servicio</h1>
    <p>Comparte tus habilidades con la comunidad y gana créditos de tiempo</p>
</section>

<div class="page-wrap">

    <a class="back-link" href="/kronet/public/anuncios/mis-anuncios"><i class="fas fa-arrow-left"></i> Mis anuncios</a>

    <div id="msg"></div>

    <div class="form-container">
        <form id="formCrear" novalidate>

            <div class="form-group">
                <label for="titulo">Título del servicio <span style="color:#dc2626;">*</span></label>
                <input type="text" name="titulo" id="titulo" placeholder="Ej: Clases de guitarra para principiantes" maxlength="255" required>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción <span style="color:#dc2626;">*</span></label>
                <textarea name="descripcion" id="descripcion" rows="5" maxlength="2000" placeholder="Describe tu servicio, qué incluye, qué experiencia tienes..." required></textarea>
                <span class="help-text">Mínimo 20 caracteres recomendado. Sé específico para que otros confíen en ti.</span>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="tipo_anuncio">Tipo de anuncio <span style="color:#dc2626;">*</span></label>
                    <select name="tipo_anuncio" id="tipo_anuncio" required>
                        <option value="oferta">Oferta (ofrezco un servicio)</option>
                        <option value="demanda">Demanda (necesito ayuda)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="categoria">Categoría <span style="color:#dc2626;">*</span></label>
                    <select name="categoria" id="categoria" required>
                        <option value="">— Selecciona una categoría —</option>
                        <?php foreach ($categorias as $slug => $cat): ?>
                            <option value="<?= $slug ?>"
                                    data-mult="<?= htmlspecialchars($multiplicadores[$slug] ?? 1) ?>">
                                <?= htmlspecialchars($cat['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="duracion_estimada">Duración (horas) <span style="color:#dc2626;">*</span></label>
                    <input type="number" name="duracion_estimada" id="duracion_estimada" min="1" max="100" value="1" required>
                </div>

                <div class="form-group">
                    <label for="plazas_totales">Plazas disponibles <span style="color:#dc2626;">*</span></label>
                    <input type="number" name="plazas_totales" id="plazas_totales" min="1" max="50" value="1" required>
                    <span class="help-text">Cuántas personas pueden apuntarse a este anuncio.</span>
                </div>
            </div>

            <div class="price-preview" id="pricePreview">
                <div class="pp-left">
                    <div class="pp-icon"><i class="fas fa-coins"></i></div>
                    <div>
                        <div class="pp-title">Precio en créditos</div>
                        <div class="pp-sub" id="ppSub">Selecciona categoría y duración</div>
                    </div>
                </div>
                <div class="pp-amount">
                    <span id="ppAmount">—</span> <small>créditos</small>
                </div>
            </div>

            <div class="form-actions">
                <a href="/kronet/public/" class="btn btn-ghost">Cancelar</a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-plus"></i> Publicar anuncio
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
    const mult = parseFloat(opt?.dataset?.mult || '0');
    const dur = Math.max(1, parseInt(inDur.value || '1', 10));
    if (!opt || !opt.value) {
        ppAmount.textContent = '—';
        ppSub.textContent = 'Selecciona categoría y duración';
        return;
    }
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

document.getElementById('formCrear').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('submitBtn');
    const msg = document.getElementById('msg');

    // Validaciones cliente
    const titulo = document.getElementById('titulo').value.trim();
    const desc   = document.getElementById('descripcion').value.trim();
    const cat    = selCat.value;
    if (!titulo || !desc) { showToast('Faltan campos obligatorios', 'error'); return; }
    if (!cat) { showToast('Elige una categoría', 'error'); return; }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Publicando...';

    try {
        const formData = new FormData(this);
        const data = await apiPost('/kronet/public/anuncios/crear', formData);

        msg.className = data.ok ? 'flash flash-ok' : 'flash flash-error';
        msg.innerHTML = (data.ok ? '<i class="fas fa-check-circle"></i> ' : '<i class="fas fa-exclamation-circle"></i> ') + data.msg;
        msg.style.display = 'flex';

        if (data.ok) {
            showToast('¡Anuncio publicado!', 'ok');
            window.scrollTo({ top: 0, behavior: 'smooth' });
            setTimeout(() => { window.location.href = '/kronet/public/anuncios/mis-anuncios'; }, 900);
        } else {
            showToast(data.msg, 'error');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-plus"></i> Publicar anuncio';
        }
    } catch (e) {
        showToast('Error de conexión', 'error');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-plus"></i> Publicar anuncio';
    }
});
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
