<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Disciplina extends Model
{
    protected $table = 'disciplinas';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'slug',
        'nombre',
        'categoria',
        'color_acento',
        'foto_url',
        'descripcion',
        'sede_principal',
        'sede_maps_url',
        'campeon_actual',
    ];

    public function series(): HasMany
    {
        return $this->hasMany(Serie::class, 'disciplina_id');
    }

    public function nominas(): HasMany
    {
        return $this->hasMany(NominaAtleta::class, 'disciplina_id');
    }
}
