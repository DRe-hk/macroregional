import Link from 'next/link';
import { ArrowRight, MapPin } from 'lucide-react';
import { Disciplina } from '@/lib/types';

interface DisciplinaCardProps {
  disciplina: Disciplina;
}

export default function DisciplinaCard({ disciplina }: DisciplinaCardProps) {
  return (
    <Link
      href={`/d/${disciplina.slug}`}
      className="group flex flex-col justify-between overflow-hidden rounded-xl border border-slate-200 bg-white transition-all duration-150 hover:border-slate-400 hover:shadow-sm"
    >
      {/* Imagen del deporte */}
      <div className="relative aspect-[16/10] w-full overflow-hidden bg-slate-100">
        <img
          src={disciplina.fotoUrl}
          alt={disciplina.nombre}
          className="size-full object-cover transition-transform duration-300 group-hover:scale-105"
          loading="lazy"
        />
        <div className="absolute top-2.5 left-2.5">
          <span className="inline-block rounded-md bg-white/90 px-2 py-0.5 text-[11px] font-extrabold uppercase tracking-wider text-slate-800 backdrop-blur-xs shadow-xs">
            {disciplina.categoria}
          </span>
        </div>
      </div>

      {/* Información del deporte */}
      <div className="p-4 flex flex-col justify-between flex-1">
        <div>
          <h3 className="text-lg font-black text-slate-950 tracking-tight leading-snug group-hover:text-blue-600 transition-colors">
            {disciplina.nombre}
          </h3>
          <p className="mt-1 text-xs text-slate-500 font-medium flex items-center gap-1 line-clamp-1">
            <MapPin className="size-3 text-slate-400 shrink-0" />
            <span>{disciplina.sedePrincipal}</span>
          </p>
        </div>

        <div className="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-700">
          <span>{disciplina.series?.length || 0} series creadas</span>
          <span className="flex items-center gap-1 text-blue-600 group-hover:translate-x-0.5 transition-transform">
            Ver fixture <ArrowRight className="size-3.5" />
          </span>
        </div>
      </div>
    </Link>
  );
}
