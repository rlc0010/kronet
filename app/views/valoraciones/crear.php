<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dejar valoración - Kronet</title>
    <link rel="stylesheet" href="/kronet/public/assets/css/kronet.css">
</head>
<body>

<div class="page-wrap">

    <a class="back-link" href="/kronet/public/"><i class="fas fa-arrow-left"></i> Volver al inicio</a>

    <div class="page-header">
        <h1><i class="fas fa-star"></i> Dejar valoración</h1>
        <p>Comparte tu experiencia con este usuario</p>
    </div>

    <div id="msg"></div>

    <div class="form-container">

        <div class="form-group">
            <label>Puntuación</label>
            <div class="stars-input">
                <input type="radio" name="puntuacion" id="star5" value="5">
                <label for="star5">★</label>
                <input type="radio" name="puntuacion" id="star4" value="4">
                <label for="star4">★</label>
                <input type="radio" name="puntuacion" id="star3" value="3">
                <label for="star3">★</label>
                <input type="radio" name="puntuacion" id="star2" value="2">
                <label for="star2">★</label>
                <input type="radio" name="puntuacion" id="star1" value="1">
                <label for="star1">★</label>
            </div>
        </div>

        <div class="form-group">
            <label for="comentario">Comentario (opcional)</label>
            <textarea class="kro-input" id="comentario" rows="4" maxlength="500" placeholder="Describe tu experiencia con este usuario..."></textarea>
        </div>

        <div class="form-actions">
            <a href="/kronet/public/" class="btn btn-ghost">Cancelar</a>
            <button class="btn btn-primary" onclick="enviarValoracion()">
                <i class="fas fa-paper-plane"></i> Enviar valoración
            </button>
        </div>

    </div>

</div>

<script>
    const idDestino = <?= (int)$idDestino ?>;

    function enviarValoracion() {
        const puntuacionInput = document.querySelector('input[name="puntuacion"]:checked');
        const comentario = document.getElementById('comentario').value;
        const msgDiv = document.getElementById('msg');

        if (!puntuacionInput) {
            msgDiv.className = 'flash flash-error';
            msgDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Selecciona una puntuación.';
            return;
        }

        const formData = new FormData();
        formData.append('id_destino', idDestino);
        formData.append('puntuacion', puntuacionInput.value);
        formData.append('comentario', comentario);

        fetch('/kronet/public/valoraciones/crear', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                msgDiv.className = data.ok ? 'flash flash-ok' : 'flash flash-error';
                msgDiv.innerHTML = (data.ok ? '<i class="fas fa-check-circle"></i> ' : '<i class="fas fa-exclamation-circle"></i> ') + data.msg;
                if (data.ok) {
                    setTimeout(() => {
                        window.location.href = '/kronet/public/valoraciones/mis-valoraciones';
                    }, 1500);
                }
            })
            .catch(() => {
                msgDiv.className = 'flash flash-error';
                msgDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Error de conexión. Inténtalo de nuevo.';
            });
    }
</script>

</body>
</html>