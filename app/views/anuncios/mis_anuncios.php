<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis anuncios - Kronet</title>
</head>
<body>

    <h1>Mis anuncios</h1>

    <p><a href="/kronet/public/anuncios/crear">+ Publicar nuevo anuncio</a></p>
    <p><a href="/kronet/public/">Volver al inicio</a></p>

    <!-- Si el usuario no tiene anuncios mostramos un mensaje -->
    <?php if (empty($anuncios)): ?>
        <p>Todavía no has publicado ningún anuncio.</p>
    <?php else: ?>
        <!-- Recorremos todos los anuncios del usuario y los mostramos -->
        <?php foreach ($anuncios as $anuncio): ?>
            <!-- Ponemos el ID en el div para poder eliminarlo del DOM sin recargar -->
            <div id="anuncio-<?= $anuncio['id_anuncio'] ?>">
                <h2><?= htmlspecialchars($anuncio['titulo']) ?></h2>
                <p><?= htmlspecialchars($anuncio['descripcion']) ?></p>
                <p>Tipo: <?= htmlspecialchars($anuncio['tipo_anuncio']) ?></p>
                <p>Categoría: <?= htmlspecialchars($anuncio['categoria']) ?></p>
                <p>Duración: <?= htmlspecialchars($anuncio['duracion_estimada']) ?> horas</p>
                <p>Estado: <?= htmlspecialchars($anuncio['estado']) ?></p>

                <!-- Enlace para editar el anuncio -->
                <a href="/kronet/public/anuncios/<?= $anuncio['id_anuncio'] ?>/editar">Editar</a> |
                <!-- Botón de eliminar: llama a la función JS de abajo -->
                <button onclick="eliminar(<?= $anuncio['id_anuncio'] ?>)">Eliminar</button>
            </div>
            <hr>
        <?php endforeach; ?>
    <?php endif; ?>

    <script>
        function eliminar(id) {
            // Pedimos confirmación antes de eliminar
            if (!confirm('¿Seguro que quieres eliminar este anuncio?')) return;

            // Enviamos la petición de eliminación al servidor
            fetch('/kronet/public/anuncios/' + id + '/eliminar', {
                method: 'POST'
            })
            .then(res => res.json())
            .then(data => {
                if (data.ok) {
                    // Si se eliminó correctamente quitamos el div del DOM
                    // sin necesidad de recargar la página
                    document.getElementById('anuncio-' + id).remove();
                } else {
                    alert('Error: ' + data.msg);
                }
            });
        }
    </script>

</body>
</html>
