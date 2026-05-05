<?php
require_once __DIR__ . '/../models/Intercambio.php';

class IntercambioController {

    // Muestra las ofertas recibidas en los anuncios del usuario logueado
    public function ofertasRecibidas() {
        $intercambios = Intercambio::findOfertasRecibidas($_SESSION['user']);
        require __DIR__ . '/../views/intercambios/ofertas_recibidas.php';
    }

    // Acepta una oferta
    public function aceptar($id) {
        header('Content-Type: application/json');

        $intercambio = Intercambio::findById($id);

        if (!$intercambio) {
            echo json_encode(['ok' => false, 'msg' => 'Oferta no encontrada']);
            return;
        }

        if ($intercambio['estado'] != 'pendiente') {
            echo json_encode(['ok' => false, 'msg' => 'Esta oferta ya ha sido gestionada']);
            return;
        }

        $resultado = Intercambio::aceptar($id);
        echo json_encode(['ok' => $resultado, 'msg' => 'Oferta aceptada correctamente']);
    }

    // Rechaza una oferta
    public function rechazar($id) {
        header('Content-Type: application/json');

        $intercambio = Intercambio::findById($id);

        if (!$intercambio) {
            echo json_encode(['ok' => false, 'msg' => 'Oferta no encontrada']);
            return;
        }

        if ($intercambio['estado'] != 'pendiente') {
            echo json_encode(['ok' => false, 'msg' => 'Esta oferta ya ha sido gestionada']);
            return;
        }

        $resultado = Intercambio::rechazar($id);
        echo json_encode(['ok' => $resultado, 'msg' => 'Oferta rechazada correctamente']);
    }
}