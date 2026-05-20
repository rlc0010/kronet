<?php
require_once __DIR__ . '/../../config/conexion_db.php';

/**
 * Valoraciones de usuario a usuario (1–5 estrellas).
 * Solo se permite una valoración por par autor→destino,
 * lo que obliga a que sea reflexionada y no se repita.
 */
class Valoracion {

    public static function crear($idAutor, $idDestino, $puntuacion, $comentario) {
        global $pdo;
        $stmt = $pdo->prepare("
            INSERT INTO valoraciones
            (id_usuario_autor, id_usuario_destino, puntuacion, comentario, fecha)
            VALUES (?, ?, ?, ?, NOW())
        ");
        return $stmt->execute([$idAutor, $idDestino, $puntuacion, $comentario]);
    }

    public static function findByDestinatario($idUsuario) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT v.*, u.nombre AS nombre_autor
            FROM valoraciones v
            JOIN usuarios u ON v.id_usuario_autor = u.id_usuario
            WHERE v.id_usuario_destino = ?
            ORDER BY v.fecha DESC
        ");
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function mediaUsuario($idUsuario) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT AVG(puntuacion) AS media, COUNT(*) AS total
            FROM valoraciones
            WHERE id_usuario_destino = ?
        ");
        $stmt->execute([$idUsuario]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$res) return ['media' => 0, 'total' => 0];
        return ['media' => (float)($res['media'] ?? 0), 'total' => (int)($res['total'] ?? 0)];
    }

    /** Para evitar que un mismo usuario valore más de una vez al mismo destinatario. */
    public static function yaValorado($idAutor, $idDestino) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT COUNT(*) FROM valoraciones
            WHERE id_usuario_autor = ? AND id_usuario_destino = ?
        ");
        $stmt->execute([$idAutor, $idDestino]);
        return $stmt->fetchColumn() > 0;
    }
}
