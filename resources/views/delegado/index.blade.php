@extends('layouts.app')

@section('title', 'Portal de Delegado · ' . ($delegacion?->nombre ?? 'Delegación'))

@section('content')
<div class="min-h-screen bg-slate-50 py-8 px-4 sm:px-6" x-data="{ tab: 'partidos' }">
    <div class="mx-auto max-w-[1200px] space-y-6">
        
        <!-- Tarjeta de Identificación del Delegado -->
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="grid size-12 place-items-center rounded-xl bg-blue-600 text-white font-black text-lg shrink-0 shadow-xs">
                    {{ substr($delegacion?->siglas ?? 'UG', 0, 2) }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="rounded bg-blue-100 px-2 py-0.5 text-[10px] font-black uppercase tracking-wider text-blue-800">
                            Portal Oficial de Delegado
                        </span>
                        <span class="text-xs font-bold text-slate-400">·</span>
                        <span class="text-xs font-bold text-slate-500">{{ $user->name }}</span>
                    </div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight mt-0.5">
                        {{ $delegacion?->nombre ?? 'Delegación sin asignar' }}
                    </h1>
                    <p class="text-xs text-slate-500 font-medium">
                        Provincia: <strong>{{ $delegacion?->provincia ?? '-' }}</strong> · Siglas: <strong>{{ $delegacion?->siglas ?? '-' }}</strong>
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-4 border-t md:border-t-0 md:border-l border-slate-200 pt-3 md:pt-0 md:pl-6 text-xs">
                <div class="text-left">
                    <span class="block text-xl font-black text-slate-900 tabular-nums">{{ $delegacion?->puntos ?? 0 }}</span>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Puntos</span>
                </div>
                <div class="h-6 w-px bg-slate-200"></div>
                <div class="text-left">
                    <span class="block text-xl font-black text-slate-900 tabular-nums">{{ $partidos->count() }}</span>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Partidos</span>
                </div>
                <div class="h-6 w-px bg-slate-200"></div>
                <div class="text-left">
                    <span class="block text-xl font-black text-slate-900 tabular-nums">{{ $atletas->count() }}</span>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Deportistas</span>
                </div>
            </div>
        </div>

        <!-- Selector de Pestañas -->
        <div class="flex items-center gap-2 border-b border-slate-200 pb-2">
            <button
                type="button"
                @click="tab = 'partidos'"
                :class="tab === 'partidos' ? 'bg-slate-900 text-white shadow-2xs font-extrabold' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200 font-bold'"
                class="px-4 py-2 text-xs uppercase tracking-wider rounded-lg transition-colors cursor-pointer"
            >
                Partidos de mi Delegación ({{ $partidos->count() }})
            </button>
            <button
                type="button"
                @click="tab = 'nomina'"
                :class="tab === 'nomina' ? 'bg-slate-900 text-white shadow-2xs font-extrabold' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200 font-bold'"
                class="px-4 py-2 text-xs uppercase tracking-wider rounded-lg transition-colors cursor-pointer"
            >
                Nómina de Deportistas ({{ $atletas->count() }})
            </button>
        </div>

        <!-- PESTAÑA 1: Partidos de mi Delegación -->
        <div x-show="tab === 'partidos'" class="space-y-4">
            <div class="rounded-xl border border-blue-200 bg-blue-50/60 p-4 text-xs font-medium text-blue-900">
                Como delegado oficial de <strong>{{ $delegacion?->nombre }}</strong>, puedes registrar y actualizar el resultado final de los partidos disputados por tu equipo.
            </div>

            @forelse($partidos as $partido)
                @php
                    $esFinalizado = $partido->estado === 'FINALIZADO';
                    $esLocal = $partido->local_id === $user->delegacion_id;
                @endphp
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-2xs">
                    <form action="{{ route('delegado.marcador') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="partido_id" value="{{ $partido->id }}">

                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
                            <div class="flex items-center gap-2">
                                <span class="rounded bg-slate-900 px-2 py-0.5 text-[10px] font-black uppercase text-white">
                                    {{ $partido->serie?->disciplina?->nombre ?? 'Deporte' }}
                                </span>
                                <span class="text-xs font-bold text-slate-500">
                                    {{ $partido->serie?->nombre }} · {{ $partido->ronda_nombre }}
                                </span>
                            </div>

                            <div class="flex items-center gap-2 text-xs text-slate-500 font-semibold">
                                <span>{{ $partido->cancha ?: 'Cancha Principal' }}</span>
                                <span>·</span>
                                <span>{{ $partido->horario ?: '09:00 AM' }}</span>
                                @if($esFinalizado)
                                    <span class="rounded bg-emerald-100 px-2 py-0.5 text-[10px] font-black text-emerald-800 uppercase">
                                        Finalizado
                                    </span>
                                @else
                                    <span class="rounded bg-amber-100 px-2 py-0.5 text-[10px] font-black text-amber-800 uppercase">
                                        Programado
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Marcadores y Equipos -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-center">
                            
                            <!-- Equipo Local -->
                            <div class="flex items-center justify-between p-3 rounded-lg border {{ $partido->local_id === $user->delegacion_id ? 'border-blue-300 bg-blue-50/40' : 'border-slate-200 bg-slate-50/40' }}">
                                <div class="flex items-center gap-2">
                                    <span class="grid size-6 place-items-center rounded bg-slate-900 text-white text-[10px] font-black shrink-0">
                                        {{ substr($partido->local?->siglas ?? 'L', 0, 2) }}
                                    </span>
                                    <div>
                                        <span class="text-xs font-black text-slate-900 block">
                                            {{ $partido->local?->nombre ?? 'Local por definir' }}
                                        </span>
                                        @if($partido->local_id === $user->delegacion_id)
                                            <span class="text-[10px] font-bold text-blue-600 uppercase">Tu Delegación</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-1">
                                    <label class="text-[10px] font-bold text-slate-400">Goles:</label>
                                    <input
                                        type="number"
                                        name="local_goles"
                                        min="0"
                                        value="{{ $partido->local_goles }}"
                                        class="w-14 rounded-lg border border-slate-300 bg-white p-1.5 text-center text-sm font-black text-slate-900 focus:border-slate-900 focus:outline-none tabular-nums"
                                    />
                                </div>
                            </div>

                            <!-- Equipo Visitante -->
                            <div class="flex items-center justify-between p-3 rounded-lg border {{ $partido->visitante_id === $user->delegacion_id ? 'border-blue-300 bg-blue-50/40' : 'border-slate-200 bg-slate-50/40' }}">
                                <div class="flex items-center gap-2">
                                    <span class="grid size-6 place-items-center rounded bg-slate-900 text-white text-[10px] font-black shrink-0">
                                        {{ substr($partido->visitante?->siglas ?? 'V', 0, 2) }}
                                    </span>
                                    <div>
                                        <span class="text-xs font-black text-slate-900 block">
                                            {{ $partido->visitante?->nombre ?? 'Visitante por definir' }}
                                        </span>
                                        @if($partido->visitante_id === $user->delegacion_id)
                                            <span class="text-[10px] font-bold text-blue-600 uppercase">Tu Delegación</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-1">
                                    <label class="text-[10px] font-bold text-slate-400">Goles:</label>
                                    <input
                                        type="number"
                                        name="visitante_goles"
                                        min="0"
                                        value="{{ $partido->visitante_goles }}"
                                        class="w-14 rounded-lg border border-slate-300 bg-white p-1.5 text-center text-sm font-black text-slate-900 focus:border-slate-900 focus:outline-none tabular-nums"
                                    />
                                </div>
                            </div>

                        </div>

                        <!-- Observaciones y Guardado -->
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
                            <input
                                type="text"
                                name="observaciones"
                                value="{{ $partido->observaciones }}"
                                placeholder="Observaciones o incidencias del encuentro (opcional)..."
                                class="w-full sm:w-2/3 rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-700 placeholder:text-slate-400 focus:border-slate-900 focus:outline-none"
                            />

                            <button
                                type="submit"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 rounded-lg bg-slate-900 px-4 py-2 text-xs font-extrabold text-white hover:bg-slate-800 transition-colors shrink-0 cursor-pointer"
                            >
                                <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                <span>Guardar Resultado</span>
                            </button>
                        </div>
                    </form>
                </div>
            @empty
                <div class="rounded-xl border border-dashed border-slate-300 bg-white p-12 text-center">
                    <p class="text-sm font-semibold text-slate-600">
                        No hay partidos programados actualmente para tu delegación.
                    </p>
                    <p class="text-xs text-slate-400 mt-1">
                        La comisión organizadora publicará los cruces y horarios en el fixture oficial.
                    </p>
                </div>
            @endforelse
        </div>

        <!-- PESTAÑA 2: Nómina de Deportistas -->
        <div x-show="tab === 'nomina'" class="space-y-6">
            
            <!-- Formulario Inscribir Atleta -->
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-2xs">
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider mb-3">
                    Inscribir Deportista en la Nómina Oficial
                </h3>

                <form action="{{ route('delegado.atleta.guardar') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
                    @csrf

                    <div>
                        <label class="block text-[11px] font-bold uppercase text-slate-600 mb-1">DNI</label>
                        <input
                            type="text"
                            name="dni"
                            placeholder="Ej. 70829143"
                            maxlength="15"
                            required
                            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-900 focus:border-slate-900 focus:outline-none"
                        />
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-bold uppercase text-slate-600 mb-1">Nombre Completo</label>
                        <input
                            type="text"
                            name="nombre_completo"
                            placeholder="Ej. Carlos Mendoza Ramos"
                            required
                            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-900 focus:border-slate-900 focus:outline-none"
                        />
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase text-slate-600 mb-1">Disciplina</label>
                        <select
                            name="disciplina_id"
                            required
                            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-900 focus:border-slate-900 focus:outline-none"
                        >
                            @foreach($disciplinas as $d)
                                <option value="{{ $d->id }}">{{ $d->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button
                            type="submit"
                            class="w-full inline-flex items-center justify-center gap-1.5 rounded-lg bg-blue-600 py-2.5 text-xs font-extrabold text-white hover:bg-blue-700 transition-colors cursor-pointer"
                        >
                            <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                            <span>Inscribir</span>
                        </button>
                    </div>

                    <div class="sm:col-span-2 md:col-span-2">
                        <label class="block text-[11px] font-bold uppercase text-slate-600 mb-1">N° Camiseta (Opcional)</label>
                        <input
                            type="text"
                            name="numero_camiseta"
                            placeholder="Ej. 10"
                            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-900 focus:border-slate-900 focus:outline-none"
                        />
                    </div>

                    <div class="sm:col-span-2 md:col-span-3">
                        <label class="block text-[11px] font-bold uppercase text-slate-600 mb-1">Condición en el Equipo</label>
                        <select
                            name="rol_equipo"
                            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-900 focus:border-slate-900 focus:outline-none"
                        >
                            <option value="Titular">Titular</option>
                            <option value="Suplente">Suplente</option>
                            <option value="Capitán">Capitán / Delegado de Campo</option>
                        </select>
                    </div>
                </form>
            </div>

            <!-- Tabla de Deportistas Registrados -->
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="border-b border-slate-200 bg-slate-50 text-[11px] font-black uppercase text-slate-500">
                            <tr>
                                <th class="py-3 pl-4 pr-3">DNI</th>
                                <th class="px-3 py-3">Nombre Completo</th>
                                <th class="px-3 py-3">Disciplina</th>
                                <th class="px-3 py-3 text-center">N°</th>
                                <th class="px-3 py-3">Condición</th>
                                <th class="py-3 pl-3 pr-4 text-right">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($atletas as $atleta)
                                <tr class="hover:bg-slate-50">
                                    <td class="py-3 pl-4 pr-3 font-mono font-bold text-slate-800">
                                        {{ $atleta->dni }}
                                    </td>
                                    <td class="px-3 py-3 font-extrabold text-slate-900">
                                        {{ $atleta->nombre_completo }}
                                    </td>
                                    <td class="px-3 py-3 font-semibold text-slate-600">
                                        {{ $atleta->disciplina?->nombre ?? '-' }}
                                    </td>
                                    <td class="px-3 py-3 text-center font-black text-slate-800">
                                        {{ $atleta->numero_camiseta ?: '-' }}
                                    </td>
                                    <td class="px-3 py-3">
                                        <span class="rounded bg-slate-100 px-2 py-0.5 text-[10px] font-bold text-slate-700">
                                            {{ $atleta->rol_equipo }}
                                        </span>
                                    </td>
                                    <td class="py-3 pl-3 pr-4 text-right">
                                        <form action="{{ route('delegado.atleta.eliminar', $atleta->id) }}" method="POST" onsubmit="return confirm('¿Retirar este deportista de la nómina?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold cursor-pointer">
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-slate-400 font-medium">
                                        Aún no has registrado deportistas en la nómina de tu delegación.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
