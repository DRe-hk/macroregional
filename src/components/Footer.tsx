import { getInfoTorneo } from '@/lib/store';

export default function Footer() {
  const info = getInfoTorneo();

  return (
    <footer className="mt-16 border-t border-slate-200 bg-slate-50 text-slate-600">
      <div className="mx-auto max-w-[920px] px-4 py-8 text-center sm:px-6">
        <h2 className="text-xs font-black tracking-widest text-slate-900 uppercase sm:text-sm">
          {info.nombre}
        </h2>
        <span className="mx-auto my-2.5 block h-px w-16 bg-slate-300" aria-hidden="true" />
        <p className="text-xs font-medium text-slate-600">
          Organizado por <strong className="font-extrabold text-slate-900">{info.organizador}</strong>{' '}
          · Sede: {info.sedePrincipal} · <span className="font-bold">© {info.anio}</span>
        </p>
        <p className="mt-1 text-[11px] text-slate-400">
          Plataforma oficial de cobertura y gestión de fixture deportivo en tiempo real.
        </p>
      </div>
    </footer>
  );
}
