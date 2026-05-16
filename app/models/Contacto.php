<?php
require_once __DIR__ . '/../../config/conexion_db.php';

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
            return false; // ya existía (clave única)
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
}
