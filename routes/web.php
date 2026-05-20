<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/conexion_db.php';
require_once __DIR__ . '/../app/controllers/UserController.php';
require_once __DIR__ . '/../app/controllers/AnuncioController.php';
require_once __DIR__ . '/../app/controllers/MensajeController.php';
require_once __DIR__ . '/../app/controllers/PerfilController.php';
require_once __DIR__ . '/../app/controllers/IntercambioController.php';
require_once __DIR__ . '/../app/controllers/ValoracionController.php';
require_once __DIR__ . '/../app/controllers/SuscripcionController.php';
require_once __DIR__ . '/../app/controllers/UsuariosController.php';
require_once __DIR__ . '/../app/controllers/NotificacionController.php';

$uri    = strtok($_SERVER['REQUEST_URI'], '?');
$method = $_SERVER['REQUEST_METHOD'];

/**
 * Saneamiento de sesión.
 *
 * En cada petición, si hay un id_usuario en la sesión, comprobamos contra
 * la BD que el usuario:
 *   - sigue existiendo (puede haber sido borrado por un admin),
 *   - tiene la cuenta activa (no bloqueada).
 *
 * Si algo no cuadra, destruimos la sesión: no queremos permitir navegar
 * con un id "fantasma" que ya no existe en la BD.
 */
if (isset($_SESSION['id_usuario'])) {
    $u = User::findById($_SESSION['id_usuario']);
    if (!$u || ($u['estado_cuenta'] ?? 'activa') !== 'activa') {
        session_destroy();
        // Vacía la sesión también en memoria del request actual
        $_SESSION = [];
    } else {
        // Sincroniza el nombre por si el usuario lo cambió en otra sesión
        $_SESSION['nombre'] = $u['nombre'];
    }
}

/**
 * Exige que haya sesión válida. Si no la hay, redirige al login para vistas
 * HTML, o devuelve JSON 401 para endpoints AJAX (POST con tipo JSON).
 */
function requireLogin() {
    if (!isset($_SESSION['id_usuario'])) {
        // Si es una petición AJAX/POST, devolvemos JSON en vez de redirigir
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        $isAjax = ($_SERVER['REQUEST_METHOD'] === 'POST') ||
                  (strpos($accept, 'application/json') !== false) ||
                  (!empty($_SERVER['HTTP_X_REQUESTED_WITH']));
        if ($isAjax && $_SERVER['REQUEST_METHOD'] === 'POST') {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'msg' => 'Necesitas iniciar sesión', 'login_required' => true]);
        } else {
            header("Location: /kronet/public/login");
        }
        exit;
    }
}

$userController        = new AuthController();
$anuncioController     = new AnuncioController();
$mensajeController     = new MensajeController();
$perfilController      = new PerfilController();
$intercambioController = new IntercambioController();
$valoracionController  = new ValoracionController();
$suscripcionController = new SuscripcionController();
$usuariosController    = new UsuariosController();
$notificacionController= new NotificacionController();

// ===== HOME (pública: la vista muestra distinto contenido según haya sesión) =====
if ($uri == '/kronet/public/' || $uri == '/kronet/public') {
    require __DIR__ . '/../app/views/home.php';
    exit;
}

// ===== AUTH =====
if ($uri == '/kronet/public/login' && $method == 'GET')      $userController->showLogin();
if ($uri == '/kronet/public/login' && $method == 'POST')     $userController->login();
if ($uri == '/kronet/public/register' && $method == 'GET')   $userController->showRegister();
if ($uri == '/kronet/public/register' && $method == 'POST')  $userController->register();

if ($uri == '/kronet/public/logout') {
    session_destroy();
    header("Location: /kronet/public/login");
    exit;
}

// ===== ANUNCIOS =====
// Crear / editar / mis-anuncios: requieren cuenta
if ($uri == '/kronet/public/anuncios/crear' && $method == 'GET')  { requireLogin(); $anuncioController->showCrear(); }
if ($uri == '/kronet/public/anuncios/crear' && $method == 'POST') { requireLogin(); $anuncioController->crear(); }
if ($uri == '/kronet/public/anuncios/mis-anuncios' && $method == 'GET') { requireLogin(); $anuncioController->misAnuncios(); }

// Buscar y ver ficha: PÚBLICAS (anónimos pueden explorar)
if ($uri == '/kronet/public/anuncios/buscar' && $method == 'GET') { $anuncioController->showBuscar(); }

