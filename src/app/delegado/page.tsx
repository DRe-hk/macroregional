'use client';

import { useState, useEffect } from 'react';
import {
  User,
  Shield,
  Trophy,
  Users,
  CheckCircle2,
  AlertCircle,
  LogOut,
  Save,
  Plus,
  Trash2,
  FileText,
  Clock,
} from 'lucide-react';
import { Disciplina, AtletaInscrito, Partido } from '@/lib/types';
import Link from 'next/link';

interface SesionDelegado {
  id: string;
  usuario: string;
  nombre: string;
  rol: string;
  delegacionId?: string;
  delegacionNombre?: string;
}

export default function DelegadoPage() {
  const [sesion, setSesion] = useState<SesionDelegado | null>(null);
  const [usuarioInput, setUsuarioInput] = useState('delegado_puno');
  const [claveInput, setClaveInput] = useState('puno2026');
  const [loginError, setLoginError] = useState('');
  const [loginCargando, setLoginCargando] = useState(false);

  // Estados del portal
  const [tab, setTab] = useState<'partidos' | 'nomina'>('partidos');
  const [disciplinas, setDisciplinas] = useState<Disciplina[]>([]);
  const [atletas, setAtletas] = useState<AtletaInscrito[]>([]);
  const [mensaje, setMensaje] = useState<{ tipo: 'ok' | 'error'; texto: string } | null>(null);

  // Formulario nuevo deportista
  const [nuevoAtleta, setNuevoAtleta] = useState({
    dni: '',
    nombreCompleto: '',
    disciplinaSlug: 'futbol-libre',
    numeroCamiseta: '',
    rolEquipo: 'Titular',
  });

  // Cargar sesión guardada
  useEffect(() => {
    const raw = localStorage.getItem('drep_delegado_session');
    if (raw) {
      try {
        setSesion(JSON.parse(raw));
      } catch (e) {
        localStorage.removeItem('drep_delegado_session');
      }
    }
  }, []);

  // Cargar datos relevantes para el delegado
  const cargarDatos = async (delId: string) => {
    try {
      const [resDisc, resAtletas] = await Promise.all([
        fetch('/api/disciplinas').then((r) => r.json()),
        fetch(`/api/nominas?delegacionId=${delId}`).then((r) => r.json()),
      ]);

      if (resDisc.data) setDisciplinas(resDisc.data);
      if (resAtletas.data) setAtletas(resAtletas.data);
    } catch (e) {
      console.error(e);
    }
  };

  useEffect(() => {
    if (sesion?.delegacionId) {
      cargarDatos(sesion.delegacionId);
    }
  }, [sesion]);

  const handleLogin = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoginCargando(true);
    setLoginError('');

    try {
      const res = await fetch('/api/auth/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ usuario: usuarioInput, clave: claveInput }),
      });

      const data = await res.json();

      if (!res.ok) {
        setLoginError(data.error || 'Credenciales no válidas');
      } else if (data.user?.rol === 'ADMIN') {
        localStorage.setItem('drep_admin_session', 'true');
        localStorage.setItem('drep_user_session', JSON.stringify(data.user));
        window.location.href = '/admin';
        return;
      } else {
        setSesion(data.user);
        localStorage.setItem('drep_delegado_session', JSON.stringify(data.user));
      }
    } catch {
      setLoginError('Error al contactar el servidor');
    } finally {
      setLoginCargando(false);
    }
  };

  const handleLogout = () => {
    setSesion(null);
    localStorage.removeItem('drep_delegado_session');
  };

  // Guardar Marcador del Partido de su equipo
  const handleGuardarMarcador = async (
    disciplinaSlug: string,
    serieLetra: string,
    partidoId: string,
    localGoles: string,
    visitanteGoles: string,
    ganadorId?: string,
    observaciones?: string
  ) => {
    if (!sesion?.delegacionId) return;

    const res = await fetch('/api/partidos', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        disciplinaSlug,
        serieLetra,
        partidoId,
        localGoles: localGoles === '' ? null : Number(localGoles),
        visitanteGoles: visitanteGoles === '' ? null : Number(visitanteGoles),
        ganadorId,
        observaciones,
      }),
    });

    if (res.ok) {
      setMensaje({ tipo: 'ok', texto: 'Resultado reportado y actualizado con éxito' });
      if (sesion?.delegacionId) cargarDatos(sesion.delegacionId);
    } else {
      const err = await res.json();
      setMensaje({ tipo: 'error', texto: err.error || 'Error al reportar resultado' });
    }
  };

  // Inscribir Deportista
  const handleRegistrarAtleta = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!sesion?.delegacionId) return;

    const res = await fetch('/api/nominas', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        ...nuevoAtleta,
        delegacionId: sesion.delegacionId,
      }),
    });

    if (res.ok) {
      setMensaje({ tipo: 'ok', texto: 'Deportista registrado en la nómina oficial' });
      setNuevoAtleta({
        dni: '',
        nombreCompleto: '',
        disciplinaSlug: 'futbol-libre',
        numeroCamiseta: '',
        rolEquipo: 'Titular',
      });
      cargarDatos(sesion.delegacionId);
    } else {
      const err = await res.json();
      setMensaje({ tipo: 'error', texto: err.error || 'Error al inscribir atleta' });
    }
  };

  // Eliminar Atleta
  const handleEliminarAtleta = async (id: string) => {
    if (!sesion?.delegacionId) return;
    if (confirm('¿Retirar a este deportista de la nómina?')) {
      const res = await fetch(`/api/nominas?id=${id}&delegacionId=${sesion.delegacionId}`, {
        method: 'DELETE',
      });
      if (res.ok) {
        setMensaje({ tipo: 'ok', texto: 'Deportista retirado' });
        cargarDatos(sesion.delegacionId);
      }
    }
  };

  // Filtrar partidos que involucran a este delegado
  const misPartidos: {
    disciplinaSlug: string;
    disciplinaNombre: string;
    serieLetra: string;
    partido: Partido;
  }[] = [];

  if (sesion?.delegacionId) {
    disciplinas.forEach((d) => {
      d.series?.forEach((s) => {
        s.partidos?.forEach((p) => {
          if (p.localId === sesion.delegacionId || p.visitanteId === sesion.delegacionId) {
            misPartidos.push({
              disciplinaSlug: d.slug,
              disciplinaNombre: d.nombre,
              serieLetra: s.letra,
              partido: p,
            });
          }
        });
      });
    });
  }

  // --- VISTA 1: Login de Delegado ---
  if (!sesion) {
    return (
      <div className="min-h-[85vh] flex items-center justify-center p-4 bg-slate-50">
        <div className="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
          <div className="text-center mb-6">
            <span className="mx-auto grid size-12 place-items-center rounded-xl bg-blue-600 text-white shadow-xs">
              <User className="size-6" />
            </span>
            <h1 className="mt-3 text-2xl font-black text-slate-900 tracking-tight">
              Portal de Delegados
            </h1>
            <p className="text-xs text-slate-500 mt-1 font-medium">
              Ingresa con las credenciales asignadas a tu delegación/UGEL.
            </p>
          </div>

          {loginError && (
            <div className="mb-4 flex items-center gap-2 rounded-lg bg-red-50 p-3 text-xs font-bold text-red-700 border border-red-200">
              <AlertCircle className="size-4 shrink-0" />
              <span>{loginError}</span>
            </div>
          )}

          <form onSubmit={handleLogin} className="space-y-4 text-xs">
            <div>
              <label className="block font-bold text-slate-700 mb-1 uppercase">Usuario Asignado</label>
              <input
                type="text"
                required
                value={usuarioInput}
                onChange={(e) => setUsuarioInput(e.target.value)}
                placeholder="ej: delegado_puno"
                className="w-full rounded-lg border border-slate-300 p-2.5 font-bold text-slate-900 text-sm focus:border-blue-600 focus:outline-none"
              />
            </div>

            <div>
              <label className="block font-bold text-slate-700 mb-1 uppercase">Contraseña</label>
              <input
                type="password"
                required
                value={claveInput}
                onChange={(e) => setClaveInput(e.target.value)}
                placeholder="puno2026"
                className="w-full rounded-lg border border-slate-300 p-2.5 font-bold text-slate-900 text-sm focus:border-blue-600 focus:outline-none"
              />
              <span className="mt-1 block text-[11px] text-slate-400">
                Credencial de muestra: <strong>delegado_puno</strong> / clave: <strong>puno2026</strong>
              </span>
            </div>

            <button
              type="submit"
              disabled={loginCargando}
              className="w-full rounded-lg bg-blue-600 py-3 text-xs font-black uppercase tracking-wider text-white hover:bg-blue-700 transition-colors disabled:opacity-50"
            >
              {loginCargando ? 'Verificando...' : 'Entrar al Portal de Delegado'}
            </button>
          </form>

          <div className="mt-6 pt-4 border-t border-slate-100 text-center text-xs font-semibold text-slate-500">
            <Link href="/" className="hover:text-slate-900">
              ← Volver al sitio público
            </Link>
          </div>
        </div>
      </div>
    );
  }

  // --- VISTA 2: Panel Operativo del Delegado ---
  return (
    <div className="min-h-screen bg-slate-50 py-8 px-4 sm:px-6">
      <div className="mx-auto max-w-[1200px] space-y-6">
        {/* Barra Superior del Delegado */}
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200">
          <div>
            <div className="inline-flex items-center gap-1.5 text-xs font-black uppercase text-blue-600 mb-1">
              <Shield className="size-3.5" />
              <span>{sesion.delegacionNombre || 'Delegación Oficial'}</span>
            </div>
            <h1 className="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
              {sesion.nombre}
            </h1>
            <p className="text-xs sm:text-sm text-slate-500 font-medium">
              Acceso limitado: Reporte de marcadores de tu equipo y registro de nómina de deportistas.
            </p>
          </div>

          <div className="flex items-center gap-2.5">
            <Link
              href="/"
              className="rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50"
            >
              Ver Fixture Público
            </Link>
            <button
              onClick={handleLogout}
              className="inline-flex items-center gap-1.5 rounded-lg bg-slate-900 px-3.5 py-2 text-xs font-bold text-white hover:bg-slate-800"
            >
              <LogOut className="size-3.5" />
              <span>Cerrar Sesión</span>
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

        {/* Pestañas del Delegado */}
        <div className="flex border-b border-slate-200 bg-white px-3 pt-2 rounded-t-xl gap-1">
          <button
            onClick={() => setTab('partidos')}
            className={`px-4 py-2.5 text-xs font-black uppercase tracking-wider rounded-t-lg transition-colors border-b-2 ${
              tab === 'partidos'
                ? 'border-blue-600 text-blue-600 bg-blue-50/50'
                : 'border-transparent text-slate-500 hover:text-slate-900'
            }`}
          >
            ⚽ Partidos de mi Delegación ({misPartidos.length})
          </button>

          <button
            onClick={() => setTab('nomina')}
            className={`px-4 py-2.5 text-xs font-black uppercase tracking-wider rounded-t-lg transition-colors border-b-2 ${
              tab === 'nomina'
                ? 'border-blue-600 text-blue-600 bg-blue-50/50'
                : 'border-transparent text-slate-500 hover:text-slate-900'
            }`}
          >
            📋 Nómina de Atletas ({atletas.length})
          </button>
        </div>

        {/* Contenido */}
        <div className="bg-white p-6 rounded-b-2xl border border-t-0 border-slate-200">
          {/* PESTAÑA 1: MIS PARTIDOS */}
          {tab === 'partidos' && (
            <div className="space-y-4">
              <div>
                <h3 className="text-sm font-black text-slate-900">
                  Partidos Asignados a {sesion.delegacionNombre}
                </h3>
                <p className="text-xs text-slate-500">
                  Puedes registrar el marcador y agregar notas de acta para los partidos donde compitió tu delegación.
                </p>
              </div>

              {misPartidos.length === 0 ? (
                <div className="rounded-xl border border-dashed border-slate-300 p-8 text-center bg-slate-50">
                  <Clock className="mx-auto size-8 text-slate-400 mb-2" />
                  <p className="text-xs font-bold text-slate-600">
                    Tu delegación aún no tiene partidos programados en las series actuales.
                  </p>
                  <p className="text-[11px] text-slate-400 mt-1">
                    El Administrador General programará los encuentros en el fixture oficial.
                  </p>
                </div>
              ) : (
                <div className="space-y-3">
                  {misPartidos.map(({ disciplinaSlug, disciplinaNombre, serieLetra, partido: p }) => (
                    <div
                      key={p.id}
                      className="rounded-xl border border-slate-200 bg-slate-50/50 p-4 space-y-3 text-xs"
                    >
                      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-200 pb-2">
                        <div>
                          <span className="font-extrabold text-blue-700 uppercase">
                            {disciplinaNombre} · Serie {serieLetra} · {p.rondaNombre}
                          </span>
                          <span className="text-slate-500 ml-2 font-medium">
                            {p.horario || 'Horario por confirmar'}
                          </span>
                        </div>
                        <span
                          className={`text-[11px] font-black uppercase px-2 py-0.5 rounded ${
                            p.jugado ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-700'
                          }`}
                        >
                          {p.jugado ? 'Resultado Reportado' : 'Pendiente de Marcador'}
                        </span>
                      </div>

                      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div className="text-sm font-black text-slate-900">
                          <span className={p.localId === sesion.delegacionId ? 'text-blue-700' : ''}>
                            {p.localNombre}
                          </span>
                          <span className="text-slate-400 font-normal mx-2">vs</span>
                          <span className={p.visitanteId === sesion.delegacionId ? 'text-blue-700' : ''}>
                            {p.visitanteNombre}
                          </span>
                        </div>

                        {/* Controles para reportar resultado */}
                        <div className="flex items-center gap-2">
                          <div className="flex items-center gap-1.5">
                            <span className="text-[11px] font-bold text-slate-500">Local:</span>
                            <input
                              type="number"
                              id={`del-loc-${p.id}`}
                              defaultValue={p.localGoles ?? ''}
                              className="w-14 rounded-lg border border-slate-300 p-1.5 font-black text-center bg-white"
                            />
                          </div>

                          <span className="font-black text-slate-400">-</span>

                          <div className="flex items-center gap-1.5">
                            <span className="text-[11px] font-bold text-slate-500">Visita:</span>
                            <input
                              type="number"
                              id={`del-vis-${p.id}`}
                              defaultValue={p.visitanteGoles ?? ''}
                              className="w-14 rounded-lg border border-slate-300 p-1.5 font-black text-center bg-white"
                            />
                          </div>

                          <button
                            type="button"
                            onClick={() => {
                              const gLoc = (document.getElementById(`del-loc-${p.id}`) as HTMLInputElement)?.value;
                              const gVis = (document.getElementById(`del-vis-${p.id}`) as HTMLInputElement)?.value;
                              let ganador: string | undefined = undefined;
                              if (gLoc !== '' && gVis !== '') {
                                if (Number(gLoc) > Number(gVis)) ganador = p.localId;
                                else if (Number(gVis) > Number(gLoc)) ganador = p.visitanteId;
                              }
                              handleGuardarMarcador(disciplinaSlug, serieLetra, p.id, gLoc, gVis, ganador);
                            }}
                            className="rounded-lg bg-blue-600 px-3 py-1.5 font-black text-white hover:bg-blue-700 transition-colors"
                          >
                            Guardar
                          </button>
                        </div>
                      </div>
                    </div>
                  ))}
                </div>
              )}
            </div>
          )}

          {/* PESTAÑA 2: NÓMINA DE ATLETAS */}
          {tab === 'nomina' && (
            <div className="space-y-6">
              {/* Formulario para inscribir atleta */}
              <div className="rounded-xl border border-slate-200 bg-slate-50 p-4">
                <h3 className="text-xs font-black uppercase tracking-wider text-slate-700 mb-3 flex items-center gap-1.5">
                  <Plus className="size-4 text-blue-600" /> Inscribir Deportista en {sesion.delegacionNombre}
                </h3>

                <form onSubmit={handleRegistrarAtleta} className="grid grid-cols-1 sm:grid-cols-5 gap-3 text-xs">
                  <div>
                    <label className="block font-bold text-slate-700 mb-1">DNI del Trabajador</label>
                    <input
                      type="text"
                      required
                      placeholder="8 dígitos"
                      value={nuevoAtleta.dni}
                      onChange={(e) => setNuevoAtleta({ ...nuevoAtleta, dni: e.target.value })}
                      className="w-full rounded-lg border border-slate-300 p-2 font-bold bg-white"
                    />
                  </div>

                  <div className="sm:col-span-2">
                    <label className="block font-bold text-slate-700 mb-1">Nombres y Apellidos</label>
                    <input
                      type="text"
                      required
                      placeholder="Nombres completos"
                      value={nuevoAtleta.nombreCompleto}
                      onChange={(e) => setNuevoAtleta({ ...nuevoAtleta, nombreCompleto: e.target.value })}
                      className="w-full rounded-lg border border-slate-300 p-2 font-bold bg-white"
                    />
                  </div>

                  <div>
                    <label className="block font-bold text-slate-700 mb-1">Deporte</label>
                    <select
                      value={nuevoAtleta.disciplinaSlug}
                      onChange={(e) => setNuevoAtleta({ ...nuevoAtleta, disciplinaSlug: e.target.value })}
                      className="w-full rounded-lg border border-slate-300 p-2 font-bold bg-white"
                    >
                      {disciplinas.map((d) => (
                        <option key={d.slug} value={d.slug}>
                          {d.nombre}
                        </option>
                      ))}
                    </select>
                  </div>

                  <div>
                    <label className="block font-bold text-slate-700 mb-1">Dorsal / Camiseta</label>
                    <input
                      type="text"
                      placeholder="ej: 10"
                      value={nuevoAtleta.numeroCamiseta}
                      onChange={(e) => setNuevoAtleta({ ...nuevoAtleta, numeroCamiseta: e.target.value })}
                      className="w-full rounded-lg border border-slate-300 p-2 font-bold bg-white"
                    />
                  </div>

                  <div className="sm:col-span-5 flex justify-end">
                    <button
                      type="submit"
                      className="rounded-lg bg-blue-600 px-4 py-2 font-black text-white hover:bg-blue-700"
                    >
                      Inscribir en la Nómina
                    </button>
                  </div>
                </form>
              </div>

              {/* Tabla de Nómina Oficial */}
              <div>
                <h3 className="text-sm font-black text-slate-900 mb-2">
                  Nómina Registrada ({atletas.length} atletas)
                </h3>
                {atletas.length === 0 ? (
                  <p className="text-xs text-slate-400 italic p-4 bg-slate-50 rounded-xl">
                    No has inscrito deportistas todavía. Añade a los integrantes de tu delegación arriba.
                  </p>
                ) : (
                  <div className="overflow-x-auto rounded-xl border border-slate-200">
                    <table className="w-full text-left text-xs">
                      <thead className="bg-slate-50 border-b border-slate-200 font-black text-slate-700 uppercase">
                        <tr>
                          <th className="p-3">DNI</th>
                          <th className="p-3">Nombre Completo</th>
                          <th className="p-3">Deporte</th>
                          <th className="p-3 text-center">Dorsal</th>
                          <th className="p-3 text-right">Acción</th>
                        </tr>
                      </thead>
                      <tbody className="divide-y divide-slate-100">
                        {atletas.map((a) => (
                          <tr key={a.id} className="hover:bg-slate-50">
                            <td className="p-3 font-mono font-bold text-slate-700">{a.dni}</td>
                            <td className="p-3 font-bold text-slate-900">{a.nombreCompleto}</td>
                            <td className="p-3 text-slate-600 uppercase font-semibold">{a.disciplinaSlug}</td>
                            <td className="p-3 text-center tabular-nums font-black">{a.numeroCamiseta || '-'}</td>
                            <td className="p-3 text-right">
                              <button
                                onClick={() => handleEliminarAtleta(a.id)}
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
                )}
              </div>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
