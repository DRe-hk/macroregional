<?php

namespace App\Http\Controllers;

use App\Models\Delegacion;
use App\Models\Disciplina;
use App\Models\NominaAtleta;
use App\Models\Partido;
use App\Services\TournamentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DelegadoController extends Controller
{
    public function __construct(protected TournamentService $tournamentService) {}

    public function index(): View
    {
        $user = Auth::user();
        $delegacion = $user->delegacion_id ? Delegacion::find($user->delegacion_id) : null;

        $partidos = Partido::with(['serie.disciplina', 'local', 'visitante', 'ganador'])
            ->where(function ($q) use ($user) {
                $q->where('local_id', $user->delegacion_id)
                    ->orWhere('visitante_id', $user->delegacion_id);
            })
            ->get();

        $disciplinas = Disciplina::all();

        $atletas = NominaAtleta::with('disciplina')
            ->where('delegacion_id', $user->delegacion_id)
            ->orderBy('nombre_completo')
            ->get();

        return view('delegado.index', compact('user', 'delegacion', 'partidos', 'disciplinas', 'atletas'));
    }

    public function actualizarMarcador(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $data = $request->validate([
            'partido_id' => ['required', 'string', 'exists:partidos,id'],
            'local_goles' => ['nullable', 'integer', 'min:0'],
            'visitante_goles' => ['nullable', 'integer', 'min:0'],
            'observaciones' => ['nullable', 'string', 'max:500'],
        ]);

        $partido = Partido::findOrFail($data['partido_id']);

        // Control estricto de seguridad: el delegado solo puede editar marcadores de su delegación
        if ($partido->local_id !== $user->delegacion_id && $partido->visitante_id !== $user->delegacion_id) {
            abort(403, 'No tienes permiso para actualizar un partido donde tu delegación no participa.');
        }

        $localGoles = $data['local_goles'] !== null ? (int) $data['local_goles'] : null;
        $visitanteGoles = $data['visitante_goles'] !== null ? (int) $data['visitante_goles'] : null;

        $this->tournamentService->actualizarMarcador(
            $partido,
            $localGoles,
            $visitanteGoles,
            null,
            $data['observaciones'] ?? null
        );

        return back()->with('success', 'Marcador del partido actualizado exitosamente.');
    }

    public function guardarAtleta(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (! $user->delegacion_id) {
            abort(403, 'No tienes una delegación asignada.');
        }

        $data = $request->validate([
            'dni' => ['required', 'string', 'max:15'],
            'nombre_completo' => ['required', 'string', 'max:150'],
            'disciplina_id' => ['required', 'string', 'exists:disciplinas,id'],
            'numero_camiseta' => ['nullable', 'string', 'max:10'],
            'rol_equipo' => ['required', 'string', 'max:50'],
        ]);

        NominaAtleta::create([
            'delegacion_id' => $user->delegacion_id,
            'disciplina_id' => $data['disciplina_id'],
            'dni' => $data['dni'],
            'nombre_completo' => $data['nombre_completo'],
            'numero_camiseta' => $data['numero_camiseta'],
            'rol_equipo' => $data['rol_equipo'],
        ]);

        return back()->with('success', 'Deportista inscrito en la nómina oficial correctamente.');
    }

    public function eliminarAtleta(int $id): RedirectResponse
    {
        $user = Auth::user();
        $atleta = NominaAtleta::where('id', $id)
            ->where('delegacion_id', $user->delegacion_id)
            ->firstOrFail();

        $atleta->delete();

        return back()->with('success', 'Deportista retirado de la nómina correctamente.');
    }
}
