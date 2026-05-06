<?php
require_once __DIR__ . '/../models/Intercambio.php';
require_once __DIR__ . '/../models/Anuncio.php';

class IntercambioController {
    // Muestra las ofertas recibidas en los anuncios del usuario logueado
    public function ofertasRecibidas() {
        $intercambios = Intercambio::findOfertasRecibidas($_SESSION['id_usuario']);
        require __DIR__ . '/../views/intercambios/ofertas_recibidas.php';
    }
    public function solicitar() {
        header('Content-Type: application/json');

        try {
            $idAnuncio = $_POST['id_anuncio'] ?? null;
            $idSolicitante = $_SESSION['id_usuario'];

            if (!$idAnuncio) {
                throw new Exception('Anuncio inválido');
            }

            $anuncio = Anuncio::findById($idAnuncio);

            if (!$anuncio) {
                throw new Exception('Anuncio no encontrado');
            }

            $idOfertante = $anuncio['id_usuario'];
            $monedas = $anuncio['duracion_estimada'];

            $saldo = Intercambio::obtenerSaldo($idSolicitante);

            if ($saldo < $monedas) {
                throw new Exception('Saldo insuficiente');
            }

            Intercambio::create($idAnuncio, $idOfertante, $idSolicitante, $monedas);
            Intercambio::actualizarSaldo($idSolicitante, $saldo - $monedas);

            $saldoOfertante = Intercambio::obtenerSaldo($idOfertante);
            Intercambio::actualizarSaldo($idOfertante, $saldoOfertante + $monedas);

            echo json_encode(['ok' => true]);

        } catch (Exception $e) {
            echo json_encode([
                'ok' => false,
                'msg' => $e->getMessage()
            ]);
        }
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

