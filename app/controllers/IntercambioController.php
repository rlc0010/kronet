<?php
require_once __DIR__ . '/../models/Intercambio.php';
require_once __DIR__ . '/../models/Anuncio.php';
require_once __DIR__ . '/../models/Notificacion.php';

class IntercambioController {

    /**
     * Solicitar/apuntarse a un anuncio.
     *
     * Reglas de flujo de créditos:
     *   - tipo_anuncio = 'oferta'  → solicitante PAGA, dueño RECIBE
     *   - tipo_anuncio = 'demanda' → dueño PAGA, solicitante RECIBE
     *
     * Aquí solo se VERIFICA que el pagador tiene saldo suficiente.
     * El descuento real ocurre cuando se acepta el intercambio.
     */
    public function solicitar() {
        header('Content-Type: application/json');

        $idAnuncio    = (int)($_POST['id_anuncio'] ?? 0);
        $idSolicitante = (int)$_SESSION['id_usuario'];

        $anuncio = Anuncio::findById($idAnuncio);
        if (!$anuncio) {
            echo json_encode(['ok' => false, 'msg' => 'Anuncio no encontrado']);
            return;
        }
        if ($anuncio['estado'] !== 'activo') {
            echo json_encode(['ok' => false, 'msg' => 'Este anuncio ya no acepta solicitudes']);
            return;
        }
        if ((int)$anuncio['plazas_ocupadas'] >= (int)$anuncio['plazas_totales']) {
            echo json_encode(['ok' => false, 'msg' => 'No quedan plazas disponibles']);
            return;
        }
        if ((int)$anuncio['id_usuario'] === $idSolicitante) {
            echo json_encode(['ok' => false, 'msg' => 'No puedes solicitar tu propio anuncio']);
            return;
        }
        if (Intercambio::existeActivo($idAnuncio, $idSolicitante)) {
            echo json_encode(['ok' => false, 'msg' => 'Ya tienes una solicitud activa para este anuncio']);
            return;
        }

        $idOfertante = (int)$anuncio['id_usuario'];
        $monedas     = (int)$anuncio['precio_creditos'];

        // Determinar pagador/receptor según el tipo de anuncio
        if ($anuncio['tipo_anuncio'] === 'oferta') {
            // Dueño OFRECE → solicitante paga
            $idPagador  = $idSolicitante;
            $idReceptor = $idOfertante;
        } else {
            // Dueño DEMANDA → dueño paga
            $idPagador  = $idOfertante;
            $idReceptor = $idSolicitante;
        }

        // Verificar saldo del pagador
        $saldoPagador = Intercambio::obtenerSaldo($idPagador);
        if ($saldoPagador < $monedas) {
            if ($idPagador === $idSolicitante) {
                echo json_encode(['ok' => false, 'msg' => 'No tienes créditos suficientes (' . $monedas . ' necesarios)']);
            } else {
                echo json_encode(['ok' => false, 'msg' => 'El usuario que demanda no tiene créditos suficientes en este momento']);
            }
            return;
        }

        $ok = Intercambio::create($idAnuncio, $idOfertante, $idSolicitante, $monedas, $idPagador, $idReceptor);
        if (!$ok) {
            echo json_encode(['ok' => false, 'msg' => 'No se pudo registrar la solicitud']);
            return;
        }

        // Reservar la plaza desde ya, así no se sobrevende
        Anuncio::ocuparPlaza($idAnuncio);

        // Notificar al dueño del anuncio
        Notificacion::crear($idOfertante, 'oferta',
            'Nueva solicitud en tu anuncio',
            'Has recibido una solicitud para "' . $anuncio['titulo'] . '"',
            '/kronet/public/intercambios/ofertas-recibidas');

        echo json_encode(['ok' => true, 'msg' => 'Solicitud enviada. Espera la respuesta del usuario.']);
    }

    public function ofertasRecibidas() {
        $intercambios = Intercambio::findOfertasRecibidas($_SESSION['id_usuario']);
        require __DIR__ . '/../views/intercambios/ofertas_recibidas.php';
    }

    public function aceptar($id) {
        header('Content-Type: application/json');

        $i = Intercambio::findById($id);
        if (!$i) {
            echo json_encode(['ok' => false, 'msg' => 'Intercambio no encontrado']);
            return;
        }
        if ((int)$i['id_usuario_ofertante'] !== (int)$_SESSION['id_usuario']) {
            echo json_encode(['ok' => false, 'msg' => 'No tienes permiso']);
            return;
        }
        if ($i['estado'] !== 'pendiente') {
            echo json_encode(['ok' => false, 'msg' => 'Este intercambio ya no está pendiente']);
            return;
        }

        $ok = Intercambio::aceptar($id);
        if (!$ok) {
            // Probable falta de saldo del pagador en este momento
            echo json_encode(['ok' => false, 'msg' => 'No se pudo aceptar (¿saldo insuficiente?)']);
            return;
        }

        // Notificar al solicitante
        $idSolicitante = (int)$i['id_usuario_solicitante'];
        Notificacion::crear($idSolicitante, 'intercambio',
            'Tu solicitud ha sido aceptada',
            'El intercambio ya está confirmado y los créditos se han transferido.',
            '/kronet/public/intercambios/mis-intercambios');

        echo json_encode(['ok' => true, 'msg' => 'Intercambio confirmado. Créditos transferidos.']);
    }

    public function rechazar($id) {
        header('Content-Type: application/json');

        $i = Intercambio::findById($id);
        if (!$i) {
            echo json_encode(['ok' => false, 'msg' => 'Intercambio no encontrado']);
            return;
        }
        if ((int)$i['id_usuario_ofertante'] !== (int)$_SESSION['id_usuario']) {
            echo json_encode(['ok' => false, 'msg' => 'No tienes permiso']);
            return;
        }
        if ($i['estado'] !== 'pendiente') {
            echo json_encode(['ok' => false, 'msg' => 'Este intercambio ya no está pendiente']);
            return;
        }

        Intercambio::rechazar($id);
        // Liberar la plaza que se había ocupado
        if (!empty($i['id_anuncio'])) {
            Anuncio::liberarPlaza((int)$i['id_anuncio']);
        }

        // Notificar al solicitante
        Notificacion::crear((int)$i['id_usuario_solicitante'], 'intercambio',
            'Tu solicitud ha sido rechazada',
            'Puedes seguir explorando otros anuncios.',
            '/kronet/public/anuncios/buscar');

        echo json_encode(['ok' => true, 'msg' => 'Solicitud rechazada']);
    }
}
