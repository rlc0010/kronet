<?php
require_once __DIR__ . '/../models/Anuncio.php';
require_once __DIR__ . '/../models/Intercambio.php';
require_once __DIR__ . '/../models/Suscripcion.php';
require_once __DIR__ . '/../models/Pago.php';
require_once __DIR__ . '/../models/Denuncia.php';
require_once __DIR__ . '/../models/Notificacion.php';
require_once __DIR__ . '/../helpers/Categorias.php';
require_once __DIR__ . '/../helpers/PrecioCalculator.php';

class AnuncioController {

    public function showCrear() {
        $categorias = Categorias::todas();
        $multiplicadores = PrecioCalculator::MULTIPLICADORES;
        require __DIR__ . '/../views/anuncios/crear.php';
    }

    public function crear() {
        header('Content-Type: application/json');

        $titulo         = trim($_POST['titulo']         ?? '');
        $descripcion    = trim($_POST['descripcion']    ?? '');
        $tipo           = $_POST['tipo_anuncio']        ?? '';
        $categoria      = $_POST['categoria']           ?? '';
        $duracion       = (int)($_POST['duracion_estimada'] ?? 0);
        $plazas         = (int)($_POST['plazas_totales']    ?? 1);

        if ($titulo === '' || $descripcion === '' || !$categoria || !$duracion) {
            echo json_encode(['ok' => false, 'msg' => 'Faltan campos obligatorios']);
            return;
        }
        if (mb_strlen($titulo) > 255) {
            echo json_encode(['ok' => false, 'msg' => 'El título es demasiado largo']);
            return;
        }
        if (!in_array($tipo, ['oferta', 'demanda'], true)) {
            echo json_encode(['ok' => false, 'msg' => 'Tipo de anuncio no válido']);
            return;
        }
        if (!Categorias::existe($categoria)) {
            echo json_encode(['ok' => false, 'msg' => 'Categoría no válida']);
            return;
        }
        if ($duracion < 1 || $duracion > 100) {
            echo json_encode(['ok' => false, 'msg' => 'La duración debe estar entre 1 y 100 horas']);
            return;
        }
        if ($plazas < 1 || $plazas > 50) {
            echo json_encode(['ok' => false, 'msg' => 'Las plazas deben estar entre 1 y 50']);
            return;
        }

        $id = Anuncio::create($_SESSION['id_usuario'], $titulo, $descripcion, $tipo, $categoria, $duracion, $plazas);
        echo json_encode(['ok' => true, 'msg' => 'Anuncio publicado correctamente', 'id' => $id]);
    }

    public function showEditar($id) {
        $anuncio = Anuncio::findById($id);
        if (!$anuncio || $anuncio['id_usuario'] != $_SESSION['id_usuario']) {
            header("Location: /kronet/public/anuncios/mis-anuncios");
            exit;
        }
        $categorias = Categorias::todas();
        $multiplicadores = PrecioCalculator::MULTIPLICADORES;
        require __DIR__ . '/../views/anuncios/editar.php';
    }

    public function editar($id) {
        header('Content-Type: application/json');

        $anuncio = Anuncio::findById($id);
        if (!$anuncio) {
            echo json_encode(['ok' => false, 'msg' => 'Anuncio no encontrado']);
            return;
        }
        if ($anuncio['id_usuario'] != $_SESSION['id_usuario']) {
            echo json_encode(['ok' => false, 'msg' => 'No tienes permiso']);
            return;
        }

        $titulo         = trim($_POST['titulo']         ?? '');
        $descripcion    = trim($_POST['descripcion']    ?? '');
        $tipo           = $_POST['tipo_anuncio']        ?? '';
        $categoria      = $_POST['categoria']           ?? '';
        $duracion       = (int)($_POST['duracion_estimada'] ?? 0);
        $plazas         = (int)($_POST['plazas_totales']    ?? 1);

        if ($titulo === '' || $descripcion === '' || !$categoria || !$duracion) {
            echo json_encode(['ok' => false, 'msg' => 'Faltan campos obligatorios']);
            return;
        }
        if (!in_array($tipo, ['oferta', 'demanda'], true)) {
            echo json_encode(['ok' => false, 'msg' => 'Tipo no válido']);
            return;
        }
        if (!Categorias::existe($categoria)) {
            echo json_encode(['ok' => false, 'msg' => 'Categoría no válida']);
            return;
        }

        Anuncio::update($id, $titulo, $descripcion, $tipo, $categoria, $duracion, $plazas);
        echo json_encode(['ok' => true, 'msg' => 'Anuncio actualizado']);
    }

    public function eliminar($id) {
        header('Content-Type: application/json');
        $anuncio = Anuncio::findById($id);
        if (!$anuncio) { echo json_encode(['ok' => false, 'msg' => 'Anuncio no encontrado']); return; }
        if ($anuncio['id_usuario'] != $_SESSION['id_usuario']) {
            echo json_encode(['ok' => false, 'msg' => 'No tienes permiso']); return;
        }
        Anuncio::delete($id);
        echo json_encode(['ok' => true, 'msg' => 'Anuncio eliminado']);
    }

    public function misAnuncios() {
        $anuncios = Anuncio::findByUsuario($_SESSION['id_usuario']);
        require __DIR__ . '/../views/anuncios/mis_anuncios.php';
    }

