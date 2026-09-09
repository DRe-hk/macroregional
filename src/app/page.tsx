import Hero from '@/components/Hero';
import DisciplinaCard from '@/components/DisciplinaCard';
import { getDisciplinas, getInfoTorneo, getDelegaciones } from '@/lib/store';
import Link from 'next/link';
import { Trophy, ArrowRight, Shield } from 'lucide-react';

export default function HomePage() {
  const info = getInfoTorneo();
  const disciplinas = getDisciplinas();
  const delegaciones = getDelegaciones();

  return (
    <div className="bg-white min-h-screen">
      {/* Hero Deportivo Minimalista */}
      <Hero
        titulo={info.nombre}
        subtitulo={info.subtitulo}
        organizador={info.organizador}
        totalDisciplinas={disciplinas.length}
        totalEquipos={delegaciones.length}
      />

      {/* Contenedor Principal */}
      <div className="mx-auto max-w-[1360px] px-4 py-8 sm:px-6 space-y-10">
        {/* Barra de Título */}
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
          <div>
            <h2 className="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
              <Trophy className="size-6 text-slate-800" />
              Disciplinas Deportivas
            </h2>
            <p className="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">
              Selecciona un deporte para ver sus series, fixture y árbol de eliminatorias en vivo.
            </p>
          </div>

          <div className="flex items-center gap-2 shrink-0">
            <span className="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3.5 py-1.5 text-xs font-extrabold text-slate-700">
              {disciplinas.length} Deportes en competencia
            </span>
          </div>
        </div>

        {/* Cuadrícula de Deportes */}
        {disciplinas.length > 0 ? (
          <div className="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3">
            {disciplinas.map((disciplina) => (
              <DisciplinaCard key={disciplina.id} disciplina={disciplina} />
            ))}
          </div>
        ) : (
          <div className="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-12 text-center">
            <Trophy className="mx-auto size-12 text-slate-400 mb-3" />
            <h3 className="text-lg font-black text-slate-800">No hay disciplinas registradas</h3>
            <p className="text-sm text-slate-500 mt-1 max-w-sm mx-auto">
              Próximamente se publicará la programación de los deportes en competencia.
            </p>
          </div>
        )}

        {/* Enlace Directo a Tabla de Posiciones */}
        <div className="rounded-xl border border-slate-200 bg-slate-50 p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div className="flex items-center gap-3">
            <div className="grid size-10 place-items-center rounded-lg bg-white border border-slate-200 text-slate-900 font-black">
              #1
            </div>
            <div>
              <h3 className="text-base font-black text-slate-900">Tabla General de Posiciones</h3>
              <p className="text-xs text-slate-500 font-medium">
                Consulta los puntos acumulados por cada una de las 15 UGELs en tiempo real.
              </p>
            </div>
          </div>

          <Link
            href="/clasificacion"
            className="inline-flex items-center gap-1.5 rounded-lg bg-slate-900 px-4 py-2 text-xs font-bold text-white hover:bg-slate-800 transition-colors shrink-0"
          >
            <span>Ver Tabla Completa</span>
            <ArrowRight className="size-3.5" />
          </Link>
        </div>
      </div>
    </div>
  );
}
