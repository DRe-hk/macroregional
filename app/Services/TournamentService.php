<?php

namespace App\Services;

use App\Models\Delegacion;
use App\Models\Disciplina;
use App\Models\Partido;

class TournamentService
{
    /**
     * Calcula los puntos de un partido según el sistema oficial del deporte (RVM N° 092-2026-MINEDU).
     *
     * @return array{local_pts: int, vis_pts: int, local_pg: int, local_pe: int, local_pp: int, vis_pg: int, vis_pe: int, vis_pp: int}
     */
    public function calcularPuntosPartido(Partido $partido, string $sistema = 'FUTBOL'): array
    {
        $localGoles = $partido->local_goles ?? 0;
        $visGoles = $partido->visitante_goles ?? 0;
        $esWo = (bool) $partido->es_wo;

        $res = [
            'local_pts' => 0,
            'vis_pts' => 0,
            'local_pg' => 0,
            'local_pe' => 0,
            'local_pp' => 0,
            'vis_pg' => 0,
            'vis_pe' => 0,
            'vis_pp' => 0,
        ];

        $sistemaNorm = strtoupper($sistema);

        switch ($sistemaNorm) {
            case 'BASQUET':
                if ($esWo) {
                    if ($localGoles > $visGoles) {
                        $res['local_pts'] = 2;
                        $res['local_pg'] = 1;
                        $res['vis_pp'] = 1;
                    } else {
                        $res['vis_pts'] = 2;
                        $res['vis_pg'] = 1;
                        $res['local_pp'] = 1;
                    }
                } elseif ($localGoles > $visGoles) {
                    $res['local_pts'] = 2;
                    $res['local_pg'] = 1;
                    $res['vis_pts'] = 1;
                    $res['vis_pp'] = 1;
                } elseif ($visGoles > $localGoles) {
                    $res['vis_pts'] = 2;
                    $res['vis_pg'] = 1;
                    $res['local_pts'] = 1;
                    $res['local_pp'] = 1;
                } else {
                    $res['local_pts'] = 1;
                    $res['local_pe'] = 1;
                    $res['vis_pts'] = 1;
                    $res['vis_pe'] = 1;
                }
                break;

            case 'HANDBALL':
                if ($esWo) {
                    if ($localGoles > $visGoles) {
                        $res['local_pts'] = 2;
                        $res['local_pg'] = 1;
                        $res['vis_pts'] = -2;
                        $res['vis_pp'] = 1;
                    } else {
                        $res['vis_pts'] = 2;
                        $res['vis_pg'] = 1;
                        $res['local_pts'] = -2;
                        $res['local_pp'] = 1;
                    }
                } elseif ($localGoles > $visGoles) {
                    $res['local_pts'] = 2;
                    $res['local_pg'] = 1;
                    $res['vis_pp'] = 1;
                } elseif ($visGoles > $localGoles) {
                    $res['vis_pts'] = 2;
                    $res['vis_pg'] = 1;
                    $res['local_pp'] = 1;
                } else {
                    $res['local_pts'] = 1;
                    $res['local_pe'] = 1;
                    $res['vis_pts'] = 1;
                    $res['vis_pe'] = 1;
                }
                break;

            case 'VOLEIBOL':
            case 'VOLEY_PLAYA':
                if ($esWo) {
                    if ($localGoles > $visGoles) {
                        $res['local_pts'] = 3;
                        $res['local_pg'] = 1;
                        $res['vis_pp'] = 1;
                    } else {
                        $res['vis_pts'] = 3;
                        $res['vis_pg'] = 1;
                        $res['local_pp'] = 1;
                    }
                } elseif ($localGoles > $visGoles) {
                    $res['local_pg'] = 1;
                    $res['vis_pp'] = 1;
                    // Victoria 2-0 o 3-0/3-1 => 3 pts; Victoria 2-1 o 3-2 => 2 pts (1 pt para perdedor)
                    if (($localGoles - $visGoles) >= 2) {
                        $res['local_pts'] = 3;
                        $res['vis_pts'] = 0;
                    } else {
                        $res['local_pts'] = 2;
                        $res['vis_pts'] = 1;
                    }
                } elseif ($visGoles > $localGoles) {
                    $res['vis_pg'] = 1;
                    $res['local_pp'] = 1;
                    if (($visGoles - $localGoles) >= 2) {
                        $res['vis_pts'] = 3;
                        $res['local_pts'] = 0;
                    } else {
                        $res['vis_pts'] = 2;
                        $res['local_pts'] = 1;
                    }
                }
                break;

            case 'FUTBOL':
            case 'FUTSAL':
            default:
                if ($esWo) {
                    if ($localGoles > $visGoles) {
                        $res['local_pts'] = 3;
                        $res['local_pg'] = 1;
                        $res['vis_pp'] = 1;
                    } else {
                        $res['vis_pts'] = 3;
                        $res['vis_pg'] = 1;
                        $res['local_pp'] = 1;
                    }
                } elseif ($localGoles > $visGoles) {
                    $res['local_pts'] = 3;
                    $res['local_pg'] = 1;
                    $res['vis_pp'] = 1;
                } elseif ($visGoles > $localGoles) {
                    $res['vis_pts'] = 3;
                    $res['vis_pg'] = 1;
                    $res['local_pp'] = 1;
                } else {
                    $res['local_pts'] = 1;
                    $res['local_pe'] = 1;
                    $res['vis_pts'] = 1;
                    $res['vis_pe'] = 1;
                }
                break;
        }

        return $res;
    }

