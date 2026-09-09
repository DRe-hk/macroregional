@extends('layouts.app')

@section('title', 'Suite de Administración General · ' . $torneo->nombre)

@section('content')
<div class="min-h-screen bg-slate-50 py-8 px-4 sm:px-6" x-data="{ tab: 'torneo' }">
    <div class="mx-auto max-w-[1360px] space-y-6">
        
        <!-- Cabecera de Administración -->
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="grid size-12 place-items-center rounded-xl bg-slate-900 text-white font-black text-lg shrink-0 shadow-xs">
                    <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="rounded bg-rose-100 px-2 py-0.5 text-[10px] font-black uppercase tracking-wider text-rose-900">
                            Administrador General
                        </span>
                        <span class="text-xs font-bold text-slate-400">·</span>
                        <span class="text-xs font-bold text-slate-500">Acceso Privado</span>
                    </div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight mt-0.5">
                        Panel de Control Deportivo
                    </h1>
                    <p class="text-xs text-slate-500 font-medium">
                        Gestión total del torneo, disciplinas, delegaciones, programación de partidos y emisión de credenciales.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 self-start md:self-auto">
                <a
                    href="{{ route('home') }}"
                    target="_blank"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors shadow-2xs"
                >
                    <span>Ver Sitio Público</span>
                    <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" x2="21" y1="14" y2="3"/></svg>
                </a>
            </div>
        </div>

        <!-- Barra de Pestañas del Panel Admin -->
        <div class="flex items-center gap-1.5 border-b border-slate-200 pb-2 overflow-x-auto">
            <button
                type="button"
                @click="tab = 'torneo'"
                :class="tab === 'torneo' ? 'bg-slate-900 text-white shadow-2xs font-black' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200 font-bold'"
                class="px-4 py-2 text-xs uppercase tracking-wider rounded-lg transition-colors shrink-0 cursor-pointer"
            >
                🏆 Torneo
            </button>
            <button
                type="button"
                @click="tab = 'deportes'"
                :class="tab === 'deportes' ? 'bg-slate-900 text-white shadow-2xs font-black' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200 font-bold'"
                class="px-4 py-2 text-xs uppercase tracking-wider rounded-lg transition-colors shrink-0 cursor-pointer"
            >
                ⚽ Deportes ({{ $disciplinas->count() }})
            </button>
            <button
                type="button"
                @click="tab = 'equipos'"
                :class="tab === 'equipos' ? 'bg-slate-900 text-white shadow-2xs font-black' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200 font-bold'"
                class="px-4 py-2 text-xs uppercase tracking-wider rounded-lg transition-colors shrink-0 cursor-pointer"
            >
                🏛️ Delegaciones ({{ $delegaciones->count() }})
            </button>
            <button
                type="button"
                @click="tab = 'fixture'"
                :class="tab === 'fixture' ? 'bg-slate-900 text-white shadow-2xs font-black' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200 font-bold'"
                class="px-4 py-2 text-xs uppercase tracking-wider rounded-lg transition-colors shrink-0 cursor-pointer"
            >
                📅 Fixture & Marcadores
            </button>
            <button
                type="button"
                @click="tab = 'delegados'"
                :class="tab === 'delegados' ? 'bg-slate-900 text-white shadow-2xs font-black' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200 font-bold'"
                class="px-4 py-2 text-xs uppercase tracking-wider rounded-lg transition-colors shrink-0 cursor-pointer"
            >
                🔑 Delegados ({{ $usuarios->where('role', 'DELEGADO')->count() }})
            </button>
            <button
                type="button"
                @click="tab = 'reiniciar'"
                :class="tab === 'reiniciar' ? 'bg-rose-900 text-white shadow-2xs font-black' : 'bg-white text-rose-700 hover:bg-rose-50 border border-rose-200 font-bold'"
                class="px-4 py-2 text-xs uppercase tracking-wider rounded-lg transition-colors shrink-0 cursor-pointer"
            >
                🔄 Reiniciar BD
            </button>
        </div>

        <!-- 1. PESTAÑA: TORNEO -->
        <div x-show="tab === 'torneo'" class="rounded-xl border border-slate-200 bg-white p-6 shadow-2xs">
            <h2 class="text-base font-black text-slate-900 mb-4">Información General del Torneo</h2>
            <form action="{{ route('admin.torneo.update') }}" method="POST" class="space-y-4 max-w-2xl">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Nombre Oficial del Torneo</label>
                    <input type="text" name="nombre" value="{{ $torneo->nombre }}" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm font-bold text-slate-900 focus:border-slate-900 focus:outline-none" />
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Subtítulo / Denominación</label>
                    <input type="text" name="subtitulo" value="{{ $torneo->subtitulo }}" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-900 focus:border-slate-900 focus:outline-none" />
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Entidad Organizadora</label>
                    <input type="text" name="organizador" value="{{ $torneo->organizador }}" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-900 focus:border-slate-900 focus:outline-none" />
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Sede Principal</label>
                        <input type="text" name="sede_principal" value="{{ $torneo->sede_principal }}" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-900 focus:border-slate-900 focus:outline-none" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Año de Edición</label>
                        <input type="number" name="anio" value="{{ $torneo->anio }}" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-900 focus:border-slate-900 focus:outline-none" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">% de Avance</label>
                        <input type="number" name="avance_porcentaje" min="0" max="100" value="{{ $torneo->avance_porcentaje }}" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-900 focus:border-slate-900 focus:outline-none" />
                    </div>
                </div>

                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-5 py-2.5 text-xs font-black uppercase tracking-wider text-white hover:bg-slate-800 transition-colors cursor-pointer">
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    <span>Guardar Cambios del Torneo</span>
                </button>
            </form>
        </div>

        <!-- 2. PESTAÑA: DEPORTES -->
        <div x-show="tab === 'deportes'" class="space-y-6">
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-2xs">
                <h2 class="text-base font-black text-slate-900 mb-4">Añadir o Editar Disciplina Deportiva</h2>
                <form action="{{ route('admin.deporte.guardar') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Nombre del Deporte</label>
                        <input type="text" name="nombre" placeholder="Ej. Balonmano Varones" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Categoría</label>
                        <input type="text" name="categoria" placeholder="Ej. Balonmano" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Color de Acento</label>
                        <input type="color" name="color_acento" value="#2563eb" class="w-full h-9 rounded-lg border border-slate-300 p-1 cursor-pointer" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Sede Principal</label>
                        <input type="text" name="sede_principal" placeholder="Ej. Coliseo Puno" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none" />
                    </div>
                    <div class="sm:col-span-2 md:col-span-3">
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Descripción</label>
                        <input type="text" name="descripcion" placeholder="Detalle de la competencia..." class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none" />
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 rounded-lg bg-slate-900 py-2.5 text-xs font-bold text-white hover:bg-slate-800 transition-colors cursor-pointer">
                            <span>Crear Deporte</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabla de Deportes -->
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xs">
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-slate-200 bg-slate-50 text-[11px] font-black uppercase text-slate-500">
                        <tr>
                            <th class="py-3 pl-4 pr-3">Nombre</th>
                            <th class="px-3 py-3">Categoría</th>
                            <th class="px-3 py-3">Series Creadas</th>
                            <th class="px-3 py-3">Sede</th>
                            <th class="px-3 py-3">Campeón Actual</th>
                            <th class="py-3 pl-3 pr-4 text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($disciplinas as $d)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 pl-4 pr-3 font-extrabold text-slate-900">
                                    {{ $d->nombre }}
                                </td>
                                <td class="px-3 py-3 font-semibold text-slate-600">
                                    {{ $d->categoria }}
                                </td>
                                <td class="px-3 py-3 font-bold text-slate-700">
                                    {{ $d->series->pluck('nombre')->join(', ') ?: 'Sin series' }}
                                </td>
                                <td class="px-3 py-3 text-slate-500">
                                    {{ $d->sede_principal }}
                                </td>
                                <td class="px-3 py-3 font-black text-amber-950">
                                    {{ $d->campeon_actual ?: '-' }}
                                </td>
                                <td class="py-3 pl-3 pr-4 text-right">
                                    <form action="{{ route('admin.deporte.eliminar', $d->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este deporte? Se borrarán sus series y partidos asociados.');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold cursor-pointer">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 3. PESTAÑA: DELEGACIONES -->
        <div x-show="tab === 'equipos'" class="space-y-6">
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-2xs">
                <h2 class="text-base font-black text-slate-900 mb-4">Añadir Nueva Delegación / UGEL</h2>
                <form action="{{ route('admin.delegacion.guardar') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    @csrf
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Nombre Completo de la Institución</label>
                        <input type="text" name="nombre" placeholder="Ej. UGEL Puno" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Siglas</label>
                        <input type="text" name="siglas" placeholder="Ej. Puno" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Provincia</label>
                        <input type="text" name="provincia" placeholder="Ej. Puno" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none" />
                    </div>
                    <div class="sm:col-span-4 flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-slate-900 px-5 py-2 text-xs font-bold text-white hover:bg-slate-800 transition-colors cursor-pointer">
                            <span>Guardar Delegación</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabla de Delegaciones -->
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xs">
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-slate-200 bg-slate-50 text-[11px] font-black uppercase text-slate-500">
                        <tr>
                            <th class="py-3 pl-4 pr-3">Nombre</th>
                            <th class="px-3 py-3">Siglas</th>
                            <th class="px-3 py-3">Provincia</th>
                            <th class="px-3 py-3 text-center">Partidos (PJ)</th>
                            <th class="px-3 py-3 text-center">Puntos</th>
                            <th class="py-3 pl-3 pr-4 text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($delegaciones as $del)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 pl-4 pr-3 font-extrabold text-slate-900">
                                    {{ $del->nombre }}
                                </td>
                                <td class="px-3 py-3 font-bold text-blue-600">
                                    {{ $del->siglas }}
                                </td>
                                <td class="px-3 py-3 text-slate-500">
                                    {{ $del->provincia }}
                                </td>
                                <td class="px-3 py-3 text-center font-bold text-slate-700 tabular-nums">
                                    {{ $del->pj }}
                                </td>
                                <td class="px-3 py-3 text-center font-black text-slate-900 tabular-nums">
                                    {{ $del->puntos }}
                                </td>
                                <td class="py-3 pl-3 pr-4 text-right">
                                    <form action="{{ route('admin.delegacion.eliminar', $del->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta delegación?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold cursor-pointer">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 4. PESTAÑA: FIXTURE & MARCADORES -->
        <div x-show="tab === 'fixture'" class="space-y-6">
            
            <!-- Programador de Nuevos Partidos -->
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-2xs">
                <h2 class="text-base font-black text-slate-900 mb-4">Programar Encuentro en Fixture</h2>
                <form action="{{ route('admin.partido.guardar') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Serie / Disciplina</label>
                        <select name="serie_id" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs font-semibold text-slate-900 focus:border-slate-900 focus:outline-none">
                            @foreach($disciplinas as $d)
                                <optgroup label="{{ $d->nombre }}">
                                    @foreach($d->series as $s)
                                        <option value="{{ $s->id }}">{{ $d->nombre }} - {{ $s->nombre }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Nombre de Ronda</label>
                        <input type="text" name="ronda_nombre" placeholder="Ej. Ronda 1 o Semifinal" value="Ronda 1" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Número de Ronda (Orden)</label>
                        <input type="number" name="ronda_numero" min="1" value="1" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Horario</label>
                        <input type="text" name="horario" placeholder="Ej. 09:00 AM" value="09:00 AM" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Equipo Local</label>
                        <select name="local_id" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs font-semibold text-slate-900 focus:border-slate-900 focus:outline-none">
                            <option value="">-- Por Definir --</option>
                            @foreach($delegaciones as $del)
                                <option value="{{ $del->id }}">{{ $del->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Equipo Visitante</label>
                        <select name="visitante_id" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs font-semibold text-slate-900 focus:border-slate-900 focus:outline-none">
                            <option value="">-- Por Definir --</option>
                            @foreach($delegaciones as $del)
                                <option value="{{ $del->id }}">{{ $del->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Cancha / Losa</label>
                        <input type="text" name="cancha" placeholder="Ej. Cancha 1" value="Cancha Principal" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none" />
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 rounded-lg bg-slate-900 py-2.5 text-xs font-bold text-white hover:bg-slate-800 transition-colors cursor-pointer">
                            <span>Añadir al Fixture</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Listado y Edición de Marcadores -->
            <div class="space-y-4">
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">
                    Partidos Programados y Carga de Marcadores
                </h3>

                @php
                    $todosPartidos = \App\Models\Partido::with(['serie.disciplina', 'local', 'visitante', 'ganador'])->orderByDesc('created_at')->get();
                @endphp

                @forelse($todosPartidos as $partido)
                    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-2xs">
                        <form action="{{ route('admin.marcador.update') }}" method="POST" class="space-y-3">
                            @csrf
                            <input type="hidden" name="partido_id" value="{{ $partido->id }}">

                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-2">
                                <div class="flex items-center gap-2">
                                    <span class="rounded bg-slate-900 px-2 py-0.5 text-[10px] font-black uppercase text-white">
                                        {{ $partido->serie?->disciplina?->nombre ?? 'Deporte' }}
                                    </span>
                                    <span class="text-xs font-bold text-slate-600">
                                        {{ $partido->serie?->nombre }} · {{ $partido->ronda_nombre }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-3 text-xs text-slate-500 font-semibold">
                                    <span>{{ $partido->cancha }} · {{ $partido->horario }}</span>
                                    <span class="rounded px-2 py-0.5 text-[10px] font-black uppercase {{ $partido->estado === 'FINALIZADO' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                                        {{ $partido->estado }}
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-center">
                                <div class="flex items-center justify-between p-3 rounded-lg border border-slate-200 bg-slate-50/50">
                                    <span class="text-xs font-extrabold text-slate-900 truncate pr-2">
                                        {{ $partido->local?->nombre ?? 'Por Definir' }}
                                    </span>
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <label class="text-[10px] font-bold text-slate-400">Goles:</label>
                                        <input type="number" name="local_goles" min="0" value="{{ $partido->local_goles }}" class="w-14 rounded-lg border border-slate-300 bg-white p-1 text-center text-sm font-black text-slate-900 tabular-nums" />
                                    </div>
                                </div>

                                <div class="flex items-center justify-between p-3 rounded-lg border border-slate-200 bg-slate-50/50">
                                    <span class="text-xs font-extrabold text-slate-900 truncate pr-2">
                                        {{ $partido->visitante?->nombre ?? 'Por Definir' }}
                                    </span>
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <label class="text-[10px] font-bold text-slate-400">Goles:</label>
                                        <input type="number" name="visitante_goles" min="0" value="{{ $partido->visitante_goles }}" class="w-14 rounded-lg border border-slate-300 bg-white p-1 text-center text-sm font-black text-slate-900 tabular-nums" />
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
                                <input type="text" name="observaciones" value="{{ $partido->observaciones }}" placeholder="Observaciones..." class="w-full sm:w-2/3 rounded-lg border border-slate-300 px-3 py-1.5 text-xs text-slate-700" />
                                <div class="flex items-center gap-2">
                                    <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-slate-900 px-4 py-1.5 text-xs font-extrabold text-white hover:bg-slate-800 transition-colors cursor-pointer">
                                        Guardar Marcador
                                    </button>
                        </form>
                                    <form action="{{ route('admin.partido.eliminar', $partido->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este partido?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-rose-600 hover:text-rose-800 font-bold text-xs cursor-pointer">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                    </div>
                @empty
                    <div class="rounded-xl border border-dashed border-slate-300 bg-white p-12 text-center text-slate-400 font-medium">
                        No hay partidos programados aún en el fixture.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- 5. PESTAÑA: DELEGADOS (CREDENCIALES) -->
        <div x-show="tab === 'delegados'" class="space-y-6">
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-2xs">
                <h2 class="text-base font-black text-slate-900 mb-1">Crear Credenciales de Acceso para Delegados</h2>
                <p class="text-xs text-slate-500 mb-4">Los delegados ingresan por <strong>/entrar</strong> y solo tienen permisos para reportar marcadores de sus propios partidos y gestionar su nómina de deportistas.</p>

                <form action="{{ route('admin.delegado.guardar') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Nombre de Usuario</label>
                        <input type="text" name="username" placeholder="Ej. delegado_melgar" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Nombre Completo del Delegado</label>
                        <input type="text" name="name" placeholder="Ej. Prof. Juan Pérez" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Contraseña</label>
                        <input type="text" name="clave" placeholder="Contraseña segura" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Delegación / UGEL Asignada</label>
                        <select name="delegacion_id" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs font-semibold text-slate-900 focus:border-slate-900 focus:outline-none">
                            @foreach($delegaciones as $del)
                                <option value="{{ $del->id }}">{{ $del->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-2 md:col-span-4 flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-5 py-2.5 text-xs font-black uppercase text-white hover:bg-blue-700 transition-colors cursor-pointer">
                            <span>Crear Cuenta de Delegado</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabla de Usuarios -->
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xs">
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-slate-200 bg-slate-50 text-[11px] font-black uppercase text-slate-500">
                        <tr>
                            <th class="py-3 pl-4 pr-3">Usuario</th>
                            <th class="px-3 py-3">Nombre</th>
                            <th class="px-3 py-3">Rol</th>
                            <th class="px-3 py-3">Delegación Asignada</th>
                            <th class="py-3 pl-3 pr-4 text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($usuarios as $u)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 pl-4 pr-3 font-mono font-bold text-slate-900">
                                    {{ $u->username }}
                                </td>
                                <td class="px-3 py-3 font-extrabold text-slate-900">
                                    {{ $u->name }}
                                </td>
                                <td class="px-3 py-3">
                                    @if($u->role === 'ADMIN')
                                        <span class="rounded bg-slate-900 px-2 py-0.5 text-[10px] font-black text-white uppercase">
                                            ADMINISTRADOR
                                        </span>
                                    @else
                                        <span class="rounded bg-blue-100 px-2 py-0.5 text-[10px] font-black text-blue-800 uppercase">
                                            DELEGADO
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-slate-600 font-semibold">
                                    {{ $u->delegacion?->nombre ?? ($u->role === 'ADMIN' ? 'Acceso Global' : '-') }}
                                </td>
                                <td class="py-3 pl-3 pr-4 text-right">
                                    @if($u->role !== 'ADMIN')
                                        <form action="{{ route('admin.delegado.eliminar', $u->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta cuenta de delegado?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold cursor-pointer">
                                                Eliminar
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-slate-400 font-semibold italic">Principal</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 6. PESTAÑA: REINICIAR BD -->
        <div x-show="tab === 'reiniciar'" class="rounded-xl border border-rose-200 bg-white p-6 shadow-2xs">
            <div class="flex items-start gap-4 max-w-2xl">
                <div class="grid size-12 place-items-center rounded-xl bg-rose-100 text-rose-700 shrink-0">
                    <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
                </div>
                <div>
                    <h2 class="text-base font-black text-slate-900">Restablecer Base de Datos a Estado Inicial</h2>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                        Esta acción ejecutará una recarga limpia del sistema: conservará las 15 delegaciones oficiales, los 6 deportes base y las credenciales iniciales, pero <strong>reiniciará a cero todos los partidos jugados, goles y puntos acumulados</strong>.
                    </p>

                    <form action="{{ route('admin.reiniciar') }}" method="POST" onsubmit="return confirm('¿ESTÁS SEGURO? Se borrarán todos los marcadores y se reiniciará el torneo a 0 puntos.');" class="mt-4">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-rose-600 px-5 py-2.5 text-xs font-black uppercase tracking-wider text-white hover:bg-rose-700 transition-colors cursor-pointer">
                            <span>Confirmar y Reiniciar Base de Datos</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
