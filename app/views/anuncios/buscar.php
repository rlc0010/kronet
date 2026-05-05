<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar anuncios - Kronet</title>
</head>

<body>

    <h1>Buscar anuncios</h1>

    <!-- Formulario de búsqueda y filtros -->
    <!-- Usamos GET para que los filtros aparezcan en la URL -->
    <form method="GET" action="/kronet/public/anuncios/buscar">

        <input type="text" name="busqueda" placeholder="Buscar anuncios..."
            value="<?= htmlspecialchars($_GET['busqueda'] ?? '') ?>">

        <select name="tipo_anuncio">
            <option value="">Todos los tipos</option>
            <option value="oferta" <?= ($_GET['tipo_anuncio'] ?? '') == 'oferta' ? 'selected' : '' ?>>Oferta</option>
            <option value="demanda" <?= ($_GET['tipo_anuncio'] ?? '') == 'demanda' ? 'selected' : '' ?>>Demanda</option>
        </select>

        <input type="text" name="categoria" placeholder="Categoría..."
            value="<?= htmlspecialchars($_GET['categoria'] ?? '') ?>">

        <button type="submit">Buscar</button>
        <a href="/kronet/public/anuncios/buscar">Limpiar filtros</a>

    </form>

    <!-- Mostramos el número de resultados -->
    <p><?= count($anuncios) ?> anuncios encontrados</p>

    <!-- Listamos los anuncios encontrados -->
    <?php if (empty($anuncios)): ?>
        <p>No se han encontrado anuncios.</p>
    <?php else: ?>
        <?php foreach ($anuncios as $anuncio): ?>
            <div>
                <h2><?= htmlspecialchars($anuncio['titulo']) ?></h2>
                <p><?= htmlspecialchars($anuncio['descripcion']) ?></p>
                <p>Tipo: <?= htmlspecialchars($anuncio['tipo_anuncio']) ?></p>
                <p>Categoría: <?= htmlspecialchars($anuncio['categoria']) ?></p>
                <p>Duración: <?= htmlspecialchars($anuncio['duracion_estimada']) ?> horas</p>
                <!-- Enlace para editar, solo visible si el anuncio es tuyo -->
                <?php if ($anuncio['id_usuario'] == $_SESSION['user']): ?>
                    <a href="/kronet/public/anuncios/<?= $anuncio['id_anuncio'] ?>/editar">Editar</a>
                <?php endif; ?>
                <?php if ($anuncio['id_usuario'] != $_SESSION['user']): ?>
                    <form method="POST" action="/kronet/public/mensaje/enviar">

                        <input
                            type="hidden"
                            name="id_receptor"
                            value="<?= htmlspecialchars($anuncio['id_usuario']) ?>">

                        <textarea
                            name="mensaje"
                            placeholder="Escribe un mensaje"
                            required></textarea>

                        <button type="submit">Enviar mensaje</button>

                    </form>
                <?php endif; ?>
            </div>
            <hr>
        <?php endforeach; ?>
    <?php endif; ?>

</body>

</html>