    /**
     * Recalcula la tabla de posiciones general de todas las delegaciones acumulando puntos reales de partidos.
     */
    public function recalcularTablaPosiciones(): void
    {
        $delegaciones = Delegacion::all();
        $partidosFinalizados = Partido::with('disciplina')->where('estado', 'FINALIZADO')->get();

        $stats = [];
        foreach ($delegaciones as $del) {
            $stats[$del->id] = [
                'pj' => 0,
                'pg' => 0,
                'pe' => 0,
                'pp' => 0,
                'gf' => 0,
                'gc' => 0,
                'puntos' => 0,
            ];
        }

        foreach ($partidosFinalizados as $partido) {
            $localId = $partido->local_id;
            $visitanteId = $partido->visitante_id;
            $localGoles = $partido->local_goles;
            $visitanteGoles = $partido->visitante_goles;

            if ($localGoles === null || $visitanteGoles === null) {
                continue;
            }

            $sistema = $partido->disciplina->sistema_puntuacion ?? 'FUTBOL';
            $puntos = $this->calcularPuntosPartido($partido, $sistema);

            if ($localId && isset($stats[$localId])) {
                $stats[$localId]['pj']++;
                $stats[$localId]['gf'] += $localGoles;
                $stats[$localId]['gc'] += $visitanteGoles;
                $stats[$localId]['pg'] += $puntos['local_pg'];
                $stats[$localId]['pe'] += $puntos['local_pe'];
                $stats[$localId]['pp'] += $puntos['local_pp'];
                $stats[$localId]['puntos'] += $puntos['local_pts'];
            }

            if ($visitanteId && isset($stats[$visitanteId])) {
                $stats[$visitanteId]['pj']++;
                $stats[$visitanteId]['gf'] += $visitanteGoles;
                $stats[$visitanteId]['gc'] += $localGoles;
                $stats[$visitanteId]['pg'] += $puntos['vis_pg'];
                $stats[$visitanteId]['pe'] += $puntos['vis_pe'];
                $stats[$visitanteId]['pp'] += $puntos['vis_pp'];
                $stats[$visitanteId]['puntos'] += $puntos['vis_pts'];
            }
        }

        foreach ($stats as $delId => $data) {
            $dg = $data['gf'] - $data['gc'];
            Delegacion::where('id', $delId)->update([
                'pj' => $data['pj'],
                'pg' => $data['pg'],
                'pe' => $data['pe'],
                'pp' => $data['pp'],
                'gf' => $data['gf'],
                'gc' => $data['gc'],
                'dg' => $dg,
                'puntos' => $data['puntos'],
            ]);
        }
    }

    /**
     * Actualiza el marcador de un partido y proclama al campeón de la disciplina si corresponde.
     */
    public function actualizarMarcador(
        Partido $partido,
        ?int $localGoles,
        ?int $visitanteGoles,
        ?string $ganadorId = null,
        ?string $observaciones = null,
        bool $esWo = false,
        ?string $fotoEvidencia = null
    ): void {
        $partido->local_goles = $localGoles;
        $partido->visitante_goles = $visitanteGoles;
        $partido->observaciones = $observaciones;
        $partido->es_wo = $esWo;

        if ($fotoEvidencia) {
            $partido->foto_evidencia = $fotoEvidencia;
        }

        if ($localGoles !== null && $visitanteGoles !== null) {
            $partido->estado = 'FINALIZADO';

            if (! $ganadorId) {
                if ($localGoles > $visitanteGoles) {
                    $ganadorId = $partido->local_id;
                } elseif ($visitanteGoles > $localGoles) {
                    $ganadorId = $partido->visitante_id;
                }
            }
            $partido->ganador_id = $ganadorId;

            // Si es la final explícita de la disciplina, registrar campeón
            if ($ganadorId && str_contains(strtolower($partido->ronda_nombre), 'final')) {
                $disciplina = $partido->disciplina;
                $ganador = Delegacion::find($ganadorId);
                if ($disciplina && $ganador) {
                    $disciplina->update(['campeon_actual' => $ganador->nombre]);
                }
            }
        } else {
            $partido->estado = 'PROGRAMADO';
            $partido->ganador_id = null;
        }

        $partido->save();
        $this->recalcularTablaPosiciones();

        // Si la disciplina no tiene partido final pero todos los partidos están finalizados, proclamar líder de tabla
        $disciplina = $partido->disciplina;
        if ($disciplina && empty($disciplina->campeon_actual)) {
            $totalPartidos = $disciplina->partidos()->count();
            $partidosFin = $disciplina->partidos()->where('estado', 'FINALIZADO')->count();
            if ($totalPartidos > 0 && $totalPartidos === $partidosFin) {
                $tabla = $this->obtenerTablaPorDisciplina($disciplina);
                if (! empty($tabla) && isset($tabla[0]['delegacion'])) {
                    $disciplina->update(['campeon_actual' => $tabla[0]['delegacion']->nombre]);
                }
            }
        }
    }

