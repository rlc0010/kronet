<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/kronet/public/assets/images/logo.png">
    <title>Crear anuncio - Kronet</title>
    <link rel="stylesheet" href="/kronet/public/assets/css/kronet.css">
</head>
<body>

<div class="page-wrap">

    <a class="back-link" href="/kronet/public/anuncios/mis-anuncios"><i class="fas fa-arrow-left"></i> Mis anuncios</a>

    <div class="page-header">
        <h1>Publicar anuncio</h1>
        <p>Comparte lo que ofreces o lo que necesitas</p>
    </div>

    <div id="msg"></div>

    <div class="form-container">
        <form id="formCrear">

            <div class="form-group">
                <label for="titulo">Título</label>
                <input type="text" name="titulo" id="titulo" placeholder="Ej: Clases de guitarra, Ayuda con mudanza..." required>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea name="descripcion" id="descripcion" placeholder="Describe con detalle lo que ofreces o necesitas..." required></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="tipo_anuncio">Tipo de anuncio</label>
                    <select name="tipo_anuncio" id="tipo_anuncio">
                        <option value="oferta">Oferta (ofrezco algo)</option>
                        <option value="demanda">Demanda (necesito algo)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="categoria">Categoría</label>
                    <input type="text" name="categoria" id="categoria" placeholder="Ej: Tecnología, Hogar..." required>
                </div>
            </div>

            <div class="form-group">
                <label for="duracion_estimada">Duración estimada (horas)</label>
                <input type="number" name="duracion_estimada" id="duracion_estimada" min="1" placeholder="Ej: 2" required>
            </div>

            <div class="form-actions">
                <a href="/kronet/public/" class="btn btn-ghost">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Publicar anuncio
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    document.getElementById('formCrear').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const msg = document.getElementById('msg');
        fetch('/kronet/public/anuncios/crear', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                msg.className = data.ok ? 'flash flash-ok' : 'flash flash-error';
                msg.innerHTML = (data.ok ? '<i class="fas fa-check-circle"></i> ' : '<i class="fas fa-exclamation-circle"></i> ') + data.msg;
                msg.style.display = 'flex';
                if (data.ok) this.reset();
            });
    });
</script>

</body>
</html>