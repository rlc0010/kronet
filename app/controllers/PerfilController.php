<?php
require_once __DIR__ . '/../models/User.php';

class PerfilController {

    public function verPerfil() {
        $usuario = User::findById($_SESSION['user']);
        require __DIR__ . '/../views/perfil/verPerfil.php';
    }
}
