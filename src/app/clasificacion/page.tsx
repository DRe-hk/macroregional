import { getClasificacionGeneral, getInfoTorneo } from '@/lib/store';
import StandingsTable from '@/components/StandingsTable';
import { Trophy } from 'lucide-react';

export const metadata = {
  title: 'Tabla de Posiciones · Competencia Deportiva Macroregional',
  description: 'Clasificación general por delegación y puntos acumulados en la competencia macroregional.',
};

export default function ClasificacionPage() {
  const info = getInfoTorneo();
  const clasificacion = getClasificacionGeneral();
  const lider = clasificacion[0];

  return (
    <div className="bg-white min-h-screen pb-16">
      {/* Cabecera Minimalista */}
      <div className="border-b border-slate-200 bg-slate-50 py-8 px-4 sm:px-6">
        <div className="mx-auto max-w-[1360px]">
          <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
              <span className="text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                Puntaje Oficial
              </span>
              <h1 className="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight mt-0.5">
                Tabla de Posiciones
              </h1>
              <p className="text-xs sm:text-sm text-slate-600 font-medium mt-1">
                {info.nombre} · {info.subtitulo}
              </p>
            </div>

            <div className="self-start sm:self-auto">
              <span className="inline-flex items-center gap-1.5 rounded-full bg-white border border-slate-200 px-3.5 py-1.5 text-xs font-bold text-slate-700 shadow-2xs">
                <Trophy className="size-3.5 text-amber-500" />
                <span>Puntaje oficial en vivo</span>
              </span>
            </div>
          </div>
        </div>
      </div>

      <div className="mx-auto max-w-[1360px] px-4 sm:px-6 mt-8 space-y-6">
        {/* Tabla Deportiva */}
        <StandingsTable items={clasificacion} />
      </div>
    </div>
  );
}
