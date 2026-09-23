<?php

namespace App\Http\Controllers;

use App\Models\Delegacion;
use App\Models\Disciplina;
use App\Models\Torneo;
use App\Services\TournamentService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(protected TournamentService $tournamentService) {}

    public function index(): View
    {
        $torneo = Torneo::actual();
        $disciplinas = Disciplina::with(['series.partidos'])->get();
        $totalEquipos = Delegacion::count();

        return view('home', compact('torneo', 'disciplinas', 'totalEquipos'));
    }

    public function clasificacion(Request $request): View
    {
        $torneo = Torneo::actual();
        $disciplinas = Disciplina::orderBy('nombre')->get();
        $deporteSlug = $request->query('deporte');

        $disciplinaSeleccionada = null;
        $tablaPorDisciplina = null;

        if ($deporteSlug) {
            $disciplinaSeleccionada = Disciplina::where('slug', $deporteSlug)->first();
            if ($disciplinaSeleccionada) {
                $tablaPorDisciplina = $this->tournamentService->obtenerTablaPorDisciplina($disciplinaSeleccionada);
            }
        }

        $delegaciones = Delegacion::orderByDesc('puntos')
            ->orderByDesc('dg')
            ->orderByDesc('gf')
            ->orderBy('nombre')
            ->get();

        return view('clasificacion', compact(
            'torneo',
            'delegaciones',
            'disciplinas',
            'disciplinaSeleccionada',
            'tablaPorDisciplina'
        ));
    }

    public function equipos(): View
    {
        $torneo = Torneo::actual();
        $delegaciones = Delegacion::orderBy('nombre')->get();

        return view('equipos', compact('torneo', 'delegaciones'));
    }

    public function campeones(): View
    {
        $torneo = Torneo::actual();
        $disciplinas = Disciplina::all();
        $disciplinasConCampeon = $disciplinas->filter(fn ($d) => ! empty($d->campeon_actual));

        return view('campeones', compact('torneo', 'disciplinasConCampeon'));
    }

    public function disciplina(Request $request, string $slug): View
    {
        $torneo = Torneo::actual();
        $disciplina = Disciplina::with([
            'series.partidos.local',
            'series.partidos.visitante',
            'series.partidos.ganador',
        ])->where('slug', $slug)->firstOrFail();

        $letraActiva = strtoupper($request->query('serie', $disciplina->series->first()?->letra ?? 'A'));
        $serieSeleccionada = $disciplina->series->firstWhere('letra', $letraActiva) ?? $disciplina->series->first();

        // Agrupar partidos por ronda_numero
        $rondasMap = [];
        if ($serieSeleccionada) {
            foreach ($serieSeleccionada->partidos as $partido) {
                $rondasMap[$partido->ronda_numero][] = $partido;
            }
            ksort($rondasMap);
        }

        // Obtener tabla de posiciones individual para esta serie o disciplina
        $tablaPosiciones = $this->tournamentService->obtenerTablaPorDisciplina($disciplina, $serieSeleccionada);

        return view('disciplina', compact('torneo', 'disciplina', 'serieSeleccionada', 'letraActiva', 'rondasMap', 'tablaPosiciones'));
    }
}
