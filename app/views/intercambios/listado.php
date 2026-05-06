<h2>Mis intercambios</h2>

<?php foreach ($intercambios as $intercambio): ?>
   <div>
       <p>ID: <?= $intercambio['id_intercambio'] ?></p>
       <p>Monedas: <?= $intercambio['monedas_intercambio'] ?></p>
       <p>Estado: <?= $intercambio['estado'] ?></p>
       <hr>
   </div>
<?php endforeach; ?>
