<?php
require_once __DIR__ . '/../../config/conexion_db.php';

class Intercambio {

    public static function create($idAnuncio, $idOfertante, $idSolicitante, $monedas) {
        global $pdo;

        $stmt = $pdo->prepare("
            INSERT INTO intercambios
            (id_anuncio, id_usuario_ofertante, id_usuario_solicitante, fecha_inicio, monedas_intercambio, estado)
            VALUES (?, ?, ?, NOW(), ?, 'pendiente')
        ");

        return $stmt->execute([
            $idAnuncio,
            $idOfertante,
            $idSolicitante,
            $monedas
        ]);
    }

    public static function obtenerSaldo($idUsuario) {
        global $pdo;

        $stmt = $pdo->prepare("
            SELECT saldo_monedas
            FROM usuarios
            WHERE id_usuario = ?
        ");

        $stmt->execute([$idUsuario]);
        return $stmt->fetchColumn();
    }

    public static function actualizarSaldo($idUsuario, $nuevoSaldo) {
        global $pdo;

        $stmt = $pdo->prepare("
            UPDATE usuarios
            SET saldo_monedas = ?
            WHERE id_usuario = ?
        ");

        return $stmt->execute([$nuevoSaldo, $idUsuario]);
    }

    public static function listarPorUsuario($idUsuario) {
        global $pdo;

        $stmt = $pdo->prepare("
            SELECT *
            FROM intercambios
            WHERE id_usuario_ofertante = ?
               OR id_usuario_solicitante = ?
            ORDER BY fecha_inicio DESC
        ");

        $stmt->execute([$idUsuario, $idUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

