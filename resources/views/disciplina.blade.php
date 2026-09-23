@extends('layouts.app')

@section('title', $disciplina->nombre . ' · ' . $torneo->nombre)

@section('content')
<div class="bg-white min-h-screen pb-16">
    <!-- Cabecera de la Disciplina -->
    <div class="border-b border-slate-200 bg-slate-50 py-8 px-4 sm:px-6">
        <div class="mx-auto max-w-[1360px]">
            <!-- Migas de Pan -->
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-3">
                <a href="{{ route('home') }}" class="hover:text-slate-900 transition-colors">Deportes</a>
                <svg class="size-3 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
                <span class="text-slate-900 font-extrabold">{{ $disciplina->nombre }}</span>
            </nav>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-blue-600 block">
                        {{ $disciplina->categoria }}
                    </span>
                    <h1 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight mt-0.5">
                        {{ $disciplina->nombre }}
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-600 font-medium mt-1">
                        {{ $disciplina->descripcion ?: 'Fixture oficial y árbol de eliminatorias en tiempo real.' }}
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    @if($disciplina->sede_principal)
                        <div class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-xs font-bold text-slate-700 shadow-2xs">
                            <svg class="size-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            <span>{{ $disciplina->sede_principal }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Foto de Referencia / Escenario si existe -->
            @if($disciplina->foto_referencia_url)
                <div class="mt-6 flex flex-col sm:flex-row items-center gap-4 rounded-xl border border-slate-200 bg-white p-3 shadow-2xs">
                    <div class="h-28 w-full sm:w-44 shrink-0 overflow-hidden rounded-lg bg-slate-100 relative">
                        <img
                            src="{{ $disciplina->foto_referencia_url }}"
                            alt="Referencia {{ $disciplina->nombre }}"
                            class="h-full w-full object-cover"
                            loading="lazy"
                        />
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-1.5 text-[10px] font-black uppercase tracking-wider text-blue-600 mb-1">
                            <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                            <span>Imagen de Referencia / Escenario Oficial</span>
                        </div>
                        <h4 class="text-sm font-black text-slate-900">{{ $disciplina->sede_principal ?: $disciplina->nombre }}</h4>
                        <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">
                            Escenario oficial designado para los encuentros de {{ $disciplina->nombre }} en la competencia macroregional.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Contenedor Principal: Series, Bracket y Tabla de Posiciones -->
    <div class="mx-auto max-w-[1360px] px-4 sm:px-6 mt-8 space-y-6" x-data="{ vistaActiva: 'bracket' }">
        
        <!-- Barra de Controles: Series y Selector de Vista (Bracket vs Tabla) -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-3">
            <!-- Selector de Series -->
            @if($disciplina->series->count() > 0)
                <div class="flex items-center gap-2 overflow-x-auto">
                    @foreach($disciplina->series as $s)
                        @php
                            $esActiva = $s->letra === $letraActiva;
                        @endphp
                        <a
                            href="{{ route('disciplina.show', ['slug' => $disciplina->slug, 'serie' => $s->letra]) }}"
                            class="flex items-center gap-1.5 px-4 py-2 text-xs font-black uppercase tracking-wider rounded-lg transition-colors shrink-0 {{ $esActiva ? 'bg-slate-900 text-white shadow-2xs' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}"
                        >
                            <span>{{ $s->nombre }}</span>
                        </a>
                    @endforeach
                </div>
            @endif

            <!-- Selector de Vista: Bracket vs Tabla Individual -->
            <div class="inline-flex rounded-xl bg-slate-100 p-1 border border-slate-200 shrink-0">
                <button
                    type="button"
                    @click="vistaActiva = 'bracket'"
                    :class="vistaActiva === 'bracket' ? 'bg-white text-slate-950 font-black shadow-2xs' : 'text-slate-600 font-bold hover:text-slate-900'"
                    class="flex items-center gap-1.5 px-3.5 py-1.5 text-xs rounded-lg transition-all"
                >
                    <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                    <span>Llaves & Fixture</span>
                </button>
                <button
                    type="button"
                    @click="vistaActiva = 'posiciones'"
                    :class="vistaActiva === 'posiciones' ? 'bg-white text-slate-950 font-black shadow-2xs' : 'text-slate-600 font-bold hover:text-slate-900'"
                    class="flex items-center gap-1.5 px-3.5 py-1.5 text-xs rounded-lg transition-all"
                >
                    <svg class="size-3.5 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                    <span>Tabla de Posiciones</span>
                </button>
            </div>
        </div>

        <!-- VISTA 1: Árbol de Eliminatorias / Bracket -->
        <div x-show="vistaActiva === 'bracket'" class="space-y-4">
            @if($serieSeleccionada)
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-2">
                    <div>
                        <h2 class="text-xl font-black text-slate-900">{{ $serieSeleccionada->nombre }}</h2>
                    </div>

                    @if($serieSeleccionada->sede_nombre)
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 bg-white border border-slate-200 px-3 py-1.5 rounded-lg shadow-2xs">
                            <svg class="size-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            <span>{{ $serieSeleccionada->sede_nombre }}</span>
                        </span>
                    @endif
                </div>

                @if(count($rondasMap) > 0)
                    <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white p-5 bracket-scroll">
                        <div class="min-w-[700px] flex items-stretch gap-6 pb-2">
                            @foreach($rondasMap as $rondaNum => $partidosRonda)
                                <div class="w-80 shrink-0 flex flex-col">
                                    <!-- Cabecera de Ronda -->
                                    <div class="mb-4 text-center">
                                        <span class="inline-block rounded-md bg-slate-100 px-3 py-1 text-xs font-black uppercase tracking-wider text-slate-800">
                                            {{ $partidosRonda[0]->ronda_nombre ?? ('Ronda ' . $rondaNum) }}
                                        </span>
                                    </div>

                                    <!-- Lista de Partidos en esta Ronda -->
                                    <div class="flex flex-col justify-around flex-1 gap-4">
                                        @foreach($partidosRonda as $partido)
                                            @php
                                                $esFinalizado = $partido->estado === 'FINALIZADO';
                                                $localGano = $partido->ganador_id && $partido->local_id && $partido->ganador_id === $partido->local_id;
                                                $visitanteGano = $partido->ganador_id && $partido->visitante_id && $partido->ganador_id === $partido->visitante_id;
                                            @endphp
                                            <div class="relative rounded-xl border border-slate-200 bg-white p-3.5 shadow-2xs transition-shadow hover:shadow-md">
                                                
                                                <!-- Meta información del Encuentro -->
                                                <div class="flex items-center justify-between text-[11px] font-bold text-slate-400 border-b border-slate-100 pb-2 mb-2.5">
                                                    <span class="truncate max-w-[150px]">{{ $partido->cancha ?: 'Cancha Principal' }}</span>
                                                    <div class="flex items-center gap-1.5 shrink-0">
                                                        @if($esFinalizado)
                                                            <span class="inline-flex items-center gap-1 text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded text-[10px] font-black">
                                                                <svg class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg>
                                                                FIN
                                                            </span>
                                                        @else
                                                            <span class="text-slate-600 bg-slate-100 px-1.5 py-0.5 rounded text-[10px] font-extrabold">
                                                                {{ $partido->horario ?: '09:00 AM' }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Equipo Local -->
                                                <div class="flex items-center justify-between py-1.5 px-2 rounded-lg {{ $localGano ? 'bg-slate-100/80 font-black' : '' }}">
                                                    <div class="flex items-center gap-2 truncate pr-2">
                                                        @if($partido->local?->logo_url)
                                                            <img src="{{ $partido->local->logo_url }}" alt="{{ $partido->local->siglas }}" class="size-5 rounded object-contain shrink-0" />
                                                        @else
                                                            <span class="grid size-5 place-items-center rounded bg-slate-900 text-white text-[9px] font-black shrink-0">
                                                                {{ substr($partido->local?->siglas ?? 'L', 0, 2) }}
                                                            </span>
                                                        @endif
                                                        <span class="text-xs truncate {{ $localGano ? 'text-slate-950 font-black' : 'text-slate-700 font-bold' }}">
                                                            {{ $partido->local?->nombre ?? 'Por Definir' }}
                                                        </span>
                                                    </div>
                                                    <span class="tabular-nums text-xs font-black px-2 py-0.5 rounded {{ $localGano ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-800' }}">
                                                        {{ $partido->local_goles !== null ? $partido->local_goles : '-' }}
                                                    </span>
                                                </div>

                                                <!-- Equipo Visitante -->
                                                <div class="flex items-center justify-between py-1.5 px-2 rounded-lg mt-1 {{ $visitanteGano ? 'bg-slate-100/80 font-black' : '' }}">
                                                    <div class="flex items-center gap-2 truncate pr-2">
                                                        @if($partido->visitante?->logo_url)
                                                            <img src="{{ $partido->visitante->logo_url }}" alt="{{ $partido->visitante->siglas }}" class="size-5 rounded object-contain shrink-0" />
                                                        @else
                                                            <span class="grid size-5 place-items-center rounded bg-slate-900 text-white text-[9px] font-black shrink-0">
                                                                {{ substr($partido->visitante?->siglas ?? 'V', 0, 2) }}
                                                            </span>
                                                        @endif
                                                        <span class="text-xs truncate {{ $visitanteGano ? 'text-slate-950 font-black' : 'text-slate-700 font-bold' }}">
                                                            {{ $partido->visitante?->nombre ?? 'Por Definir' }}
                                                        </span>
                                                    </div>
                                                    <span class="tabular-nums text-xs font-black px-2 py-0.5 rounded {{ $visitanteGano ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-800' }}">
                                                        {{ $partido->visitante_goles !== null ? $partido->visitante_goles : '-' }}
                                                    </span>
                                                </div>

                                                @if($partido->observaciones)
                                                    <div class="mt-2 pt-2 border-t border-slate-100 text-[10px] text-slate-500 italic truncate">
                                                        {{ $partido->observaciones }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
                        <p class="text-sm font-semibold text-slate-600">
                            No hay partidos programados todavía en esta serie.
                        </p>
                    </div>
                @endif
            @else
                <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
                    <p class="text-sm font-semibold text-slate-600">No hay series configuradas para esta disciplina.</p>
                </div>
            @endif
        </div>

        <!-- VISTA 2: Tabla de Posiciones Individual por Disciplina -->
        <div x-show="vistaActiva === 'posiciones'" x-cloak class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-2">
                <div>
                    <h2 class="text-xl font-black text-slate-900">
                        Tabla de Posiciones · {{ $disciplina->nombre }}
                    </h2>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">
                        Estadísticas exclusivas de {{ $disciplina->nombre }} en {{ $serieSeleccionada ? $serieSeleccionada->nombre : 'la competencia' }}.
                    </p>
                </div>
            </div>

            <!-- Tabla de Posiciones Deportiva -->
            <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-2xs">
                <table class="w-full text-left text-xs border-collapse">
                    <thead class="border-b border-slate-200 bg-slate-50 text-[11px] font-black uppercase tracking-wider text-slate-600">
                        <tr>
                            <th class="py-3.5 pl-4 pr-2 text-center w-12">#</th>
                            <th class="py-3.5 px-3">Delegación</th>
                            <th class="py-3.5 px-2 text-center" title="Partidos Jugados">PJ</th>
                            <th class="py-3.5 px-2 text-center" title="Partidos Ganados">PG</th>
                            <th class="py-3.5 px-2 text-center" title="Partidos Empatados">PE</th>
                            <th class="py-3.5 px-2 text-center" title="Partidos Perdidos">PP</th>
                            <th class="py-3.5 px-2 text-center" title="Goles/Puntos a Favor">GF</th>
                            <th class="py-3.5 px-2 text-center" title="Goles/Puntos en Contra">GC</th>
                            <th class="py-3.5 px-2 text-center" title="Diferencia de Goles/Puntos">DG</th>
                            <th class="py-3.5 pl-2 pr-4 text-center font-black text-slate-900" title="Puntos Acumulados">PTS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($tablaPosiciones as $fila)
                            @php
                                $esPrimero = $fila['pos'] === 1;
                                $esSegundo = $fila['pos'] === 2;
                                $esTercero = $fila['pos'] === 3;
                            @endphp
                            <tr class="transition-colors hover:bg-slate-50/80 {{ $esPrimero ? 'bg-amber-50/30' : '' }}">
                                <td class="py-3 pl-4 pr-2 text-center font-black">
                                    @if($esPrimero)
                                        <span class="inline-grid size-6 place-items-center rounded-full bg-amber-400 text-slate-950 text-xs font-black shadow-xs">1</span>
                                    @elseif($esSegundo)
                                        <span class="inline-grid size-6 place-items-center rounded-full bg-slate-300 text-slate-800 text-xs font-black shadow-xs">2</span>
                                    @elseif($esTercero)
                                        <span class="inline-grid size-6 place-items-center rounded-full bg-amber-700/80 text-white text-xs font-black shadow-xs">3</span>
                                    @else
                                        <span class="text-slate-500 font-bold tabular-nums">{{ $fila['pos'] }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3">
                                    <div class="flex items-center gap-2.5">
                                        @if($fila['delegacion']->logo_url)
                                            <img src="{{ $fila['delegacion']->logo_url }}" alt="{{ $fila['delegacion']->siglas }}" class="size-6 object-contain rounded shrink-0" />
                                        @else
                                            <span class="grid size-6 place-items-center rounded bg-slate-900 text-white text-[10px] font-black shrink-0">
                                                {{ substr($fila['delegacion']->siglas, 0, 2) }}
                                            </span>
                                        @endif
                                        <div>
                                            <span class="font-extrabold text-slate-900 block leading-tight">{{ $fila['delegacion']->nombre }}</span>
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $fila['delegacion']->provincia }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-2 text-center tabular-nums font-bold text-slate-600">{{ $fila['pj'] }}</td>
                                <td class="py-3 px-2 text-center tabular-nums font-bold text-slate-600">{{ $fila['pg'] }}</td>
                                <td class="py-3 px-2 text-center tabular-nums font-bold text-slate-600">{{ $fila['pe'] }}</td>
                                <td class="py-3 px-2 text-center tabular-nums font-bold text-slate-600">{{ $fila['pp'] }}</td>
                                <td class="py-3 px-2 text-center tabular-nums font-bold text-slate-600">{{ $fila['gf'] }}</td>
                                <td class="py-3 px-2 text-center tabular-nums font-bold text-slate-600">{{ $fila['gc'] }}</td>
                                <td class="py-3 px-2 text-center tabular-nums font-extrabold {{ $fila['dg'] > 0 ? 'text-emerald-600' : ($fila['dg'] < 0 ? 'text-rose-600' : 'text-slate-500') }}">
                                    {{ $fila['dg'] > 0 ? '+'.$fila['dg'] : $fila['dg'] }}
                                </td>
                                <td class="py-3 pl-2 pr-4 text-center tabular-nums font-black text-sm text-slate-950">
                                    {{ $fila['puntos'] }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="py-8 text-center text-slate-400 text-xs">
                                    No hay resultados registrados todavía para esta disciplina.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
