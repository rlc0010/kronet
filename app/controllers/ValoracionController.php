<?php
require_once __DIR__ . '/../models/Valoracion.php';
require_once __DIR__ . '/../models/Intercambio.php';

class ValoracionController {

    // Muestra el formulario para dejar una valoración a otro usuario
    public function showCrear() {
        $idDestino = $_GET['id_usuario'] ?? null;

        if (!$idDestino || !is_numeric($idDestino)) {
            header("Location: /kronet/public/");
            exit;
        }

        // No puedes valorarte a ti mismo
        if ($idDestino == $_SESSION['id_usuario']) {
            header("Location: /kronet/public/");
            exit;
        }

        require __DIR__ . '/../views/valoraciones/crear.php';
    }

    // Procesa y guarda la valoración
    public function crear() {
        header('Content-Type: application/json');

        $idAutor    = $_SESSION['id_usuario'];
        $idDestino  = $_POST['id_destino'] ?? null;
        $puntuacion = $_POST['puntuacion'] ?? null;
        $comentario = trim($_POST['comentario'] ?? '');

        if (!$idDestino || !is_numeric($idDestino)) {
            echo json_encode(['ok' => false, 'msg' => 'Usuario inválido']);
            return;
        }

        if ($idAutor == $idDestino) {
            echo json_encode(['ok' => false, 'msg' => 'No puedes valorarte a ti mismo']);
            return;
        }

        if (!$puntuacion || $puntuacion < 1 || $puntuacion > 5) {
            echo json_encode(['ok' => false, 'msg' => 'La puntuación debe estar entre 1 y 5']);
            return;
        }

        if (Valoracion::yaValorado($idAutor, $idDestino)) {
            echo json_encode(['ok' => false, 'msg' => 'Ya has valorado a este usuario']);
            return;
        }

        $ok = Valoracion::crear($idAutor, $idDestino, $puntuacion, $comentario);
        echo json_encode(['ok' => $ok, 'msg' => $ok ? 'Valoración enviada correctamente' : 'Error al guardar la valoración']);
    }

    // Lista las valoraciones recibidas por el usuario logueado
    public function misValoraciones() {
        $idUsuario   = $_SESSION['id_usuario'];
        $valoraciones = Valoracion::findByDestinatario($idUsuario);
        $media        = Valoracion::mediaUsuario($idUsuario);
        require __DIR__ . '/../views/valoraciones/listado.php';
    }
}
