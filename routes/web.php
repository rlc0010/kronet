<?php
// Cargamos la conexión a la BD y los controladores
require_once __DIR__ . '/../config/conexion_db.php';
require_once __DIR__ . '/../app/controllers/UserController.php';
require_once __DIR__ . '/../app/controllers/AnuncioController.php';
require_once __DIR__ . '/../app/controllers/MensajeController.php';
require_once __DIR__ . '/../app/controllers/PerfilController.php';
require_once __DIR__ . '/../app/controllers/IntercambioController.php';
require_once __DIR__ . '/../app/controllers/ValoracionController.php';

// Eliminamos parámetros GET de la URI
$uri    = strtok($_SERVER['REQUEST_URI'], '?');
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
$mensajeController     = new MensajeController();
$perfilController      = new PerfilController();
$intercambioController = new IntercambioController();
$valoracionController  = new ValoracionController();

// =====================
// HOME
// =====================
if ($uri == '/kronet/public/' || $uri == '/kronet/public') {
    // Si no está logueado, redirigir al login directamente
    if (!isset($_SESSION['id_usuario'])) {
        header("Location: /kronet/public/login");
        exit;
    }
    // Si está logueado, mostrar la home con diseño completo
    require __DIR__ . '/../app/views/home.php';
    exit;
}

// =====================
// AUTH
// =====================

if ($uri == '/kronet/public/login' && $method == 'GET') {
    $userController->showLogin();
}

if ($uri == '/kronet/public/login' && $method == 'POST') {
    $userController->login();
}

if ($uri == '/kronet/public/register' && $method == 'GET') {
    $userController->showRegister();
}

if ($uri == '/kronet/public/register' && $method == 'POST') {
    $userController->register();
}

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
// MENSAJES
// =====================

if ($uri == '/kronet/public/mensaje/enviar' && $method == 'POST') {
    requireLogin();
    $mensajeController->enviar();
}

if ($uri == '/kronet/public/mensajes' && $method == 'GET') {
    requireLogin();
    $mensajeController->bandeja();
}

// =====================
// PERFIL
// =====================

if ($uri == '/kronet/public/perfil' && $method == 'GET') {
    requireLogin();
    $perfilController->verPerfil();
}

// Perfil ajeno: /kronet/public/perfil/{id}
if (preg_match('/^\/kronet\/public\/perfil\/(\d+)$/', $uri, $matches) && $method == 'GET') {
    requireLogin();
    $perfilController->verPerfilAjeno($matches[1]);
}

// =====================
// INTERCAMBIOS
// =====================

if ($uri == '/kronet/public/intercambios/solicitar' && $method == 'POST') {
    requireLogin();
    $intercambioController->solicitar();
}

if ($uri == '/kronet/public/intercambios/mis-intercambios' && $method == 'GET') {
    requireLogin();
    $intercambios = \Intercambio::findByUsuario($_SESSION['id_usuario']);
    require __DIR__ . '/../app/views/intercambios/listado.php';
}

if ($uri == '/kronet/public/intercambios/ofertas-recibidas' && $method == 'GET') {
    requireLogin();
    $intercambioController->ofertasRecibidas();
}

if (preg_match('/^\/kronet\/public\/intercambios\/(\d+)\/aceptar$/', $uri, $matches) && $method == 'POST') {
    requireLogin();
    $intercambioController->aceptar($matches[1]);
}

if (preg_match('/^\/kronet\/public\/intercambios\/(\d+)\/rechazar$/', $uri, $matches) && $method == 'POST') {
    requireLogin();
    $intercambioController->rechazar($matches[1]);
}

// =====================
// VALORACIONES
// =====================

if ($uri == '/kronet/public/valoraciones/crear' && $method == 'GET') {
    requireLogin();
    $idDestino = $_GET['id_usuario'] ?? null;
    $valoracionController->showCrear();
}

if ($uri == '/kronet/public/valoraciones/crear' && $method == 'POST') {
    requireLogin();
    $valoracionController->crear();
}

if ($uri == '/kronet/public/valoraciones/mis-valoraciones' && $method == 'GET') {
    requireLogin();
    $valoracionController->misValoraciones();
}
