<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ofertas recibidas - Kronet</title>
</head>
<body>

    <h1>Ofertas recibidas</h1>
    <p><a href="/kronet/public/">Volver al inicio</a></p>

    <?php if (empty($intercambios)): ?>
        <p>No tienes ofertas pendientes.</p>
    <?php else: ?>
        <?php foreach ($intercambios as $intercambio): ?>
            <div id="intercambio-<?= $intercambio['id_intercambio'] ?>">
                <h2><?= htmlspecialchars($intercambio['titulo_anuncio']) ?></h2>
                <p>Ofertante: <?= htmlspecialchars($intercambio['nombre_ofertante']) ?></p>
                <p>Monedas: <?= htmlspecialchars($intercambio['monedas_intercambio']) ?></p>
                <p>Fecha: <?= htmlspecialchars($intercambio['fecha_inicio']) ?></p>
                <button onclick="gestionarOferta(<?= $intercambio['id_intercambio'] ?>, 'aceptar', this)">Aceptar</button>
                <button onclick="gestionarOferta(<?= $intercambio['id_intercambio'] ?>, 'rechazar', this)">Rechazar</button>
            </div>
            <hr>
        <?php endforeach; ?>
    <?php endif; ?>

    <script>
        function gestionarOferta(id, accion, btn) {
            fetch('/kronet/public/intercambios/' + id + '/' + accion, {
                method: 'POST'
            })
            .then(res => res.json())
            .then(data => {
                if (data.ok) {
                    const div = document.getElementById('intercambio-' + id);
                    div.innerHTML = '<p>' + data.msg + '</p>';
                } else {
                    alert('Error: ' + data.msg);
                }
            });
        }
    </script>

</body>
</html>