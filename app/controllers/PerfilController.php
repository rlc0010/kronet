<?php
require_once __DIR__ . '/../../config/conexion_db.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Intercambio.php';
require_once __DIR__ . '/../models/Anuncio.php';
require_once __DIR__ . '/../models/Valoracion.php';

class PerfilController {

    // Perfil propio del usuario logueado
    public function verPerfil() {
        if (!isset($_SESSION['id_usuario'])) {
            $error = "Debes iniciar sesión";
            $usuario = [];
            $historial = [];
            require __DIR__ . '/../views/perfil/verPerfil.php';
            return;
        }

        $idUsuario = $_SESSION['id_usuario'];
        $usuario   = User::findById($idUsuario);
        $historial = Intercambio::findByUsuario($idUsuario);
        $media     = Valoracion::mediaUsuario($idUsuario);

        if (!$usuario) {
            $error = "No se han podido cargar los datos";
        }

        $usuario   = $usuario  ?? [];
        $historial = $historial ?? [];

        require __DIR__ . '/../views/perfil/verPerfil.php';
    }

    // Perfil ajeno: ver el perfil de otro usuario
    public function verPerfilAjeno($idUsuario) {
        $idUsuario = (int)$idUsuario;

        // Si es tu propio perfil, redirigir
        if (isset($_SESSION['id_usuario']) && $_SESSION['id_usuario'] == $idUsuario) {
            header("Location: /kronet/public/perfil");
            exit;
        }

        $usuario      = User::findById($idUsuario);
        $anuncios     = Anuncio::findByUsuario($idUsuario);
        $valoraciones = Valoracion::findByDestinatario($idUsuario);
        $media        = Valoracion::mediaUsuario($idUsuario);
        $yaValorado   = false;

        if (!$usuario) {
            $error = "Usuario no encontrado";
            require __DIR__ . '/../views/perfil/ajeno.php';
            return;
        }

        // Solo anuncios activos
        $anuncios = array_values(array_filter($anuncios ?? [], fn($a) => $a['estado'] === 'activo'));

        if (isset($_SESSION['id_usuario'])) {
            $yaValorado = Valoracion::yaValorado($_SESSION['id_usuario'], $idUsuario);
        }

        require __DIR__ . '/../views/perfil/ajeno.php';
    }
}
