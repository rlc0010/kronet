<?php
/**
 * Cabecera HTML común a todas las vistas.
 * Variables esperadas:
 *   $pageTitle  string (opcional)
 */
$titulo = isset($pageTitle) ? $pageTitle . ' — Kronet' : 'Kronet — Intercambia tiempo, comparte habilidades';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo) ?></title>
    <link rel="icon" type="image/png" href="/kronet/public/assets/images/logo.png">
    <link rel="stylesheet" href="/kronet/public/assets/css/kronet.css">
</head>
<body>