if (preg_match('/^\/kronet\/public\/anuncios\/(\d+)\/editar$/', $uri, $m) && $method == 'GET') {
    requireLogin(); $anuncioController->showEditar($m[1]);
}
if (preg_match('/^\/kronet\/public\/anuncios\/(\d+)\/editar$/', $uri, $m) && $method == 'POST') {
    requireLogin(); $anuncioController->editar($m[1]);
}
if (preg_match('/^\/kronet\/public\/anuncios\/(\d+)\/eliminar$/', $uri, $m) && $method == 'POST') {
    requireLogin(); $anuncioController->eliminar($m[1]);
}
if (preg_match('/^\/kronet\/public\/anuncios\/(\d+)\/destacar$/', $uri, $m) && $method == 'GET') {
    requireLogin(); $anuncioController->showDestacar($m[1]);
}
if (preg_match('/^\/kronet\/public\/anuncios\/(\d+)\/destacar$/', $uri, $m) && $method == 'POST') {
    requireLogin(); $anuncioController->destacar($m[1]);
}
if (preg_match('/^\/kronet\/public\/anuncios\/(\d+)\/denunciar$/', $uri, $m) && $method == 'POST') {
    requireLogin(); $anuncioController->denunciar($m[1]);
}
// Ficha pública (anonimo puede ver detalle, pero las acciones requieren login)
if (preg_match('/^\/kronet\/public\/anuncios\/(\d+)$/', $uri, $m) && $method == 'GET') {
    $anuncioController->showFicha($m[1]);
}

// ===== MENSAJES =====
if ($uri == '/kronet/public/mensajes' && $method == 'GET')        { requireLogin(); $mensajeController->bandeja(); }
if ($uri == '/kronet/public/mensaje/enviar' && $method == 'POST') { requireLogin(); $mensajeController->enviar(); }
if ($uri == '/kronet/public/api/conversacion' && $method == 'GET'){ requireLogin(); $mensajeController->api_conversacion(); }

// ===== PERFIL =====
if ($uri == '/kronet/public/perfil' && $method == 'GET') { requireLogin(); $perfilController->verPerfil(); }
if ($uri == '/kronet/public/perfil/editar' && $method == 'POST') { requireLogin(); $perfilController->editarPerfil(); }
if (preg_match('/^\/kronet\/public\/perfil\/(\d+)$/', $uri, $m) && $method == 'GET') {
    requireLogin(); $perfilController->verPerfilAjeno($m[1]);
}

// ===== INTERCAMBIOS =====
if ($uri == '/kronet/public/intercambios/solicitar' && $method == 'POST') { requireLogin(); $intercambioController->solicitar(); }
if ($uri == '/kronet/public/intercambios/mis-intercambios' && $method == 'GET') {
    requireLogin();
    $intercambios = Intercambio::findByUsuario($_SESSION['id_usuario']);
    require __DIR__ . '/../app/views/intercambios/listado.php';
}
if ($uri == '/kronet/public/intercambios/ofertas-recibidas' && $method == 'GET') {
    requireLogin(); $intercambioController->ofertasRecibidas();
}
if (preg_match('/^\/kronet\/public\/intercambios\/(\d+)\/aceptar$/', $uri, $m) && $method == 'POST') {
    requireLogin(); $intercambioController->aceptar($m[1]);
}
if (preg_match('/^\/kronet\/public\/intercambios\/(\d+)\/rechazar$/', $uri, $m) && $method == 'POST') {
    requireLogin(); $intercambioController->rechazar($m[1]);
}

// ===== VALORACIONES =====
if ($uri == '/kronet/public/valoraciones/crear' && $method == 'GET')  { requireLogin(); $valoracionController->showCrear(); }
if ($uri == '/kronet/public/valoraciones/crear' && $method == 'POST') { requireLogin(); $valoracionController->crear(); }
if ($uri == '/kronet/public/valoraciones/mis-valoraciones' && $method == 'GET') {
    requireLogin(); $valoracionController->misValoraciones();
}

// ===== SUSCRIPCIÓN =====
if ($uri == '/kronet/public/suscripcion' && $method == 'GET')        { requireLogin(); $suscripcionController->show(); }
if ($uri == '/kronet/public/suscripcion/activar' && $method == 'POST'){ requireLogin(); $suscripcionController->suscribir(); }
if ($uri == '/kronet/public/suscripcion/cancelar' && $method == 'POST'){ requireLogin(); $suscripcionController->cancelar(); }

// ===== CONTACTOS =====
if ($uri == '/kronet/public/contactos' && $method == 'GET') {
    requireLogin();
    require_once __DIR__ . '/../app/models/Contacto.php';
    $contactos = Contacto::listarConDetalles((int)$_SESSION['id_usuario']);
    require __DIR__ . '/../app/views/contactos/listado.php';
    exit;
}

// ===== USUARIOS (contactos, bloqueos) =====
if ($uri == '/kronet/public/usuarios/agregar' && $method == 'POST')    { requireLogin(); $usuariosController->agregar(); }
if ($uri == '/kronet/public/usuarios/quitar' && $method == 'POST')     { requireLogin(); $usuariosController->quitarContacto(); }
if ($uri == '/kronet/public/usuarios/bloquear' && $method == 'POST')   { requireLogin(); $usuariosController->bloquear(); }
if ($uri == '/kronet/public/usuarios/desbloquear' && $method == 'POST'){ requireLogin(); $usuariosController->desbloquear(); }

// ===== NOTIFICACIONES =====
if ($uri == '/kronet/public/notificaciones' && $method == 'GET')             { requireLogin(); $notificacionController->listado(); }
if ($uri == '/kronet/public/notificaciones/marcar-leidas' && $method == 'POST'){ requireLogin(); $notificacionController->marcarTodasLeidas(); }
