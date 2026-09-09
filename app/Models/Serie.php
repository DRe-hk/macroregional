<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Serie extends Model
{
    protected $table = 'series';

    protected $fillable = [
        'disciplina_id',
        'letra',
        'nombre',
        'sede_nombre',
    ];

    public function disciplina(): BelongsTo
    {
        return $this->belongsTo(Disciplina::class, 'disciplina_id');
    }

    public function partidos(): HasMany
    {
        return $this->hasMany(Partido::class, 'serie_id')->orderBy('ronda_numero')->orderBy('id');
    }
}
