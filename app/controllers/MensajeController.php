<?php
require_once __DIR__ . '/../models/Mensaje.php';
require_once __DIR__ . '/../models/Anuncio.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Bloqueo.php';
require_once __DIR__ . '/../models/Notificacion.php';

/**
 * Gestiona el sistema de mensajería por anuncio.
 * Cada conversación está ligada a un anuncio concreto y a un par de usuarios,
 * por lo que no existe mensajería directa fuera del contexto de un anuncio.
 */
class MensajeController {

    /**
     * Bandeja con dos modos:
     *   - Sin parámetros: lista de conversaciones (sidebar) y vacío a la derecha
     *   - Con ?anuncio=X&usuario=Y: abre esa conversación a la derecha
     */
    public function bandeja() {
        $miId = (int)$_SESSION['id_usuario'];

        $conversaciones = Mensaje::listarConversaciones($miId);

        $convAbierta = null;
        $anuncio     = null;
        $otroUsuario = null;
        $mensajes    = [];

        $idAnuncio = isset($_GET['anuncio']) ? (int)$_GET['anuncio'] : 0;
        $idOtro    = isset($_GET['usuario']) ? (int)$_GET['usuario'] : 0;

        if ($idAnuncio && $idOtro) {
            $anuncio     = Anuncio::findById($idAnuncio);
            $otroUsuario = User::findById($idOtro);
            if ($anuncio && $otroUsuario) {
                $convAbierta = ['id_anuncio' => $idAnuncio, 'otro_usuario' => $idOtro];
                $mensajes    = Mensaje::conversacion($idAnuncio, $miId, $idOtro);
                Mensaje::marcarConversacionLeida($idAnuncio, $miId, $idOtro);
            }
        } elseif (!empty($conversaciones)) {
            // Abre la primera conversación por defecto
            $c = $conversaciones[0];
            $idAnuncio = (int)$c['id_anuncio'];
            $idOtro    = (int)$c['otro_usuario'];
            $anuncio     = Anuncio::findById($idAnuncio);
            $otroUsuario = User::findById($idOtro);
            if ($anuncio && $otroUsuario) {
                $convAbierta = ['id_anuncio' => $idAnuncio, 'otro_usuario' => $idOtro];
                $mensajes    = Mensaje::conversacion($idAnuncio, $miId, $idOtro);
                Mensaje::marcarConversacionLeida($idAnuncio, $miId, $idOtro);
            }
        }

        require __DIR__ . '/../views/mensajes/bandeja.php';
    }

    public function enviar() {
        header('Content-Type: application/json');

        $idAnuncio   = (int)($_POST['id_anuncio']  ?? 0);
        $idReceptor  = (int)($_POST['id_receptor'] ?? 0);
        $contenido   = trim($_POST['mensaje']      ?? '');

        if (!$idAnuncio || !$idReceptor || $contenido === '') {
            echo json_encode(['ok' => false, 'msg' => 'Faltan datos del mensaje']);
            return;
        }
        if (mb_strlen($contenido) > 2000) {
            echo json_encode(['ok' => false, 'msg' => 'Mensaje demasiado largo (máx 2000)']);
            return;
        }
        if ($idReceptor === (int)$_SESSION['id_usuario']) {
            echo json_encode(['ok' => false, 'msg' => 'No puedes enviarte mensajes a ti mismo']);
            return;
        }

        $anuncio = Anuncio::findById($idAnuncio);
        if (!$anuncio) {
            echo json_encode(['ok' => false, 'msg' => 'Anuncio no encontrado']);
            return;
        }

        // La conversación tiene que ser SIEMPRE entre el dueño del anuncio y otro usuario
        $duenio = (int)$anuncio['id_usuario'];
        $miId   = (int)$_SESSION['id_usuario'];
        if (!(($miId === $duenio && $idReceptor !== $duenio) ||
              ($miId !== $duenio && $idReceptor === $duenio))) {
            echo json_encode(['ok' => false, 'msg' => 'Esta conversación no es válida para este anuncio']);
            return;
        }

        // Comprobar bloqueos en ambas direcciones
        if (Bloqueo::hayBloqueo($miId, $idReceptor)) {
            echo json_encode(['ok' => false, 'msg' => 'No puedes enviar mensajes a este usuario']);
            return;
        }

        $ok = Mensaje::enviar($miId, $idReceptor, $contenido, $idAnuncio);
        if (!$ok) {
            echo json_encode(['ok' => false, 'msg' => 'No se pudo enviar el mensaje']);
            return;
        }

        // Notificar
        Notificacion::crear($idReceptor, 'mensaje',
            'Nuevo mensaje',
            'Tienes un mensaje nuevo sobre "' . $anuncio['titulo'] . '"',
            '/kronet/public/mensajes?anuncio=' . $idAnuncio . '&usuario=' . $miId);

        echo json_encode(['ok' => true, 'msg' => 'Mensaje enviado']);
    }

    /**
     * Endpoint usado por el chat para refrescar los mensajes.
     */
    public function api_conversacion() {
        header('Content-Type: application/json');
        $idAnuncio = (int)($_GET['anuncio'] ?? 0);
        $idOtro    = (int)($_GET['usuario'] ?? 0);
        $miId      = (int)$_SESSION['id_usuario'];

        if (!$idAnuncio || !$idOtro) {
            echo json_encode(['ok' => false, 'msg' => 'Faltan parámetros']);
            return;
        }

        $mensajes = Mensaje::conversacion($idAnuncio, $miId, $idOtro);
        Mensaje::marcarConversacionLeida($idAnuncio, $miId, $idOtro);
        echo json_encode(['ok' => true, 'mensajes' => $mensajes]);
    }
}
