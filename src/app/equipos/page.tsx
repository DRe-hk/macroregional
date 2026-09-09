import { getDelegaciones, getInfoTorneo } from '@/lib/store';
import { Users, MapPin } from 'lucide-react';

export const metadata = {
  title: 'Delegaciones Participantes · Competencia Deportiva Macroregional',
  description: 'Directorio de instituciones y delegaciones deportivas participantes en la competencia macroregional.',
};

export default function EquiposPage() {
  const delegaciones = getDelegaciones();
  const info = getInfoTorneo();

  return (
    <div className="bg-white min-h-screen pb-16">
      {/* Cabecera */}
      <div className="border-b border-slate-200 bg-slate-50 py-8 px-4 sm:px-6">
        <div className="mx-auto max-w-[1360px] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <span className="text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
              Participantes Oficiales
            </span>
            <h1 className="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight mt-0.5">
              Delegaciones Participantes
            </h1>
            <p className="text-xs sm:text-sm text-slate-600 font-medium mt-1">
              {delegaciones.length} delegaciones inscritas en la competencia macroregional.
            </p>
          </div>

          <div className="self-start sm:self-auto">
            <span className="inline-flex items-center gap-1.5 rounded-full bg-white border border-slate-200 px-3.5 py-1.5 text-xs font-bold text-slate-700 shadow-2xs">
              <Users className="size-3.5 text-slate-500" />
              <span>{delegaciones.length} delegaciones participantes</span>
            </span>
          </div>
        </div>
      </div>

      <div className="mx-auto max-w-[1360px] px-4 sm:px-6 mt-8">
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          {delegaciones.map((del) => (
            <div
              key={del.id}
              className="rounded-xl border border-slate-200 bg-white p-4 flex items-center justify-between hover:border-slate-300 transition-colors shadow-2xs"
            >
              <div>
                <div className="flex items-center gap-1 text-[11px] text-slate-400 font-bold uppercase mb-0.5">
                  <MapPin className="size-3" />
                  <span>{del.provincia}</span>
                </div>
                <h3 className="font-extrabold text-base text-slate-950">{del.nombre}</h3>
                <span className="text-xs font-bold text-blue-600">Siglas: {del.siglas}</span>
              </div>

              <div className="text-right pl-3 border-l border-slate-100">
                <span className="block text-xl font-black text-slate-900 tabular-nums">
                  {del.puntos || 0}
                </span>
                <span className="text-[10px] font-bold text-slate-400 uppercase">Puntos</span>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
