<?php
require_once __DIR__ . '/../../config/conexion_db.php';

class Intercambio {

    // Devuelve todas las ofertas recibidas en los anuncios del usuario
    public static function findOfertasRecibidas($id_usuario) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT i.*, a.titulo AS titulo_anuncio, u.nombre AS nombre_ofertante
            FROM intercambios i
            JOIN anuncios a ON i.id_anuncio = a.id_anuncio
            JOIN usuarios u ON i.id_usuario_ofertante = u.id_usuario
            WHERE a.id_usuario = ?
            AND i.estado = 'pendiente'
        ");
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Acepta una oferta: cambia el estado a confirmado
    public static function aceptar($id_intercambio) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE intercambios SET estado = 'confirmado' WHERE id_intercambio = ?");
        return $stmt->execute([$id_intercambio]);
    }

    // Rechaza una oferta: cambia el estado a cancelado
    public static function rechazar($id_intercambio) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE intercambios SET estado = 'cancelado' WHERE id_intercambio = ?");
        return $stmt->execute([$id_intercambio]);
    }

    // Busca un intercambio por su ID
    public static function findById($id_intercambio) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM intercambios WHERE id_intercambio = ?");
        $stmt->execute([$id_intercambio]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}