    public function showBuscar() {
        Anuncio::limpiarDestacadosCaducados();

        $busqueda = !empty($_GET['busqueda'])     ? $_GET['busqueda']     : null;
        $tipo     = !empty($_GET['tipo_anuncio']) ? $_GET['tipo_anuncio'] : null;
        $cat      = !empty($_GET['categoria'])    ? $_GET['categoria']    : null;
        $pageNum  = max(1, (int)($_GET['p'] ?? 1));
        $perPage  = 12;
        $offset   = ($pageNum - 1) * $perPage;

        $total      = Anuncio::countSearch($busqueda, $tipo, $cat);
        $totalPages = (int)ceil($total / $perPage);
        $anuncios   = Anuncio::search($busqueda, $tipo, $cat, $perPage, $offset);

        $categorias = Categorias::todas();
        require __DIR__ . '/../views/anuncios/buscar.php';
    }

    public function showFicha($id) {
        $anuncio = Anuncio::findByIdConUsuario($id);
        if (!$anuncio) {
            header("Location: /kronet/public/anuncios/buscar");
            exit;
        }

        // Para usuarios anónimos no hay $_SESSION['id_usuario']. La vista de la
        // ficha lo detecta y muestra una versión sin botones de acción
        // (apuntarme, chatear, denunciar) sustituida por un CTA de iniciar
        // sesión. Aquí simplemente preparamos las variables tolerando que
        // el visitante no esté logueado.
        $miId = $_SESSION['id_usuario'] ?? 0;

        $esDueno    = $miId && ($anuncio['id_usuario'] == $miId);
        $yaInscrito = $miId && !$esDueno && Intercambio::existeActivo($id, $miId);

        $mensajes = [];
        if ($miId && !$esDueno) {
            require_once __DIR__ . '/../models/Mensaje.php';
            $mensajes = Mensaje::conversacion($id, $miId, $anuncio['id_usuario']);
            Mensaje::marcarConversacionLeida($id, $miId, $anuncio['id_usuario']);
        }

        require __DIR__ . '/../views/anuncios/ficha.php';
    }

    public function showDestacar($id) {
        $anuncio = Anuncio::findById($id);
        if (!$anuncio || $anuncio['id_usuario'] != $_SESSION['id_usuario']) {
            header("Location: /kronet/public/anuncios/mis-anuncios");
            exit;
        }
        $info = Suscripcion::puedeDestacarGratis($_SESSION['id_usuario']);
        $metodos = Pago::METODOS;
        require __DIR__ . '/../views/anuncios/destacar.php';
    }

    public function destacar($id) {
        header('Content-Type: application/json');

        $anuncio = Anuncio::findById($id);
        if (!$anuncio || $anuncio['id_usuario'] != $_SESSION['id_usuario']) {
            echo json_encode(['ok' => false, 'msg' => 'Anuncio no encontrado']);
            return;
        }

        $tipo = $_POST['tipo'] ?? '';

        if ($tipo === 'gratis') {
            $info = Suscripcion::puedeDestacarGratis($_SESSION['id_usuario']);
            if (!$info['puede']) {
                echo json_encode(['ok' => false, 'msg' => 'No te quedan destacados gratis esta semana']);
                return;
            }
            Anuncio::destacar($id, 7);
            Suscripcion::consumirDestacadoGratis($info['sus']['id_suscripcion']);
            Notificacion::crear($_SESSION['id_usuario'], 'sistema',
                'Anuncio destacado', 'Tu anuncio "' . $anuncio['titulo'] . '" estará destacado 7 días.',
                '/kronet/public/anuncios/' . $id);
            echo json_encode(['ok' => true, 'msg' => 'Anuncio destacado gracias a tu suscripción']);
            return;
        }

        if ($tipo === 'pago') {
            $metodo = $_POST['metodo_pago'] ?? '';
            if (!in_array($metodo, Pago::METODOS, true)) {
                echo json_encode(['ok' => false, 'msg' => 'Método de pago no válido']);
                return;
            }
            // Simulación de pasarela de pago
            Pago::registrar($_SESSION['id_usuario'], 'destacado', $metodo, Pago::PRECIO_DESTACADO, 'anuncio:' . $id);
            Anuncio::destacar($id, 7);
            Notificacion::crear($_SESSION['id_usuario'], 'sistema',
                'Pago realizado', 'Has destacado tu anuncio por 7 días (' . number_format(Pago::PRECIO_DESTACADO, 2, ',', '.') . '€).',
                '/kronet/public/anuncios/' . $id);
            echo json_encode(['ok' => true, 'msg' => 'Pago realizado y anuncio destacado']);
            return;
        }

        echo json_encode(['ok' => false, 'msg' => 'Operación no reconocida']);
    }

    public function denunciar($id) {
        header('Content-Type: application/json');

        $anuncio = Anuncio::findById($id);
        if (!$anuncio) {
            echo json_encode(['ok' => false, 'msg' => 'Anuncio no encontrado']);
            return;
        }
        if ($anuncio['id_usuario'] == $_SESSION['id_usuario']) {
            echo json_encode(['ok' => false, 'msg' => 'No puedes denunciar tu propio anuncio']);
            return;
        }

        $motivo = $_POST['motivo'] ?? 'otro';
        $desc   = trim($_POST['descripcion'] ?? '');
        if (!array_key_exists($motivo, Denuncia::MOTIVOS)) {
            echo json_encode(['ok' => false, 'msg' => 'Motivo no válido']);
            return;
        }
        if (Denuncia::yaDenunciado($_SESSION['id_usuario'], $id)) {
            echo json_encode(['ok' => false, 'msg' => 'Ya has denunciado este anuncio']);
            return;
        }

        Denuncia::crear($_SESSION['id_usuario'], $id, $motivo, $desc);
        echo json_encode(['ok' => true, 'msg' => 'Denuncia registrada. Un administrador la revisará.']);
    }
}
