import { getDisciplinas } from '@/lib/store';
import { Trophy, Award, ArrowRight } from 'lucide-react';
import Link from 'next/link';

export const metadata = {
  title: 'Cuadro de Campeones · Competencia Deportiva Macroregional',
  description: 'Campeones y ganadores por disciplina deportiva en la competencia macroregional.',
};

export default function CampeonesPage() {
  const disciplinas = getDisciplinas();
  const disciplinasConCampeon = disciplinas.filter((d) => d.campeonActual);

  return (
    <div className="bg-white min-h-screen pb-16">
      {/* Cabecera */}
      <div className="border-b border-slate-200 bg-slate-50 py-8 px-4 sm:px-6">
        <div className="mx-auto max-w-[1360px]">
          <span className="text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
            Palmarés Oficial
          </span>
          <h1 className="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight mt-0.5">
            Cuadro de Campeones
          </h1>
          <p className="text-xs sm:text-sm text-slate-600 font-medium mt-1">
            Ganadores oficiales por cada disciplina deportiva de la competencia macroregional.
          </p>
        </div>
      </div>

      <div className="mx-auto max-w-[1360px] px-4 sm:px-6 mt-8">
        {disciplinasConCampeon.length > 0 ? (
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            {disciplinasConCampeon.map((d) => (
              <div
                key={d.id}
                className="rounded-xl border border-slate-200 bg-white p-5 shadow-2xs hover:border-slate-300 transition-colors"
              >
                <div className="flex items-center justify-between border-b border-slate-100 pb-3 mb-3">
                  <div>
                    <span className="text-[10px] font-extrabold uppercase text-blue-600 block">
                      {d.categoria}
                    </span>
                    <h3 className="font-black text-base text-slate-900">{d.nombre}</h3>
                  </div>
                  <Link
                    href={`/d/${d.slug}`}
                    className="text-xs font-bold text-blue-600 hover:underline flex items-center gap-1"
                  >
                    Ver llave <ArrowRight className="size-3" />
                  </Link>
                </div>

                <div className="flex items-center justify-between rounded-lg bg-amber-50 p-3 border border-amber-200">
                  <div className="flex items-center gap-2.5">
                    <Trophy className="size-5 text-amber-600 shrink-0" />
                    <div>
                      <span className="text-[10px] font-black uppercase tracking-wider text-amber-800 block">
                        Campeón
                      </span>
                      <span className="font-black text-sm text-amber-950">
                        {d.campeonActual}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            ))}
          </div>
        ) : (
          <div className="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-12 text-center">
            <Trophy className="mx-auto size-12 text-slate-300 mb-3" />
            <h3 className="text-base font-black text-slate-800">
              El torneo está en fase de eliminatorias
            </h3>
            <p className="text-xs sm:text-sm text-slate-500 mt-1 max-w-md mx-auto">
              Aún no se han definido los campeones de las finales. Conforme se concluyan los
              partidos y se confirmen los marcadores oficiales, aparecerán aquí.
            </p>
            <Link
              href="/"
              className="mt-4 inline-block rounded-lg bg-slate-900 px-4 py-2 text-xs font-bold text-white hover:bg-slate-800"
            >
              Ver Partidos y Fixture
            </Link>
          </div>
        )}
      </div>
    </div>
  );
}
