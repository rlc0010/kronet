<?php

class User {

    public static function findByEmail($email) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public static function create($nombre, $email, $password) {
        global $pdo;

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
            INSERT INTO usuarios 
            (nombre, email, contrasenia_hash, fecha_registro, estado_cuenta) 
            VALUES (?, ?, ?, NOW(), 'activa')
        ");

        return $stmt->execute([$nombre, $email, $hash]);
    }
}