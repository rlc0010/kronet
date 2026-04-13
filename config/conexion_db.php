<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$pdo = new PDO("mysql:host=localhost;dbname=kronet_db;charset=utf8", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>