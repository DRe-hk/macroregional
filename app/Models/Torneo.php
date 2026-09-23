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
        'anio',
        'avance_porcentaje',
    ];

    public static function actual(): self
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'nombre' => 'Competencia Deportiva Macroregional 2026',
                'subtitulo' => 'Torneo Deportivo Macroregional de Educación',
                'organizador' => 'Comisión Macroregional DREP',
                'sede_principal' => 'Puno / Juliaca',
                'logo_url' => null,
                'portada_url' => '/images/portada_macroregional.jpeg',
                'anio' => 2026,
                'avance_porcentaje' => 0,
            ]
        );
    }
}
