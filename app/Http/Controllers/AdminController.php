<?php

namespace App\Http\Controllers;

use App\Models\Delegacion;
use App\Models\Disciplina;
use App\Models\Partido;
use App\Models\Serie;
use App\Models\Torneo;
use App\Models\User;
use App\Services\TournamentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct(protected TournamentService $tournamentService) {}

    public function index(): View
    {
        $torneo = Torneo::actual();
        $disciplinas = Disciplina::with([
            'series.partidos.local',
            'series.partidos.visitante',
            'series.partidos.ganador',
        ])->get();
        $delegaciones = Delegacion::orderBy('nombre')->get();
        $usuarios = User::with('delegacion')->orderBy('role')->orderBy('name')->get();

        return view('admin.index', compact('torneo', 'disciplinas', 'delegaciones', 'usuarios'));
    }

    public function actualizarTorneo(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'subtitulo' => ['required', 'string', 'max:255'],
            'organizador' => ['required', 'string', 'max:255'],
            'sede_principal' => ['required', 'string', 'max:255'],
            'anio' => ['required', 'integer', 'min:2020', 'max:2050'],
            'avance_porcentaje' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $torneo = Torneo::actual();
        $torneo->update($data);

        return back()->with('success', 'Información general del torneo actualizada.');
    }

    public function guardarDeporte(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id' => ['nullable', 'string'],
            'nombre' => ['required', 'string', 'max:100'],
            'categoria' => ['required', 'string', 'max:50'],
            'color_acento' => ['required', 'string', 'max:20'],
            'foto_url' => ['nullable', 'url', 'max:500'],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'sede_principal' => ['required', 'string', 'max:150'],
        ]);

        $slug = Str::slug($data['nombre']);
        $id = ! empty($data['id']) ? $data['id'] : $slug;

        $disciplina = Disciplina::updateOrCreate(
            ['id' => $id],
            [
                'slug' => $slug,
                'nombre' => $data['nombre'],
                'categoria' => $data['categoria'],
                'color_acento' => $data['color_acento'],
                'foto_url' => $data['foto_url'] ?? 'https://images.unsplash.com/photo-1508098682722-e99c43a406b2?w=800&auto=format&fit=crop&q=80',
                'descripcion' => $data['descripcion'] ?? '',
                'sede_principal' => $data['sede_principal'],
            ]
        );

        // Crear al menos Serie A si no tiene series
        if ($disciplina->series()->count() === 0) {
            Serie::create([
                'disciplina_id' => $disciplina->id,
                'letra' => 'A',
                'nombre' => 'Serie A',
                'sede_nombre' => $data['sede_principal'],
            ]);
        }

        return back()->with('success', 'Disciplina deportiva guardada correctamente.');
    }

    public function eliminarDeporte(string $id): RedirectResponse
    {
        $disciplina = Disciplina::findOrFail($id);
        $disciplina->delete();

        return back()->with('success', 'Disciplina deportiva eliminada.');
    }

    public function guardarDelegacion(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id' => ['nullable', 'string'],
            'nombre' => ['required', 'string', 'max:100'],
            'siglas' => ['required', 'string', 'max:30'],
            'provincia' => ['required', 'string', 'max:100'],
        ]);

        $id = ! empty($data['id']) ? $data['id'] : Str::slug($data['siglas']);

        Delegacion::updateOrCreate(
            ['id' => $id],
            [
                'nombre' => $data['nombre'],
                'siglas' => $data['siglas'],
                'provincia' => $data['provincia'],
            ]
        );

        return back()->with('success', 'Delegación participante guardada con éxito.');
    }

    public function eliminarDelegacion(string $id): RedirectResponse
    {
        $del = Delegacion::findOrFail($id);
        $del->delete();

        return back()->with('success', 'Delegación eliminada.');
    }

    public function guardarPartido(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'serie_id' => ['required', 'integer', 'exists:series,id'],
            'ronda_numero' => ['required', 'integer', 'min:1'],
            'ronda_nombre' => ['required', 'string', 'max:50'],
            'local_id' => ['nullable', 'string', 'exists:delegaciones,id'],
            'visitante_id' => ['nullable', 'string', 'exists:delegaciones,id'],
            'horario' => ['nullable', 'string', 'max:50'],
            'cancha' => ['nullable', 'string', 'max:100'],
        ]);

        $partidoId = 'match-'.Str::random(8);

        Partido::create([
            'id' => $partidoId,
            'serie_id' => $data['serie_id'],
            'ronda_numero' => $data['ronda_numero'],
            'ronda_nombre' => $data['ronda_nombre'],
            'local_id' => $data['local_id'],
            'visitante_id' => $data['visitante_id'],
            'horario' => $data['horario'] ?? '09:00 AM',
            'cancha' => $data['cancha'] ?? 'Cancha Principal',
            'estado' => 'PROGRAMADO',
        ]);

        return back()->with('success', 'Partido programado en el fixture correctamente.');
    }

    public function actualizarMarcador(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'partido_id' => ['required', 'string', 'exists:partidos,id'],
            'local_goles' => ['nullable', 'integer', 'min:0'],
            'visitante_goles' => ['nullable', 'integer', 'min:0'],
            'ganador_id' => ['nullable', 'string', 'exists:delegaciones,id'],
            'observaciones' => ['nullable', 'string', 'max:500'],
        ]);

        $partido = Partido::findOrFail($data['partido_id']);

        $localGoles = $data['local_goles'] !== null ? (int) $data['local_goles'] : null;
        $visitanteGoles = $data['visitante_goles'] !== null ? (int) $data['visitante_goles'] : null;

        $this->tournamentService->actualizarMarcador(
            $partido,
            $localGoles,
            $visitanteGoles,
            $data['ganador_id'] ?? null,
            $data['observaciones'] ?? null
        );

        return back()->with('success', 'Marcador y tabla de posiciones actualizados.');
    }

    public function eliminarPartido(string $id): RedirectResponse
    {
        $partido = Partido::findOrFail($id);
        $partido->delete();

        $this->tournamentService->recalcularTablaPosiciones();

        return back()->with('success', 'Partido eliminado del fixture.');
    }

    public function guardarDelegado(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:users,username'],
            'name' => ['required', 'string', 'max:150'],
            'clave' => ['required', 'string', 'min:4'],
            'delegacion_id' => ['required', 'string', 'exists:delegaciones,id'],
        ], [
            'username.unique' => 'El nombre de usuario ya está registrado en el sistema.',
        ]);

        User::create([
            'username' => $data['username'],
            'name' => $data['name'],
            'email' => $data['username'].'@macroregional.pe',
            'password' => Hash::make($data['clave']),
            'role' => 'DELEGADO',
            'delegacion_id' => $data['delegacion_id'],
            'activo' => true,
        ]);

        return back()->with('success', 'Credenciales de delegado creadas exitosamente.');
    }

    public function eliminarDelegado(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        if ($user->role === 'ADMIN') {
            return back()->with('error', 'No se puede eliminar la cuenta principal de administrador.');
        }

        $user->delete();

        return back()->with('success', 'Cuenta de delegado eliminada.');
    }

    public function reiniciarBd(): RedirectResponse
    {
        Artisan::call('migrate:fresh', [
            '--seed' => true,
            '--force' => true,
        ]);

        return back()->with('success', 'Base de datos reiniciada al estado base inicial con 0 resultados.');
    }
}
