<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Disciplina extends Model
{
    protected $table = 'disciplinas';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'parent_id',
        'slug',
        'nombre',
        'categoria',
        'genero',
        'tipo',
        'sistema_puntuacion',
        'color_acento',
        'foto_url',
        'foto_referencia_url',
        'descripcion',
        'sede_principal',
        'sede_maps_url',
        'fechas_cronograma',
        'horario_cronograma',
        'campeon_actual',
        'podio',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'podio' => 'array',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function subcategorias(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('nombre');
    }

    public function partidos(): HasMany
    {
        return $this->hasMany(Partido::class, 'disciplina_id');
    }

    public function nominas(): HasMany
    {
        return $this->hasMany(NominaAtleta::class, 'disciplina_id');
    }

    public function delegados(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'delegado_disciplinas', 'disciplina_id', 'user_id');
    }

    public function scopePrincipales(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function esIndividual(): bool
    {
        return $this->tipo === 'INDIVIDUAL';
    }

    public function esColectivo(): bool
    {
        return $this->tipo === 'COLECTIVO';
    }
}
