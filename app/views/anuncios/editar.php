<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar anuncio - Kronet</title>
</head>
<body>

    <h1>Editar anuncio</h1>

    <!-- El formulario envía los datos por POST a la misma URL -->
    <form id="formEditar">

        <label>Título</label>
        <!-- Rellenamos los campos con los datos actuales del anuncio -->
        <input type="text" name="titulo" value="<?= htmlspecialchars($anuncio['titulo']) ?>" required>

        <label>Descripción</label>
        <textarea name="descripcion" required><?= htmlspecialchars($anuncio['descripcion']) ?></textarea>

        <label>Tipo de anuncio</label>
        <select name="tipo_anuncio">
            <option value="oferta" <?= $anuncio['tipo_anuncio'] == 'oferta' ? 'selected' : '' ?>>Oferta</option>
            <option value="demanda" <?= $anuncio['tipo_anuncio'] == 'demanda' ? 'selected' : '' ?>>Demanda</option>
        </select>

        <label>Categoría</label>
        <input type="text" name="categoria" value="<?= htmlspecialchars($anuncio['categoria']) ?>" required>

        <label>Duración estimada (horas)</label>
        <input type="number" name="duracion_estimada" value="<?= htmlspecialchars($anuncio['duracion_estimada']) ?>" required>

        <button type="submit">Guardar cambios</button>
    </form>

    <script>
        // Enviamos el formulario por fetch para no recargar la página
        document.getElementById('formEditar').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.ok) {
                    alert('Anuncio actualizado correctamente');
                } else {
                    alert('Error: ' + data.msg);
                }
            });
        });
    </script>

</body>
</html>