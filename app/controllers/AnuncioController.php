<?php
// Usamos __DIR__ para construir rutas absolutas
// Esto evita errores cuando el archivo se carga desde distintos puntos de entrada
require_once __DIR__ . '/../models/Anuncio.php';

class AnuncioController {

    // Muestra el formulario vacío para crear un nuevo anuncio
    public function showCrear() {
        require __DIR__ . '/../views/anuncios/crear.php';
    }

    // Recoge los datos del formulario de creación y los guarda en la BD
    // Devuelve JSON para que el JS del frontend pueda mostrar el resultado
    public function crear() {
        header('Content-Type: application/json');

        // Recogemos los datos del formulario, null si no vienen
        $id_usuario       = $_SESSION['user'];
        $titulo           = $_POST['titulo'] ?? null;
        $descripcion      = $_POST['descripcion'] ?? null;
        $tipo_anuncio     = $_POST['tipo_anuncio'] ?? null;
        $categoria        = $_POST['categoria'] ?? null;
        $duracion_estimada = $_POST['duracion_estimada'] ?? null;

        // Comprobamos que todos los campos obligatorios están rellenos
        if (!$titulo || !$descripcion || !$tipo_anuncio || !$categoria || !$duracion_estimada) {
            echo json_encode(['ok' => false, 'msg' => 'Todos los campos son obligatorios']);
            return;
        }

        // Llamamos al modelo para insertar el anuncio en la BD
        Anuncio::create($id_usuario, $titulo, $descripcion, $tipo_anuncio, $categoria, $duracion_estimada);
        echo json_encode(['ok' => true, 'msg' => 'Anuncio creado correctamente']);
    }

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
        require __DIR__ . '/../views/anuncios/editar.php';
    }

    // Procesa el formulario de edición y guarda los cambios en la BD
    // Devuelve JSON para que el JS del frontend pueda mostrar el resultado
    public function editar($id) {
        header('Content-Type: application/json');

        // Recogemos los datos del formulario
        $titulo           = $_POST['titulo'] ?? null;
        $descripcion      = $_POST['descripcion'] ?? null;
        $tipo_anuncio     = $_POST['tipo_anuncio'] ?? null;
        $categoria        = $_POST['categoria'] ?? null;
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

    // Elimina un anuncio de la BD
    // Solo puede eliminarlo el usuario que lo creó
    public function eliminar($id) {
        header('Content-Type: application/json');

        // Buscamos el anuncio para verificar que existe y que pertenece al usuario
        $anuncio = Anuncio::findById($id);

        if (!$anuncio) {
            echo json_encode(['ok' => false, 'msg' => 'Anuncio no encontrado']);
            return;
        }

        // Comprobamos que el anuncio pertenece al usuario logueado
        if ($anuncio['id_usuario'] != $_SESSION['user']) {
            echo json_encode(['ok' => false, 'msg' => 'No tienes permiso para eliminar este anuncio']);
            return;
        }

        // Llamamos al modelo para eliminar el anuncio
        Anuncio::delete($id);
        echo json_encode(['ok' => true, 'msg' => 'Anuncio eliminado correctamente']);
    }

    // Muestra todos los anuncios publicados por el usuario logueado
    public function misAnuncios() {
        // Obtenemos los anuncios del usuario de la BD
        $anuncios = Anuncio::findByUsuario($_SESSION['user']);

        // Cargamos la vista pasándole el array de anuncios
        require __DIR__ . '/../views/anuncios/mis_anuncios.php';
    }

    // Muestra la página de búsqueda y procesa los filtros
    public function showBuscar() {
        // Recogemos los parámetros GET y convertimos cadenas vacías a null
        // para que el modelo no aplique filtros vacíos
        $busqueda     = !empty($_GET['busqueda'])     ? $_GET['busqueda']     : null;
        $tipo_anuncio = !empty($_GET['tipo_anuncio']) ? $_GET['tipo_anuncio'] : null;
        $categoria    = !empty($_GET['categoria'])    ? $_GET['categoria']    : null;

        // Buscamos los anuncios aplicando los filtros que haya
        $anuncios = Anuncio::search($busqueda, $tipo_anuncio, $categoria);

        // Cargamos la vista pasándole los resultados
        require __DIR__ . '/../views/anuncios/buscar.php';
    }


    public function cambiarEstado() {
        Anuncio::toggleEstado($_POST['id']);
    }

}
