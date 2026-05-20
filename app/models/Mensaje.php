<?php
require_once __DIR__ . '/../../config/conexion_db.php';

/**
 * Modelo de mensajes con lógica de "chat por anuncio".
 *
 * Una conversación se identifica por (id_anuncio, par_de_usuarios).
 * El par de usuarios es {dueño_del_anuncio, otro_usuario}, así que para
 * cualquier anuncio cada usuario externo tiene exactamente UNA
 * conversación con su dueño.
 */
class Mensaje {

    public static function enviar($emisor, $receptor, $texto, $idAnuncio = null) {
        global $pdo;
        $stmt = $pdo->prepare("
            INSERT INTO mensajes
            (id_anuncio, id_emisor, id_receptor, contenido, fecha_envio, leido)
            VALUES (?, ?, ?, ?, NOW(), 0)
        ");
        return $stmt->execute([$idAnuncio, $emisor, $receptor, $texto]);
    }

    /**
     * Devuelve los mensajes de una conversación de un anuncio entre dos usuarios,
     * en orden cronológico.
     */
    public static function conversacion($idAnuncio, $u1, $u2) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT *
            FROM mensajes
            WHERE id_anuncio = ?
              AND (
                    (id_emisor = ? AND id_receptor = ?)
                 OR (id_emisor = ? AND id_receptor = ?)
              )
            ORDER BY fecha_envio ASC
        ");
        $stmt->execute([$idAnuncio, $u1, $u2, $u2, $u1]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Marca como leídos todos los mensajes recibidos por $usuario en una
     * conversación concreta.
     */
    public static function marcarConversacionLeida($idAnuncio, $usuario, $otroUsuario) {
        global $pdo;
        $stmt = $pdo->prepare("
            UPDATE mensajes
            SET leido = 1
            WHERE id_anuncio = ? AND id_receptor = ? AND id_emisor = ?
        ");
        return $stmt->execute([$idAnuncio, $usuario, $otroUsuario]);
    }

    /**
     * Lista las conversaciones del usuario.
     * Cada fila = una conversación (anuncio + otro usuario), con el último
     * mensaje y el contador de no leídos.
     *
     * Devolvemos un agregado por conversación; el "último mensaje" se obtiene
     * con una consulta extra por conversación para mantener portabilidad
     * entre motores (sin SEPARATOR de MySQL ni window functions).
     */
    public static function listarConversaciones($idUsuario) {
        global $pdo;

        // Paso 1: agrupar las conversaciones del usuario (id_anuncio + otro_usuario)
        $sql = "
            SELECT
                id_anuncio,
                CASE WHEN id_emisor = :uid THEN id_receptor ELSE id_emisor END AS otro_usuario,
                MAX(fecha_envio) AS fecha_ultimo,
                SUM(CASE WHEN id_receptor = :uid AND leido = 0 THEN 1 ELSE 0 END) AS no_leidos
            FROM mensajes
            WHERE (id_emisor = :uid OR id_receptor = :uid)
              AND id_anuncio IS NOT NULL
            GROUP BY id_anuncio,
                     CASE WHEN id_emisor = :uid THEN id_receptor ELSE id_emisor END
            ORDER BY fecha_ultimo DESC
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':uid' => $idUsuario]);
        $convs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$convs) return [];

        // Paso 2: enriquecer cada conversación con título del anuncio, nombre del
        // otro usuario y último mensaje.
        $resultado = [];
        $sqlUltimo = $pdo->prepare("
            SELECT contenido FROM mensajes
            WHERE id_anuncio = ?
              AND ((id_emisor = ? AND id_receptor = ?) OR (id_emisor = ? AND id_receptor = ?))
            ORDER BY fecha_envio DESC LIMIT 1
        ");
        $sqlAnuncio = $pdo->prepare("SELECT titulo, id_usuario FROM anuncios WHERE id_anuncio = ?");
        $sqlUsuario = $pdo->prepare("SELECT nombre FROM usuarios WHERE id_usuario = ?");

        foreach ($convs as $c) {
            $sqlUltimo->execute([
                $c['id_anuncio'],
                $idUsuario, $c['otro_usuario'],
                $c['otro_usuario'], $idUsuario
            ]);
            $ultimo = $sqlUltimo->fetchColumn() ?: '';

            $sqlAnuncio->execute([$c['id_anuncio']]);
            $a = $sqlAnuncio->fetch(PDO::FETCH_ASSOC) ?: ['titulo' => null, 'id_usuario' => null];

            $sqlUsuario->execute([$c['otro_usuario']]);
            $nombreOtro = $sqlUsuario->fetchColumn() ?: 'Usuario';

            $resultado[] = [
                'id_anuncio'       => (int)$c['id_anuncio'],
                'otro_usuario'     => (int)$c['otro_usuario'],
                'titulo_anuncio'   => $a['titulo'],
                'id_dueno_anuncio' => $a['id_usuario'],
                'nombre_otro'      => $nombreOtro,
                'ultimo_mensaje'   => $ultimo,
                'fecha_ultimo'     => $c['fecha_ultimo'],
                'no_leidos'        => (int)$c['no_leidos'],
            ];
        }

        return $resultado;
    }

    public static function contarNoLeidos($idUsuario) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM mensajes WHERE id_receptor = ? AND leido = 0");
        $stmt->execute([$idUsuario]);
        return (int)$stmt->fetchColumn();
    }
}
