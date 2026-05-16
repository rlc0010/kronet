<?php
/**
 * Categorías canónicas de Kronet.
 *
 * Una sola fuente de verdad para la lista de categorías disponibles en
 * publicación de anuncios y en el filtrado de búsquedas. Incluye además
 * un icono FontAwesome asociado para usar en la UI.
 *
 * Si se añade una categoría nueva, hay que actualizar también el
 * multiplicador correspondiente en PrecioCalculator::MULTIPLICADORES.
 */
class Categorias {

    public const LISTA = [
        'tecnologia'      => ['nombre' => 'Tecnología',         'icono' => 'fas fa-laptop-code'],
        'educacion'       => ['nombre' => 'Educación',          'icono' => 'fas fa-graduation-cap'],
        'idiomas'         => ['nombre' => 'Idiomas',            'icono' => 'fas fa-language'],
        'musica'          => ['nombre' => 'Música',             'icono' => 'fas fa-music'],
        'arte'            => ['nombre' => 'Arte y Creatividad', 'icono' => 'fas fa-palette'],
        'salud'           => ['nombre' => 'Salud y Bienestar',  'icono' => 'fas fa-heartbeat'],
        'cuidados'        => ['nombre' => 'Cuidados',           'icono' => 'fas fa-hand-holding-heart'],
        'reparaciones'    => ['nombre' => 'Reparaciones',       'icono' => 'fas fa-tools'],
        'hogar'           => ['nombre' => 'Hogar y Limpieza',   'icono' => 'fas fa-home'],
        'cocina'          => ['nombre' => 'Cocina',             'icono' => 'fas fa-utensils'],
        'jardineria'      => ['nombre' => 'Jardinería',         'icono' => 'fas fa-leaf'],
        'mascotas'        => ['nombre' => 'Mascotas',           'icono' => 'fas fa-paw'],
        'deporte'         => ['nombre' => 'Deporte',            'icono' => 'fas fa-dumbbell'],
        'transporte'      => ['nombre' => 'Transporte',         'icono' => 'fas fa-truck'],
        'asesoramiento'   => ['nombre' => 'Asesoramiento',      'icono' => 'fas fa-comments'],
        'otros'           => ['nombre' => 'Otros',              'icono' => 'fas fa-ellipsis-h'],
    ];

    public static function todas() {
        return self::LISTA;
    }

    public static function existe($slug) {
        return isset(self::LISTA[$slug]);
    }

    public static function nombre($slug) {
        return self::LISTA[$slug]['nombre'] ?? ucfirst($slug);
    }

    public static function icono($slug) {
        return self::LISTA[$slug]['icono'] ?? 'fas fa-folder';
    }
}
