<?php
require_once __DIR__ . '/../../config/conexion_db.php';

class Valoracion {

    // Crea una nueva valoración entre usuarios tras un intercambio
    public static function crear($idAutor, $idDestino, $puntuacion, $comentario) {
        global $pdo;
        $stmt = $pdo->prepare("
            INSERT INTO valoraciones
            (id_usuario_autor, id_usuario_destino, puntuacion, comentario, fecha)
            VALUES (?, ?, ?, ?, NOW())
        ");
        return $stmt->execute([
            $idAutor,
            $idDestino,
            $puntuacion,
            $comentario
        ]);
    }

    // Obtiene todas las valoraciones recibidas por un usuario
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

    // Calcula la media de puntuación de un usuario
    public static function mediaUsuario($idUsuario) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT AVG(puntuacion) AS media, COUNT(*) AS total
            FROM valoraciones
            WHERE id_usuario_destino = ?
        ");
        $stmt->execute([$idUsuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Comprueba si ya existe una valoración del autor hacia el destino
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
