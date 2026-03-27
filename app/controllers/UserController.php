<?php
session_start();
require_once '../app/models/User.php';

class AuthController {

    public function showLogin() {
        require '../app/views/auth/login.php';
    }

    public function showRegister() {
        require '../app/views/auth/register.php';
    }

    public function register() {
        $nombre = $_POST['nombre'] ?? null;
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;
    
        header('Content-Type: application/json');
    
        if (!$nombre || !$email || !$password) {
            echo json_encode(['ok' => false, 'msg' => 'Campos obligatorios']);
            return;
        }
    
        if (User::findByEmail($email)) {
            echo json_encode(['ok' => false, 'msg' => 'El email ya está registrado']);
            return;
        }
    
        User::create($nombre, $email, $password);
        echo json_encode(['ok' => true]);
    }

    public function login() {
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;

        if (!$email || !$password) {
            echo "Campos obligatorios";
            return;
        }

        $user = User::findByEmail($email);

        if ($user && password_verify($password, $user['contrasenia_hash'])) {

            $_SESSION['user'] = $user['id_usuario'];

            echo "Login correcto";
        } else {
            echo "Credenciales incorrectas";
        }
    }
}