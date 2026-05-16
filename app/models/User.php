<?php
require_once __DIR__ . '/../../config/conexion_db.php';

class User {

    public static function findByEmail($email) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function findById($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($nombre, $email, $password) {
        global $pdo;
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("
            INSERT INTO usuarios
            (nombre, email, contrasenia_hash, fecha_registro, estado_cuenta, tipo_usuario, saldo_monedas)
            VALUES (?, ?, ?, NOW(), 'activa', 'registrado', 5)
        ");
        return $stmt->execute([$nombre, $email, $hash]);
    }

    public static function updatePerfil($id, $nombre, $email, $descripcion = null) {
        global $pdo;
        $stmt = $pdo->prepare("
            UPDATE usuarios
            SET nombre = ?, email = ?, descripcion = ?
            WHERE id_usuario = ?
        ");
        return $stmt->execute([$nombre, $email, $descripcion, $id]);
    }

    /**
     * Sube de 'registrado' a 'suscrito' (lo llama el controlador de
     * suscripciones tras un pago correcto).
     */
    public static function marcarComoSuscrito($id) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE usuarios SET tipo_usuario = 'suscrito' WHERE id_usuario = ?");
        return $stmt->execute([$id]);
    }

    public static function marcarComoRegistrado($id) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE usuarios SET tipo_usuario = 'registrado' WHERE id_usuario = ?");
        return $stmt->execute([$id]);
    }

    public static function esSuscrito($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT tipo_usuario FROM usuarios WHERE id_usuario = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() === 'suscrito';
    }
}
