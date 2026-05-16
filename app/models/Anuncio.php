<?php
require_once __DIR__ . '/../../config/conexion_db.php';
require_once __DIR__ . '/../helpers/PrecioCalculator.php';

class Anuncio {

    public static function findById($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM anuncios WHERE id_anuncio = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function findByIdConUsuario($id) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT a.*, u.nombre AS nombre_usuario
            FROM anuncios a
            JOIN usuarios u ON a.id_usuario = u.id_usuario
            WHERE a.id_anuncio = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function findByUsuario($id_usuario) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT * FROM anuncios
            WHERE id_usuario = ?
            ORDER BY fecha_publicacion DESC
        ");
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Inserta un anuncio nuevo. El precio en créditos se calcula con
     * PrecioCalculator a partir de la duración y la categoría.
     */
    public static function create($id_usuario, $titulo, $descripcion, $tipo_anuncio,
                                  $categoria, $duracion_estimada, $plazas_totales = 1) {
        global $pdo;

        $duracion_estimada = max(1, (int)$duracion_estimada);
        $plazas_totales    = max(1, (int)$plazas_totales);
        $precio_creditos   = PrecioCalculator::calcular($categoria, $duracion_estimada);

        $stmt = $pdo->prepare("
            INSERT INTO anuncios
            (id_usuario, titulo, descripcion, tipo_anuncio, categoria,
             duracion_estimada, precio_creditos, plazas_totales, plazas_ocupadas,
             fecha_publicacion, estado, destacado)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, NOW(), 'activo', 0)
        ");
        $stmt->execute([
            $id_usuario, $titulo, $descripcion, $tipo_anuncio, $categoria,
            $duracion_estimada, $precio_creditos, $plazas_totales
        ]);
        return $pdo->lastInsertId();
    }

    public static function update($id_anuncio, $titulo, $descripcion, $tipo_anuncio,
                                  $categoria, $duracion_estimada, $plazas_totales = 1) {
        global $pdo;

        $duracion_estimada = max(1, (int)$duracion_estimada);
        $plazas_totales    = max(1, (int)$plazas_totales);
        $precio_creditos   = PrecioCalculator::calcular($categoria, $duracion_estimada);

        $stmt = $pdo->prepare("
            UPDATE anuncios
            SET titulo = ?, descripcion = ?, tipo_anuncio = ?, categoria = ?,
                duracion_estimada = ?, precio_creditos = ?, plazas_totales = GREATEST(?, plazas_ocupadas)
            WHERE id_anuncio = ?
        ");
        return $stmt->execute([
            $titulo, $descripcion, $tipo_anuncio, $categoria,
            $duracion_estimada, $precio_creditos, $plazas_totales, $id_anuncio
        ]);
    }

    public static function delete($id_anuncio) {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM anuncios WHERE id_anuncio = ?");
        return $stmt->execute([$id_anuncio]);
    }

    /**
     * Búsqueda con filtros opcionales y paginación.
     */
    public static function search($busqueda = null, $tipo_anuncio = null, $categoria = null,
                                  $limit = 20, $offset = 0) {
        global $pdo;

        $sql = "
            SELECT a.*, u.nombre AS nombre_usuario
            FROM anuncios a
            JOIN usuarios u ON a.id_usuario = u.id_usuario
            WHERE a.estado = 'activo'
        ";
        $params = [];

        if ($busqueda) {
            $sql .= " AND (a.titulo LIKE ? OR a.descripcion LIKE ?)";
            $params[] = "%$busqueda%";
            $params[] = "%$busqueda%";
        }
        if ($tipo_anuncio) {
            $sql .= " AND a.tipo_anuncio = ?";
            $params[] = $tipo_anuncio;
        }
        if ($categoria) {
            $sql .= " AND a.categoria = ?";
            $params[] = $categoria;
        }

        $sql .= " ORDER BY a.destacado DESC, a.fecha_publicacion DESC LIMIT " .
                (int)$limit . " OFFSET " . (int)$offset;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function countSearch($busqueda = null, $tipo_anuncio = null, $categoria = null) {
        global $pdo;
        $sql = "SELECT COUNT(*) FROM anuncios WHERE estado = 'activo'";
        $params = [];

        if ($busqueda) {
            $sql .= " AND (titulo LIKE ? OR descripcion LIKE ?)";
            $params[] = "%$busqueda%";
            $params[] = "%$busqueda%";
        }
        if ($tipo_anuncio) {
            $sql .= " AND tipo_anuncio = ?";
            $params[] = $tipo_anuncio;
        }
        if ($categoria) {
            $sql .= " AND categoria = ?";
            $params[] = $categoria;
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Marca un anuncio como destacado durante N días.
     */
    public static function destacar($id_anuncio, $dias = 7) {
        global $pdo;
        $hasta = date('Y-m-d H:i:s', strtotime('+' . (int)$dias . ' day'));
        $stmt = $pdo->prepare("
            UPDATE anuncios
            SET destacado = 1, destacado_hasta = ?
            WHERE id_anuncio = ?
        ");
        return $stmt->execute([$hasta, $id_anuncio]);
    }

    /**
     * Limpia destacados caducados (se llama periódicamente, p.ej. al cargar buscar).
     */
    public static function limpiarDestacadosCaducados() {
        global $pdo;
        $stmt = $pdo->prepare("
            UPDATE anuncios
            SET destacado = 0
            WHERE destacado = 1 AND destacado_hasta IS NOT NULL AND destacado_hasta < NOW()
        ");
        return $stmt->execute();
    }

    /**
     * Incrementa el contador de plazas ocupadas. Devuelve true si quedaba sitio.
     * Si se llena, marca el anuncio como 'completo'.
     */
    public static function ocuparPlaza($id_anuncio) {
        global $pdo;

        $stmt = $pdo->prepare("
            UPDATE anuncios
            SET plazas_ocupadas = plazas_ocupadas + 1,
                estado = CASE WHEN (plazas_ocupadas + 1) >= plazas_totales THEN 'completo' ELSE estado END
            WHERE id_anuncio = ? AND plazas_ocupadas < plazas_totales AND estado = 'activo'
        ");
        $stmt->execute([$id_anuncio]);
        return $stmt->rowCount() > 0;
    }

    public static function liberarPlaza($id_anuncio) {
        global $pdo;
        $stmt = $pdo->prepare("
            UPDATE anuncios
            SET plazas_ocupadas = GREATEST(plazas_ocupadas - 1, 0),
                estado = CASE WHEN estado = 'completo' THEN 'activo' ELSE estado END
            WHERE id_anuncio = ?
        ");
        return $stmt->execute([$id_anuncio]);
    }

    public static function contarPorUsuario($id_usuario) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM anuncios WHERE id_usuario = ?");
        $stmt->execute([$id_usuario]);
        return (int)$stmt->fetchColumn();
    }
}
