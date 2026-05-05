<?php
// Cargamos la conexión a la BD y los controladores que vamos a necesitar
require_once __DIR__ . '/../config/conexion_db.php';
require_once __DIR__ . '/../app/controllers/UserController.php';
require_once __DIR__ . '/../app/controllers/AnuncioController.php';
require_once __DIR__ . '/../app/controllers/PerfilController.php';
require_once __DIR__ . '/../app/controllers/IntercambioController.php';

// Eliminamos parámetros GET de la URI
$uri = strtok($_SERVER['REQUEST_URI'], '?');
$method = $_SERVER['REQUEST_METHOD'];

// Función helper para proteger rutas privadas
function requireLogin() {
    if (!isset($_SESSION['id_usuario'])) {
        header("Location: /kronet/public/login");
        exit;
    }
}

// Instanciamos los controladores
$userController        = new AuthController();
$anuncioController     = new AnuncioController();
$perfilController      = new PerfilController();
$intercambioController = new IntercambioController();

// =====================
// HOME
// =====================
if ($uri == '/kronet/public/' || $uri == '/kronet/public') {
    echo "<h1>Kronet</h1>";

    if (isset($_SESSION['id_usuario'])) {
        echo "<p>Sesión iniciada</p>";
        echo "<a href='/kronet/public/anuncios/crear'>Publicar anuncio</a> | ";
        echo "<a href='/kronet/public/anuncios/mis-anuncios'>Mis anuncios</a> | ";
        echo "<a href='/kronet/public/anuncios/buscar'>Buscar anuncios</a> | ";
        echo "<a href='/kronet/public/perfil'>Mi perfil</a> | ";
        echo "<a href='/kronet/public/logout'>Cerrar sesión</a>";
    } else {
        echo "<a href='/kronet/public/login'>Login</a><br>";
        echo "<a href='/kronet/public/register'>Registro</a>";
    }
}

// =====================
// AUTH
// =====================

// Mostrar login
if ($uri == '/kronet/public/login' && $method == 'GET') {
    $userController->showLogin();
}

// Procesar login
if ($uri == '/kronet/public/login' && $method == 'POST') {
    $userController->login();
}

// Mostrar registro
if ($uri == '/kronet/public/register' && $method == 'GET') {
    $userController->showRegister();
}

// Procesar registro
if ($uri == '/kronet/public/register' && $method == 'POST') {
    $userController->register();
}

// Logout
if ($uri == '/kronet/public/logout') {
    session_start();
    session_destroy();
    header("Location: /kronet/public/login");
    exit;
}

// =====================
// ANUNCIOS
// =====================

if ($uri == '/kronet/public/anuncios/crear' && $method == 'GET') {
    requireLogin();
    $anuncioController->showCrear();
}

if ($uri == '/kronet/public/anuncios/crear' && $method == 'POST') {
    requireLogin();
    $anuncioController->crear();
}

if ($uri == '/kronet/public/anuncios/mis-anuncios' && $method == 'GET') {
    requireLogin();
    $anuncioController->misAnuncios();
}

if ($uri == '/kronet/public/anuncios/buscar' && $method == 'GET') {
    requireLogin();
    $anuncioController->showBuscar();
}

if (preg_match('/^\/kronet\/public\/anuncios\/(\d+)\/editar$/', $uri, $matches) && $method == 'GET') {
    requireLogin();
    $anuncioController->showEditar($matches[1]);
}

if (preg_match('/^\/kronet\/public\/anuncios\/(\d+)\/editar$/', $uri, $matches) && $method == 'POST') {
    requireLogin();
    $anuncioController->editar($matches[1]);
}

if (preg_match('/^\/kronet\/public\/anuncios\/(\d+)\/eliminar$/', $uri, $matches) && $method == 'POST') {
    requireLogin();
    $anuncioController->eliminar($matches[1]);
}

// =====================
// PERFIL
// =====================

if ($uri == '/kronet/public/perfil' && $method == 'GET') {
    requireLogin();
    $perfilController->verPerfil();
}

// =====================
// INTERCAMBIOS
// =====================

// Ofertas recibidas
if ($uri == '/kronet/public/intercambios/ofertas-recibidas' && $method == 'GET') {
    requireLogin();
    $intercambioController->ofertasRecibidas();
}

// Aceptar oferta
if (preg_match('/^\/kronet\/public\/intercambios\/(\d+)\/aceptar$/', $uri, $matches) && $method == 'POST') {
    requireLogin();
    $intercambioController->aceptar($matches[1]);
}

// Rechazar oferta
if (preg_match('/^\/kronet\/public\/intercambios\/(\d+)\/rechazar$/', $uri, $matches) && $method == 'POST') {
    requireLogin();
    $intercambioController->rechazar($matches[1]);
}