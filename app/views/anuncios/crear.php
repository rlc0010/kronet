<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear anuncio - Kronet</title>
</head>
<body>

    <h1>Publicar anuncio</h1>

    <!-- Formulario de creación de anuncio -->
    <!-- El envío se gestiona por fetch en el JS de abajo para no recargar la página -->
    <form id="formCrear">

        <label>Título</label><br>
        <input type="text" name="titulo" required><br><br>

        <label>Descripción</label><br>
        <textarea name="descripcion" required></textarea><br><br>

        <label>Tipo de anuncio</label><br>
        <select name="tipo_anuncio">
            <!-- Oferta: el usuario ofrece algo -->
            <option value="oferta">Oferta (ofrezco algo)</option>
            <!-- Demanda: el usuario necesita algo -->
            <option value="demanda">Demanda (necesito algo)</option>
        </select><br><br>

        <label>Categoría</label><br>
        <input type="text" name="categoria" required><br><br>

        <label>Duración estimada (horas)</label><br>
        <input type="number" name="duracion_estimada" min="1" required><br><br>

        <button type="submit">Publicar</button>
    </form>

    <!-- Aquí mostramos mensajes de éxito o error tras el envío -->
    <p id="msg" style="color:red;"></p>

    <p><a href="/kronet/public/anuncios/mis-anuncios">Ver mis anuncios</a></p>
    <p><a href="/kronet/public/">Volver al inicio</a></p>

    <script>
        document.getElementById('formCrear').addEventListener('submit', function(e) {
            e.preventDefault();

            // Recogemos todos los campos del formulario con FormData
            const formData = new FormData(this);
            const msg = document.getElementById('msg');

            fetch('/kronet/public/anuncios/crear', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.ok) {
                    // Si todo fue bien mostramos el mensaje en verde y limpiamos el formulario
                    msg.style.color = 'green';
                    msg.textContent = data.msg;
                    this.reset();
                } else {
                    // Si hubo error mostramos el mensaje en rojo
                    msg.style.color = 'red';
                    msg.textContent = data.msg;
                }
            });
        });
    </script>

</body>
</html>
