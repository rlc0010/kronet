<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../models/User.php';

/**
 * Controlador de autenticación (login / registro / logout).
 * Ambos métodos devuelven JSON {ok: bool, msg: string}.
 */
class AuthController {

    public function showLogin() {
        require __DIR__ . '/../views/auth/login.php';
    }

    public function showRegister() {
        require __DIR__ . '/../views/auth/register.php';
    }

    public function register() {
        header('Content-Type: application/json');

        $nombre   = trim($_POST['nombre']   ?? '');
        $email    = trim($_POST['email']    ?? '');
        $password = $_POST['password']      ?? '';

        if ($nombre === '' || $email === '' || $password === '') {
            echo json_encode(['ok' => false, 'msg' => 'Todos los campos son obligatorios']);
            return;
        }
        if (mb_strlen($nombre) < 2 || mb_strlen($nombre) > 100) {
            echo json_encode(['ok' => false, 'msg' => 'El nombre debe tener entre 2 y 100 caracteres']);
            return;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['ok' => false, 'msg' => 'Email con formato no válido']);
            return;
        }
        if (!$this->passwordValida($password)) {
            echo json_encode([
                'ok'  => false,
                'msg' => 'La contraseña debe tener mínimo 8 caracteres, una mayúscula, una minúscula y un número'
            ]);
            return;
        }
        if (User::findByEmail($email)) {
            echo json_encode(['ok' => false, 'msg' => 'Ese email ya está registrado']);
            return;
        }

        $ok = User::create($nombre, $email, $password);
        echo json_encode(['ok' => (bool)$ok, 'msg' => $ok ? 'Cuenta creada' : 'No se pudo crear la cuenta']);
    }

    /**
     * Login: devuelve JSON `{ok, msg}` (no texto plano, porque el cliente
     * antes hacía `includes('correcto')` y la palabra "correcto" también
     * aparece dentro de "incorrectos" → falso positivo). Con JSON
     * estructurado el frontend se basa en el flag booleano `ok`.
     */
    public function login() {
        header('Content-Type: application/json');

        $email    = trim($_POST['email']    ?? '');
        $password = $_POST['password']      ?? '';

        if ($email === '' || $password === '') {
            echo json_encode(['ok' => false, 'msg' => 'Todos los campos son obligatorios']);
            return;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['ok' => false, 'msg' => 'Email con formato no válido']);
            return;
        }

        $user = User::findByEmail($email);
        if (!$user) {
            echo json_encode(['ok' => false, 'msg' => 'Email o contraseña incorrectos']);
            return;
        }
        if (($user['estado_cuenta'] ?? 'activa') !== 'activa') {
            echo json_encode(['ok' => false, 'msg' => 'Tu cuenta no está activa. Contacta con soporte.']);
            return;
        }

        if (password_verify($password, $user['contrasenia_hash'])) {
            $_SESSION['id_usuario'] = (int)$user['id_usuario'];
            $_SESSION['nombre']     = $user['nombre'];
            echo json_encode(['ok' => true, 'msg' => 'Sesión iniciada']);
        } else {
            echo json_encode(['ok' => false, 'msg' => 'Email o contraseña incorrectos']);
        }
    }

    private function passwordValida($pass) {
        return strlen($pass) >= 8
            && preg_match('/[A-Z]/', $pass)
            && preg_match('/[a-z]/', $pass)
            && preg_match('/[0-9]/', $pass);
    }
}
