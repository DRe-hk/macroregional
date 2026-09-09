'use client';

import { Trophy, CheckCircle2, Ticket, MapPin } from 'lucide-react';
import { Serie, Partido } from '@/lib/types';

interface TournamentBracketProps {
  serie: Serie;
  disciplinaNombre: string;
  disciplinaSlug: string;
}

export default function TournamentBracket({
  serie,
  disciplinaNombre,
  disciplinaSlug,
}: TournamentBracketProps) {
  // Agrupar partidos por rondas
  const rondasMap: Record<number, Partido[]> = {};
  (serie.partidos || []).forEach((partido) => {
    if (!rondasMap[partido.rondaNumero]) {
      rondasMap[partido.rondaNumero] = [];
    }
    rondasMap[partido.rondaNumero].push(partido);
  });

  const rondasOrdenadas = Object.keys(rondasMap)
    .map(Number)
    .sort((a, b) => a - b);

  return (
    <div className="w-full space-y-4">
      {/* Barra de cabecera de la serie */}
      <div className="flex flex-wrap items-center justify-between gap-3 bg-slate-50 border border-slate-200 rounded-xl p-4">
        <div>
          <span className="text-[11px] font-bold uppercase tracking-wider text-slate-500">
            {disciplinaNombre}
          </span>
          <h2 className="text-xl font-black text-slate-900">{serie.nombre}</h2>
        </div>

        <div className="flex items-center gap-2">
          {serie.sedeNombre && (
            <span className="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 bg-white border border-slate-200 px-3 py-1.5 rounded-lg shadow-2xs">
              <MapPin className="size-3.5 text-slate-400" />
              <span>{serie.sedeNombre}</span>
            </span>
          )}
        </div>
      </div>

      {/* Árbol de Eliminatorias / Bracket Scrollable */}
      {rondasOrdenadas.length > 0 ? (
        <div className="overflow-x-auto rounded-xl border border-slate-200 bg-white p-5 bracket-scroll">
          <div className="min-w-[700px] flex items-stretch gap-6 pb-2">
            {rondasOrdenadas.map((rondaNum) => {
              const partidosRonda = rondasMap[rondaNum];
              const tituloRonda =
                rondaNum === 1
                  ? 'Ronda Inicial'
                  : rondaNum === 2
                  ? 'Segunda Ronda'
                  : rondaNum === 3
                  ? 'Semifinales'
                  : 'Final';

              return (
                <div key={rondaNum} className="flex-1 flex flex-col min-w-[220px]">
                  {/* Encabezado de la Ronda */}
                  <div className="mb-3 text-center">
                    <span className="inline-block rounded-md bg-slate-100 border border-slate-200 px-3 py-1 text-xs font-extrabold uppercase tracking-wider text-slate-700">
                      {tituloRonda}
                    </span>
                  </div>

                  {/* Partidos de la Ronda */}
                  <div className="flex flex-1 flex-col justify-around gap-4">
                    {partidosRonda.map((partido) => {
                      if (partido.esBye) {
                        return (
                          <div
                            key={partido.id}
                            className="rounded-lg border border-amber-200 bg-amber-50/60 p-3"
                          >
                            <div className="flex items-center justify-between text-[10px] font-black uppercase text-amber-800 mb-1">
                              <span>Pase Directo</span>
                              <Ticket className="size-3" />
                            </div>
                            <div className="flex items-center justify-between font-bold text-sm text-slate-900">
                              <span>{partido.localNombre}</span>
                              <CheckCircle2 className="size-4 text-emerald-600" />
                            </div>
                          </div>
                        );
                      }

                      const localGano =
                        partido.jugado &&
                        (partido.ganadorId === partido.localId ||
                          (partido.localGoles ?? 0) > (partido.visitanteGoles ?? 0));
                      const visitanteGano =
                        partido.jugado &&
                        (partido.ganadorId === partido.visitanteId ||
                          (partido.visitanteGoles ?? 0) > (partido.localGoles ?? 0));

                      return (
                        <div
                          key={partido.id}
                          className="rounded-lg border border-slate-200 bg-white shadow-2xs overflow-hidden"
                        >
                          {/* Cabecera del Partido */}
                          <div className="flex items-center justify-between bg-slate-100 px-3 py-1 text-[11px] font-bold text-slate-600 border-b border-slate-200">
                            <span>{partido.rondaNombre}</span>
                            <span className="text-[10px] font-medium text-slate-500">
                              {partido.jugado ? 'Jugado' : partido.horario || 'Programado'}
                            </span>
                          </div>

                          {/* Equipo Local */}
                          <div
                            className={`flex items-center justify-between px-3 py-2 border-b border-slate-100 ${
                              localGano ? 'bg-emerald-50/70 font-black text-emerald-950' : 'text-slate-800'
                            }`}
                          >
                            <span className="text-sm truncate pr-2">
                              {partido.localNombre || 'TBD'}
                            </span>
                            <span
                              className={`grid min-w-7 place-items-center rounded px-1.5 py-0.5 text-xs font-black tabular-nums ${
                                localGano
                                  ? 'bg-emerald-600 text-white'
                                  : 'bg-slate-100 text-slate-700'
                              }`}
                            >
                              {partido.jugado ? partido.localGoles : '-'}
                            </span>
                          </div>

                          {/* Equipo Visitante */}
                          <div
                            className={`flex items-center justify-between px-3 py-2 ${
                              visitanteGano ? 'bg-emerald-50/70 font-black text-emerald-950' : 'text-slate-800'
                            }`}
                          >
                            <span className="text-sm truncate pr-2">
                              {partido.visitanteNombre || 'TBD'}
                            </span>
                            <span
                              className={`grid min-w-7 place-items-center rounded px-1.5 py-0.5 text-xs font-black tabular-nums ${
                                visitanteGano
                                  ? 'bg-emerald-600 text-white'
                                  : 'bg-slate-100 text-slate-700'
                              }`}
                            >
                              {partido.jugado ? partido.visitanteGoles : '-'}
                            </span>
                          </div>
                        </div>
                      );
                    })}
                  </div>
                </div>
              );
            })}
          </div>
        </div>
      ) : (
        <div className="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
          <p className="text-sm font-semibold text-slate-600">
            No hay partidos programados todavía en esta serie.
          </p>
          <p className="text-xs text-slate-400 mt-1">
            La programación oficial de encuentros será publicada próximamente.
          </p>
        </div>
      )}
    </div>
  );
}
