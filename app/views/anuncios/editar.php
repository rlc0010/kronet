<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/kronet/public/assets/images/logo.png">
    <title>Editar anuncio - Kronet</title>
    <link rel="stylesheet" href="/kronet/public/assets/css/kronet.css">
</head>
<body>

<div class="page-wrap">

    <a class="back-link" href="/kronet/public/anuncios/mis-anuncios"><i class="fas fa-arrow-left"></i> Mis anuncios</a>

    <div class="page-header">
        <h1>Editar anuncio</h1>
        <p>Modifica los datos de tu publicación</p>
    </div>

    <div class="form-container">
        <form id="formEditar">

            <div class="form-group">
                <label for="titulo">Título</label>
                <input type="text" name="titulo" id="titulo" value="<?= htmlspecialchars($anuncio['titulo']) ?>" required>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea name="descripcion" id="descripcion" required><?= htmlspecialchars($anuncio['descripcion']) ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="tipo_anuncio">Tipo de anuncio</label>
                    <select name="tipo_anuncio" id="tipo_anuncio">
                        <option value="oferta"  <?= $anuncio['tipo_anuncio'] == 'oferta'  ? 'selected' : '' ?>>Oferta</option>
                        <option value="demanda" <?= $anuncio['tipo_anuncio'] == 'demanda' ? 'selected' : '' ?>>Demanda</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="categoria">Categoría</label>
                    <input type="text" name="categoria" id="categoria" value="<?= htmlspecialchars($anuncio['categoria']) ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="duracion_estimada">Duración estimada (horas)</label>
                <input type="number" name="duracion_estimada" id="duracion_estimada" value="<?= htmlspecialchars($anuncio['duracion_estimada']) ?>" required>
            </div>

            <div class="form-actions">
                <a href="/kronet/public/anuncios/mis-anuncios" class="btn btn-ghost">Cancelar</a>
                <button type="submit" class="btn btn-secondary">
                    <i class="fas fa-save"></i> Guardar cambios
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    document.getElementById('formEditar').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch(window.location.href, { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if (data.ok) { alert('Anuncio actualizado correctamente'); }
                else { alert('Error: ' + data.msg); }
            });
    });
</script>

</body>
</html>