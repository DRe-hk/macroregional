<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Delegacion extends Model
{
    protected $table = 'delegaciones';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'nombre',
        'siglas',
        'provincia',
        'puntos',
        'pj',
        'pg',
        'pe',
        'pp',
        'gf',
        'gc',
        'dg',
    ];

    public function nominas(): HasMany
    {
        return $this->hasMany(NominaAtleta::class, 'delegacion_id');
    }

    public function partidosLocal(): HasMany
    {
        return $this->hasMany(Partido::class, 'local_id');
    }

    public function partidosVisitante(): HasMany
    {
        return $this->hasMany(Partido::class, 'visitante_id');
    }
}
