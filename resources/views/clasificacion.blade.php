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

    <!-- Contenido Tabla -->
    <div class="mx-auto max-w-[1360px] px-4 sm:px-6 mt-8 space-y-6">
        
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
                                        <span class="inline-grid size-7 place-items-center rounded-full bg-amber-100 text-amber-900 text-xs font-black shadow-2xs">
                                            1
                                        </span>
                                    @elseif($pos === 2)
                                        <span class="inline-grid size-7 place-items-center rounded-full bg-slate-200 text-slate-800 text-xs font-black shadow-2xs">
                                            2
                                        </span>
                                    @elseif($pos === 3)
                                        <span class="inline-grid size-7 place-items-center rounded-full bg-amber-700/20 text-amber-950 text-xs font-black shadow-2xs">
                                            3
                                        </span>
                                    @else
                                        <span class="text-xs font-bold text-slate-400 tabular-nums">
                                            {{ $pos }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Delegación -->
                                <td class="px-3 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="grid size-8 place-items-center rounded-lg bg-slate-900 text-white font-black text-xs shrink-0">
                                            {{ substr($del->siglas, 0, 2) }}
                                        </div>
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

                                <!-- Métricas Numéricas Tabulares -->
                                <td class="px-3 py-4 text-center font-semibold text-slate-600 tabular-nums">
                                    {{ $del->pj }}
                                </td>
                                <td class="px-3 py-4 text-center font-semibold text-emerald-700 tabular-nums">
                                    {{ $del->pg }}
                                </td>
                                <td class="px-3 py-4 text-center font-semibold text-slate-500 tabular-nums">
                                    {{ $del->pe }}
                                </td>
                                <td class="px-3 py-4 text-center font-semibold text-rose-600 tabular-nums">
                                    {{ $del->pp }}
                                </td>
                                <td class="px-3 py-4 text-center font-semibold text-slate-500 tabular-nums hidden md:table-cell">
                                    {{ $del->gf }}
                                </td>
                                <td class="px-3 py-4 text-center font-semibold text-slate-500 tabular-nums hidden md:table-cell">
                                    {{ $del->gc }}
                                </td>
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
