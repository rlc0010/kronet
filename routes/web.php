<?php
// Cargamos la conexión a la BD y los controladores que vamos a necesitar
require_once __DIR__ . '/../config/conexion_db.php';
require_once __DIR__ . '/../app/controllers/UserController.php';
require_once __DIR__ . '/../app/controllers/AnuncioController.php';
require_once __DIR__ . '/../app/controllers/PerfilController.php';
// strtok elimina los parámetros GET de la URI (todo lo que va después del ?)
// Ejemplo: /anuncios/buscar?busqueda=guitarra → /anuncios/buscar
// Así el router reconoce correctamente la ruta
$uri = strtok($_SERVER['REQUEST_URI'], '?');
$method = $_SERVER['REQUEST_METHOD'];

// Función helper para proteger rutas privadas
// Si el usuario no está logueado lo mandamos al login y paramos la ejecución
function requireLogin() {
    if (!isset($_SESSION['user'])) {
        header("Location: /kronet/public/login");
        exit;
    }
}

// Instanciamos los controladores
$userController    = new AuthController();
$anuncioController = new AnuncioController();
$perfilController = new PerfilController();

// =====================
// HOME
// =====================
if ($uri == '/kronet/public/' || $uri == '/kronet/public') {
    echo "<h1>Kronet</h1>";

    // Mostramos opciones distintas según si el usuario está logueado o no
    if (isset($_SESSION['user'])) {
        echo "<p>Sesión iniciada</p>";
        echo "<a href='/kronet/public/anuncios/crear'>Publicar anuncio</a> | ";
        echo "<a href='/kronet/public/anuncios/mis-anuncios'>Mis anuncios</a> | ";
        echo "<a href='/kronet/public/anuncios/buscar'>Buscar anuncios</a> | ";
        echo "<a href='/kronet/public/logout'>Cerrar sesión</a>";
    } else {
        echo "<a href='/kronet/public/login'>Login</a><br>";
        echo "<a href='/kronet/public/register'>Registro</a>";
    }
}

// =====================
// AUTH
// =====================

// Mostramos el formulario de login
if ($uri == '/kronet/public/login' && $method == 'GET') {
    $userController->showLogin();
}

// Procesamos el formulario de login
if ($uri == '/kronet/public/login' && $method == 'POST') {
    $userController->login();
}

// Mostramos el formulario de registro
if ($uri == '/kronet/public/register' && $method == 'GET') {
    $userController->showRegister();
}

// Procesamos el formulario de registro
if ($uri == '/kronet/public/register' && $method == 'POST') {
    $userController->register();
}

// Destruimos la sesión y redirigimos al login
if ($uri == '/kronet/public/logout') {
    session_destroy();
    header("Location: /kronet/public/login");
    exit;
}

// =====================
// ANUNCIOS
// =====================

// Mostramos el formulario vacío para crear un anuncio
if ($uri == '/kronet/public/anuncios/crear' && $method == 'GET') {
    requireLogin();
    $anuncioController->showCrear();
}

// Procesamos el formulario de creación y guardamos en la BD
if ($uri == '/kronet/public/anuncios/crear' && $method == 'POST') {
    requireLogin();
    $anuncioController->crear();
}

// Mostramos todos los anuncios del usuario logueado
if ($uri == '/kronet/public/anuncios/mis-anuncios' && $method == 'GET') {
    requireLogin();
    $anuncioController->misAnuncios();
}

// Mostramos la página de búsqueda con filtros
if ($uri == '/kronet/public/anuncios/buscar' && $method == 'GET') {
    requireLogin();
    $anuncioController->showBuscar();
}

// Mostramos el formulario de edición con los datos actuales del anuncio
// Usamos una expresión regular para capturar el ID de la URL
// Ejemplo: /kronet/public/anuncios/5/editar → $matches[1] = 5
if (preg_match('/^\/kronet\/public\/anuncios\/(\d+)\/editar$/', $uri, $matches) && $method == 'GET') {
    requireLogin();
    $anuncioController->showEditar($matches[1]);
}

// Procesamos el formulario de edición y guardamos los cambios en la BD
if (preg_match('/^\/kronet\/public\/anuncios\/(\d+)\/editar$/', $uri, $matches) && $method == 'POST') {
    requireLogin();
    $anuncioController->editar($matches[1]);
}

// Eliminamos el anuncio cuyo ID viene en la URL
// Solo acepta POST para evitar eliminaciones accidentales por GET
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