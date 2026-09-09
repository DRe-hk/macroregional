'use client';

import { useState, useEffect } from 'react';
import {
  Trophy,
  Users,
  Plus,
  Trash2,
  Save,
  RotateCcw,
  CheckCircle2,
  AlertCircle,
  Key,
  ExternalLink,
  Lock,
  LogOut,
  ShieldAlert,
  UserCheck,
} from 'lucide-react';
import { InfoTorneo, Delegacion, Disciplina, UsuarioSistema } from '@/lib/types';
import Link from 'next/link';

export default function AdminPage() {
  // Estado de Autenticación de Admin
  const [autenticado, setAutenticado] = useState(false);
  const [adminUsuario, setAdminUsuario] = useState('admin');
  const [adminClave, setAdminClave] = useState('');
  const [loginError, setLoginError] = useState('');
  const [loginCargando, setLoginCargando] = useState(false);

  // Estados del Panel
  const [tabActiva, setTabActiva] = useState<'torneo' | 'deportes' | 'equipos' | 'marcadores' | 'delegados'>('torneo');
  const [cargando, setCargando] = useState(true);
  const [mensaje, setMensaje] = useState<{ tipo: 'ok' | 'error'; texto: string } | null>(null);

  // Datos
  const [torneo, setTorneo] = useState<InfoTorneo | null>(null);
  const [disciplinas, setDisciplinas] = useState<Disciplina[]>([]);
  const [delegaciones, setDelegaciones] = useState<Delegacion[]>([]);
  const [usuarios, setUsuarios] = useState<UsuarioSistema[]>([]);

  // Formularios
  const [formTorneo, setFormTorneo] = useState({
    nombre: '',
    subtitulo: '',
    organizador: '',
    sedePrincipal: '',
    anio: 2026,
  });

  const [formDeporte, setFormDeporte] = useState({
    nombre: '',
    categoria: 'Fútbol',
    sedePrincipal: 'Estadio Enrique Torres Belón · Puno',
    colorAcento: '#2563eb',
    fotoUrl: 'https://images.unsplash.com/photo-1508098682722-e99c43a406b2?w=800&auto=format&fit=crop&q=80',
    descripcion: '',
  });

  const [formDelegacion, setFormDelegacion] = useState({
    nombre: '',
    siglas: '',
    provincia: 'Puno',
  });

  // Formulario de nuevo delegado
  const [formDelegado, setFormDelegado] = useState({
    usuario: '',
    clave: '',
    nombre: '',
    delegacionId: '',
  });

  // Marcadores
  const [deporteSeleccionadoSlug, setDeporteSeleccionadoSlug] = useState('');
  const [serieSeleccionadaLetra, setSerieSeleccionadaLetra] = useState('A');
  const [nuevoPartido, setNuevoPartido] = useState({
    rondaNombre: 'Ronda 1',
    rondaNumero: 1,
    localId: '',
    visitanteId: '',
    horario: '09:00 AM',
    cancha: 'Cancha Principal',
  });

  // Verificar login guardado en sesión
  useEffect(() => {
    const sesion = localStorage.getItem('drep_admin_session');
    if (sesion === 'true') {
      setAutenticado(true);
    }
  }, []);

  // Cargar datos del sistema
  const cargarDatos = async () => {
    setCargando(true);
    try {
      const [resTorneo, resDisc, resEquipos, resUsuarios] = await Promise.all([
        fetch('/api/torneo').then((r) => r.json()),
        fetch('/api/disciplinas').then((r) => r.json()),
        fetch('/api/equipos').then((r) => r.json()),
        fetch('/api/usuarios').then((r) => r.json()),
      ]);

      if (resTorneo.data) {
        setTorneo(resTorneo.data);
        setFormTorneo(resTorneo.data);
      }
      if (resDisc.data) {
        setDisciplinas(resDisc.data);
        if (resDisc.data.length > 0 && !deporteSeleccionadoSlug) {
          setDeporteSeleccionadoSlug(resDisc.data[0].slug);
        }
      }
      if (resEquipos.data) {
        setDelegaciones(resEquipos.data);
        if (resEquipos.data.length > 0) {
          if (!nuevoPartido.localId && resEquipos.data.length >= 2) {
            setNuevoPartido((prev) => ({
              ...prev,
              localId: resEquipos.data[0].id,
              visitanteId: resEquipos.data[1].id,
            }));
          }
          if (!formDelegado.delegacionId) {
            setFormDelegado((prev) => ({ ...prev, delegacionId: resEquipos.data[0].id }));
          }
        }
      }
      if (resUsuarios.data) {
        setUsuarios(resUsuarios.data);
      }
    } catch (e) {
      console.error(e);
      setMensaje({ tipo: 'error', texto: 'Error al conectar con la base de datos' });
    } finally {
      setCargando(false);
    }
  };

  useEffect(() => {
    if (autenticado) {
      cargarDatos();
    }
  }, [autenticado]);

  // Login de Administrador
  const handleLoginAdmin = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoginCargando(true);
    setLoginError('');

    try {
      const res = await fetch('/api/auth/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ usuario: adminUsuario, clave: adminClave }),
      });

      const data = await res.json();

      if (!res.ok) {
        setLoginError(data.error || 'Credenciales inválidas');
      } else if (data.user?.rol === 'DELEGADO') {
        localStorage.setItem('drep_delegado_session', JSON.stringify(data.user));
        localStorage.setItem('drep_user_session', JSON.stringify(data.user));
        window.location.href = '/delegado';
        return;
      } else {
        setAutenticado(true);
        localStorage.setItem('drep_admin_session', 'true');
        localStorage.setItem('drep_user_session', JSON.stringify(data.user));
      }
    } catch {
      setLoginError('Error al contactar el servidor');
    } finally {
      setLoginCargando(false);
    }
  };

  const handleLogoutAdmin = () => {
    setAutenticado(false);
    localStorage.removeItem('drep_admin_session');
    setAdminClave('');
  };

  const deporteActual = disciplinas.find((d) => d.slug === deporteSeleccionadoSlug);
  const serieActual = deporteActual?.series?.find(
    (s) => s.letra.toUpperCase() === serieSeleccionadaLetra.toUpperCase()
  ) || deporteActual?.series?.[0];

  // Acciones Administrativas
  const handleGuardarTorneo = async (e: React.FormEvent) => {
    e.preventDefault();
    const res = await fetch('/api/torneo', {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(formTorneo),
    });
    if (res.ok) {
      setMensaje({ tipo: 'ok', texto: 'Información del torneo actualizada' });
      cargarDatos();
    }
  };

  const handleResetearTodo = async () => {
    if (confirm('¿Restablecer toda la base de datos a estado limpio? Se reiniciarán marcadores.')) {
      const res = await fetch('/api/torneo', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'reset' }),
      });
      if (res.ok) {
        setMensaje({ tipo: 'ok', texto: 'Base de datos restablecida a limpio con éxito' });
        cargarDatos();
      }
    }
  };

  const handleCrearDeporte = async (e: React.FormEvent) => {
    e.preventDefault();
    const res = await fetch('/api/disciplinas', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(formDeporte),
    });
    if (res.ok) {
      setMensaje({ tipo: 'ok', texto: `Disciplina "${formDeporte.nombre}" creada exitosamente` });
      setFormDeporte({
        nombre: '',
        categoria: 'Fútbol',
        sedePrincipal: 'Estadio Enrique Torres Belón · Puno',
        colorAcento: '#2563eb',
        fotoUrl: 'https://images.unsplash.com/photo-1508098682722-e99c43a406b2?w=800&auto=format&fit=crop&q=80',
        descripcion: '',
      });
      cargarDatos();
    }
  };

  const handleEliminarDeporte = async (slug: string) => {
    if (confirm('¿Eliminar esta disciplina deportiva?')) {
      const res = await fetch(`/api/disciplinas/${slug}`, { method: 'DELETE' });
      if (res.ok) {
        setMensaje({ tipo: 'ok', texto: 'Disciplina eliminada' });
        cargarDatos();
      }
    }
  };

  const handleCrearDelegacion = async (e: React.FormEvent) => {
    e.preventDefault();
    const res = await fetch('/api/equipos', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(formDelegacion),
    });
    if (res.ok) {
      setMensaje({ tipo: 'ok', texto: `Delegación "${formDelegacion.nombre}" guardada` });
      setFormDelegacion({ nombre: '', siglas: '', provincia: 'Puno' });
      cargarDatos();
    }
  };

  const handleEliminarDelegacion = async (id: string) => {
    if (confirm('¿Eliminar esta delegación?')) {
      const res = await fetch(`/api/equipos/${id}`, { method: 'DELETE' });
      if (res.ok) {
        setMensaje({ tipo: 'ok', texto: 'Delegación eliminada' });
        cargarDatos();
      }
    }
  };

  // Crear Delegado con Credenciales
  const handleCrearDelegado = async (e: React.FormEvent) => {
    e.preventDefault();
    const del = delegaciones.find((d) => d.id === formDelegado.delegacionId);

    const res = await fetch('/api/usuarios', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        usuario: formDelegado.usuario,
        clave: formDelegado.clave,
        nombre: formDelegado.nombre,
        rol: 'DELEGADO',
        delegacionId: formDelegado.delegacionId,
        delegacionNombre: del?.nombre || 'Delegación',
      }),
    });

    if (res.ok) {
      setMensaje({ tipo: 'ok', texto: `Cuenta creada para el delegado de ${del?.nombre}` });
      setFormDelegado({ usuario: '', clave: '', nombre: '', delegacionId: delegaciones[0]?.id || '' });
      cargarDatos();
    } else {
      const err = await res.json();
      setMensaje({ tipo: 'error', texto: err.error || 'Error al crear cuenta' });
    }
  };

  const handleEliminarUsuario = async (id: string) => {
    if (confirm('¿Eliminar las credenciales de este delegado?')) {
      const res = await fetch(`/api/usuarios/${id}`, { method: 'DELETE' });
      if (res.ok) {
        setMensaje({ tipo: 'ok', texto: 'Credencial eliminada' });
        cargarDatos();
      }
    }
  };

  const handleCrearPartido = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!deporteActual || !serieActual) return;

    const loc = delegaciones.find((d) => d.id === nuevoPartido.localId);
    const vis = delegaciones.find((d) => d.id === nuevoPartido.visitanteId);

    const res = await fetch('/api/partidos', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        action: 'crear',
        disciplinaSlug: deporteActual.slug,
        serieLetra: serieActual.letra,
        partido: {
          ...nuevoPartido,
          localNombre: loc?.nombre || 'Equipo 1',
          visitanteNombre: vis?.nombre || 'Equipo 2',
        },
      }),
    });

    if (res.ok) {
      setMensaje({ tipo: 'ok', texto: 'Partido programado en la serie' });
      cargarDatos();
    }
  };

  const handleActualizarMarcador = async (
    partidoId: string,
    localGoles: string,
    visitanteGoles: string,
    ganadorId?: string
  ) => {
    if (!deporteActual || !serieActual) return;

    const res = await fetch('/api/partidos', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        disciplinaSlug: deporteActual.slug,
        serieLetra: serieActual.letra,
        partidoId,
        localGoles: localGoles === '' ? null : Number(localGoles),
        visitanteGoles: visitanteGoles === '' ? null : Number(visitanteGoles),
        ganadorId,
      }),
    });

    if (res.ok) {
      setMensaje({ tipo: 'ok', texto: 'Marcador guardado y tabla de posiciones actualizada' });
      cargarDatos();
    }
  };

  // --- VISTA 1: Pantalla de Login Protegida para /admin ---
  if (!autenticado) {
    return (
      <div className="min-h-[85vh] flex items-center justify-center p-4 bg-slate-50">
        <div className="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
          <div className="text-center mb-6">
            <span className="mx-auto grid size-12 place-items-center rounded-xl bg-slate-900 text-white shadow-xs">
              <Lock className="size-5" />
            </span>
            <h1 className="mt-3 text-2xl font-black text-slate-900 tracking-tight">
              Acceso a Administración
            </h1>
            <p className="text-xs text-slate-500 mt-1 font-medium">
              Solo accesible para el Comité Organizador y Mesa de Control.
            </p>
          </div>

          {loginError && (
            <div className="mb-4 flex items-center gap-2 rounded-lg bg-red-50 p-3 text-xs font-bold text-red-700 border border-red-200">
              <AlertCircle className="size-4 shrink-0" />
              <span>{loginError}</span>
            </div>
          )}

          <form onSubmit={handleLoginAdmin} className="space-y-4 text-xs">
            <div>
              <label className="block font-bold text-slate-700 mb-1 uppercase">Usuario Administrador</label>
              <input
                type="text"
                required
                value={adminUsuario}
                onChange={(e) => setAdminUsuario(e.target.value)}
                placeholder="admin"
                className="w-full rounded-lg border border-slate-300 p-2.5 font-bold text-slate-900 text-sm focus:border-slate-900 focus:outline-none"
              />
            </div>

            <div>
              <label className="block font-bold text-slate-700 mb-1 uppercase">Contraseña</label>
              <input
                type="password"
                required
                value={adminClave}
                onChange={(e) => setAdminClave(e.target.value)}
                placeholder="drep2026"
                className="w-full rounded-lg border border-slate-300 p-2.5 font-bold text-slate-900 text-sm focus:border-slate-900 focus:outline-none"
              />
              <span className="mt-1 block text-[11px] text-slate-400">
                Clave por defecto: <strong>drep2026</strong>
              </span>
            </div>

            <button
              type="submit"
              disabled={loginCargando}
              className="w-full rounded-lg bg-slate-900 py-3 text-xs font-black uppercase tracking-wider text-white hover:bg-slate-800 transition-colors disabled:opacity-50"
            >
              {loginCargando ? 'Verificando...' : 'Entrar al Panel Admin'}
            </button>
          </form>

          <div className="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-semibold text-slate-500">
            <Link href="/" className="hover:text-slate-900">
              ← Volver al sitio público
            </Link>
            <Link href="/delegado" className="text-blue-600 hover:underline">
              Soy delegado →
            </Link>
          </div>
        </div>
      </div>
    );
  }

  // --- VISTA 2: Panel de Administración Completo (Autenticado) ---
  const delegadosList = usuarios.filter((u) => u.rol === 'DELEGADO');

  return (
    <div className="min-h-screen bg-slate-50 py-8 px-4 sm:px-6">
      <div className="mx-auto max-w-[1360px] space-y-6">
        {/* Barra Superior */}
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200">
          <div>
            <div className="inline-flex items-center gap-1.5 text-xs font-black uppercase text-blue-600 mb-1">
              <UserCheck className="size-3.5" />
              <span>Administrador Principal (DREP)</span>
            </div>
            <h1 className="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
              Panel de Control General
            </h1>
            <p className="text-xs sm:text-sm text-slate-500 font-medium">
              Gestión de torneo, disciplinas, delegaciones, credenciales y marcadores.
            </p>
          </div>

          <div className="flex items-center gap-2.5">
            <button
              onClick={handleResetearTodo}
              className="inline-flex items-center gap-1.5 rounded-lg border border-red-200 bg-red-50 px-3.5 py-2 text-xs font-extrabold text-red-700 hover:bg-red-100 transition-colors"
            >
              <RotateCcw className="size-3.5" />
              <span>Limpiar Todo</span>
            </button>

            <Link
              href="/"
              className="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors"
            >
              <span>Ver Web</span>
              <ExternalLink className="size-3.5" />
            </Link>

            <button
              onClick={handleLogoutAdmin}
              className="inline-flex items-center gap-1.5 rounded-lg bg-slate-900 px-3.5 py-2 text-xs font-bold text-white hover:bg-slate-800 transition-colors"
            >
              <LogOut className="size-3.5" />
              <span>Salir</span>
            </button>
          </div>
        </div>

        {/* Notificación */}
        {mensaje && (
          <div
            className={`flex items-center justify-between p-4 rounded-xl border text-xs font-extrabold ${
              mensaje.tipo === 'ok'
                ? 'bg-emerald-50 text-emerald-800 border-emerald-200'
                : 'bg-red-50 text-red-800 border-red-200'
            }`}
          >
            <div className="flex items-center gap-2">
              {mensaje.tipo === 'ok' ? (
                <CheckCircle2 className="size-4 text-emerald-600" />
              ) : (
                <AlertCircle className="size-4 text-red-600" />
              )}
              <span>{mensaje.texto}</span>
            </div>
            <button onClick={() => setMensaje(null)} className="text-slate-400 hover:text-slate-700">
              ✕
            </button>
          </div>
        )}

        {/* Barra de Pestañas */}
        <div className="flex border-b border-slate-200 bg-white px-3 pt-2 rounded-t-xl gap-1 overflow-x-auto">
          <button
            onClick={() => setTabActiva('torneo')}
            className={`px-4 py-2.5 text-xs font-black uppercase tracking-wider rounded-t-lg transition-colors border-b-2 ${
              tabActiva === 'torneo'
                ? 'border-slate-900 text-slate-950 bg-slate-50'
                : 'border-transparent text-slate-500 hover:text-slate-900'
            }`}
          >
            🏆 Torneo
          </button>

          <button
            onClick={() => setTabActiva('deportes')}
            className={`px-4 py-2.5 text-xs font-black uppercase tracking-wider rounded-t-lg transition-colors border-b-2 ${
              tabActiva === 'deportes'
                ? 'border-slate-900 text-slate-950 bg-slate-50'
                : 'border-transparent text-slate-500 hover:text-slate-900'
            }`}
          >
            ⚽ Deportes ({disciplinas.length})
          </button>

          <button
            onClick={() => setTabActiva('equipos')}
            className={`px-4 py-2.5 text-xs font-black uppercase tracking-wider rounded-t-lg transition-colors border-b-2 ${
              tabActiva === 'equipos'
                ? 'border-slate-900 text-slate-950 bg-slate-50'
                : 'border-transparent text-slate-500 hover:text-slate-900'
            }`}
          >
            👥 UGELs ({delegaciones.length})
          </button>

          <button
            onClick={() => setTabActiva('delegados')}
            className={`px-4 py-2.5 text-xs font-black uppercase tracking-wider rounded-t-lg transition-colors border-b-2 ${
              tabActiva === 'delegados'
                ? 'border-blue-600 text-blue-600 bg-blue-50/50'
                : 'border-transparent text-slate-500 hover:text-slate-900'
            }`}
          >
            🔑 Delegados ({delegadosList.length})
          </button>

          <button
            onClick={() => setTabActiva('marcadores')}
            className={`px-4 py-2.5 text-xs font-black uppercase tracking-wider rounded-t-lg transition-colors border-b-2 ${
              tabActiva === 'marcadores'
                ? 'border-emerald-600 text-emerald-700 bg-emerald-50/50'
                : 'border-transparent text-slate-500 hover:text-slate-900'
            }`}
          >
            ⚡ Fixture & Marcadores
          </button>
        </div>

        {/* Contenido de Pestañas */}
        <div className="bg-white p-6 rounded-b-2xl border border-t-0 border-slate-200">
          {/* 1. Torneo */}
          {tabActiva === 'torneo' && (
            <form onSubmit={handleGuardarTorneo} className="max-w-2xl space-y-4">
              <h3 className="text-base font-black text-slate-900">Datos Generales del Torneo</h3>

              <div>
                <label className="block text-xs font-extrabold uppercase text-slate-700 mb-1">
                  Nombre del Campeonato
                </label>
                <input
                  type="text"
                  required
                  value={formTorneo.nombre}
                  onChange={(e) => setFormTorneo({ ...formTorneo, nombre: e.target.value })}
                  className="w-full rounded-lg border border-slate-300 p-2.5 text-sm font-bold text-slate-900"
                />
              </div>

              <div>
                <label className="block text-xs font-extrabold uppercase text-slate-700 mb-1">
                  Subtítulo / Edición
                </label>
                <input
                  type="text"
                  value={formTorneo.subtitulo}
                  onChange={(e) => setFormTorneo({ ...formTorneo, subtitulo: e.target.value })}
                  className="w-full rounded-lg border border-slate-300 p-2.5 text-sm font-semibold text-slate-800"
                />
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-extrabold uppercase text-slate-700 mb-1">
                    Institución Organizadora
                  </label>
                  <input
                    type="text"
                    value={formTorneo.organizador}
                    onChange={(e) => setFormTorneo({ ...formTorneo, organizador: e.target.value })}
                    className="w-full rounded-lg border border-slate-300 p-2.5 text-sm font-semibold text-slate-800"
                  />
                </div>

                <div>
                  <label className="block text-xs font-extrabold uppercase text-slate-700 mb-1">
                    Sede Principal
                  </label>
                  <input
                    type="text"
                    value={formTorneo.sedePrincipal}
                    onChange={(e) => setFormTorneo({ ...formTorneo, sedePrincipal: e.target.value })}
                    className="w-full rounded-lg border border-slate-300 p-2.5 text-sm font-semibold text-slate-800"
                  />
                </div>
              </div>

              <button
                type="submit"
                className="mt-4 inline-flex items-center gap-2 rounded-lg bg-slate-900 px-5 py-2.5 text-xs font-extrabold text-white hover:bg-slate-800"
              >
                <Save className="size-4" />
                <span>Guardar Cambios del Torneo</span>
              </button>
            </form>
          )}

          {/* 2. Deportes */}
          {tabActiva === 'deportes' && (
            <div className="space-y-8">
              <div className="rounded-xl border border-slate-200 bg-slate-50 p-5">
                <h3 className="text-sm font-black text-slate-900 mb-3 flex items-center gap-1.5">
                  <Plus className="size-4 text-blue-600" /> Añadir Nuevo Deporte
                </h3>

                <form onSubmit={handleCrearDeporte} className="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                  <div>
                    <label className="block font-bold text-slate-700 mb-1">Nombre</label>
                    <input
                      type="text"
                      required
                      placeholder="ej: Tenis de Mesa"
                      value={formDeporte.nombre}
                      onChange={(e) => setFormDeporte({ ...formDeporte, nombre: e.target.value })}
                      className="w-full rounded-lg border border-slate-300 p-2 font-semibold bg-white"
                    />
                  </div>

                  <div>
                    <label className="block font-bold text-slate-700 mb-1">Categoría</label>
                    <select
                      value={formDeporte.categoria}
                      onChange={(e) => setFormDeporte({ ...formDeporte, categoria: e.target.value })}
                      className="w-full rounded-lg border border-slate-300 p-2 font-semibold bg-white"
                    >
                      <option value="Fútbol">Fútbol</option>
                      <option value="Futsal">Futsal</option>
                      <option value="Voleibol">Voleibol</option>
                      <option value="Básquet">Básquet</option>
                      <option value="Tenis de Mesa">Tenis de Mesa</option>
                      <option value="Ajedrez">Ajedrez</option>
                      <option value="Atletismo">Atletismo</option>
                      <option value="Otro">Otro Deporte</option>
                    </select>
                  </div>

                  <div>
                    <label className="block font-bold text-slate-700 mb-1">Sede Principal</label>
                    <input
                      type="text"
                      placeholder="Coliseo Eduardo Rodríguez"
                      value={formDeporte.sedePrincipal}
                      onChange={(e) => setFormDeporte({ ...formDeporte, sedePrincipal: e.target.value })}
                      className="w-full rounded-lg border border-slate-300 p-2 font-semibold bg-white"
                    />
                  </div>

                  <div className="sm:col-span-3 flex justify-end">
                    <button
                      type="submit"
                      className="rounded-lg bg-blue-600 px-4 py-2 font-black text-white hover:bg-blue-700"
                    >
                      Crear Deporte
                    </button>
                  </div>
                </form>
              </div>

              <div>
                <h3 className="text-sm font-black text-slate-900 mb-3">Disciplinas Registradas</h3>
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                  {disciplinas.map((d) => (
                    <div
                      key={d.id}
                      className="flex items-center justify-between p-3.5 rounded-xl border border-slate-200 bg-white"
                    >
                      <div>
                        <span className="text-[10px] font-extrabold uppercase text-blue-600 block">
                          {d.categoria}
                        </span>
                        <h4 className="text-sm font-black text-slate-900">{d.nombre}</h4>
                        <span className="text-xs text-slate-500">{d.series?.length || 0} series</span>
                      </div>

                      <button
                        onClick={() => handleEliminarDeporte(d.slug)}
                        className="text-slate-400 hover:text-red-600 p-2"
                        title="Eliminar deporte"
                      >
                        <Trash2 className="size-4" />
                      </button>
                    </div>
                  ))}
                </div>
              </div>
            </div>
          )}

          {/* 3. Equipos / UGELs */}
          {tabActiva === 'equipos' && (
            <div className="space-y-6">
              <div className="rounded-xl border border-slate-200 bg-slate-50 p-4">
                <h3 className="text-sm font-black text-slate-900 mb-3 flex items-center gap-1.5">
                  <Plus className="size-4 text-blue-600" /> Añadir Delegación
                </h3>

                <form onSubmit={handleCrearDelegacion} className="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                  <div>
                    <label className="block font-bold text-slate-700 mb-1">Nombre</label>
                    <input
                      type="text"
                      required
                      placeholder="UGEL Ilave"
                      value={formDelegacion.nombre}
                      onChange={(e) => setFormDelegacion({ ...formDelegacion, nombre: e.target.value })}
                      className="w-full rounded-lg border border-slate-300 p-2 font-semibold bg-white"
                    />
                  </div>

                  <div>
                    <label className="block font-bold text-slate-700 mb-1">Siglas</label>
                    <input
                      type="text"
                      required
                      placeholder="ILAVE"
                      value={formDelegacion.siglas}
                      onChange={(e) => setFormDelegacion({ ...formDelegacion, siglas: e.target.value })}
                      className="w-full rounded-lg border border-slate-300 p-2 font-semibold bg-white"
                    />
                  </div>

                  <div>
                    <label className="block font-bold text-slate-700 mb-1">Provincia</label>
                    <input
                      type="text"
                      placeholder="El Collao"
                      value={formDelegacion.provincia}
                      onChange={(e) => setFormDelegacion({ ...formDelegacion, provincia: e.target.value })}
                      className="w-full rounded-lg border border-slate-300 p-2 font-semibold bg-white"
                    />
                  </div>

                  <div className="sm:col-span-3 flex justify-end">
                    <button
                      type="submit"
                      className="rounded-lg bg-slate-900 px-4 py-2 font-black text-white hover:bg-slate-800"
                    >
                      Guardar Delegación
                    </button>
                  </div>
                </form>
              </div>

              <div className="overflow-x-auto rounded-xl border border-slate-200">
                <table className="w-full text-left text-xs">
                  <thead className="bg-slate-50 border-b border-slate-200 font-black text-slate-700 uppercase">
                    <tr>
                      <th className="p-3">Nombre</th>
                      <th className="p-3">Siglas</th>
                      <th className="p-3">Provincia</th>
                      <th className="p-3 text-center">PJ</th>
                      <th className="p-3 text-center">Puntos</th>
                      <th className="p-3 text-right">Acción</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100">
                    {delegaciones.map((del) => (
                      <tr key={del.id} className="hover:bg-slate-50">
                        <td className="p-3 font-bold text-slate-900">{del.nombre}</td>
                        <td className="p-3 font-semibold text-slate-600">{del.siglas}</td>
                        <td className="p-3 text-slate-500">{del.provincia}</td>
                        <td className="p-3 text-center tabular-nums font-bold">{del.pj || 0}</td>
                        <td className="p-3 text-center tabular-nums font-black text-slate-900">{del.puntos || 0}</td>
                        <td className="p-3 text-right">
                          <button
                            onClick={() => handleEliminarDelegacion(del.id)}
                            className="text-slate-400 hover:text-red-600 p-1"
                            title="Eliminar"
                          >
                            <Trash2 className="size-3.5" />
                          </button>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </div>
          )}

          {/* 4. Delegados y Credenciales */}
          {tabActiva === 'delegados' && (
            <div className="space-y-6">
              {/* Formulario para registrar credenciales a un delegado */}
              <div className="rounded-xl border border-slate-200 bg-slate-50 p-4">
                <h3 className="text-sm font-black text-slate-900 mb-2 flex items-center gap-1.5">
                  <Key className="size-4 text-blue-600" /> Crear Credencial para Delegado Oficial
                </h3>
                <p className="text-xs text-slate-500 mb-4">
                  El delegado solo tendrá acceso a ver y registrar marcadores de su propia delegación y subir la nómina de deportistas.
                </p>

                <form onSubmit={handleCrearDelegado} className="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
                  <div>
                    <label className="block font-bold text-slate-700 mb-1">Delegación / UGEL</label>
                    <select
                      value={formDelegado.delegacionId}
                      onChange={(e) => setFormDelegado({ ...formDelegado, delegacionId: e.target.value })}
                      className="w-full rounded-lg border border-slate-300 p-2 font-bold bg-white"
                    >
                      {delegaciones.map((del) => (
                        <option key={del.id} value={del.id}>
                          {del.nombre}
                        </option>
                      ))}
                    </select>
                  </div>

                  <div>
                    <label className="block font-bold text-slate-700 mb-1">Nombre del Delegado</label>
                    <input
                      type="text"
                      required
                      placeholder="Prof. Juan Mamani"
                      value={formDelegado.nombre}
                      onChange={(e) => setFormDelegado({ ...formDelegado, nombre: e.target.value })}
                      className="w-full rounded-lg border border-slate-300 p-2 font-semibold bg-white"
                    />
                  </div>

                  <div>
                    <label className="block font-bold text-slate-700 mb-1">Usuario de Acceso</label>
                    <input
                      type="text"
                      required
                      placeholder="ej: delegado_melgar"
                      value={formDelegado.usuario}
                      onChange={(e) => setFormDelegado({ ...formDelegado, usuario: e.target.value })}
                      className="w-full rounded-lg border border-slate-300 p-2 font-semibold bg-white"
                    />
                  </div>

                  <div>
                    <label className="block font-bold text-slate-700 mb-1">Contraseña</label>
                    <input
                      type="text"
                      required
                      placeholder="ej: melgar2026"
                      value={formDelegado.clave}
                      onChange={(e) => setFormDelegado({ ...formDelegado, clave: e.target.value })}
                      className="w-full rounded-lg border border-slate-300 p-2 font-semibold bg-white"
                    />
                  </div>

                  <div className="sm:col-span-4 flex justify-end">
                    <button
                      type="submit"
                      className="rounded-lg bg-blue-600 px-4 py-2 font-black text-white hover:bg-blue-700 transition-colors"
                    >
                      Generar Credencial
                    </button>
                  </div>
                </form>
              </div>

              {/* Tabla de Delegados Registrados */}
              <div>
                <h3 className="text-sm font-black text-slate-900 mb-3">Cuentas de Delegados Activas</h3>
                <div className="overflow-x-auto rounded-xl border border-slate-200">
                  <table className="w-full text-left text-xs">
                    <thead className="bg-slate-50 border-b border-slate-200 font-black text-slate-700 uppercase">
                      <tr>
                        <th className="p-3">Delegación</th>
                        <th className="p-3">Nombre del Delegado</th>
                        <th className="p-3">Usuario</th>
                        <th className="p-3">Contraseña</th>
                        <th className="p-3 text-right">Acción</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-100">
                      {delegadosList.map((usr) => (
                        <tr key={usr.id} className="hover:bg-slate-50">
                          <td className="p-3 font-extrabold text-blue-900">{usr.delegacionNombre || usr.delegacionId}</td>
                          <td className="p-3 font-bold text-slate-800">{usr.nombre}</td>
                          <td className="p-3 font-mono font-bold text-slate-600">{usr.usuario}</td>
                          <td className="p-3 font-mono text-slate-500">{usr.clave}</td>
                          <td className="p-3 text-right">
                            <button
                              onClick={() => handleEliminarUsuario(usr.id)}
                              className="text-slate-400 hover:text-red-600 p-1"
                              title="Eliminar credencial"
                            >
                              <Trash2 className="size-3.5" />
                            </button>
                          </td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          )}

          {/* 5. Fixture y Marcadores */}
          {tabActiva === 'marcadores' && (
            <div className="space-y-6">
              <div className="flex flex-wrap items-center gap-3 p-4 rounded-xl border border-slate-200 bg-slate-50">
                <div>
                  <label className="block text-[11px] font-black uppercase text-slate-700 mb-1">
                    Seleccionar Deporte
                  </label>
                  <select
                    value={deporteSeleccionadoSlug}
                    onChange={(e) => setDeporteSeleccionadoSlug(e.target.value)}
                    className="rounded-lg border border-slate-300 p-2 text-xs font-bold text-slate-900 bg-white"
                  >
                    {disciplinas.map((d) => (
                      <option key={d.slug} value={d.slug}>
                        {d.nombre}
                      </option>
                    ))}
                  </select>
                </div>

                {deporteActual && (
                  <div>
                    <label className="block text-[11px] font-black uppercase text-slate-700 mb-1">
                      Serie
                    </label>
                    <select
                      value={serieSeleccionadaLetra}
                      onChange={(e) => setSerieSeleccionadaLetra(e.target.value)}
                      className="rounded-lg border border-slate-300 p-2 text-xs font-bold text-slate-900 bg-white"
                    >
                      {deporteActual.series?.map((s) => (
                        <option key={s.letra} value={s.letra}>
                          {s.nombre}
                        </option>
                      ))}
                    </select>
                  </div>
                )}
              </div>

              {deporteActual && serieActual && (
                <div className="rounded-xl border border-slate-200 bg-white p-4">
                  <h3 className="text-xs font-black uppercase tracking-wider text-slate-700 mb-3">
                    Programar Nuevo Partido en {serieActual.nombre}
                  </h3>

                  <form onSubmit={handleCrearPartido} className="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
                    <div>
                      <label className="block font-bold text-slate-600 mb-1">Ronda</label>
                      <input
                        type="text"
                        value={nuevoPartido.rondaNombre}
                        onChange={(e) => setNuevoPartido({ ...nuevoPartido, rondaNombre: e.target.value })}
                        className="w-full rounded-lg border border-slate-300 p-2 font-semibold"
                        placeholder="Ronda 1 o Semifinal"
                      />
                    </div>

                    <div>
                      <label className="block font-bold text-slate-600 mb-1">Equipo Local</label>
                      <select
                        value={nuevoPartido.localId}
                        onChange={(e) => setNuevoPartido({ ...nuevoPartido, localId: e.target.value })}
                        className="w-full rounded-lg border border-slate-300 p-2 font-bold"
                      >
                        {delegaciones.map((del) => (
                          <option key={del.id} value={del.id}>
                            {del.nombre}
                          </option>
                        ))}
                      </select>
                    </div>

                    <div>
                      <label className="block font-bold text-slate-600 mb-1">Equipo Visitante</label>
                      <select
                        value={nuevoPartido.visitanteId}
                        onChange={(e) => setNuevoPartido({ ...nuevoPartido, visitanteId: e.target.value })}
                        className="w-full rounded-lg border border-slate-300 p-2 font-bold"
                      >
                        {delegaciones.map((del) => (
                          <option key={del.id} value={del.id}>
                            {del.nombre}
                          </option>
                        ))}
                      </select>
                    </div>

                    <div>
                      <label className="block font-bold text-slate-600 mb-1">Horario / Cancha</label>
                      <input
                        type="text"
                        value={nuevoPartido.horario}
                        onChange={(e) => setNuevoPartido({ ...nuevoPartido, horario: e.target.value })}
                        className="w-full rounded-lg border border-slate-300 p-2 font-semibold"
                        placeholder="09:00 AM"
                      />
                    </div>

                    <div className="sm:col-span-4 flex justify-end">
                      <button
                        type="submit"
                        className="rounded-lg bg-blue-600 px-4 py-2 font-black text-white hover:bg-blue-700"
                      >
                        Añadir Partido a la Serie
                      </button>
                    </div>
                  </form>
                </div>
              )}

              {serieActual ? (
                <div className="space-y-3">
                  <h3 className="text-sm font-black text-slate-900">
                    Marcadores de {serieActual.nombre} ({serieActual.partidos?.length || 0} partidos)
                  </h3>

                  {serieActual.partidos?.length === 0 ? (
                    <p className="text-xs text-slate-500 italic p-4 bg-slate-50 rounded-xl">
                      No hay partidos en esta serie. Añade uno arriba.
                    </p>
                  ) : (
                    serieActual.partidos.map((p) => (
                      <div
                        key={p.id}
                        className="p-4 rounded-xl border border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-xs"
                      >
                        <div>
                          <span className="font-extrabold text-blue-700 uppercase block mb-1">
                            {p.rondaNombre} · {p.horario || 'Programado'}
                          </span>
                          <div className="text-sm font-bold text-slate-900">
                            {p.localNombre} <span className="text-slate-400 font-normal">vs</span> {p.visitanteNombre}
                          </div>
                        </div>

                        <div className="flex items-center gap-2">
                          <input
                            type="number"
                            placeholder="Local"
                            defaultValue={p.localGoles ?? ''}
                            id={`goles-loc-${p.id}`}
                            className="w-16 rounded-lg border border-slate-300 p-2 font-black text-center bg-white tabular-nums"
                          />
                          <span className="font-black text-slate-400">-</span>
                          <input
                            type="number"
                            placeholder="Visita"
                            defaultValue={p.visitanteGoles ?? ''}
                            id={`goles-vis-${p.id}`}
                            className="w-16 rounded-lg border border-slate-300 p-2 font-black text-center bg-white tabular-nums"
                          />

                          <button
                            type="button"
                            onClick={() => {
                              const gLoc = (document.getElementById(`goles-loc-${p.id}`) as HTMLInputElement)?.value;
                              const gVis = (document.getElementById(`goles-vis-${p.id}`) as HTMLInputElement)?.value;
                              let ganador: string | undefined = undefined;
                              if (gLoc !== '' && gVis !== '') {
                                if (Number(gLoc) > Number(gVis)) ganador = p.localId;
                                else if (Number(gVis) > Number(gLoc)) ganador = p.visitanteId;
                              }
                              handleActualizarMarcador(p.id, gLoc, gVis, ganador);
                            }}
                            className="rounded-lg bg-emerald-600 px-3 py-2 font-black text-white hover:bg-emerald-700 transition-colors"
                          >
                            Guardar
                          </button>
                        </div>
                      </div>
                    ))
                  )}
                </div>
              ) : null}
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
