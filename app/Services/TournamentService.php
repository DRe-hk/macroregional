<?php

namespace App\Services;

use App\Models\Delegacion;
use App\Models\Disciplina;
use App\Models\Partido;
use App\Models\Serie;

class TournamentService
{
    /**
     * Recalcula la tabla de posiciones general de todas las delegaciones.
     */
    public function recalcularTablaPosiciones(): void
    {
        $delegaciones = Delegacion::all();
        $partidosFinalizados = Partido::where('estado', 'FINALIZADO')->get();

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

            if ($localId && isset($stats[$localId])) {
                $stats[$localId]['pj']++;
                $stats[$localId]['gf'] += $localGoles;
                $stats[$localId]['gc'] += $visitanteGoles;

                if ($localGoles > $visitanteGoles) {
                    $stats[$localId]['pg']++;
                    $stats[$localId]['puntos'] += 3;
                } elseif ($localGoles === $visitanteGoles) {
                    $stats[$localId]['pe']++;
                    $stats[$localId]['puntos'] += 1;
                } else {
                    $stats[$localId]['pp']++;
                }
            }

            if ($visitanteId && isset($stats[$visitanteId])) {
                $stats[$visitanteId]['pj']++;
                $stats[$visitanteId]['gf'] += $visitanteGoles;
                $stats[$visitanteId]['gc'] += $localGoles;

                if ($visitanteGoles > $localGoles) {
                    $stats[$visitanteId]['pg']++;
                    $stats[$visitanteId]['puntos'] += 3;
                } elseif ($visitanteGoles === $localGoles) {
                    $stats[$visitanteId]['pe']++;
                    $stats[$visitanteId]['puntos'] += 1;
                } else {
                    $stats[$visitanteId]['pp']++;
                }
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
     * Actualiza el marcador de un partido y avanza al ganador si corresponde.
     */
    public function actualizarMarcador(
        Partido $partido,
        ?int $localGoles,
        ?int $visitanteGoles,
        ?string $ganadorId = null,
        ?string $observaciones = null
    ): void {
        $partido->local_goles = $localGoles;
        $partido->visitante_goles = $visitanteGoles;
        $partido->observaciones = $observaciones;

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

            // Si es la final de una serie o disciplina, registrar campeón actual
            if ($ganadorId && str_contains(strtolower($partido->ronda_nombre), 'final')) {
                $serie = $partido->serie;
                if ($serie) {
                    $disciplina = $serie->disciplina;
                    $ganador = Delegacion::find($ganadorId);
                    if ($disciplina && $ganador) {
                        $disciplina->update(['campeon_actual' => $ganador->nombre]);
                    }
                }
            }
        } else {
            $partido->estado = 'PROGRAMADO';
            $partido->ganador_id = null;
        }

        $partido->save();
        $this->recalcularTablaPosiciones();
    }

    /**
     * Obtiene la tabla de posiciones individual calculada para una disciplina (o serie específica).
     *
     * @return array<int, array{pos: int, delegacion: Delegacion, pj: int, pg: int, pe: int, pp: int, gf: int, gc: int, dg: int, puntos: int}>
     */
    public function obtenerTablaPorDisciplina(Disciplina $disciplina, ?Serie $serie = null): array
    {
        $seriesIds = $serie ? collect([$serie->id]) : $disciplina->series()->pluck('id');

        $partidos = Partido::whereIn('serie_id', $seriesIds)->get();
        $partidosFinalizados = $partidos->where('estado', 'FINALIZADO');

        // Obtener IDs de delegaciones que participan en estos partidos
        $participantesIds = $partidos->pluck('local_id')
            ->merge($partidos->pluck('visitante_id'))
            ->filter()
            ->unique();

        if ($participantesIds->isEmpty()) {
            $delegaciones = Delegacion::orderBy('nombre')->get();
        } else {
            $delegaciones = Delegacion::whereIn('id', $participantesIds)->orderBy('nombre')->get();
        }

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

            if ($localId && isset($stats[$localId])) {
                $stats[$localId]['pj']++;
                $stats[$localId]['gf'] += $localGoles;
                $stats[$localId]['gc'] += $visitanteGoles;

                if ($localGoles > $visitanteGoles) {
                    $stats[$localId]['pg']++;
                    $stats[$localId]['puntos'] += 3;
                } elseif ($localGoles === $visitanteGoles) {
                    $stats[$localId]['pe']++;
                    $stats[$localId]['puntos'] += 1;
                } else {
                    $stats[$localId]['pp']++;
                }
            }

            if ($visitanteId && isset($stats[$visitanteId])) {
                $stats[$visitanteId]['pj']++;
                $stats[$visitanteId]['gf'] += $visitanteGoles;
                $stats[$visitanteId]['gc'] += $localGoles;

                if ($visitanteGoles > $localGoles) {
                    $stats[$visitanteId]['pg']++;
                    $stats[$visitanteId]['puntos'] += 3;
                } elseif ($visitanteGoles === $localGoles) {
                    $stats[$visitanteId]['pe']++;
                    $stats[$visitanteId]['puntos'] += 1;
                } else {
                    $stats[$visitanteId]['pp']++;
                }
            }
        }

        foreach ($stats as $delId => &$data) {
            $data['dg'] = $data['gf'] - $data['gc'];
        }
        unset($data);

        // Ordenar por: Puntos DESC, DG DESC, GF DESC, Nombre ASC
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

        // Asignar posición 1-indexed
        $tabla = [];
        $pos = 1;
        foreach ($stats as $item) {
            $item['pos'] = $pos++;
            $tabla[] = $item;
        }

        return $tabla;
    }
}
