import { getDisciplinaBySlug } from '@/lib/store';
import TournamentBracket from '@/components/TournamentBracket';
import { notFound } from 'next/navigation';
import Link from 'next/link';
import { ChevronRight, MapPin, Trophy } from 'lucide-react';

export default async function DisciplinaPage({
  params,
  searchParams,
}: {
  params: Promise<{ slug: string }>;
  searchParams: Promise<{ serie?: string }>;
}) {
  const { slug } = await params;
  const { serie: serieParam } = await searchParams;

  const disciplina = getDisciplinaBySlug(slug);

  if (!disciplina) {
    notFound();
  }

  // Determinar la serie activa
  const series = disciplina.series || [];
  const letraActiva = (serieParam || series[0]?.letra || 'A').toUpperCase();
  const serieSeleccionada =
    series.find((s) => s.letra.toUpperCase() === letraActiva) || series[0];

  return (
    <div className="bg-white min-h-screen pb-16">
      {/* Cabecera Minimalista */}
      <div className="border-b border-slate-200 bg-slate-50 py-8 px-4 sm:px-6">
        <div className="mx-auto max-w-[1360px]">
          {/* Breadcrumb */}
          <nav className="flex items-center gap-1.5 text-xs font-bold text-slate-500 mb-2">
            <Link href="/" className="hover:text-slate-900 transition-colors">
              Deportes
            </Link>
            <ChevronRight className="size-3 text-slate-400" />
            <span className="text-slate-900">{disciplina.nombre}</span>
          </nav>

          <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
              <span className="inline-block text-[11px] font-extrabold uppercase tracking-wider text-blue-600 bg-blue-50 px-2 py-0.5 rounded border border-blue-200 mb-1">
                {disciplina.categoria}
              </span>
              <h1 className="text-2xl sm:text-4xl font-black text-slate-950 tracking-tight">
                {disciplina.nombre}
              </h1>
              {disciplina.descripcion && (
                <p className="mt-1 text-xs sm:text-sm text-slate-600 font-medium max-w-2xl">
                  {disciplina.descripcion}
                </p>
              )}
            </div>

            <div className="flex items-center gap-2">
              {disciplina.sedePrincipal && (
                <div className="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-xs font-bold text-slate-700">
                  <MapPin className="size-3.5 text-slate-500" />
                  <span>{disciplina.sedePrincipal}</span>
                </div>
              )}
            </div>
          </div>
        </div>
      </div>

      <div className="mx-auto max-w-[1360px] px-4 sm:px-6 mt-8 space-y-6">
        {/* Selector de Series */}
        {series.length > 0 && (
          <div className="flex items-center gap-2 border-b border-slate-200 pb-2 overflow-x-auto">
            {series.map((s) => {
              const esActiva = s.letra.toUpperCase() === letraActiva;

              return (
                <Link
                  key={s.letra}
                  href={`/d/${disciplina.slug}?serie=${s.letra}`}
                  className={`flex items-center gap-1.5 px-4 py-2 text-xs font-black uppercase tracking-wider rounded-lg transition-colors shrink-0 ${
                    esActiva
                      ? 'bg-slate-900 text-white shadow-2xs'
                      : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'
                  }`}
                >
                  <span>{s.nombre}</span>
                </Link>
              );
            })}
          </div>
        )}

        {/* Visualizador de Llaves / Bracket */}
        {serieSeleccionada ? (
          <TournamentBracket
            serie={serieSeleccionada}
            disciplinaNombre={disciplina.nombre}
            disciplinaSlug={disciplina.slug}
          />
        ) : (
          <div className="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
            <Trophy className="mx-auto size-10 text-slate-400 mb-2" />
            <h3 className="text-base font-black text-slate-800">
              No hay series configuradas para esta disciplina
            </h3>
            <p className="mt-1 text-xs text-slate-500 max-w-sm mx-auto">
              Próximamente se publicará el fixture y las llaves de competencia.
            </p>
          </div>
        )}
      </div>
    </div>
  );
}
