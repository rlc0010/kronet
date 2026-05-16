<?php
$pageTitle = 'Dejar valoración';
$navActive = '';
require __DIR__ . '/../partials/head.php';
require __DIR__ . '/../partials/navbar.php';
?>

<section class="hero-section hero-compact">
    <h1>Dejar valoración</h1>
    <p>Comparte tu experiencia con
        <strong><?= htmlspecialchars($destinatario['nombre'] ?? 'este usuario') ?></strong>
    </p>
</section>

<div class="page-wrap">

    <a class="back-link" href="javascript:history.back()"><i class="fas fa-arrow-left"></i> Volver</a>

    <div id="msg"></div>

    <div class="form-container">

        <div class="form-group">
            <label>Puntuación</label>
            <div class="stars-input">
                <input type="radio" name="puntuacion" id="star5" value="5"><label for="star5">★</label>
                <input type="radio" name="puntuacion" id="star4" value="4"><label for="star4">★</label>
                <input type="radio" name="puntuacion" id="star3" value="3"><label for="star3">★</label>
                <input type="radio" name="puntuacion" id="star2" value="2"><label for="star2">★</label>
                <input type="radio" name="puntuacion" id="star1" value="1"><label for="star1">★</label>
            </div>
            <span class="help-text">Selecciona entre 1 y 5 estrellas.</span>
        </div>

        <div class="form-group">
            <label for="comentario">Comentario (opcional)</label>
            <textarea id="comentario" rows="4" maxlength="500" placeholder="Describe tu experiencia con este usuario..."></textarea>
        </div>

        <div class="form-actions">
            <a href="javascript:history.back()" class="btn btn-ghost">Cancelar</a>
            <button class="btn btn-primary" id="btnEnviar" onclick="enviar()">
                <i class="fas fa-paper-plane"></i> Enviar valoración
            </button>
        </div>

    </div>

</div>

<script>
const ID_DESTINO = <?= (int)$idDestino ?>;

async function enviar() {
    const punt = document.querySelector('input[name="puntuacion"]:checked');
    const msg  = document.getElementById('msg');
    const btn  = document.getElementById('btnEnviar');

    if (!punt) {
        msg.className = 'flash flash-error';
        msg.innerHTML = '<i class="fas fa-exclamation-circle"></i> Selecciona una puntuación.';
        msg.style.display = 'flex';
        showToast('Selecciona una puntuación', 'error');
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';

    const data = await apiPost('/kronet/public/valoraciones/crear', {
        id_destino:  ID_DESTINO,
        puntuacion:  punt.value,
        comentario:  document.getElementById('comentario').value
    });

    showToast(data.msg, data.ok ? 'ok' : 'error');
    msg.className = data.ok ? 'flash flash-ok' : 'flash flash-error';
    msg.innerHTML = (data.ok ? '<i class="fas fa-check-circle"></i> ' : '<i class="fas fa-exclamation-circle"></i> ') + data.msg;
    msg.style.display = 'flex';

    if (data.ok) {
        setTimeout(() => window.location.href = '/kronet/public/perfil/' + ID_DESTINO, 1100);
    } else {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane"></i> Enviar valoración';
    }
}
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
