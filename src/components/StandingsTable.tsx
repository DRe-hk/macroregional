'use client';

import { ClasificacionItem } from '@/lib/types';
import { Trophy, Medal, Award } from 'lucide-react';

interface StandingsTableProps {
  items: ClasificacionItem[];
}

export default function StandingsTable({ items }: StandingsTableProps) {
  return (
    <div className="w-full">
      <div className="overflow-x-auto rounded-xl border border-slate-200 bg-white">
        <table className="w-full text-left border-collapse text-sm">
          <thead>
            <tr className="bg-slate-50 border-b border-slate-200 text-xs font-black uppercase text-slate-700 tracking-wider">
              <th className="sticky left-0 z-20 bg-slate-50 px-4 py-3 border-r border-slate-200">
                Puesto & Delegación
              </th>
              <th className="px-4 py-3 text-slate-500 font-semibold">Provincia</th>
              <th className="px-3 py-3 text-center">PJ</th>
              <th className="px-3 py-3 text-center">PG</th>
              <th className="px-3 py-3 text-center">PP</th>
              <th className="px-4 py-3 text-right font-black text-slate-900">Puntos</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-slate-100">
            {items.map((item) => (
              <tr key={item.delegacionId} className="hover:bg-slate-50 transition-colors">
                {/* Columna fija de delegación */}
                <td className="sticky left-0 z-10 bg-inherit px-4 py-3 border-r border-slate-200">
                  <div className="flex items-center gap-3">
                    <span
                      className={`grid size-6 place-items-center rounded-md text-xs font-black shrink-0 ${
                        item.puesto === 1
                          ? 'bg-amber-100 text-amber-900 border border-amber-300'
                          : item.puesto === 2
                          ? 'bg-slate-100 text-slate-800 border border-slate-300'
                          : item.puesto === 3
                          ? 'bg-orange-100 text-orange-900 border border-orange-300'
                          : 'text-slate-500 bg-slate-50'
                      }`}
                    >
                      {item.puesto}
                    </span>
                    <div>
                      <span className="font-extrabold text-slate-900 whitespace-nowrap block">
                        {item.delegacionNombre}
                      </span>
                      <span className="text-[11px] font-semibold text-slate-500">
                        {item.siglas}
                      </span>
                    </div>
                  </div>
                </td>

                <td className="px-4 py-3 text-slate-600 font-medium whitespace-nowrap">
                  {item.provincia}
                </td>

                <td className="px-3 py-3 text-center tabular-nums font-bold text-slate-700">
                  {item.partidosJugados}
                </td>

                <td className="px-3 py-3 text-center tabular-nums font-bold text-emerald-700">
                  {item.partidosGanados}
                </td>

                <td className="px-3 py-3 text-center tabular-nums font-bold text-slate-500">
                  {item.partidosPerdidos}
                </td>

                <td className="px-4 py-3 text-right tabular-nums font-black text-base text-slate-950">
                  {item.puntos}
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      <p className="mt-2.5 text-center text-xs text-slate-500 font-medium">
        Cálculo oficial: Victoria = 3 pts | Empate = 1 pt | Derrota = 0 pts.
      </p>
    </div>
  );
}
