<?php
/**
 * Calculadora de precio en créditos de tiempo para un servicio.
 *
 * Filosofía
 * ---------
 * Un banco de tiempo puro asume "1 hora = 1 crédito", pero Kronet añade un
 * matiz: no cuesta lo mismo una hora de fontanería (requiere un oficio muy
 * especializado, herramientas, riesgo) que una hora de paseo de perro o
 * compañía. Para mantener el sistema justo aplicamos un multiplicador por
 * categoría inspirado en la noción patrón/trabajo: el patrón (la persona que
 * tiene el oficio) recibe un poco más por hora que un trabajo no cualificado.
 *
 * Fórmula
 * -------
 *      créditos = round( duración_horas * multiplicador_categoria )
 *
 * Multiplicador
 * -------------
 * Refleja el grado de especialización medio que la categoría demanda. La
 * cota mínima es 1.0 (un servicio sin especialización equivale al banco de
 * tiempo clásico). El máximo es 2.5 para oficios técnicos que requieren
 * conocimiento específico y/o herramientas (fontanería, electricidad, etc.).
 *
 * Si en un futuro se quiere afinar más, este es el sitio donde tocar.
 */
class PrecioCalculator {

    public const MULTIPLICADORES = [
        'reparaciones'    => 2.5,   // fontanería, electricidad, albañilería...
        'tecnologia'      => 2.0,   // programación, soporte IT
        'salud'           => 2.0,   // fisioterapia, masajes terapéuticos
        'asesoramiento'   => 1.8,   // gestoría, asesoría financiera o legal
        'idiomas'         => 1.5,
        'educacion'       => 1.5,
        'musica'          => 1.5,
        'arte'            => 1.3,
        'cuidados'        => 1.3,
        'deporte'         => 1.2,
        'cocina'          => 1.1,
        'jardineria'      => 1.0,
        'hogar'           => 1.0,
        'mascotas'        => 1.0,
        'transporte'      => 1.0,
        'otros'           => 1.0,
    ];

    public const MIN_CREDITOS = 1;
    public const MAX_CREDITOS = 999;

    /**
     * Calcula el coste en créditos de un servicio.
     *
     * @param string $categoria Slug de categoría (debe existir en Categorias)
     * @param int    $duracionHoras Duración estimada en horas
     * @return int               Créditos finales (siempre >= 1)
     */
    public static function calcular(string $categoria, int $duracionHoras): int {
        if ($duracionHoras < 1) {
            $duracionHoras = 1;
        }
        $mult = self::MULTIPLICADORES[$categoria] ?? 1.0;
        $creditos = (int) round($duracionHoras * $mult);

        if ($creditos < self::MIN_CREDITOS) $creditos = self::MIN_CREDITOS;
        if ($creditos > self::MAX_CREDITOS) $creditos = self::MAX_CREDITOS;

        return $creditos;
    }

    /**
     * Devuelve el multiplicador (útil para mostrarlo en la UI al usuario).
     */
    public static function multiplicador(string $categoria): float {
        return self::MULTIPLICADORES[$categoria] ?? 1.0;
    }

    /**
     * Texto descriptivo del nivel de especialización de una categoría.
     */
    public static function nivel(string $categoria): string {
        $m = self::multiplicador($categoria);
        if ($m >= 2.0) return 'Alta especialización';
        if ($m >= 1.5) return 'Especialización media';
        if ($m >= 1.2) return 'Cualificación ligera';
        return 'Sin cualificación específica';
    }
}
