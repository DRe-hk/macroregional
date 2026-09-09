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

                <div class="flex items-center gap-2">
                    @if($disciplina->sede_principal)
                        <div class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-xs font-bold text-slate-700 shadow-2xs">
                            <svg class="size-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            <span>{{ $disciplina->sede_principal }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Contenedor Principal: Series y Bracket -->
    <div class="mx-auto max-w-[1360px] px-4 sm:px-6 mt-8 space-y-6">
        
        <!-- Selector de Series -->
        @if($disciplina->series->count() > 0)
            <div class="flex items-center gap-2 border-b border-slate-200 pb-2 overflow-x-auto">
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

        <!-- Visualizador de Bracket / Fixture -->
        @if($serieSeleccionada)
            <div class="space-y-4">
                <!-- Cabecera de la Serie -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-200 pb-3">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">
                            {{ $disciplina->nombre }}
                        </span>
                        <h2 class="text-xl font-black text-slate-900">{{ $serieSeleccionada->nombre }}</h2>
                    </div>

                    <div class="flex items-center gap-2">
                        @if($serieSeleccionada->sede_nombre)
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 bg-white border border-slate-200 px-3 py-1.5 rounded-lg shadow-2xs">
                                <svg class="size-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                <span>{{ $serieSeleccionada->sede_nombre }}</span>
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Árbol de Eliminatorias / Bracket Scrollable -->
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
                                                        <span class="grid size-5 place-items-center rounded bg-slate-900 text-white text-[9px] font-black shrink-0">
                                                            {{ substr($partido->local?->siglas ?? 'L', 0, 2) }}
                                                        </span>
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
                                                        <span class="grid size-5 place-items-center rounded bg-slate-900 text-white text-[9px] font-black shrink-0">
                                                            {{ substr($partido->visitante?->siglas ?? 'V', 0, 2) }}
                                                        </span>
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
                        <p class="text-xs text-slate-400 mt-1">
                            La programación oficial de encuentros será publicada próximamente.
                        </p>
                    </div>
                @endif
            </div>
        @else
            <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
                <svg class="mx-auto size-10 text-slate-400 mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                <h3 class="text-base font-black text-slate-800">
                    No hay series configuradas para esta disciplina
                </h3>
                <p class="mt-1 text-xs text-slate-500 max-w-sm mx-auto">
                    Próximamente se publicará el fixture y las llaves de competencia.
                </p>
            </div>
        @endif

    </div>
</div>
@endsection
