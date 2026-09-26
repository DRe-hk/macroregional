@extends('layouts.app')

@section('title', ($disciplina->nombre ?? 'Disciplina') . ' · ' . $torneo->nombre)

@section('content')
<div class="bg-white min-h-screen pb-16" x-data="{ modalEvidencia: { open: false, url: '', partido: '', fecha: '', score: '' } }">
    <!-- Cabecera de la Disciplina -->
    <div class="border-b border-slate-200 bg-slate-50 py-8 px-4 sm:px-6">
        <div class="mx-auto max-w-[1360px]">
            <!-- Migas de Pan -->
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-3">
                <a href="{{ route('home') }}" class="hover:text-slate-900 transition-colors">Deportes</a>
                <svg class="size-3 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
                @if($disciplinaPadre && $disciplinaPadre->id !== $disciplina->id)
                    <a href="{{ route('disciplina.show', $disciplinaPadre->slug) }}" class="hover:text-slate-900 transition-colors">{{ $disciplinaPadre->nombre }}</a>
                    <svg class="size-3 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
                @endif
                <span class="text-slate-900 font-extrabold">{{ $disciplina->nombre }}</span>
            </nav>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-blue-600 block">
                            {{ $disciplina->categoria ?: ($disciplina->tipo === 'INDIVIDUAL' ? 'Deporte Individual' : 'Colectivo') }}
                        </span>
                        @if($disciplina->genero)
                            <span class="text-slate-300">·</span>
                            <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                                Rama {{ $disciplina->genero }}
                            </span>
                        @endif
                    </div>
                    <h1 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight mt-0.5">
                        {{ $disciplina->nombre }}
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-600 font-medium mt-1">
                        {{ $disciplina->descripcion ?: 'Programación oficial, escenarios y resultados en tiempo real.' }}
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    @if($disciplina->sede_principal)
                        <div class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-xs font-bold text-slate-700 shadow-2xs">
                            <svg class="size-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            <span>{{ $disciplina->sede_principal }}</span>
                        </div>
                    @endif
                    @if($disciplina->fechas_cronograma)
                        <div class="inline-flex items-center gap-1.5 rounded-lg bg-blue-50 border border-blue-200 px-3.5 py-2 text-xs font-bold text-blue-800 shadow-2xs">
                            <svg class="size-3.5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                            <span>{{ $disciplina->fechas_cronograma }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Selector de Subcategorías si el deporte padre las tiene -->
            @if($disciplinaPadre && $disciplinaPadre->subcategorias->isNotEmpty())
                <div class="mt-6 pt-4 border-t border-slate-200">
                    <span class="text-[11px] font-black uppercase tracking-wider text-slate-400 block mb-2">
                        Subcategorías Oficiales en Competencia:
                    </span>
                    <div class="flex items-center gap-2 overflow-x-auto pb-1">
                        @foreach($disciplinaPadre->subcategorias as $sub)
                            <a
                                href="{{ route('disciplina.show', ['slug' => $disciplinaPadre->slug, 'sub' => $sub->slug]) }}"
                                class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 {{ $disciplina->id === $sub->id ? 'bg-slate-900 text-white font-black shadow-xs' : 'bg-white border border-slate-300 text-slate-700 hover:bg-slate-100' }}"
                            >
                                {{ $sub->nombre }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Contenedor Principal -->
    <div class="mx-auto max-w-[1360px] px-4 sm:px-6 mt-8 space-y-6">

        @if($disciplina->esIndividual())
            <!-- VISTA ESPECIAL: DEPORTES INDIVIDUALES (Natación y Atletismo) -->
            <div class="space-y-6">
                <!-- Ficha Técnica Oficial del Cronograma -->
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-2xs">
                    <div class="flex items-center gap-3 border-b border-slate-100 pb-3 mb-4">
                        <div class="grid size-10 place-items-center rounded-xl bg-amber-500 text-slate-950 font-black">
                            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                        </div>
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-wider text-amber-700 block">Modalidad Individual Oficial</span>
                            <h2 class="text-xl font-black text-slate-900">Ficha Técnica y Escenario Deportivo</h2>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="rounded-xl bg-slate-50 p-4 border border-slate-200/80">
                            <span class="text-[10px] font-black uppercase text-slate-400 block">Escenario Oficial</span>
                            <span class="text-sm font-extrabold text-slate-900 mt-0.5 block">{{ $disciplina->sede_principal }}</span>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-4 border border-slate-200/80">
                            <span class="text-[10px] font-black uppercase text-slate-400 block">Fechas de Competencia</span>
                            <span class="text-sm font-extrabold text-blue-700 mt-0.5 block">{{ $disciplina->fechas_cronograma ?: '28 y 29 de Setiembre' }}</span>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-4 border border-slate-200/80">
                            <span class="text-[10px] font-black uppercase text-slate-400 block">Horario de Partida</span>
                            <span class="text-sm font-extrabold text-slate-900 mt-0.5 block">{{ $disciplina->horario_cronograma ?: '08:00 AM' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Cuadro de Ganadores y Podio Oficial -->
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-2xs">
                    <h3 class="text-lg font-black text-slate-900 mb-1 flex items-center gap-2">
                        <svg class="size-5 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                        <span>Podio y Ganadores Oficiales de {{ $disciplina->nombre }}</span>
                    </h3>
                    <p class="text-xs text-slate-500 mb-6">Resultados proclamados por la Comisión Técnica y Árbitros Oficiales JEDPA.</p>

                    @php
                        $podio = $disciplina->podio ?? [];
                    @endphp

                    @if(!empty($podio) && (isset($podio['oro']) || isset($disciplina->campeon_actual)))
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <!-- 1° Lugar / ORO -->
                            <div class="rounded-2xl border-2 border-amber-300 bg-amber-50/50 p-5 shadow-xs relative overflow-hidden">
                                <div class="absolute -top-3 -right-3 size-16 rounded-full bg-amber-200/50 flex items-end justify-start p-2">
                                    <span class="text-2xl font-black text-amber-700/40">1</span>
                                </div>
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-400 px-3 py-1 text-xs font-black uppercase text-slate-950 shadow-2xs mb-3">
                                    🥇 Medalla de Oro / Ganador
                                </span>
                                <h4 class="text-lg font-black text-slate-950">
                                    {{ is_array($podio['oro'] ?? null) ? ($podio['oro']['delegacion'] ?? '') : ($disciplina->campeon_actual ?? 'DRE Puno') }}
                                </h4>
                                @if(is_array($podio['oro'] ?? null) && !empty($podio['oro']['atleta']))
                                    <p class="text-xs font-bold text-amber-900 mt-1">
                                        Atleta: <span class="font-extrabold text-slate-900">{{ $podio['oro']['atleta'] }}</span>
                                    </p>
                                @endif
                                @if(is_array($podio['oro'] ?? null) && !empty($podio['oro']['marca']))
                                    <span class="mt-3 inline-block rounded-md bg-white border border-amber-300 px-2 py-0.5 text-[11px] font-black text-amber-900 tabular-nums">
                                        Tiempo / Marca: {{ $podio['oro']['marca'] }}
                                    </span>
                                @endif
                            </div>

                            <!-- 2° Lugar / PLATA -->
                            @if(!empty($podio['plata']))
                                <div class="rounded-2xl border border-slate-300 bg-slate-50 p-5 shadow-2xs relative overflow-hidden">
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-300 px-3 py-1 text-xs font-black uppercase text-slate-800 shadow-2xs mb-3">
                                        🥈 Medalla de Plata
                                    </span>
                                    <h4 class="text-lg font-black text-slate-900">
                                        {{ is_array($podio['plata']) ? ($podio['plata']['delegacion'] ?? '') : $podio['plata'] }}
                                    </h4>
                                    @if(is_array($podio['plata']) && !empty($podio['plata']['atleta']))
                                        <p class="text-xs font-bold text-slate-600 mt-1">
                                            Atleta: <span class="font-extrabold text-slate-900">{{ $podio['plata']['atleta'] }}</span>
                                        </p>
                                    @endif
                                    @if(is_array($podio['plata']) && !empty($podio['plata']['marca']))
                                        <span class="mt-3 inline-block rounded-md bg-white border border-slate-300 px-2 py-0.5 text-[11px] font-black text-slate-700 tabular-nums">
                                            Tiempo / Marca: {{ $podio['plata']['marca'] }}
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <!-- 3° Lugar / BRONCE -->
                            @if(!empty($podio['bronce']))
                                <div class="rounded-2xl border border-amber-800/30 bg-amber-900/5 p-5 shadow-2xs relative overflow-hidden">
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-800/20 text-amber-900 px-3 py-1 text-xs font-black uppercase shadow-2xs mb-3">
                                        🥉 Medalla de Bronce
                                    </span>
                                    <h4 class="text-lg font-black text-slate-900">
                                        {{ is_array($podio['bronce']) ? ($podio['bronce']['delegacion'] ?? '') : $podio['bronce'] }}
                                    </h4>
                                    @if(is_array($podio['bronce']) && !empty($podio['bronce']['atleta']))
                                        <p class="text-xs font-bold text-amber-950 mt-1">
                                            Atleta: <span class="font-extrabold text-slate-900">{{ $podio['bronce']['atleta'] }}</span>
                                        </p>
                                    @endif
                                    @if(is_array($podio['bronce']) && !empty($podio['bronce']['marca']))
                                        <span class="mt-3 inline-block rounded-md bg-white border border-amber-800/20 px-2 py-0.5 text-[11px] font-black text-amber-900 tabular-nums">
                                            Tiempo / Marca: {{ $podio['bronce']['marca'] }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
                            <p class="text-sm font-bold text-slate-600">
                                Las pruebas oficiales de {{ $disciplina->nombre }} están programadas para iniciarse el {{ $disciplina->fechas_cronograma }}.
                            </p>
                            <p class="text-xs text-slate-400 mt-1">Los ganadores proclamados se publicarán aquí en tiempo real.</p>
                        </div>
                    @endif
                </div>
            </div>

        @else
            <!-- VISTA DEPORTES COLECTIVOS: FIXTURE POR FECHAS & TABLA DE POSICIONES -->
            <div x-data="{ vistaActiva: 'bracket' }">
                <!-- Barra de Controles: Selector de Vista (Bracket vs Tabla) -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-3">
                    <div>
                        <h2 class="text-lg font-black text-slate-900">Encuentros y Clasificación Oficial</h2>
                        <p class="text-xs text-slate-500 font-medium">Fixture organizado por jornadas y tabla de puntuación oficial (RVM N° 092-2026-MINEDU).</p>
                    </div>

                    <!-- Selector de Vista: Bracket vs Tabla Individual -->
                    <div class="inline-flex rounded-xl bg-slate-100 p-1 border border-slate-200 shrink-0">
                        <button
                            type="button"
                            @click="vistaActiva = 'bracket'"
                            :class="vistaActiva === 'bracket' ? 'bg-white text-slate-950 font-black shadow-2xs' : 'text-slate-600 font-bold hover:text-slate-900'"
                            class="flex items-center gap-1.5 px-3.5 py-1.5 text-xs rounded-lg transition-all cursor-pointer"
                        >
                            <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                            <span>Fixture & Partidos</span>
                        </button>
                        <button
                            type="button"
                            @click="vistaActiva = 'posiciones'"
                            :class="vistaActiva === 'posiciones' ? 'bg-white text-slate-950 font-black shadow-2xs' : 'text-slate-600 font-bold hover:text-slate-900'"
                            class="flex items-center gap-1.5 px-3.5 py-1.5 text-xs rounded-lg transition-all cursor-pointer"
                        >
                            <svg class="size-3.5 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                            <span>Tabla de Posiciones</span>
                        </button>
                    </div>
                </div>

                <!-- Filtro por Fechas de Partido -->
                @if(!empty($fechasDisponibles))
                    <div class="flex items-center gap-2 overflow-x-auto py-2">
                        <span class="text-[11px] font-black uppercase text-slate-400 shrink-0">Jornadas:</span>
                        <a
                            href="{{ route('disciplina.show', ['slug' => $disciplinaPadre->slug, 'sub' => $disciplina->slug]) }}"
                            class="px-3 py-1 rounded-md text-xs font-bold transition-all shrink-0 {{ empty($fechaActiva) ? 'bg-slate-900 text-white font-black' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}"
                        >
                            Todas las fechas
                        </a>
                        @foreach($fechasDisponibles as $f)
                            <a
                                href="{{ route('disciplina.show', ['slug' => $disciplinaPadre->slug, 'sub' => $disciplina->slug, 'fecha' => $f]) }}"
                                class="px-3 py-1 rounded-md text-xs font-bold transition-all shrink-0 {{ $fechaActiva === $f ? 'bg-blue-600 text-white font-black' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}"
                            >
                                📅 {{ \Carbon\Carbon::parse($f)->translatedFormat('l d \d\e F') }}
                            </a>
                        @endforeach
                    </div>
                @endif

                <!-- VISTA 1: Fixture de Partidos -->
                <div x-show="vistaActiva === 'bracket'" class="space-y-4">
                    @if(count($rondasMap) > 0)
                        <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white p-5 bracket-scroll">
                            <div class="min-w-[700px] flex items-stretch gap-6 pb-2">
                                @foreach($rondasMap as $rondaNum => $partidosRonda)
                                    <div class="w-84 shrink-0 flex flex-col">
                                        <!-- Cabecera de Ronda -->
                                        <div class="mb-4 text-center">
                                            <span class="inline-block rounded-md bg-slate-100 px-3 py-1 text-xs font-black uppercase tracking-wider text-slate-800">
                                                {{ $partidosRonda[0]->ronda_nombre ?? ('Fecha ' . $rondaNum) }}
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
                                                    
                                                    <!-- Meta información del Encuentro (Fecha, Horario, Cancha) -->
                                                    <div class="flex items-center justify-between text-[11px] font-bold text-slate-500 border-b border-slate-100 pb-2 mb-2.5">
                                                        <div class="flex items-center gap-1 truncate max-w-[170px]">
                                                            @if($partido->fecha)
                                                                <span class="text-blue-700 font-extrabold">📅 {{ $partido->fecha->translatedFormat('d M') }}</span>
                                                                <span>·</span>
                                                            @endif
                                                            <span class="truncate">{{ $partido->cancha ?: 'Cancha Oficial' }}</span>
                                                        </div>
                                                        <div class="flex items-center gap-1.5 shrink-0">
                                                            @if($esFinalizado)
                                                                <span class="inline-flex items-center gap-1 text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded text-[10px] font-black">
                                                                    <svg class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg>
                                                                    FINAL
                                                                </span>
                                                            @else
                                                                <span class="text-slate-700 bg-slate-100 px-1.5 py-0.5 rounded text-[10px] font-extrabold">
                                                                    ⏰ {{ $partido->horario ?: '09:00 AM' }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Equipo Local -->
                                                    <div class="flex items-center justify-between py-1.5 px-2 rounded-lg {{ $localGano ? 'bg-slate-100/90 font-black' : '' }}">
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
                                                    <div class="flex items-center justify-between py-1.5 px-2 rounded-lg mt-1 {{ $visitanteGano ? 'bg-slate-100/90 font-black' : '' }}">
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

                                                    <!-- Botón de Evidencia Fotográfica / Acta Oficial -->
                                                    @if($partido->foto_evidencia)
                                                        <div class="mt-2.5 pt-2 border-t border-slate-100 flex items-center justify-between">
                                                            <button
                                                                type="button"
                                                                @click="modalEvidencia = {
                                                                    open: true,
                                                                    url: '{{ $partido->foto_evidencia }}',
                                                                    partido: '{{ addslashes($partido->local?->nombre) }} vs {{ addslashes($partido->visitante?->nombre) }}',
                                                                    fecha: '{{ $partido->fecha ? $partido->fecha->translatedFormat('d M Y') : '' }}',
                                                                    score: '{{ $partido->local_goles }} - {{ $partido->visitante_goles }}'
                                                                }"
                                                                class="inline-flex items-center gap-1.5 text-[11px] font-black text-blue-700 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-2.5 py-1 rounded-md transition cursor-pointer"
                                                            >
                                                                <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                                                                <span>Ver Acta Oficial / Evidencia</span>
                                                            </button>
                                                        </div>
                                                    @endif

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
                                No hay partidos programados todavía para los filtros seleccionados.
                            </p>
                        </div>
                    @endif
                </div>

                <!-- VISTA 2: Tabla de Posiciones Individual por Disciplina -->
                <div x-show="vistaActiva === 'posiciones'" x-cloak class="space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-2">
                        <div>
                            <h2 class="text-xl font-black text-slate-900">
                                Tabla de Posiciones Oficial · {{ $disciplina->nombre }}
                            </h2>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">
                                Puntuación oficial de {{ $disciplina->sistema_puntuacion ?? 'FUTBOL' }} (RVM N° 092-2026-MINEDU).
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
                                    <th class="py-3.5 px-2 text-center" title="Goles / Canastas / Sets a Favor">GF</th>
                                    <th class="py-3.5 px-2 text-center" title="Goles / Canastas / Sets en Contra">GC</th>
                                    <th class="py-3.5 px-2 text-center" title="Diferencia de Goles">DG</th>
                                    <th class="py-3.5 pl-2 pr-4 text-center font-black text-slate-900" title="Puntos Oficiales Acumulados">PTS</th>
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
        @endif

    </div>

    <!-- Modal para Visualización de Acta Oficial / Evidencia -->
    <div
        x-show="modalEvidencia.open"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-xs"
        @keydown.escape.window="modalEvidencia.open = false"
    >
        <div
            @click.outside="modalEvidencia.open = false"
            class="relative w-full max-w-3xl overflow-hidden rounded-2xl bg-white shadow-2xl border border-slate-200"
        >
            <div class="flex items-center justify-between p-4 border-b border-slate-200 bg-slate-50">
                <div>
                    <span class="text-[10px] font-black uppercase text-blue-600 block">Documento de Mesa / Acta Oficial</span>
                    <h3 class="text-base font-black text-slate-900" x-text="modalEvidencia.partido"></h3>
                    <p class="text-xs text-slate-500" x-text="'Marcador Final: ' + modalEvidencia.score + (modalEvidencia.fecha ? ' · Fecha: ' + modalEvidencia.fecha : '')"></p>
                </div>
                <button
                    type="button"
                    @click="modalEvidencia.open = false"
                    class="size-8 rounded-lg bg-slate-200 hover:bg-slate-300 text-slate-700 flex items-center justify-center cursor-pointer transition"
                >
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
            <div class="p-4 max-h-[75vh] overflow-y-auto bg-slate-950 flex items-center justify-center">
                <img :src="modalEvidencia.url" alt="Acta Oficial" class="max-h-[70vh] max-w-full rounded object-contain shadow-lg" />
            </div>
            <div class="p-3 bg-slate-50 border-t border-slate-200 flex justify-between items-center text-xs">
                <span class="text-slate-500 font-bold">Acta debidamente firmada por los delegados acreditados y mesa de control.</span>
                <a :href="modalEvidencia.url" target="_blank" class="font-bold text-blue-600 hover:underline">Abrir original en pestaña</a>
            </div>
        </div>
    </div>
</div>
@endsection
