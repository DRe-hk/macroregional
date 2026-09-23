@extends('layouts.app')

@section('title', 'Tabla de Posiciones · ' . $torneo->nombre)

@section('content')
<div class="bg-white min-h-screen pb-16">
    <!-- Cabecera de Página -->
    <div class="border-b border-slate-200 bg-slate-50 py-8 px-4 sm:px-6">
        <div class="mx-auto max-w-[1360px]">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                        Puntaje Oficial
                    </span>
                    <h1 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight mt-0.5">
                        Tabla de Posiciones
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-600 font-medium mt-1">
                        {{ $torneo->nombre }} · {{ $torneo->subtitulo }}
                    </p>
                </div>

                <div class="self-start sm:self-auto">
                    <span className="inline-flex items-center gap-1.5 rounded-full bg-white border border-slate-200 px-3.5 py-1.5 text-xs font-bold text-slate-700 shadow-2xs">
                        Puntaje oficial en vivo
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido Tabla y Filtros -->
    <div class="mx-auto max-w-[1360px] px-4 sm:px-6 mt-8 space-y-6">
        
        <!-- Barra de Filtros por Disciplina -->
        <div class="flex items-center gap-2 border-b border-slate-200 pb-3 overflow-x-auto">
            <a
                href="{{ route('clasificacion') }}"
                class="flex items-center gap-1.5 px-4 py-2 text-xs font-black uppercase tracking-wider rounded-lg transition-colors shrink-0 {{ !$disciplinaSeleccionada ? 'bg-slate-900 text-white shadow-2xs' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}"
            >
                <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <span>General Macroregional</span>
            </a>

            @foreach($disciplinas as $d)
                @php
                    $esActivo = $disciplinaSeleccionada && $disciplinaSeleccionada->id === $d->id;
                @endphp
                <a
                    href="{{ route('clasificacion', ['deporte' => $d->slug]) }}"
                    class="flex items-center gap-1.5 px-4 py-2 text-xs font-black uppercase tracking-wider rounded-lg transition-colors shrink-0 {{ $esActivo ? 'bg-slate-900 text-white shadow-2xs' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}"
                >
                    <span>{{ $d->nombre }}</span>
                </a>
            @endforeach
        </div>

        @if($disciplinaSeleccionada)
            <!-- Banner Informativo del Deporte Seleccionado -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-xl border border-slate-200 bg-slate-50 p-4 shadow-2xs">
                <div>
                    <span class="text-[11px] font-black uppercase tracking-wider text-blue-600 block">
                        {{ $disciplinaSeleccionada->categoria }}
                    </span>
                    <h2 class="text-xl font-black text-slate-900">
                        Tabla de Posiciones · {{ $disciplinaSeleccionada->nombre }}
                    </h2>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">
                        Estadísticas y puntuación exclusiva para esta disciplina.
                    </p>
                </div>
                <div>
                    <a href="{{ route('disciplina.show', $disciplinaSeleccionada->slug) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-slate-900 px-3.5 py-2 text-xs font-bold text-white shadow-2xs transition hover:bg-slate-800">
                        <span>Ver Llaves & Fixture</span>
                        <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
                    </a>
                </div>
            </div>

            <!-- Tabla de Posiciones por Disciplina -->
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-slate-200 bg-slate-50/80 text-[11px] font-black uppercase tracking-wider text-slate-500">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-center sm:pl-6 w-16">Pos</th>
                                <th scope="col" class="px-3 py-3.5">Delegación / UGEL</th>
                                <th scope="col" class="px-3 py-3.5 text-center w-14">PJ</th>
                                <th scope="col" class="px-3 py-3.5 text-center w-14">PG</th>
                                <th scope="col" class="px-3 py-3.5 text-center w-14">PE</th>
                                <th scope="col" class="px-3 py-3.5 text-center w-14">PP</th>
                                <th scope="col" class="px-3 py-3.5 text-center w-14 hidden md:table-cell">GF</th>
                                <th scope="col" class="px-3 py-3.5 text-center w-14 hidden md:table-cell">GC</th>
                                <th scope="col" class="px-3 py-3.5 text-center w-14 hidden md:table-cell">DG</th>
                                <th scope="col" class="py-3.5 pl-3 pr-4 text-center sm:pr-6 w-20 font-black text-slate-900">PTS</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse($tablaPorDisciplina as $fila)
                                @php
                                    $pos = $fila['pos'];
                                @endphp
                                <tr class="hover:bg-slate-50/60 transition-colors {{ $pos <= 3 ? 'bg-slate-50/30' : '' }}">
                                    <!-- Posición con Medallero -->
                                    <td class="py-4 pl-4 pr-3 text-center font-black sm:pl-6">
                                        @if($pos === 1)
                                            <span class="inline-grid size-7 place-items-center rounded-full bg-amber-100 text-amber-900 text-xs font-black shadow-2xs">1</span>
                                        @elseif($pos === 2)
                                            <span class="inline-grid size-7 place-items-center rounded-full bg-slate-200 text-slate-800 text-xs font-black shadow-2xs">2</span>
                                        @elseif($pos === 3)
                                            <span class="inline-grid size-7 place-items-center rounded-full bg-amber-700/20 text-amber-950 text-xs font-black shadow-2xs">3</span>
                                        @else
                                            <span class="text-xs font-bold text-slate-400 tabular-nums">{{ $pos }}</span>
                                        @endif
                                    </td>

                                    <!-- Delegación con Logo -->
                                    <td class="px-3 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($fila['delegacion']->logo_url)
                                                <img src="{{ $fila['delegacion']->logo_url }}" alt="{{ $fila['delegacion']->siglas }}" class="size-8 rounded object-contain shrink-0" />
                                            @else
                                                <div class="grid size-8 place-items-center rounded-lg bg-slate-900 text-white font-black text-xs shrink-0">
                                                    {{ substr($fila['delegacion']->siglas, 0, 2) }}
                                                </div>
                                            @endif
                                            <div>
                                                <span class="font-extrabold text-slate-950 block leading-snug">
                                                    {{ $fila['delegacion']->nombre }}
                                                </span>
                                                <span class="text-[11px] font-bold text-slate-400 uppercase">
                                                    {{ $fila['delegacion']->provincia }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-3 py-4 text-center font-semibold text-slate-600 tabular-nums">{{ $fila['pj'] }}</td>
                                    <td class="px-3 py-4 text-center font-semibold text-emerald-700 tabular-nums">{{ $fila['pg'] }}</td>
                                    <td class="px-3 py-4 text-center font-semibold text-slate-500 tabular-nums">{{ $fila['pe'] }}</td>
                                    <td class="px-3 py-4 text-center font-semibold text-rose-600 tabular-nums">{{ $fila['pp'] }}</td>
                                    <td class="px-3 py-4 text-center font-semibold text-slate-500 tabular-nums hidden md:table-cell">{{ $fila['gf'] }}</td>
                                    <td class="px-3 py-4 text-center font-semibold text-slate-500 tabular-nums hidden md:table-cell">{{ $fila['gc'] }}</td>
                                    <td class="px-3 py-4 text-center font-semibold text-slate-600 tabular-nums hidden md:table-cell">
                                        {{ $fila['dg'] > 0 ? '+' . $fila['dg'] : $fila['dg'] }}
                                    </td>

                                    <!-- Puntos Totales -->
                                    <td class="py-4 pl-3 pr-4 text-center sm:pr-6">
                                        <span class="inline-block rounded-lg bg-slate-900 px-2.5 py-1 text-xs font-black text-white tabular-nums shadow-2xs">
                                            {{ $fila['puntos'] }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="py-12 text-center text-slate-500 font-medium">
                                        No hay partidos finalizados todavía en {{ $disciplinaSeleccionada->nombre }}.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <!-- Tabla de Posiciones General Acumulada -->
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-slate-200 bg-slate-50/80 text-[11px] font-black uppercase tracking-wider text-slate-500">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-center sm:pl-6 w-16">Pos</th>
                                <th scope="col" class="px-3 py-3.5">Delegación / Institución</th>
                                <th scope="col" class="px-3 py-3.5 text-center w-14">PJ</th>
                                <th scope="col" class="px-3 py-3.5 text-center w-14">PG</th>
                                <th scope="col" class="px-3 py-3.5 text-center w-14">PE</th>
                                <th scope="col" class="px-3 py-3.5 text-center w-14">PP</th>
                                <th scope="col" class="px-3 py-3.5 text-center w-14 hidden md:table-cell">GF</th>
                                <th scope="col" class="px-3 py-3.5 text-center w-14 hidden md:table-cell">GC</th>
                                <th scope="col" class="px-3 py-3.5 text-center w-14 hidden md:table-cell">DG</th>
                                <th scope="col" class="py-3.5 pl-3 pr-4 text-center sm:pr-6 w-20 font-black text-slate-900">PTS</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse($delegaciones as $index => $del)
                                @php
                                    $pos = $index + 1;
                                @endphp
                                <tr class="hover:bg-slate-50/60 transition-colors {{ $pos <= 3 ? 'bg-slate-50/30' : '' }}">
                                    <!-- Posición con Medallero -->
                                    <td class="py-4 pl-4 pr-3 text-center font-black sm:pl-6">
                                        @if($pos === 1)
                                            <span class="inline-grid size-7 place-items-center rounded-full bg-amber-100 text-amber-900 text-xs font-black shadow-2xs">1</span>
                                        @elseif($pos === 2)
                                            <span class="inline-grid size-7 place-items-center rounded-full bg-slate-200 text-slate-800 text-xs font-black shadow-2xs">2</span>
                                        @elseif($pos === 3)
                                            <span class="inline-grid size-7 place-items-center rounded-full bg-amber-700/20 text-amber-950 text-xs font-black shadow-2xs">3</span>
                                        @else
                                            <span class="text-xs font-bold text-slate-400 tabular-nums">{{ $pos }}</span>
                                        @endif
                                    </td>

                                    <!-- Delegación con Logo -->
                                    <td class="px-3 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($del->logo_url)
                                                <img src="{{ $del->logo_url }}" alt="{{ $del->siglas }}" class="size-8 rounded object-contain shrink-0" />
                                            @else
                                                <div class="grid size-8 place-items-center rounded-lg bg-slate-900 text-white font-black text-xs shrink-0">
                                                    {{ substr($del->siglas, 0, 2) }}
                                                </div>
                                            @endif
                                            <div>
                                                <span class="font-extrabold text-slate-950 block leading-snug">
                                                    {{ $del->nombre }}
                                                </span>
                                                <span class="text-[11px] font-bold text-slate-400 uppercase">
                                                    {{ $del->provincia }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-3 py-4 text-center font-semibold text-slate-600 tabular-nums">{{ $del->pj }}</td>
                                    <td class="px-3 py-4 text-center font-semibold text-emerald-700 tabular-nums">{{ $del->pg }}</td>
                                    <td class="px-3 py-4 text-center font-semibold text-slate-500 tabular-nums">{{ $del->pe }}</td>
                                    <td class="px-3 py-4 text-center font-semibold text-rose-600 tabular-nums">{{ $del->pp }}</td>
                                    <td class="px-3 py-4 text-center font-semibold text-slate-500 tabular-nums hidden md:table-cell">{{ $del->gf }}</td>
                                    <td class="px-3 py-4 text-center font-semibold text-slate-500 tabular-nums hidden md:table-cell">{{ $del->gc }}</td>
                                    <td class="px-3 py-4 text-center font-semibold text-slate-600 tabular-nums hidden md:table-cell">
                                        {{ $del->dg > 0 ? '+' . $del->dg : $del->dg }}
                                    </td>

                                    <!-- Puntos Totales -->
                                    <td class="py-4 pl-3 pr-4 text-center sm:pr-6">
                                        <span class="inline-block rounded-lg bg-slate-900 px-2.5 py-1 text-xs font-black text-white tabular-nums shadow-2xs">
                                            {{ $del->puntos }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="py-12 text-center text-slate-500 font-medium">
                                        No hay delegaciones registradas en la tabla de clasificación.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Leyenda de Criterios -->
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-xs font-medium text-slate-500 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-4">
                <span><strong>PJ:</strong> Partidos Jugados</span>
                <span><strong>PG:</strong> Ganados (3 pts)</span>
                <span><strong>PE:</strong> Empatados (1 pt)</span>
                <span><strong>PP:</strong> Perdidos (0 pts)</span>
            </div>
            <div>
                <span>Criterio de desempate: Mayor puntaje, diferencia de goles (DG) y goles a favor (GF).</span>
            </div>
        </div>

    </div>
</div>
@endsection
