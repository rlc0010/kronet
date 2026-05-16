<?php
require_once __DIR__ . '/../../config/conexion_db.php';

class Bloqueo {

    public static function bloquear($idUsuario, $idBloqueado) {
        global $pdo;
        if ($idUsuario == $idBloqueado) return false;
        try {
            $stmt = $pdo->prepare("
                INSERT INTO bloqueos (id_usuario, id_usuario_bloqueado, fecha)
                VALUES (?, ?, NOW())
            ");
            return $stmt->execute([$idUsuario, $idBloqueado]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function desbloquear($idUsuario, $idBloqueado) {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM bloqueos WHERE id_usuario = ? AND id_usuario_bloqueado = ?");
        return $stmt->execute([$idUsuario, $idBloqueado]);
    }

    public static function estaBloqueado($idUsuario, $idBloqueado) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM bloqueos WHERE id_usuario = ? AND id_usuario_bloqueado = ?");
        $stmt->execute([$idUsuario, $idBloqueado]);
        return $stmt->fetchColumn() > 0;
    }

    public static function listar($idUsuario) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT u.id_usuario, u.nombre, b.fecha
            FROM bloqueos b
            JOIN usuarios u ON b.id_usuario_bloqueado = u.id_usuario
            WHERE b.id_usuario = ?
            ORDER BY b.fecha DESC
        ");
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
