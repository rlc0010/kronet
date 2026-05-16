<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Anuncio.php';
require_once __DIR__ . '/../models/Intercambio.php';
require_once __DIR__ . '/../models/Valoracion.php';
require_once __DIR__ . '/../models/Suscripcion.php';
require_once __DIR__ . '/../models/Contacto.php';
require_once __DIR__ . '/../models/Bloqueo.php';

class PerfilController {

    public function verPerfil() {
        $miId   = (int)$_SESSION['id_usuario'];
        $usuario = User::findById($miId);
        if (!$usuario) {
            header("Location: /kronet/public/logout"); exit;
        }

        $stats         = Intercambio::statsPorUsuario($miId);
        $media         = Valoracion::mediaUsuario($miId);
        $historial     = Intercambio::findByUsuario($miId);
        $totalAnuncios = Anuncio::contarPorUsuario($miId);
        $suscripcion   = Suscripcion::activaPorUsuario($miId);

        require __DIR__ . '/../views/perfil/verPerfil.php';
    }

    public function verPerfilAjeno($id) {
        $id = (int)$id;
        $miId = (int)$_SESSION['id_usuario'];

        if ($id === $miId) {
            header("Location: /kronet/public/perfil"); exit;
        }

        $usuario = User::findById($id);
        if (!$usuario) {
            $error = 'Usuario no encontrado';
            require __DIR__ . '/../views/perfil/ajeno.php';
            return;
        }

        $media        = Valoracion::mediaUsuario($id);
        $anuncios     = Anuncio::findByUsuario($id);
        // Filtrar a sólo activos (en perfil ajeno)
        $anuncios     = array_values(array_filter($anuncios, fn($a) => $a['estado'] !== 'cancelado'));
        $valoraciones = Valoracion::findByDestinatario($id);
        $yaValorado   = Valoracion::yaValorado($miId, $id);
        $esContacto   = Contacto::esContacto($miId, $id);
        $estaBloqueado= Bloqueo::estaBloqueado($miId, $id);

        require __DIR__ . '/../views/perfil/ajeno.php';
    }

    public function editarPerfil() {
        header('Content-Type: application/json');

        $miId = (int)$_SESSION['id_usuario'];
        $nombre = trim($_POST['nombre'] ?? '');
        $email  = trim($_POST['email']  ?? '');
        $desc   = trim($_POST['descripcion'] ?? '');

        if ($nombre === '' || $email === '') {
            echo json_encode(['ok' => false, 'msg' => 'Nombre y email obligatorios']);
            return;
        }
        if (mb_strlen($nombre) < 2 || mb_strlen($nombre) > 100) {
            echo json_encode(['ok' => false, 'msg' => 'Nombre con longitud no válida']);
            return;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['ok' => false, 'msg' => 'Email con formato no válido']);
            return;
        }

        // Evitar colisiones de email
        $otro = User::findByEmail($email);
        if ($otro && (int)$otro['id_usuario'] !== $miId) {
            echo json_encode(['ok' => false, 'msg' => 'Ese email ya está en uso']);
            return;
        }
        if (mb_strlen($desc) > 1000) {
            echo json_encode(['ok' => false, 'msg' => 'Descripción demasiado larga']);
            return;
        }

        $ok = User::updatePerfil($miId, $nombre, $email, $desc);
        $_SESSION['nombre'] = $nombre;
        echo json_encode(['ok' => (bool)$ok, 'msg' => $ok ? 'Perfil actualizado' : 'No se pudo actualizar']);
    }
}
