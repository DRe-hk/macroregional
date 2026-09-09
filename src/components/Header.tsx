'use client';

import Link from 'next/link';
import { usePathname } from 'next/navigation';
import { Trophy, LogIn, Menu, X } from 'lucide-react';
import { useState } from 'react';

const NAV_ITEMS = [
  { href: '/', label: 'Deportes' },
  { href: '/clasificacion', label: 'Tabla de Posiciones' },
  { href: '/equipos', label: 'Delegaciones' },
];

export default function Header() {
  const pathname = usePathname();
  const [menuAbierto, setMenuAbierto] = useState(false);

  return (
    <header className="sticky top-0 z-50 bg-white border-b border-slate-200">
      <div className="mx-auto flex w-full max-w-[1360px] items-center justify-between gap-4 px-4 py-3 sm:px-6">
        {/* Marca / Logo Deportivo */}
        <Link href="/" className="flex items-center gap-3 shrink-0 group">
          <span className="grid size-9 sm:size-10 place-items-center rounded-lg bg-slate-900 text-white font-black text-base transition-transform group-hover:scale-105">
            <Trophy className="size-5" />
          </span>
          <div className="flex flex-col">
            <span className="font-extrabold tracking-tight text-base sm:text-lg text-slate-900 leading-tight">
              MACROREGIONAL 2026
            </span>
            <span className="text-[10px] font-bold text-slate-500 tracking-wider uppercase">
              Competencia Deportiva Oficial
            </span>
          </div>
        </Link>

        {/* Navegación Desktop */}
        <nav className="hidden md:flex items-center gap-1">
          {NAV_ITEMS.map((item) => {
            const isActive =
              pathname === item.href ||
              (item.href !== '/' && pathname.startsWith(item.href));

            return (
              <Link
                key={item.href}
                href={item.href}
                className={`px-3.5 py-1.5 text-sm font-bold rounded-lg transition-colors ${
                  isActive
                    ? 'text-slate-950 bg-slate-100 font-extrabold'
                    : 'text-slate-600 hover:text-slate-950 hover:bg-slate-50'
                }`}
              >
                {item.label}
              </Link>
            );
          })}
        </nav>

        {/* Botón Ingresar */}
        <div className="flex items-center gap-2">
          <Link
            href="/entrar"
            className="flex items-center gap-2 rounded-lg bg-slate-900 px-3.5 py-2 text-xs font-bold text-white shadow-xs transition-colors hover:bg-slate-800"
          >
            <LogIn className="size-3.5" />
            <span>Ingresar</span>
          </Link>

          <button
            type="button"
            onClick={() => setMenuAbierto(!menuAbierto)}
            className="md:hidden p-2 rounded-lg text-slate-700 hover:bg-slate-100"
            aria-label="Abrir menú"
          >
            {menuAbierto ? <X className="size-5" /> : <Menu className="size-5" />}
          </button>
        </div>
      </div>

      {/* Menú Móvil */}
      {menuAbierto && (
        <nav className="md:hidden border-t border-slate-200 bg-white px-4 py-3 flex flex-col gap-1">
          {NAV_ITEMS.map((item) => {
            const isActive =
              pathname === item.href ||
              (item.href !== '/' && pathname.startsWith(item.href));

            return (
              <Link
                key={item.href}
                href={item.href}
                onClick={() => setMenuAbierto(false)}
                className={`px-3 py-2 rounded-lg text-sm font-bold ${
                  isActive
                    ? 'text-slate-950 bg-slate-100 font-extrabold'
                    : 'text-slate-600 hover:bg-slate-50'
                }`}
              >
                {item.label}
              </Link>
            );
          })}
          <Link
            href="/entrar"
            onClick={() => setMenuAbierto(false)}
            className="mt-2 flex items-center justify-center gap-2 rounded-lg bg-slate-900 px-3.5 py-2.5 text-xs font-bold text-white hover:bg-slate-800"
          >
            <LogIn className="size-3.5" />
            <span>Ingresar</span>
          </Link>
        </nav>
      )}
    </header>
  );
}
