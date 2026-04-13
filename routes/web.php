<?php
require_once '../config/conexion_db.php';
require_once '../app/controllers/UserController.php';


// strtok elimina los parámetros GET de la URI (todo lo que va después del ?)
// Ejemplo: /anuncios/buscar?busqueda=guitarra → /anuncios/buscar
// Así el router reconoce correctamente la ruta
// Eliminamos los parámetros GET de la URI para que las rutas funcionen correctamente
$uri = strtok($_SERVER['REQUEST_URI'], '?');
$method = $_SERVER['REQUEST_METHOD'];

function requireLogin() {
    if (!isset($_SESSION['user'])) {
        header("Location: /kronet/public/login");
        exit;
    }
}

$userController = new AuthController();

// HOME
if ($uri == '/kronet/public/' || $uri == '/kronet/public') {
    echo "<h1>Kronet</h1>";

    if (isset($_SESSION['user'])) {
        echo "<p>Sesión iniciada</p>";
        echo "<a href='/kronet/public/logout'>Cerrar sesión</a>";
    } else {
        echo "<a href='/kronet/public/login'>Login</a><br>";
        echo "<a href='/kronet/public/register'>Registro</a>";
    }
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

// LOGOUT
if ($uri == '/kronet/public/logout') {
    session_destroy();
    header("Location: /kronet/public/login");
    exit;
}



// Incluimos el controlador de anuncios
require_once '../app/controllers/AnuncioController.php';
$anuncioController = new AnuncioController();

// EDITAR ANUNCIO - Muestra el formulario con los datos actuales
if (preg_match('/^\/kronet\/public\/anuncios\/(\d+)\/editar$/', $uri, $matches) && $method == 'GET') {
    requireLogin();
    $anuncioController->showEditar($matches[1]);
}

// EDITAR ANUNCIO - Procesa el formulario y guarda los cambios
if (preg_match('/^\/kronet\/public\/anuncios\/(\d+)\/editar$/', $uri, $matches) && $method == 'POST') {
    requireLogin();
    $anuncioController->editar($matches[1]);
}

// BUSCAR Y FILTRAR ANUNCIOS
if ($uri == '/kronet/public/anuncios/buscar' && $method == 'GET') {
    requireLogin();
    $anuncioController->showBuscar();
}