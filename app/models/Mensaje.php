<?php
class Mensaje {

    public static function enviar($emisor, $receptor, $texto) {
        global $pdo;

        $stmt = $pdo->prepare("
            INSERT INTO mensajes
            (id_emisor, id_receptor, mensaje, fecha_envio)
            VALUES (?, ?, ?, NOW())
        ");

        return $stmt->execute([$emisor, $receptor, $texto]);
    }

    public static function obtenerConversacion($u1, $u2) {
        global $pdo;

        $stmt = $pdo->prepare("
            SELECT *
            FROM mensajes
            WHERE (id_emisor=? AND id_receptor=?)
               OR (id_emisor=? AND id_receptor=?)
            ORDER BY fecha_envio
        ");

        $stmt->execute([$u1,$u2,$u2,$u1]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}