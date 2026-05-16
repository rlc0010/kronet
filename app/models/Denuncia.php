<?php
require_once __DIR__ . '/../../config/conexion_db.php';

class Denuncia {

    public const MOTIVOS = [
        'spam'            => 'Spam o publicidad engañosa',
        'inapropiado'     => 'Contenido inapropiado u ofensivo',
        'fraude'          => 'Posible fraude o estafa',
        'duplicado'       => 'Anuncio duplicado',
        'ilegal'          => 'Contenido ilegal',
        'otro'            => 'Otro motivo',
    ];

    public static function crear($idUsuario, $idAnuncio, $motivo, $descripcion = '') {
        global $pdo;
        $stmt = $pdo->prepare("
            INSERT INTO denuncias (id_usuario, id_anuncio, motivo, descripcion, fecha, estado)
            VALUES (?, ?, ?, ?, NOW(), 'pendiente')
        ");
        return $stmt->execute([$idUsuario, $idAnuncio, $motivo, $descripcion]);
    }

    public static function yaDenunciado($idUsuario, $idAnuncio) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT COUNT(*) FROM denuncias
            WHERE id_usuario = ? AND id_anuncio = ? AND estado = 'pendiente'
        ");
        $stmt->execute([$idUsuario, $idAnuncio]);
        return $stmt->fetchColumn() > 0;
    }

    public static function listarPendientes() {
        global $pdo;
        $stmt = $pdo->query("
            SELECT d.*, a.titulo AS titulo_anuncio, u.nombre AS nombre_denunciante
            FROM denuncias d
            LEFT JOIN anuncios a ON d.id_anuncio = a.id_anuncio
            JOIN usuarios u ON d.id_usuario = u.id_usuario
            WHERE d.estado = 'pendiente'
            ORDER BY d.fecha DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
