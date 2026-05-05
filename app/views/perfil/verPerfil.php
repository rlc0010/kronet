<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi perfil - Kronet</title>
</head>
<body>

    <h1>Mi perfil</h1>
    <p><a href="/kronet/public/">Volver al inicio</a></p>

    <?php if (isset($error)): ?>
        <p style="color:red"><?= htmlspecialchars($error) ?></p>
    <?php else: ?>

        <h2>Datos personales</h2>
        <?php if (empty($usuario['nombre']) || empty($usuario['email'])): ?>
            <p style="color:orange">Algunos datos de tu perfil están incompletos.</p>
        <?php endif; ?>

        <p>Nombre: <?= htmlspecialchars($usuario['nombre'] ?? 'No disponible') ?></p>
        <p>Email: <?= htmlspecialchars($usuario['email'] ?? 'No disponible') ?></p>
        <p>Descripción: <?= htmlspecialchars($usuario['descripcion'] ?? 'No disponible') ?></p>
        <p>Créditos de tiempo: <?= htmlspecialchars($usuario['saldo_monedas'] ?? 0) ?></p>

        <h2>Historial de intercambios</h2>
        <?php if (empty($historial)): ?>
            <p>Todavía no has participado en ningún intercambio.</p>
        <?php else: ?>
            <?php foreach ($historial as $intercambio): ?>
                <div>
                    <p>Anuncio: <?= htmlspecialchars($intercambio['titulo_anuncio']) ?></p>
                    <p>Estado: <?= htmlspecialchars($intercambio['estado']) ?></p>
                    <p>Fecha: <?= htmlspecialchars($intercambio['fecha_inicio']) ?></p>
                </div>
                <hr>
            <?php endforeach; ?>
        <?php endif; ?>

    <?php endif; ?>

</body>
</html>