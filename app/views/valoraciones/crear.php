<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dejar valoración - Kronet</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 40px auto; padding: 0 20px; }
        h1 { color: #333; }
        .form-group { margin-bottom: 16px; }
        label { display: block; font-weight: bold; margin-bottom: 6px; }
        textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; resize: vertical; }
        .stars { display: flex; flex-direction: row-reverse; justify-content: flex-end; gap: 4px; margin-bottom: 8px; }
        .stars input { display: none; }
        .stars label { font-size: 36px; color: #ccc; cursor: pointer; font-weight: normal; }
        .stars input:checked ~ label,
        .stars label:hover,
        .stars label:hover ~ label { color: #f5a623; }
        button { background: #4a90e2; color: white; padding: 10px 24px; border: none; border-radius: 6px; font-size: 15px; cursor: pointer; }
        button:hover { background: #357abd; }
        #msg { margin-top: 14px; font-weight: bold; }
        .back { color: #4a90e2; text-decoration: none; }
    </style>
</head>
<body>

    <h1>⭐ Dejar valoración</h1>
    <p><a class="back" href="/kronet/public/">← Volver al inicio</a></p>

    <div id="msg"></div>

    <div class="form-group">
        <label>Puntuación</label>
        <div class="stars">
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
        <textarea id="comentario" rows="4" maxlength="500" placeholder="Describe tu experiencia con este usuario..."></textarea>
    </div>

    <button onclick="enviarValoracion()">Enviar valoración</button>

    <script>
        const idDestino = <?= (int)$idDestino ?>;

        function enviarValoracion() {
            const puntuacionInput = document.querySelector('input[name="puntuacion"]:checked');
            const comentario = document.getElementById('comentario').value;
            const msgDiv = document.getElementById('msg');

            if (!puntuacionInput) {
                msgDiv.style.color = 'red';
                msgDiv.textContent = 'Selecciona una puntuación.';
                return;
            }

            const formData = new FormData();
            formData.append('id_destino', idDestino);
            formData.append('puntuacion', puntuacionInput.value);
            formData.append('comentario', comentario);

            fetch('/kronet/public/valoraciones/crear', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                msgDiv.style.color = data.ok ? 'green' : 'red';
                msgDiv.textContent = data.msg;
                if (data.ok) {
                    setTimeout(() => {
                        window.location.href = '/kronet/public/valoraciones/mis-valoraciones';
                    }, 1500);
                }
            })
            .catch(() => {
                msgDiv.style.color = 'red';
                msgDiv.textContent = 'Error de conexión. Inténtalo de nuevo.';
            });
        }
    </script>

</body>
</html>
