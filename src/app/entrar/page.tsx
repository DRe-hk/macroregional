'use client';

import { useState, useEffect } from 'react';
import { useRouter } from 'next/navigation';
import Link from 'next/link';
import { Trophy, ArrowLeft, LogIn, AlertCircle, CheckCircle2 } from 'lucide-react';

export default function EntrarPage() {
  const router = useRouter();
  const [usuario, setUsuario] = useState('');
  const [clave, setClave] = useState('');
  const [cargando, setCargando] = useState(false);
  const [error, setError] = useState('');
  const [mensajeExito, setMensajeExito] = useState('');
  const [sesionActiva, setSesionActiva] = useState<{ rol: string; nombre: string } | null>(null);

  useEffect(() => {
    // Detectar si ya hay una sesión previa
    const rawDelegado = localStorage.getItem('drep_delegado_session');
    const rawAdmin = localStorage.getItem('drep_admin_session');

    if (rawDelegado) {
      try {
        const u = JSON.parse(rawDelegado);
        setSesionActiva({ rol: 'DELEGADO', nombre: u.nombre || 'Delegado' });
      } catch {
        localStorage.removeItem('drep_delegado_session');
      }
    } else if (rawAdmin === 'true') {
      setSesionActiva({ rol: 'ADMIN', nombre: 'Administrador General' });
    }
  }, []);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!usuario.trim() || !clave.trim()) {
      setError('Por favor ingresa tu usuario y contraseña.');
      return;
    }

    setCargando(true);
    setError('');
    setMensajeExito('');

    try {
      const res = await fetch('/api/auth/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ usuario: usuario.trim(), clave: clave.trim() }),
      });

      const data = await res.json();

      if (!res.ok) {
        setError(data.error || 'Credenciales incorrectas. Verifica tus datos.');
        setCargando(false);
        return;
      }

      // Guardar información de sesión de acuerdo al rol
      if (data.user?.rol === 'ADMIN') {
        localStorage.setItem('drep_admin_session', 'true');
        localStorage.setItem('drep_user_session', JSON.stringify(data.user));
        setMensajeExito('Acceso autorizado como Administrador. Redirigiendo a /admin...');
        setTimeout(() => {
          router.push('/admin');
        }, 400);
      } else if (data.user?.rol === 'DELEGADO') {
        localStorage.setItem('drep_delegado_session', JSON.stringify(data.user));
        localStorage.setItem('drep_user_session', JSON.stringify(data.user));
        setMensajeExito('Acceso autorizado como Delegado. Redirigiendo a tu portal...');
        setTimeout(() => {
          router.push('/delegado');
        }, 400);
      } else {
        setError('Rol de usuario no reconocido en el sistema.');
        setCargando(false);
      }
    } catch {
      setError('Error al comunicarse con el servidor. Intenta de nuevo.');
      setCargando(false);
    }
  };

  const handleCerrarSesionPrevia = () => {
    localStorage.removeItem('drep_admin_session');
    localStorage.removeItem('drep_delegado_session');
    localStorage.removeItem('drep_user_session');
    setSesionActiva(null);
  };

  return (
    <div className="min-h-[85vh] flex items-center justify-center py-12 px-4 sm:px-6 bg-slate-50/50">
      <div className="w-full max-w-md">
        {/* Cabecera del formulario */}
        <div className="text-center mb-8">
          <Link
            href="/"
            className="inline-flex items-center gap-2 mb-6 text-xs font-bold text-slate-500 hover:text-slate-900 transition-colors"
          >
            <ArrowLeft className="size-3.5" />
            <span>Volver al inicio</span>
          </Link>

          <div className="mx-auto size-12 rounded-xl bg-slate-900 text-white flex items-center justify-center shadow-xs mb-3">
            <Trophy className="size-6" />
          </div>

          <h1 className="text-2xl font-black text-slate-900 tracking-tight">
            Acceso al Sistema
          </h1>
          <p className="mt-1 text-xs sm:text-sm text-slate-500 font-medium">
            Ingreso oficial para delegaciones de la Competencia Macroregional
          </p>
        </div>

        {/* Tarjeta de Sesión Activa (si ya hay una) */}
        {sesionActiva && (
          <div className="mb-6 rounded-xl border border-blue-200 bg-blue-50/60 p-4 text-xs">
            <div className="flex items-center justify-between">
              <div>
                <p className="font-extrabold text-blue-950">
                  Sesión activa: {sesionActiva.nombre}
                </p>
                <p className="text-blue-700 font-medium mt-0.5">
                  Rol: {sesionActiva.rol === 'ADMIN' ? 'Administrador General' : 'Delegado de UGEL'}
                </p>
              </div>
              <div className="flex items-center gap-2">
                <Link
                  href={sesionActiva.rol === 'ADMIN' ? '/admin' : '/delegado'}
                  className="rounded-lg bg-blue-600 px-3 py-1.5 font-bold text-white hover:bg-blue-700 transition-colors"
                >
                  Entrar
                </Link>
                <button
                  type="button"
                  onClick={handleCerrarSesionPrevia}
                  className="rounded-lg border border-blue-300 bg-white px-3 py-1.5 font-bold text-blue-800 hover:bg-blue-100 transition-colors cursor-pointer"
                >
                  Cambiar
                </button>
              </div>
            </div>
          </div>
        )}

        {/* Tarjeta del Formulario */}
        <div className="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-xs">
          <form onSubmit={handleSubmit} className="space-y-4">
            {error && (
              <div className="flex items-start gap-2.5 rounded-lg border border-red-200 bg-red-50 p-3 text-xs text-red-700 font-medium">
                <AlertCircle className="size-4 shrink-0 mt-0.5" />
                <span>{error}</span>
              </div>
            )}

            {mensajeExito && (
              <div className="flex items-start gap-2.5 rounded-lg border border-emerald-200 bg-emerald-50 p-3 text-xs text-emerald-800 font-medium">
                <CheckCircle2 className="size-4 shrink-0 mt-0.5" />
                <span>{mensajeExito}</span>
              </div>
            )}

            <div>
              <label
                htmlFor="usuario"
                className="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5"
              >
                Usuario
              </label>
              <input
                id="usuario"
                type="text"
                value={usuario}
                onChange={(e) => setUsuario(e.target.value)}
                placeholder="Ej. delegado_puno"
                autoComplete="username"
                className="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 transition-colors"
                required
              />
            </div>

            <div>
              <label
                htmlFor="clave"
                className="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5"
              >
                Contraseña
              </label>
              <input
                id="clave"
                type="password"
                value={clave}
                onChange={(e) => setClave(e.target.value)}
                placeholder="••••••••"
                autoComplete="current-password"
                className="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 transition-colors"
                required
              />
            </div>

            <button
              type="submit"
              disabled={cargando}
              className="w-full mt-2 inline-flex items-center justify-center gap-2 rounded-lg bg-slate-900 py-3 text-xs font-extrabold uppercase tracking-wider text-white shadow-xs hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2 transition-colors disabled:opacity-50 cursor-pointer"
            >
              {cargando ? (
                <span>Verificando credenciales...</span>
              ) : (
                <>
                  <LogIn className="size-4" />
                  <span>Ingresar</span>
                </>
              )}
            </button>
          </form>

          <div className="mt-6 pt-4 border-t border-slate-100 text-center">
            <p className="text-[11px] text-slate-400 font-medium">
              Las credenciales de acceso son emitidas por la comisión organizadora del torneo.
            </p>
          </div>
        </div>
      </div>
    </div>
  );
}
