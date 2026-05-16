<?php
require_once __DIR__ . '/../../config/conexion_db.php';

class Notificacion {

    public static function crear($idUsuario, $tipo, $titulo, $contenido = '', $enlace = null) {
        global $pdo;
        $stmt = $pdo->prepare("
            INSERT INTO notificaciones (id_usuario, tipo, titulo, contenido, enlace, leida, fecha)
            VALUES (?, ?, ?, ?, ?, 0, NOW())
        ");
        return $stmt->execute([$idUsuario, $tipo, $titulo, $contenido, $enlace]);
    }

    public static function listarPorUsuario($idUsuario, $limite = 50) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT * FROM notificaciones
            WHERE id_usuario = ?
            ORDER BY fecha DESC
            LIMIT " . (int)$limite
        );
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function contarNoLeidas($idUsuario) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM notificaciones WHERE id_usuario = ? AND leida = 0");
        $stmt->execute([$idUsuario]);
        return (int)$stmt->fetchColumn();
    }

    public static function marcarTodasLeidas($idUsuario) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE notificaciones SET leida = 1 WHERE id_usuario = ?");
        return $stmt->execute([$idUsuario]);
    }

    public static function marcarLeida($idNotificacion, $idUsuario) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE notificaciones SET leida = 1 WHERE id_notificacion = ? AND id_usuario = ?");
        return $stmt->execute([$idNotificacion, $idUsuario]);
    }
}
