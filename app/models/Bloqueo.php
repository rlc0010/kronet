<?php
require_once __DIR__ . '/../../config/conexion_db.php';

/**
 * Gestiona los bloqueos entre usuarios.
 * Un bloqueo impide mensajes, solicitudes de intercambio y oculta
 * los anuncios del bloqueado en la búsqueda pública.
 */
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

    /** true si existe bloqueo en CUALQUIER dirección entre los dos usuarios */
    public static function hayBloqueo($idA, $idB) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT COUNT(*) FROM bloqueos
            WHERE (id_usuario = ? AND id_usuario_bloqueado = ?)
               OR (id_usuario = ? AND id_usuario_bloqueado = ?)
        ");
        $stmt->execute([$idA, $idB, $idB, $idA]);
        return $stmt->fetchColumn() > 0;
    }

    /** IDs de todos los usuarios con los que existe un bloqueo (en cualquier dirección) */
    public static function listarIdsBloqueo($idUsuario) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT id_usuario_bloqueado AS id FROM bloqueos WHERE id_usuario = ?
            UNION
            SELECT id_usuario          AS id FROM bloqueos WHERE id_usuario_bloqueado = ?
        ");
        $stmt->execute([$idUsuario, $idUsuario]);
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'id');
    }
}
