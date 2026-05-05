<?php
require_once __DIR__ . '/../../config/conexion_db.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Intercambio.php';

class PerfilController {

    public function verPerfil() {

        // Comprobar login
        if (!isset($_SESSION['id_usuario'])) {
            $error = "Debes iniciar sesión";
            $usuario = [];
            $historial = [];
            require __DIR__ . '/../views/perfil/verPerfil.php';
            return;
        }

        $idUsuario = $_SESSION['id_usuario'];

        // Obtener usuario
        $usuario = User::findById($idUsuario);

        // Obtener historial
        $historial = Intercambio::findByUsuario($idUsuario);

        // Error A1
        if (!$usuario) {
            $error = "No se han podido cargar los datos";
        }

        // Evitar errores en la vista
        $usuario = $usuario ?? [];
        $historial = $historial ?? [];

        require __DIR__ . '/../views/perfil/verPerfil.php';
    }
}