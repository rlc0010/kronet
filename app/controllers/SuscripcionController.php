<?php
require_once __DIR__ . '/../models/Suscripcion.php';
require_once __DIR__ . '/../models/Pago.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Notificacion.php';

/**
 * Gestiona la suscripción Premium: alta, cancelación y pantalla informativa.
 * El pago es ficticio (demo), pero los efectos en BD son reales: cambia el
 * tipo_usuario y habilita el destacado gratuito semanal.
 */
class SuscripcionController {

    public function show() {
        $miId = (int)$_SESSION['id_usuario'];
        $suscripcion = Suscripcion::activaPorUsuario($miId);
        if ($suscripcion) {
            // Refresca el contador semanal antes de mostrar
            $suscripcion = Suscripcion::resetearContadorSemanalSiCorresponde($suscripcion['id_suscripcion']);
        }
        require __DIR__ . '/../views/suscripcion/index.php';
    }

    public function suscribir() {
        header('Content-Type: application/json');

        $miId   = (int)$_SESSION['id_usuario'];
        $metodo = $_POST['metodo_pago'] ?? '';

        if (!in_array($metodo, Pago::METODOS, true)) {
            echo json_encode(['ok' => false, 'msg' => 'Método de pago no válido']);
            return;
        }

        if (Suscripcion::activaPorUsuario($miId)) {
            echo json_encode(['ok' => false, 'msg' => 'Ya tienes una suscripción activa']);
            return;
        }

        // Pasarela simulada
        $idSus = Suscripcion::crear($miId, 30);
        Pago::registrar($miId, 'suscripcion', $metodo, Suscripcion::PRECIO_MENSUAL, 'suscripcion:' . $idSus);
        User::marcarComoSuscrito($miId);

        Notificacion::crear($miId, 'sistema',
            '¡Bienvenido a Kronet Premium!',
            'Disfruta de tus ventajas durante el próximo mes.',
            '/kronet/public/perfil');

        echo json_encode(['ok' => true, 'msg' => 'Suscripción activada correctamente']);
    }

    public function cancelar() {
        header('Content-Type: application/json');
        $miId = (int)$_SESSION['id_usuario'];
        Suscripcion::cancelar($miId);
        User::marcarComoRegistrado($miId);
        echo json_encode(['ok' => true, 'msg' => 'Suscripción cancelada']);
    }
}
