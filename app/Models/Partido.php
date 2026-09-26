<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Partido extends Model
{
    protected $table = 'partidos';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'disciplina_id',
        'ronda_numero',
        'ronda_nombre',
        'local_id',
        'visitante_id',
        'local_goles',
        'visitante_goles',
        'ganador_id',
        'estado',
        'fecha',
        'horario',
        'cancha',
        'es_wo',
        'foto_evidencia',
        'sets_detalle',
        'observaciones',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fecha' => 'date:Y-m-d',
            'es_wo' => 'boolean',
            'sets_detalle' => 'array',
        ];
    }

    public function disciplina(): BelongsTo
    {
        return $this->belongsTo(Disciplina::class, 'disciplina_id');
    }

    public function local(): BelongsTo
    {
        return $this->belongsTo(Delegacion::class, 'local_id');
    }

    public function visitante(): BelongsTo
    {
        return $this->belongsTo(Delegacion::class, 'visitante_id');
    }

    public function ganador(): BelongsTo
    {
        return $this->belongsTo(Delegacion::class, 'ganador_id');
    }

    public function delegados(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'delegado_partidos', 'partido_id', 'user_id');
    }
}
