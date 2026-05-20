<?php
require_once __DIR__ . '/../models/Notificacion.php';

/**
 * Lista y marca como leídas las notificaciones del usuario logueado.
 */
class NotificacionController {

    public function listado() {
        $miId = (int)$_SESSION['id_usuario'];
        $notificaciones = Notificacion::listarPorUsuario($miId);
        require __DIR__ . '/../views/notificaciones/listado.php';
    }

    public function marcarTodasLeidas() {
        header('Content-Type: application/json');
        $miId = (int)$_SESSION['id_usuario'];
        Notificacion::marcarTodasLeidas($miId);
        echo json_encode(['ok' => true, 'msg' => 'Notificaciones marcadas como leídas']);
    }
}
