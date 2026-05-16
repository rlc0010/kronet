<?php
require_once __DIR__ . '/../../config/conexion_db.php';

class Suscripcion {

    public const PRECIO_MENSUAL = 5.00;
    public const DESTACADOS_GRATIS_SEMANA = 1;

    public static function activaPorUsuario($idUsuario) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT * FROM suscripciones
            WHERE id_usuario = ? AND estado = 'activa' AND fecha_fin >= NOW()
            ORDER BY fecha_fin DESC
            LIMIT 1
        ");
        $stmt->execute([$idUsuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function crear($idUsuario, $dias = 30) {
        global $pdo;
        $fechaFin = date('Y-m-d H:i:s', strtotime('+' . (int)$dias . ' day'));
        $hoy = date('Y-m-d');
        $stmt = $pdo->prepare("
            INSERT INTO suscripciones
            (id_usuario, fecha_inicio, fecha_fin, estado, destacados_usados_semana, semana_destacados)
            VALUES (?, NOW(), ?, 'activa', 0, ?)
        ");
        $stmt->execute([$idUsuario, $fechaFin, $hoy]);
        return $pdo->lastInsertId();
    }

    public static function cancelar($idUsuario) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE suscripciones SET estado = 'cancelada' WHERE id_usuario = ? AND estado = 'activa'");
        return $stmt->execute([$idUsuario]);
    }

    /**
     * Reinicia el contador de destacados semanales si toca (es decir, si el
     * lunes de la semana actual es posterior al "semana_destacados" guardado).
     * Devuelve la fila actualizada.
     */
    public static function resetearContadorSemanalSiCorresponde($idSuscripcion) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM suscripciones WHERE id_suscripcion = ?");
        $stmt->execute([$idSuscripcion]);
        $sus = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$sus) return null;

        $hoy = new DateTime('now');
        $lunesActual = (clone $hoy)->modify('monday this week');

        $semanaGuardada = $sus['semana_destacados'] ? new DateTime($sus['semana_destacados']) : null;

        if (!$semanaGuardada || $semanaGuardada < $lunesActual) {
            $upd = $pdo->prepare("
                UPDATE suscripciones
                SET destacados_usados_semana = 0, semana_destacados = ?
                WHERE id_suscripcion = ?
            ");
            $upd->execute([$lunesActual->format('Y-m-d'), $idSuscripcion]);
            $sus['destacados_usados_semana'] = 0;
            $sus['semana_destacados'] = $lunesActual->format('Y-m-d');
        }
        return $sus;
    }

    /**
     * ¿Le quedan destacados gratis esta semana al usuario?
     * Devuelve [puede:bool, restantes:int, sus:array|null]
     */
    public static function puedeDestacarGratis($idUsuario) {
        $sus = self::activaPorUsuario($idUsuario);
        if (!$sus) return ['puede' => false, 'restantes' => 0, 'sus' => null];

        $sus = self::resetearContadorSemanalSiCorresponde($sus['id_suscripcion']);
        $restantes = max(0, self::DESTACADOS_GRATIS_SEMANA - (int)$sus['destacados_usados_semana']);
        return ['puede' => $restantes > 0, 'restantes' => $restantes, 'sus' => $sus];
    }

    public static function consumirDestacadoGratis($idSuscripcion) {
        global $pdo;
        $stmt = $pdo->prepare("
            UPDATE suscripciones
            SET destacados_usados_semana = destacados_usados_semana + 1
            WHERE id_suscripcion = ?
        ");
        return $stmt->execute([$idSuscripcion]);
    }
}
