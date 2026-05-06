<h1>Bandeja de mensajes</h1>

<?php if (empty($mensajes)): ?>
    <p>No tienes mensajes</p>
<?php else: ?>
    <?php foreach ($mensajes as $m): ?>
        <div>
            <strong><?= htmlspecialchars($m['nombre']) ?></strong>:
            <?= htmlspecialchars($m['contenido']) ?>
            <br>
            <small><?= $m['fecha_envio'] ?></small>
        </div>
        <hr>
    <?php endforeach; ?>
<?php endif; ?>