<?php
require_once __DIR__ . '/../../config/conexion_db.php';

class Intercambio {

    /**
     * Crea un intercambio.
     *
     * Convención del sistema:
     *   - id_usuario_ofertante   = dueño del anuncio
     *   - id_usuario_solicitante = quien responde al anuncio
     *   - id_usuario_pagador     = quien paga los créditos al confirmar
     *   - id_usuario_receptor    = quien los cobra
     *
     * Si tipo_anuncio == 'oferta'  → el dueño OFRECE un servicio: paga
     *   el solicitante, cobra el ofertante.
     * 
     * Si tipo_anuncio == 'demanda' → el dueño PIDE un servicio: paga
     *   el ofertante (porque es quien recibe la ayuda), cobra el
     *   solicitante (porque es quien hace el trabajo).
     */
    public static function create($idAnuncio, $idOfertante, $idSolicitante, $monedas,
                                   $idPagador, $idReceptor) {
        global $pdo;
        $stmt = $pdo->prepare("
            INSERT INTO intercambios
            (id_anuncio, id_usuario_ofertante, id_usuario_solicitante,
             fecha_inicio, monedas_intercambio, id_usuario_pagador, id_usuario_receptor, estado)
            VALUES (?, ?, ?, NOW(), ?, ?, ?, 'pendiente')
        ");
        return $stmt->execute([
            $idAnuncio, $idOfertante, $idSolicitante,
            $monedas, $idPagador, $idReceptor
        ]);
    }

    public static function obtenerSaldo($idUsuario) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT saldo_monedas FROM usuarios WHERE id_usuario = ?");
        $stmt->execute([$idUsuario]);
        return (int)$stmt->fetchColumn();
    }

    public static function actualizarSaldo($idUsuario, $nuevoSaldo) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE usuarios SET saldo_monedas = ? WHERE id_usuario = ?");
        return $stmt->execute([$nuevoSaldo, $idUsuario]);
    }

    public static function sumarSaldo($idUsuario, $cantidad) {
        global $pdo;
        $stmt = $pdo->prepare("
            UPDATE usuarios SET saldo_monedas = saldo_monedas + ? WHERE id_usuario = ?
        ");
        return $stmt->execute([(int)$cantidad, $idUsuario]);
    }

    public static function restarSaldo($idUsuario, $cantidad) {
        global $pdo;
        $stmt = $pdo->prepare("
            UPDATE usuarios SET saldo_monedas = saldo_monedas - ? WHERE id_usuario = ?
        ");
        return $stmt->execute([(int)$cantidad, $idUsuario]);
    }

    /**
     * Ofertas recibidas en los anuncios del usuario logueado, en estado 'pendiente'.
     */
    public static function findOfertasRecibidas($id_usuario) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT i.*,
                   a.titulo AS titulo_anuncio,
                   a.tipo_anuncio,
                   uOf.nombre AS nombre_ofertante,
                   uSo.nombre AS nombre_solicitante
            FROM intercambios i
            JOIN anuncios a   ON i.id_anuncio = a.id_anuncio
            JOIN usuarios uOf ON i.id_usuario_ofertante   = uOf.id_usuario
            JOIN usuarios uSo ON i.id_usuario_solicitante = uSo.id_usuario
            WHERE a.id_usuario = ?
              AND i.estado = 'pendiente'
            ORDER BY i.fecha_inicio DESC
        ");
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cambia el estado del intercambio a 'confirmado'.
     * Aquí ya se mueven los créditos del pagador al receptor.
     */
    public static function aceptar($id_intercambio) {
        global $pdo;
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare("SELECT * FROM intercambios WHERE id_intercambio = ?");
            $stmt->execute([$id_intercambio]);
            $i = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$i || $i['estado'] !== 'pendiente') {
                $pdo->rollBack();
                return false;
            }

            $pagador = $i['id_usuario_pagador'] ?: $i['id_usuario_solicitante'];
            $receptor = $i['id_usuario_receptor'] ?: $i['id_usuario_ofertante'];

            // Verificar saldo del pagador todavía suficiente
            $saldo = (int)$pdo->query("SELECT saldo_monedas FROM usuarios WHERE id_usuario = " . (int)$pagador)
                ->fetchColumn();
            if ($saldo < (int)$i['monedas_intercambio']) {
                $pdo->rollBack();
                return false;
            }

            $u = $pdo->prepare("UPDATE usuarios SET saldo_monedas = saldo_monedas - ? WHERE id_usuario = ?");
            $u->execute([(int)$i['monedas_intercambio'], $pagador]);

            $u = $pdo->prepare("UPDATE usuarios SET saldo_monedas = saldo_monedas + ? WHERE id_usuario = ?");
            $u->execute([(int)$i['monedas_intercambio'], $receptor]);

            $u = $pdo->prepare("UPDATE intercambios SET estado = 'confirmado', fecha_fin = NOW() WHERE id_intercambio = ?");
            $u->execute([$id_intercambio]);

            $pdo->commit();
            return true;
        } catch (Exception $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            return false;
        }
    }

    public static function rechazar($id_intercambio) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE intercambios SET estado = 'cancelado' WHERE id_intercambio = ? AND estado = 'pendiente'");
        return $stmt->execute([$id_intercambio]);
    }

    public static function findById($id_intercambio) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM intercambios WHERE id_intercambio = ?");
        $stmt->execute([$id_intercambio]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function findByUsuario($id_usuario) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT i.*,
                   a.titulo AS titulo_anuncio,
                   a.tipo_anuncio
            FROM intercambios i
            LEFT JOIN anuncios a ON i.id_anuncio = a.id_anuncio
            WHERE i.id_usuario_ofertante = ? OR i.id_usuario_solicitante = ?
            ORDER BY i.fecha_inicio DESC
        ");
        $stmt->execute([$id_usuario, $id_usuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Para conocer si ya exixte un intercambio activo entre los dos usuarios para el mismo anuncio y evitar duplicados.
     */
    public static function existeActivo($idAnuncio, $idSolicitante) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT COUNT(*) FROM intercambios
            WHERE id_anuncio = ? AND id_usuario_solicitante = ?
              AND estado IN ('pendiente','confirmado')
        ");
        $stmt->execute([$idAnuncio, $idSolicitante]);
        return $stmt->fetchColumn() > 0;
    }

    public static function statsPorUsuario($id_usuario) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN estado = 'confirmado' THEN 1 ELSE 0 END) AS confirmados,
                SUM(CASE WHEN estado = 'pendiente'  THEN 1 ELSE 0 END) AS pendientes,
                SUM(CASE WHEN estado = 'cancelado'  THEN 1 ELSE 0 END) AS cancelados
            FROM intercambios
            WHERE id_usuario_ofertante = ? OR id_usuario_solicitante = ?
        ");
        $stmt->execute([$id_usuario, $id_usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: ['total'=>0,'confirmados'=>0,'pendientes'=>0,'cancelados'=>0];
    }
}
