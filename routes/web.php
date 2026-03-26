<?php
require_once '../config/conexion_db.php';
require_once '../app/controllers/UserController.php';

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$userController = new AuthController();

// HOME
if ($uri == '/kronet/public/' || $uri == '/kronet/public') {
    echo "<h1>Kronet</h1>";
    echo "<a href='/kronet/public/login'>Login</a><br>";
    echo "<a href='/kronet/public/register'>Registro</a>";
}

// LOGIN
if ($uri == '/kronet/public/login' && $method == 'GET') {
    $userController->showLogin();
}

if ($uri == '/kronet/public/login' && $method == 'POST') {
    $userController->login();
}

// REGISTER
if ($uri == '/kronet/public/register' && $method == 'GET') {
    $userController->showRegister();
}

if ($uri == '/kronet/public/register' && $method == 'POST') {
    $userController->register();
}