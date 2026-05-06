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
                <?php if ($anuncio['id_usuario'] == $_SESSION['id_usuario']): ?>
                    <a href="/kronet/public/anuncios/<?= $anuncio['id_anuncio'] ?>/editar">Editar</a>
                <?php endif; ?>
                <?php if ($anuncio['id_usuario'] != $_SESSION['id_usuario']): ?>
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