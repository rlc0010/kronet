<?php
require_once __DIR__ . '/../models/Valoracion.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Notificacion.php';

class ValoracionController {

    public function showCrear() {
        $idDestino = (int)($_GET['id_usuario'] ?? 0);
        $destinatario = User::findById($idDestino);
        if (!$destinatario || $idDestino === (int)$_SESSION['id_usuario']) {
            header("Location: /kronet/public/"); exit;
        }
        require __DIR__ . '/../views/valoraciones/crear.php';
    }

    public function crear() {
        header('Content-Type: application/json');

        $miId      = (int)$_SESSION['id_usuario'];
        $idDestino = (int)($_POST['id_destino'] ?? 0);
        $puntuacion= (int)($_POST['puntuacion'] ?? 0);
        $comentario= trim($_POST['comentario'] ?? '');

        if (!$idDestino || $idDestino === $miId) {
            echo json_encode(['ok' => false, 'msg' => 'Destinatario no válido']);
            return;
        }
        if ($puntuacion < 1 || $puntuacion > 5) {
            echo json_encode(['ok' => false, 'msg' => 'Selecciona entre 1 y 5 estrellas']);
            return;
        }
        if (mb_strlen($comentario) > 500) {
            echo json_encode(['ok' => false, 'msg' => 'Comentario demasiado largo (máx 500)']);
            return;
        }
        if (!User::findById($idDestino)) {
            echo json_encode(['ok' => false, 'msg' => 'Usuario no encontrado']);
            return;
        }
        if (Valoracion::yaValorado($miId, $idDestino)) {
            echo json_encode(['ok' => false, 'msg' => 'Ya has valorado a este usuario']);
            return;
        }

        $ok = Valoracion::crear($miId, $idDestino, $puntuacion, $comentario);
        if ($ok) {
            Notificacion::crear($idDestino, 'valoracion',
                'Has recibido una valoración',
                'Un usuario te ha valorado con ' . $puntuacion . ' estrellas.',
                '/kronet/public/valoraciones/mis-valoraciones');
        }
        echo json_encode(['ok' => (bool)$ok, 'msg' => $ok ? 'Valoración registrada' : 'No se pudo enviar']);
    }

    public function misValoraciones() {
        $miId = (int)$_SESSION['id_usuario'];
        $valoraciones = Valoracion::findByDestinatario($miId);
        $media = Valoracion::mediaUsuario($miId);
        require __DIR__ . '/../views/valoraciones/listado.php';
    }
}
