<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Torneo extends Model
{
    protected $table = 'torneos';

    protected $fillable = [
        'nombre',
        'subtitulo',
        'organizador',
        'sede_principal',
        'logo_url',
        'portada_url',
        'logo_texto',
        'logo_subtexto',
        'footer_texto',
        'carrusel_slides',
        'anio',
        'avance_porcentaje',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'carrusel_slides' => 'array',
            'anio' => 'integer',
            'avance_porcentaje' => 'integer',
        ];
    }

    /**
     * Devuelve los slides del carrusel o una lista por defecto con las imágenes oficiales.
     *
     * @return array<int, array{imagen: string, titulo: string, subtitulo: string}>
     */
    public function getSlides(): array
    {
        if (! empty($this->carrusel_slides) && is_array($this->carrusel_slides)) {
            return $this->carrusel_slides;
        }

        return [
            [
                'imagen' => $this->portada_url ?: '/images/portada_macroregional.jpeg',
                'titulo' => 'JEDPA Puno 2026',
                'subtitulo' => 'Juegos Escolares Deportivos y Paradeportivos - Sede Macrorregional N° 07',
            ],
            [
                'imagen' => 'https://images.unsplash.com/photo-1508098682722-e99c43a406b2?w=1200&auto=format&fit=crop&q=80',
                'titulo' => 'Pasión y Excelencia Deportiva',
                'subtitulo' => 'Estadio Enrique Torres Belón y Coliseos de la Región Puno',
            ],
            [
                'imagen' => 'https://images.unsplash.com/photo-1612872087720-bb876e2e67d1?w=1200&auto=format&fit=crop&q=80',
                'titulo' => 'Competencia de Alto Rendimiento',
                'subtitulo' => 'Participación de delegaciones escolares clasificadas',
            ],
        ];
    }

    public static function actual(): self
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'nombre' => 'Juegos Escolares Deportivos y Paradeportivos 2026',
                'subtitulo' => 'Etapa Macrorregional - Sede Puno',
                'organizador' => 'DRE Puno - MINEDU',
                'sede_principal' => 'Puno',
                'logo_url' => null,
                'portada_url' => '/images/portada_macroregional.jpeg',
                'logo_texto' => 'JEDPA 2026',
                'logo_subtexto' => 'Etapa Macrorregional Sede Puno',
                'footer_texto' => 'Dirección Regional de Educación Puno - Oficina de Informática. Todos los derechos reservados.',
                'carrusel_slides' => null,
                'anio' => 2026,
                'avance_porcentaje' => 0,
            ]
        );
    }
}
