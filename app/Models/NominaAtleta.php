<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NominaAtleta extends Model
{
    protected $table = 'nominas';

    protected $fillable = [
        'delegacion_id',
        'disciplina_id',
        'dni',
        'nombre_completo',
        'numero_camiseta',
        'rol_equipo',
    ];

    public function delegacion(): BelongsTo
    {
        return $this->belongsTo(Delegacion::class, 'delegacion_id');
    }

    public function disciplina(): BelongsTo
    {
        return $this->belongsTo(Disciplina::class, 'disciplina_id');
    }
}
