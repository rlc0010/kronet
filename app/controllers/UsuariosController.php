<?php
require_once __DIR__ . '/../models/Contacto.php';
require_once __DIR__ . '/../models/Bloqueo.php';
require_once __DIR__ . '/../models/User.php';

/**
 * Operaciones de relación entre usuarios: agregar/quitar contactos y bloquear/desbloquear.
 * Todos los endpoints son POST y devuelven JSON para consumirse desde la vista.
 */
class UsuariosController {

    public function agregar() {
        header('Content-Type: application/json');
        $miId = (int)$_SESSION['id_usuario'];
        $idOtro = (int)($_POST['id_usuario'] ?? 0);

        if (!$idOtro || $idOtro === $miId) {
            echo json_encode(['ok' => false, 'msg' => 'Usuario no válido']);
            return;
        }
        if (!User::findById($idOtro)) {
            echo json_encode(['ok' => false, 'msg' => 'Usuario no encontrado']);
            return;
        }
        if (Contacto::esContacto($miId, $idOtro)) {
            echo json_encode(['ok' => false, 'msg' => 'Ya está en tus contactos']);
            return;
        }

        $ok = Contacto::agregar($miId, $idOtro);
        echo json_encode(['ok' => (bool)$ok, 'msg' => $ok ? 'Añadido a contactos' : 'No se pudo añadir']);
    }

    public function quitarContacto() {
        header('Content-Type: application/json');
        $miId = (int)$_SESSION['id_usuario'];
        $idOtro = (int)($_POST['id_usuario'] ?? 0);

        Contacto::eliminar($miId, $idOtro);
        echo json_encode(['ok' => true, 'msg' => 'Quitado de contactos']);
    }

    public function bloquear() {
        header('Content-Type: application/json');
        $miId = (int)$_SESSION['id_usuario'];
        $idOtro = (int)($_POST['id_usuario'] ?? 0);

        if (!$idOtro || $idOtro === $miId) {
            echo json_encode(['ok' => false, 'msg' => 'Usuario no válido']);
            return;
        }
        if (!User::findById($idOtro)) {
            echo json_encode(['ok' => false, 'msg' => 'Usuario no encontrado']);
            return;
        }
        if (Bloqueo::estaBloqueado($miId, $idOtro)) {
            echo json_encode(['ok' => false, 'msg' => 'Ya estaba bloqueado']);
            return;
        }

        // Al bloquear, lo quitamos de contactos también
        Contacto::eliminar($miId, $idOtro);
        $ok = Bloqueo::bloquear($miId, $idOtro);
        echo json_encode(['ok' => (bool)$ok, 'msg' => $ok ? 'Usuario bloqueado' : 'No se pudo bloquear']);
    }

    public function desbloquear() {
        header('Content-Type: application/json');
        $miId = (int)$_SESSION['id_usuario'];
        $idOtro = (int)($_POST['id_usuario'] ?? 0);

        Bloqueo::desbloquear($miId, $idOtro);
        echo json_encode(['ok' => true, 'msg' => 'Usuario desbloqueado']);
    }
}
