<?php
// Necesitamos la conexión a la BD ($pdo)
require_once '../config/conexion_db.php';

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
    public static function findByUsuario($id_usuario) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM anuncios WHERE id_usuario = ?");
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
}