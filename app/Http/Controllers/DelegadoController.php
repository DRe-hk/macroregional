<?php

namespace App\Http\Controllers;

use App\Models\Delegacion;
use App\Models\Disciplina;
use App\Models\NominaAtleta;
use App\Models\Partido;
use App\Models\Torneo;
use App\Models\User;
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
        /** @var User $user */
        $user = Auth::user();
        $delegacion = $user->delegacion_id ? Delegacion::find($user->delegacion_id) : null;

        $disciplinasAsignadasIds = $user->disciplinasAsignadas()->pluck('disciplinas.id')->toArray();
        $partidosAsignadosIds = $user->partidosAsignados()->pluck('partidos.id')->toArray();

        $partidosQuery = Partido::with(['disciplina', 'local', 'visitante', 'ganador'])
            ->orderBy('fecha')
            ->orderBy('horario');

        if (! empty($disciplinasAsignadasIds) || ! empty($partidosAsignadosIds)) {
            // Incluir también las subcategorías si tiene asignado el deporte padre
            $subIds = Disciplina::whereIn('parent_id', $disciplinasAsignadasIds)->pluck('id')->toArray();
            $todasDisciplinasIds = array_unique(array_merge($disciplinasAsignadasIds, $subIds));

            $partidos = $partidosQuery->where(function ($q) use ($todasDisciplinasIds, $partidosAsignadosIds) {
                if (! empty($todasDisciplinasIds)) {
                    $q->whereIn('disciplina_id', $todasDisciplinasIds);
                }
                if (! empty($partidosAsignadosIds)) {
                    $q->orWhereIn('id', $partidosAsignadosIds);
                }
            })->get();
        } elseif ($user->delegacion_id) {
            $partidos = $partidosQuery->where(function ($q) use ($user) {
                $q->where('local_id', $user->delegacion_id)
                    ->orWhere('visitante_id', $user->delegacion_id);
            })->get();
        } else {
            $partidos = collect();
        }

        $disciplinas = ! empty($disciplinasAsignadasIds)
            ? Disciplina::whereIn('id', $disciplinasAsignadasIds)->orWhereIn('parent_id', $disciplinasAsignadasIds)->get()
            : Disciplina::all();

        $atletas = NominaAtleta::with('disciplina')
            ->where('delegacion_id', $user->delegacion_id)
            ->orderBy('nombre_completo')
            ->get();

        $torneo = Torneo::actual();

        return view('delegado.index', compact('torneo', 'user', 'delegacion', 'partidos', 'disciplinas', 'atletas'));
    }

    public function actualizarMarcador(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $data = $request->validate([
            'partido_id' => ['required', 'string', 'exists:partidos,id'],
            'local_goles' => ['nullable', 'integer', 'min:0'],
            'visitante_goles' => ['nullable', 'integer', 'min:0'],
            'es_wo' => ['nullable', 'boolean'],
            'evidencia_file' => ['nullable', 'file', 'mimes:jpeg,png,jpg,webp,avif', 'max:10240'],
            'observaciones' => ['nullable', 'string', 'max:500'],
        ]);

        $partido = Partido::findOrFail($data['partido_id']);

        // Control granular de permisos: verificar si el delegado tiene autorización sobre este partido
        if (! $user->puedeEditarPartido($partido)) {
            abort(403, 'No tienes permiso para actualizar este partido o deporte.');
        }

        $fotoEvidencia = null;
        if ($request->hasFile('evidencia_file')) {
            $fotoEvidencia = $this->almacenarImagenSegura($request->file('evidencia_file'), 'evidencias', 'acta_'.$partido->id);
        }

        $localGoles = $data['local_goles'] !== null ? (int) $data['local_goles'] : null;
        $visitanteGoles = $data['visitante_goles'] !== null ? (int) $data['visitante_goles'] : null;

        $this->tournamentService->actualizarMarcador(
            $partido,
            $localGoles,
            $visitanteGoles,
            null,
            $data['observaciones'] ?? null,
            (bool) ($data['es_wo'] ?? false),
            $fotoEvidencia
        );

        return back()->with('success', 'Marcador y evidencia oficial del partido registrados con éxito.');
    }

    public function guardarAtleta(Request $request): RedirectResponse
    {
        /** @var User $user */
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
        /** @var User $user */
        $user = Auth::user();
        $atleta = NominaAtleta::findOrFail($id);

        if ($atleta->delegacion_id !== $user->delegacion_id) {
            abort(403, 'No puedes eliminar atletas de otra delegación.');
        }

        $atleta->delete();

        return back()->with('success', 'Deportista retirado de la nómina.');
    }

    /**
     * Valida y almacena de forma segura un archivo de imagen en uploads.
     */
    private function almacenarImagenSegura(mixed $file, string $subdirectorio, string $prefijo): string
    {
        $extension = strtolower($file->extension() ?: $file->guessExtension() ?: 'jpg');
        $permitidas = ['jpeg', 'jpg', 'png', 'webp', 'avif'];

        if (! in_array($extension, $permitidas, true)) {
            abort(422, 'Formato de imagen no permitido. Solo se aceptan JPG, PNG, WEBP o AVIF.');
        }

        $nombreArchivo = $prefijo.'_'.bin2hex(random_bytes(8)).'.'.$extension;
        $destino = public_path('uploads/'.$subdirectorio);

        if (! file_exists($destino)) {
            mkdir($destino, 0755, true);
        }

        $file->move($destino, $nombreArchivo);

        return '/uploads/'.$subdirectorio.'/'.$nombreArchivo;
    }
}
