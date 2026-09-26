<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['username', 'name', 'email', 'password', 'role', 'delegacion_id', 'activo'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'activo' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'ADMIN';
    }

    public function isDelegado(): bool
    {
        return $this->role === 'DELEGADO';
    }

    public function delegacion(): BelongsTo
    {
        return $this->belongsTo(Delegacion::class, 'delegacion_id');
    }

    public function disciplinasAsignadas(): BelongsToMany
    {
        return $this->belongsToMany(Disciplina::class, 'delegado_disciplinas', 'user_id', 'disciplina_id');
    }

    public function partidosAsignados(): BelongsToMany
    {
        return $this->belongsToMany(Partido::class, 'delegado_partidos', 'user_id', 'partido_id');
    }

    /**
     * Determina si el usuario tiene permiso para editar una disciplina (o subcategoría).
     */
    public function puedeEditarDisciplina(Disciplina|string $disciplina): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        $disciplinaModel = is_string($disciplina) ? Disciplina::find($disciplina) : $disciplina;
        if (! $disciplinaModel) {
            return false;
        }

        $asignadasIds = $this->disciplinasAsignadas()->pluck('disciplinas.id')->toArray();

        // Si no tiene asignaciones explícitas, no tiene permiso granular asignado
        if (empty($asignadasIds)) {
            return false;
        }

        // Permitir si tiene asignada la disciplina directamente o su disciplina padre
        if (in_array($disciplinaModel->id, $asignadasIds, true)) {
            return true;
        }

        if ($disciplinaModel->parent_id && in_array($disciplinaModel->parent_id, $asignadasIds, true)) {
            return true;
        }

        return false;
    }

    /**
     * Determina si el usuario tiene permiso para editar un partido específico.
     */
    public function puedeEditarPartido(Partido $partido): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        // 1. Revisar si tiene el partido explícitamente asignado
        $partidosIds = $this->partidosAsignados()->pluck('partidos.id')->toArray();
        if (in_array($partido->id, $partidosIds, true)) {
            return true;
        }

        // 2. Revisar si tiene la disciplina o el deporte padre asignado
        if ($partido->disciplina_id && $this->puedeEditarDisciplina($partido->disciplina_id)) {
            return true;
        }

        // 3. Si no tiene restricciones granulares de disciplinas/partidos asignadas, pero pertenece a una delegación participante
        if (empty($partidosIds) && $this->disciplinasAsignadas()->count() === 0 && $this->delegacion_id) {
            return $partido->local_id === $this->delegacion_id || $partido->visitante_id === $this->delegacion_id;
        }

        return false;
    }
}
