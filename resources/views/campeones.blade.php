@extends('layouts.app')

@section('title', 'Cuadro Oficial de Campeones · ' . $torneo->nombre)

@section('content')
<div class="bg-white min-h-screen pb-16">
    <!-- Cabecera -->
    <div class="border-b border-slate-200 bg-slate-50 py-8 px-4 sm:px-6">
        <div class="mx-auto max-w-[1360px]">
            <span class="text-[11px] font-extrabold uppercase tracking-wider text-amber-700 block">
                Palmarés Deportivo Oficial JEDPA 2026
            </span>
            <h1 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight mt-0.5">
                Cuadro Oficial de Campeones
            </h1>
            <p class="text-xs sm:text-sm text-slate-600 font-medium mt-1">
                Ganadores oficiales y títulos consagrados por cada disciplina deportiva y delegación participante.
            </p>
        </div>
    </div>

    <div class="mx-auto max-w-[1360px] px-4 sm:px-6 mt-8 space-y-10">

        <!-- 1. Palmarés Acumulado por Delegación (Títulos de Campeón) -->
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-2xs">
            <div class="flex items-center gap-3 border-b border-slate-100 pb-3 mb-4">
                <div class="grid size-10 place-items-center rounded-xl bg-amber-400 text-slate-950 font-black">
                    🏆
                </div>
                <div>
                    <h2 class="text-lg font-black text-slate-900">Palmarés General por Delegación</h2>
                    <p class="text-xs text-slate-500">Total de campeonatos obtenidos en la competencia macroregional.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-slate-200 bg-slate-50 text-[11px] font-black uppercase text-slate-600">
                        <tr>
                            <th class="py-3 px-4 text-center w-12">#</th>
                            <th class="py-3 px-4">Delegación</th>
                            <th class="py-3 px-4 text-center">Títulos Oficiales</th>
                            <th class="py-3 px-4 text-right">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @php $rank = 1; @endphp
                        @foreach($medallasPorDelegacion as $nombreDel => $item)
                            <tr class="hover:bg-slate-50/80 transition-colors {{ $item['titulos'] > 0 ? 'bg-amber-50/30' : '' }}">
                                <td class="py-3 px-4 text-center font-black">
                                    @if($item['titulos'] > 0 && $rank === 1)
                                        <span class="inline-grid size-6 place-items-center rounded-full bg-amber-400 text-slate-950 text-xs font-black">1</span>
                                    @else
                                        <span class="text-slate-400 font-bold tabular-nums">{{ $rank }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 font-black text-slate-900">
                                    <div class="flex items-center gap-2">
                                        @if($item['delegacion']->logo_url)
                                            <img src="{{ $item['delegacion']->logo_url }}" alt="{{ $item['delegacion']->siglas }}" class="size-6 object-contain rounded" />
                                        @else
                                            <span class="grid size-6 place-items-center rounded bg-slate-900 text-white text-[10px] font-black">
                                                {{ substr($item['delegacion']->siglas, 0, 2) }}
                                            </span>
                                        @endif
                                        <span>{{ $item['delegacion']->nombre }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-black {{ $item['titulos'] > 0 ? 'bg-amber-100 text-amber-900' : 'bg-slate-100 text-slate-500' }}">
                                        🥇 {{ $item['titulos'] }} {{ $item['titulos'] === 1 ? 'Campeonato' : 'Campeonatos' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <span class="text-[11px] font-bold {{ $item['titulos'] > 0 ? 'text-emerald-700' : 'text-slate-400' }}">
                                        {{ $item['titulos'] > 0 ? 'Con títulos oficiales' : 'En competencia' }}
                                    </span>
                                </td>
                            </tr>
                            @php $rank++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 2. Campeones de Deportes Individuales (Natación y Atletismo) -->
        <div class="space-y-4">
            <div class="border-b border-slate-200 pb-2">
                <span class="text-[10px] font-black uppercase tracking-wider text-blue-600 block">Atletismo & Natación</span>
                <h2 class="text-xl font-black text-slate-900">Ganadores en Deportes Individuales</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @foreach($individuales as $ind)
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-2xs hover:border-slate-300 transition-colors">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-3">
                            <div>
                                <span class="text-[10px] font-black uppercase text-amber-600 block">Deporte Individual</span>
                                <h3 class="font-black text-base text-slate-900">{{ $ind->nombre }}</h3>
                            </div>
                            <a href="{{ route('disciplina.show', $ind->slug) }}" class="text-xs font-bold text-blue-600 hover:underline">
                                Ver detalles
                            </a>
                        </div>

                        @php
                            $podio = $ind->podio ?? [];
                        @endphp

                        @if(!empty($podio) && (isset($podio['oro']) || !empty($ind->campeon_actual)))
                            <div class="rounded-xl bg-amber-50 p-4 border border-amber-200 flex items-start gap-3">
                                <span class="text-2xl">🥇</span>
                                <div>
                                    <span class="text-[10px] font-black uppercase tracking-wider text-amber-800 block">
                                        Campeón / Medalla de Oro
                                    </span>
                                    <h4 class="font-black text-sm text-amber-950 mt-0.5">
                                        {{ is_array($podio['oro'] ?? null) ? ($podio['oro']['delegacion'] ?? '') : ($ind->campeon_actual ?? '') }}
                                    </h4>
                                    @if(is_array($podio['oro'] ?? null) && !empty($podio['oro']['atleta']))
                                        <p class="text-xs text-amber-900 mt-1">
                                            Atleta: <span class="font-extrabold">{{ $podio['oro']['atleta'] }}</span>
                                            @if(!empty($podio['oro']['marca']))
                                                <span class="text-[11px] block mt-0.5 tabular-nums font-bold">Marca: {{ $podio['oro']['marca'] }}</span>
                                            @endif
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="rounded-lg bg-slate-50 p-4 text-center text-xs text-slate-500 font-medium">
                                Pruebas programadas para el {{ $ind->fechas_cronograma }}.
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 3. Campeones de Deportes Colectivos y Subcategorías -->
        <div class="space-y-4">
            <div class="border-b border-slate-200 pb-2">
                <span class="text-[10px] font-black uppercase tracking-wider text-emerald-600 block">Deportes de Conjunto</span>
                <h2 class="text-xl font-black text-slate-900">Campeones de Deportes Colectivos</h2>
            </div>

            @if($colectivosConCampeon->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($colectivosConCampeon as $c)
                        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-2xs hover:border-slate-300 transition-colors">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-3">
                                <div>
                                    <span class="text-[10px] font-extrabold uppercase text-blue-600 block">
                                        {{ $c->parent ? $c->parent->nombre : $c->categoria }}
                                    </span>
                                    <h3 class="font-black text-base text-slate-900">{{ $c->nombre }}</h3>
                                </div>
                                <a
                                    href="{{ route('disciplina.show', $c->slug) }}"
                                    class="text-xs font-bold text-blue-600 hover:underline flex items-center gap-1"
                                >
                                    <span>Ver fixture</span>
                                    <svg class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                </a>
                            </div>

                            <div class="flex items-center justify-between rounded-lg bg-amber-50 p-3 border border-amber-200">
                                <div class="flex items-center gap-2.5">
                                    <svg class="size-5 text-amber-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                                    <div>
                                        <span class="text-[10px] font-black uppercase tracking-wider text-amber-800 block">
                                            Campeón Consagrado
                                        </span>
                                        <span class="font-black text-sm text-amber-950">
                                            {{ $c->campeon_actual }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-10 text-center">
                    <svg class="mx-auto size-12 text-slate-300 mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                    <h3 class="text-base font-black text-slate-800">
                        La fase eliminatoria de deportes colectivos está en desarrollo
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-md mx-auto">
                        Los partidos de Fútbol, Básquet, Vóley, Futsal y Handball se disputan del 30 de setiembre al 02 de octubre. Al culminar las finales, los campeones aparecerán aquí.
                    </p>
                    <a
                        href="{{ route('home') }}"
                        class="mt-4 inline-block rounded-lg bg-slate-900 px-4 py-2 text-xs font-bold text-white hover:bg-slate-800"
                    >
                        Ver Partidos y Fixture
                    </a>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
