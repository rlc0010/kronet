<?php
// Necesitamos la conexión a la BD ($pdo)
require_once __DIR__ . '/../../config/conexion_db.php';

class Anuncio {

    // Busca un anuncio por su ID
    // Se usa por ejemplo para cargar los datos en el formulario de edición
    public static function findById($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM anuncios WHERE id_anuncio = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Devuelve todos los anuncios de un usuario concreto
    // Se usa para mostrar "Mis anuncios" en el perfil
    // Ordenamos por fecha descendente para ver primero los más recientes
    public static function findByUsuario($id_usuario) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM anuncios WHERE id_usuario = ? ORDER BY fecha_publicacion DESC");
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Crea un nuevo anuncio en la BD
    // La fecha se pone automáticamente con NOW() y el estado inicial es 'activo'
    public static function create($id_usuario, $titulo, $descripcion, $tipo_anuncio, $categoria, $duracion_estimada) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO anuncios 
            (id_usuario, titulo, descripcion, tipo_anuncio, categoria, duracion_estimada, fecha_publicacion, estado, destacado) 
            VALUES (?, ?, ?, ?, ?, ?, NOW(), 'activo', 0)");
        $stmt->execute([$id_usuario, $titulo, $descripcion, $tipo_anuncio, $categoria, $duracion_estimada]);
    }

    // Actualiza los datos de un anuncio existente
    // Solo se pueden modificar estos campos, no la fecha ni el estado
    public static function update($id_anuncio, $titulo, $descripcion, $tipo_anuncio, $categoria, $duracion_estimada) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE anuncios 
            SET titulo = ?, descripcion = ?, tipo_anuncio = ?, categoria = ?, duracion_estimada = ? 
            WHERE id_anuncio = ?");
        $stmt->execute([$titulo, $descripcion, $tipo_anuncio, $categoria, $duracion_estimada, $id_anuncio]);
    }

    // Elimina un anuncio de la BD por su ID
    public static function delete($id_anuncio) {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM anuncios WHERE id_anuncio = ?");
        $stmt->execute([$id_anuncio]);
    }

    // Busca anuncios por palabra clave y filtros opcionales
    // Se usa en la página de búsqueda
    // Los parámetros son opcionales: si vienen a null no se aplica ese filtro
    public static function search($busqueda = null, $tipo_anuncio = null, $categoria = null) {
        global $pdo;

        // Empezamos con una query base que solo devuelve anuncios activos
        $sql = "SELECT * FROM anuncios WHERE estado = 'activo'";
        $params = [];

        // Si hay palabra clave buscamos en título y descripción
        if ($busqueda) {
            $sql .= " AND (titulo LIKE ? OR descripcion LIKE ?)";
            $params[] = "%$busqueda%";
            $params[] = "%$busqueda%";
        }

        // Si hay filtro de tipo (oferta/demanda) lo aplicamos
        if ($tipo_anuncio) {
            $sql .= " AND tipo_anuncio = ?";
            $params[] = $tipo_anuncio;
        }

        // Si hay filtro de categoría lo aplicamos
        if ($categoria) {
            $sql .= " AND categoria = ?";
            $params[] = $categoria;
        }

        // Los destacados aparecen primero, luego ordenamos por fecha descendente
        $sql .= " ORDER BY destacado DESC, fecha_publicacion DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function toggleEstado($id) {
        global $pdo;

        $stmt = $pdo->prepare("
        UPDATE anuncios
        SET estado = IF(estado='activa','inactiva','activa')
        WHERE id_anuncio = ?
        ");

    return $stmt->execute([$id]);
    }

}
