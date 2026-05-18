<?php
require_once __DIR__ . '/../../config/conexion_db.php';

/**
 * Gestiona la lista de contactos entre usuarios.
 * La relación es unidireccional en BD: agregar a alguien no significa
 * que ese alguien te tenga a ti. La UI lo muestra de forma recíproca
 * pero cada usuario decide quién está en su lista.
 */
class Contacto {

    public static function agregar($idUsuario, $idAmigo) {
        global $pdo;
        if ($idUsuario == $idAmigo) return false;
        try {
            $stmt = $pdo->prepare("
                INSERT INTO contactos (id_usuario, id_usuario_amigo, fecha)
                VALUES (?, ?, NOW())
            ");
            return $stmt->execute([$idUsuario, $idAmigo]);
        } catch (PDOException $e) {
            return false; // ya existía
        }
    }

    public static function eliminar($idUsuario, $idAmigo) {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM contactos WHERE id_usuario = ? AND id_usuario_amigo = ?");
        return $stmt->execute([$idUsuario, $idAmigo]);
    }

    public static function esContacto($idUsuario, $idAmigo) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM contactos WHERE id_usuario = ? AND id_usuario_amigo = ?");
        $stmt->execute([$idUsuario, $idAmigo]);
        return $stmt->fetchColumn() > 0;
    }

    public static function listar($idUsuario) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT u.id_usuario, u.nombre, u.descripcion, c.fecha
            FROM contactos c
            JOIN usuarios u ON c.id_usuario_amigo = u.id_usuario
            WHERE c.id_usuario = ?
            ORDER BY c.fecha DESC
        ");
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Contactos con mas datos */
    public static function listarConDetalles($idUsuario) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT
                u.id_usuario,
                u.nombre,
                u.descripcion,
                u.tipo_usuario,
                c.fecha,
                COALESCE(AVG(v.puntuacion), 0)    AS media_valoraciones,
                COUNT(DISTINCT v.id_valoracion)   AS total_valoraciones,
                COUNT(DISTINCT a.id_anuncio)      AS total_anuncios
            FROM contactos c
            JOIN usuarios u ON c.id_usuario_amigo = u.id_usuario
            LEFT JOIN valoraciones v ON v.id_usuario_destino = u.id_usuario
            LEFT JOIN anuncios a    ON a.id_usuario = u.id_usuario AND a.estado = 'activo'
            WHERE c.id_usuario = ?
            GROUP BY u.id_usuario, u.nombre, u.descripcion, u.tipo_usuario, c.fecha
            ORDER BY c.fecha DESC
        ");
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