    /**
     * Registra o actualiza el podio de un deporte individual (Natación o Atletismo).
     *
     * @param  array{oro: array{delegacion: string, atleta: string, marca?: string}, plata?: array{delegacion: string, atleta: string, marca?: string}, bronce?: array{delegacion: string, atleta: string, marca?: string}}  $podio
     */
    public function actualizarPodioIndividual(Disciplina $disciplina, array $podio): void
    {
        $campeonNombre = $podio['oro']['delegacion'] ?? ($podio['oro'] ?? null);
        if (is_array($campeonNombre)) {
            $campeonNombre = $campeonNombre['delegacion'] ?? null;
        }

        $disciplina->update([
            'podio' => $podio,
            'campeon_actual' => $campeonNombre,
        ]);
    }

    /**
     * Obtiene la tabla de posiciones individual calculada para una disciplina deportiva con su sistema de puntuación.
     *
     * @return array<int, array{pos: int, delegacion: Delegacion, pj: int, pg: int, pe: int, pp: int, gf: int, gc: int, dg: int, puntos: int}>
     */
    public function obtenerTablaPorDisciplina(Disciplina $disciplina): array
    {
        $partidos = Partido::where('disciplina_id', $disciplina->id)->get();
        $partidosFinalizados = $partidos->where('estado', 'FINALIZADO');

        $participantesIds = $partidos->pluck('local_id')
            ->merge($partidos->pluck('visitante_id'))
            ->filter()
            ->unique();

        if ($participantesIds->isEmpty()) {
            $delegaciones = Delegacion::orderBy('nombre')->get();
        } else {
            $delegaciones = Delegacion::whereIn('id', $participantesIds)->orderBy('nombre')->get();
        }

        $sistema = $disciplina->sistema_puntuacion ?? ($disciplina->parent?->sistema_puntuacion ?? 'FUTBOL');

        $stats = [];
        foreach ($delegaciones as $del) {
            $stats[$del->id] = [
                'delegacion' => $del,
                'pj' => 0,
                'pg' => 0,
                'pe' => 0,
                'pp' => 0,
                'gf' => 0,
                'gc' => 0,
                'dg' => 0,
                'puntos' => 0,
            ];
        }

        foreach ($partidosFinalizados as $partido) {
            $localId = $partido->local_id;
            $visitanteId = $partido->visitante_id;
            $localGoles = $partido->local_goles;
            $visitanteGoles = $partido->visitante_goles;

            if ($localGoles === null || $visitanteGoles === null) {
                continue;
            }

            $puntos = $this->calcularPuntosPartido($partido, $sistema);

            if ($localId && isset($stats[$localId])) {
                $stats[$localId]['pj']++;
                $stats[$localId]['gf'] += $localGoles;
                $stats[$localId]['gc'] += $visitanteGoles;
                $stats[$localId]['pg'] += $puntos['local_pg'];
                $stats[$localId]['pe'] += $puntos['local_pe'];
                $stats[$localId]['pp'] += $puntos['local_pp'];
                $stats[$localId]['puntos'] += $puntos['local_pts'];
            }

            if ($visitanteId && isset($stats[$visitanteId])) {
                $stats[$visitanteId]['pj']++;
                $stats[$visitanteId]['gf'] += $visitanteGoles;
                $stats[$visitanteId]['gc'] += $localGoles;
                $stats[$visitanteId]['pg'] += $puntos['vis_pg'];
                $stats[$visitanteId]['pe'] += $puntos['vis_pe'];
                $stats[$visitanteId]['pp'] += $puntos['vis_pp'];
                $stats[$visitanteId]['puntos'] += $puntos['vis_pts'];
            }
        }

        foreach ($stats as $delId => &$data) {
            $data['dg'] = $data['gf'] - $data['gc'];
        }
        unset($data);

        // Ordenar según criterios técnicos oficiales: Puntos DESC, DG DESC, GF DESC, Nombre ASC
        usort($stats, function ($a, $b) {
            if ($a['puntos'] !== $b['puntos']) {
                return $b['puntos'] <=> $a['puntos'];
            }
            if ($a['dg'] !== $b['dg']) {
                return $b['dg'] <=> $a['dg'];
            }
            if ($a['gf'] !== $b['gf']) {
                return $b['gf'] <=> $a['gf'];
            }

            return strcasecmp($a['delegacion']->nombre, $b['delegacion']->nombre);
        });

        $tabla = [];
        $pos = 1;
        foreach ($stats as $item) {
            $item['pos'] = $pos++;
            $tabla[] = $item;
        }

        return $tabla;
    }
}
