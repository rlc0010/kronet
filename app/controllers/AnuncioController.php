<?php
// Necesitamos el modelo para acceder a la BD
require_once '../app/models/Anuncio.php';

class AnuncioController {

    // Muestra el formulario de edición con los datos actuales del anuncio
    public function showEditar($id) {
        // Buscamos el anuncio en la BD por su ID
        $anuncio = Anuncio::findById($id);

        // Si no existe el anuncio redirigimos al inicio
        if (!$anuncio) {
            header("Location: /kronet/public/");
            exit;
        }

        // Comprobamos que el anuncio pertenece al usuario logueado
        // para que nadie pueda editar anuncios ajenos
        if ($anuncio['id_usuario'] != $_SESSION['user']) {
            header("Location: /kronet/public/");
            exit;
        }

        // Cargamos la vista pasándole los datos del anuncio
        require '../app/views/anuncios/editar.php';
    }

    // Procesa el formulario de edición y guarda los cambios en la BD
    public function editar($id) {
        // Recogemos los datos del formulario
        $titulo = $_POST['titulo'] ?? null;
        $descripcion = $_POST['descripcion'] ?? null;
        $tipo_anuncio = $_POST['tipo_anuncio'] ?? null;
        $categoria = $_POST['categoria'] ?? null;
        $duracion_estimada = $_POST['duracion_estimada'] ?? null;

        // Comprobamos que todos los campos obligatorios están rellenos
        if (!$titulo || !$descripcion || !$tipo_anuncio || !$categoria || !$duracion_estimada) {
            echo json_encode(['ok' => false, 'msg' => 'Todos los campos son obligatorios']);
            return;
        }

        // Llamamos al modelo para actualizar el anuncio en la BD
        Anuncio::update($id, $titulo, $descripcion, $tipo_anuncio, $categoria, $duracion_estimada);

        echo json_encode(['ok' => true, 'msg' => 'Anuncio actualizado correctamente']);
    }

    // Muestra todos los anuncios del usuario logueado
    public function misAnuncios() {
        $anuncios = Anuncio::findByUsuario($_SESSION['user']);
        require '../app/views/anuncios/mis_anuncios.php';
    }

    // Crea un nuevo anuncio
    public function crear() {
        $id_usuario = $_SESSION['user'];
        $titulo = $_POST['titulo'] ?? null;
        $descripcion = $_POST['descripcion'] ?? null;
        $tipo_anuncio = $_POST['tipo_anuncio'] ?? null;
        $categoria = $_POST['categoria'] ?? null;
        $duracion_estimada = $_POST['duracion_estimada'] ?? null;

        if (!$titulo || !$descripcion || !$tipo_anuncio || !$categoria || !$duracion_estimada) {
            echo json_encode(['ok' => false, 'msg' => 'Todos los campos son obligatorios']);
            return;
        }

        Anuncio::create($id_usuario, $titulo, $descripcion, $tipo_anuncio, $categoria, $duracion_estimada);
        echo json_encode(['ok' => true, 'msg' => 'Anuncio creado correctamente']);
    }

    // Elimina un anuncio
    public function eliminar($id) {
        $anuncio = Anuncio::findById($id);

        // Comprobamos que el anuncio pertenece al usuario logueado
        if ($anuncio['id_usuario'] != $_SESSION['user']) {
            echo json_encode(['ok' => false, 'msg' => 'No tienes permiso para eliminar este anuncio']);
            return;
        }

        Anuncio::delete($id);
        echo json_encode(['ok' => true, 'msg' => 'Anuncio eliminado correctamente']);
    }
}