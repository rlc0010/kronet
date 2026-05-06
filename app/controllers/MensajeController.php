<?php

require_once __DIR__ . '/../models/Mensaje.php';

class MensajeController
{

    public function enviar()
    {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            echo "Debes iniciar sesión";
            return;
        }

        $emisor = $_SESSION['user'];
        $receptor = $_POST['id_receptor'] ?? null;
        $texto = trim($_POST['mensaje'] ?? '');

        if (!$receptor || !is_numeric($receptor)) {
            echo "Receptor inválido";
            return;
        }

        if (!$texto) {
            echo "Mensaje vacío";
            return;
        }

        if ($emisor == $receptor) {
            echo "No puedes enviarte mensajes a ti mismo";
            return;
        }

        try {
            Mensaje::enviar($emisor, $receptor, $texto);
            echo "Mensaje enviado correctamente";
        } catch (Exception $e) {
            echo "Error al enviar el mensaje";
        }
    }

    public function bandeja()
    {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            echo "Debes iniciar sesión";
            return;
        }

        $usuario = $_SESSION['user'];

        // De momento mostramos conversación con TODOS (simple)
        $mensajes = Mensaje::obtenerRecibidos($usuario);

        require __DIR__ . '/../views/mensajes/bandeja.php';
    }
}
