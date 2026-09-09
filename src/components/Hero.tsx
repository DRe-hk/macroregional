import { Trophy, Users, Shield, Calendar } from 'lucide-react';
import Link from 'next/link';

interface HeroProps {
  titulo: string;
  subtitulo: string;
  organizador: string;
  totalDisciplinas: number;
  totalEquipos: number;
}

export default function Hero({
  titulo,
  subtitulo,
  organizador,
  totalDisciplinas,
  totalEquipos,
}: HeroProps) {
  return (
    <div className="bg-slate-50 border-b border-slate-200 py-10 sm:py-14 px-4 sm:px-6">
      <div className="mx-auto max-w-[1360px]">
        <div className="flex flex-col md:flex-row md:items-end justify-between gap-6">
          <div className="max-w-3xl">
            <div className="inline-flex items-center gap-1.5 rounded-md bg-slate-200/80 px-2.5 py-1 text-[11px] font-extrabold uppercase tracking-wider text-slate-700 mb-3">
              <Shield className="size-3.5 text-slate-800" />
              <span>{organizador}</span>
            </div>

            <h1 className="text-3xl sm:text-5xl font-black tracking-tight text-slate-950 leading-tight">
              {titulo}
            </h1>

            <p className="mt-3 text-base sm:text-lg text-slate-600 font-medium">
              {subtitulo}
            </p>
          </div>

          {/* Métricas del Torneo */}
          <div className="flex items-center gap-4 sm:gap-6 border-t md:border-t-0 md:border-l border-slate-200 pt-4 md:pt-0 md:pl-6">
            <div className="text-left">
              <span className="block text-2xl sm:text-3xl font-black text-slate-900 tabular-nums">
                {totalDisciplinas}
              </span>
              <span className="text-xs font-bold text-slate-500 uppercase tracking-wider">
                Deportes
              </span>
            </div>

            <div className="h-8 w-px bg-slate-200" />

            <div className="text-left">
              <span className="block text-2xl sm:text-3xl font-black text-slate-900 tabular-nums">
                {totalEquipos}
              </span>
              <span className="text-xs font-bold text-slate-500 uppercase tracking-wider">
                Delegaciones
              </span>
            </div>

            <div className="h-8 w-px bg-slate-200" />

            <div className="text-left">
              <span className="block text-2xl sm:text-3xl font-black text-slate-900 tabular-nums">
                2026
              </span>
              <span className="text-xs font-bold text-slate-500 uppercase tracking-wider">
                Edición
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
