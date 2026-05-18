<?php
require_once __DIR__ . '/../../config/conexion_db.php';

/**
 * Registro de pagos simulados (no hay pasarela real).
 * Guarda el historial de transacciones ficticiamente para tener trazabilidad
 * en la demo: suscripciones y destacados de anuncios.
 */
class Pago {

    public const PRECIO_DESTACADO = 2.50;

    public const METODOS = ['tarjeta', 'paypal', 'bizum'];

    public static function registrar($idUsuario, $tipoPago, $metodoPago, $importe,
                                     $referencia = null, $estado = 'completado') {
        global $pdo;
        $stmt = $pdo->prepare("
            INSERT INTO pagos
            (id_usuario, tipo_pago, metodo_pago, importe, fecha_pago, estado_pago, referencia)
            VALUES (?, ?, ?, ?, NOW(), ?, ?)
        ");
        $stmt->execute([$idUsuario, $tipoPago, $metodoPago, $importe, $estado, $referencia]);
        return $pdo->lastInsertId();
    }

    public static function listarPorUsuario($idUsuario) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT * FROM pagos WHERE id_usuario = ? ORDER BY fecha_pago DESC
        ");
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
