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
}
