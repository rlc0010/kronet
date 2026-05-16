<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

/*
 * Conexión PDO con MySQL.
 *
 * Si por alguna razón ya existe una conexión $pdo válida en el ámbito
 * global (entornos de test, scripts CLI...), la reutilizamos en lugar
 * de crear una nueva. Esto evita reconectar 10 veces por petición y
 * permite inyectar una BD de prueba.
 */

if (!isset($GLOBALS['pdo']) || !($GLOBALS['pdo'] instanceof PDO)) {
    $GLOBALS['pdo'] = new PDO("mysql:host=localhost;dbname=kronet_db;charset=utf8", "root", "");
    $GLOBALS['pdo']->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

// Hacer disponible la variable también en el ámbito del script que nos incluye.
$pdo = $GLOBALS['pdo'];