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
        $disciplinas = Disciplina::principales()
            ->with(['subcategorias', 'partidos'])
            ->get();
        $totalEquipos = Delegacion::count();

        return view('home', compact('torneo', 'disciplinas', 'totalEquipos'));
    }

    public function clasificacion(Request $request): View
    {
        $torneo = Torneo::actual();
        $disciplinas = Disciplina::principales()
            ->with('subcategorias')
            ->orderBy('nombre')
            ->get();

        $deporteSlug = $request->query('deporte');
        $disciplinaPadre = null;
        $disciplinaSeleccionada = null;
        $tablaPorDisciplina = null;

        if ($deporteSlug) {
            $disciplinaBase = Disciplina::with('subcategorias')->where('slug', $deporteSlug)->first();
            if ($disciplinaBase) {
                $disciplinaPadre = $disciplinaBase;
                $disciplinaSeleccionada = $disciplinaBase;

                if ($disciplinaBase->subcategorias->isNotEmpty()) {
                    $subSlug = $request->query('sub');
                    $sub = $subSlug
                        ? $disciplinaBase->subcategorias->firstWhere('slug', $subSlug)
                        : $disciplinaBase->subcategorias->first();
                    if ($sub) {
                        $disciplinaSeleccionada = $sub;
                    }
                }
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
            'disciplinaPadre',
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

        // 1. Deportes principales individuales (Natación, Atletismo)
        $individuales = Disciplina::where('tipo', 'INDIVIDUAL')->get();

        // 2. Deportes colectivos y subcategorías que ya tienen campeón consagrado
        $colectivosConCampeon = Disciplina::where('tipo', 'COLECTIVO')
            ->whereNotNull('campeon_actual')
            ->where('campeon_actual', '!=', '')
            ->with('parent')
            ->get();

        // 3. Resumen acumulado de campeonatos por delegación
        $medallasPorDelegacion = [];
        foreach (Delegacion::all() as $del) {
            $medallasPorDelegacion[$del->nombre] = [
                'delegacion' => $del,
                'titulos' => 0,
            ];
        }

        foreach ($individuales as $ind) {
            if ($ind->campeon_actual && isset($medallasPorDelegacion[$ind->campeon_actual])) {
                $medallasPorDelegacion[$ind->campeon_actual]['titulos']++;
            }
        }

        foreach ($colectivosConCampeon as $col) {
            if ($col->campeon_actual && isset($medallasPorDelegacion[$col->campeon_actual])) {
                $medallasPorDelegacion[$col->campeon_actual]['titulos']++;
            }
        }

        uasort($medallasPorDelegacion, fn ($a, $b) => $b['titulos'] <=> $a['titulos']);

        return view('campeones', compact('torneo', 'individuales', 'colectivosConCampeon', 'medallasPorDelegacion'));
    }

    public function disciplina(Request $request, string $slug): View
    {
        $torneo = Torneo::actual();
        $disciplinaBase = Disciplina::with(['subcategorias', 'parent.subcategorias'])->where('slug', $slug)->firstOrFail();

        // Si la disciplina consultada es en sí una subcategoría con padre
        if ($disciplinaBase->parent_id && $disciplinaBase->parent) {
            $disciplinaPadre = $disciplinaBase->parent;
            $disciplina = $disciplinaBase;
        } else {
            $disciplinaPadre = $disciplinaBase;
            $disciplina = $disciplinaBase;

            // Si la disciplina tiene subcategorías, determinar cuál mostrar
            $subSlug = $request->query('sub');
            if ($disciplinaBase->subcategorias->isNotEmpty()) {
                if ($subSlug) {
                    $sub = $disciplinaBase->subcategorias->firstWhere('slug', $subSlug);
                    if ($sub) {
                        $disciplina = $sub;
                    }
                } else {
                    $disciplina = $disciplinaBase->subcategorias->first();
                }
            }
        }

        // Si es deporte individual, no cargamos fixture de partidos
        if ($disciplina->esIndividual()) {
            return view('disciplina', [
                'torneo' => $torneo,
                'disciplinaPadre' => $disciplinaBase,
                'disciplina' => $disciplina,
                'rondasMap' => [],
                'tablaPosiciones' => [],
                'fechasDisponibles' => [],
                'fechaActiva' => null,
            ]);
        }

        // Cargar partidos de la disciplina activa
        $queryPartidos = $disciplina->partidos()
            ->with(['local', 'visitante', 'ganador'])
            ->orderBy('ronda_numero')
            ->orderBy('fecha')
            ->orderBy('horario');

        $todosLosPartidos = (clone $queryPartidos)->get();

        // Fechas únicas para la barra de filtro temporal
        $fechasDisponibles = $todosLosPartidos
            ->pluck('fecha')
            ->filter()
            ->map(fn ($f) => $f->format('Y-m-d'))
            ->unique()
            ->sort()
            ->values()
            ->all();

        $fechaActiva = $request->query('fecha');
        if ($fechaActiva) {
            $partidos = $todosLosPartidos->filter(fn ($p) => $p->fecha?->format('Y-m-d') === $fechaActiva);
        } else {
            $partidos = $todosLosPartidos;
        }

        // Agrupar partidos por ronda_numero
        $rondasMap = [];
        foreach ($partidos as $partido) {
            $rondasMap[$partido->ronda_numero][] = $partido;
        }
        ksort($rondasMap);

        // Tabla de posiciones individual calculada
        $tablaPosiciones = $this->tournamentService->obtenerTablaPorDisciplina($disciplina);

        return view('disciplina', [
            'torneo' => $torneo,
            'disciplinaPadre' => $disciplinaBase,
            'disciplina' => $disciplina,
            'rondasMap' => $rondasMap,
            'tablaPosiciones' => $tablaPosiciones,
            'fechasDisponibles' => $fechasDisponibles,
            'fechaActiva' => $fechaActiva,
        ]);
    }
}
