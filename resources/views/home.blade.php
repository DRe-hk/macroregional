@extends('layouts.app')

@section('title', $torneo->nombre . ' · Plataforma Oficial')

@section('content')
<div class="bg-white min-h-screen">
    <!-- Hero Minimalista Deportivo -->
    <div class="bg-slate-50 border-b border-slate-200 py-10 sm:py-14 px-4 sm:px-6">
        <div class="mx-auto max-w-[1360px]">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div class="max-w-3xl">
                    <div class="inline-flex items-center gap-1.5 rounded-md bg-slate-200/80 px-2.5 py-1 text-[11px] font-extrabold uppercase tracking-wider text-slate-700 mb-3">
                        <svg class="size-3.5 text-slate-800" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        <span>{{ $torneo->organizador }}</span>
                    </div>

                    <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-slate-950 leading-tight">
                        {{ $torneo->nombre }}
                    </h1>

                    <p class="mt-3 text-base sm:text-lg text-slate-600 font-medium">
                        {{ $torneo->subtitulo }}
                    </p>
                </div>

                <!-- Métricas Clave -->
                <div class="flex items-center gap-4 sm:gap-6 border-t md:border-t-0 md:border-l border-slate-200 pt-4 md:pt-0 md:pl-6">
                    <div class="text-left">
                        <span class="block text-2xl sm:text-3xl font-black text-slate-900 tabular-nums">
                            {{ $disciplinas->count() }}
                        </span>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                            Deportes
                        </span>
                    </div>

                    <div class="h-8 w-px bg-slate-200"></div>

                    <div class="text-left">
                        <span class="block text-2xl sm:text-3xl font-black text-slate-900 tabular-nums">
                            {{ $totalEquipos }}
                        </span>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                            Delegaciones
                        </span>
                    </div>

                    <div class="h-8 w-px bg-slate-200"></div>

                    <div class="text-left">
                        <span class="block text-2xl sm:text-3xl font-black text-slate-900 tabular-nums">
                            {{ $torneo->anio }}
                        </span>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                            Edición
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenedor de Disciplinas -->
    <div class="mx-auto max-w-[1360px] px-4 py-8 sm:px-6 space-y-10">
        
        <!-- Barra de Sección -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                    <svg class="size-6 text-slate-800" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                    <span>Disciplinas Deportivas</span>
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">
                    Selecciona un deporte para ver sus series, fixture y árbol de eliminatorias en vivo.
                </p>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3.5 py-1.5 text-xs font-extrabold text-slate-700">
                    {{ $disciplinas->count() }} Deportes en competencia
                </span>
            </div>
        </div>

        <!-- Cuadrícula de Deportes -->
        @if($disciplinas->count() > 0)
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($disciplinas as $disciplina)
                    <div class="group flex flex-col justify-between overflow-hidden rounded-xl border border-slate-200 bg-white transition-all hover:border-slate-400 hover:shadow-sm">
                        
                        <!-- Imagen Deportiva Superior -->
                        <div class="relative h-44 w-full overflow-hidden bg-slate-100">
                            @if($disciplina->foto_url)
                                <img
                                    src="{{ $disciplina->foto_url }}"
                                    alt="{{ $disciplina->nombre }}"
                                    class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                    loading="lazy"
                                />
                            @else
                                <div class="grid h-full w-full place-items-center bg-slate-100 text-slate-400">
                                    <svg class="size-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="m4.93 4.93 4.24 4.24"/><path d="m14.83 9.17 4.24-4.24"/><path d="m14.83 14.83 4.24 4.24"/><path d="m9.17 14.83-4.24 4.24"/></svg>
                                </div>
                            @endif

                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>

                            <!-- Categoría Badge -->
                            <div class="absolute top-3 left-3">
                                <span class="rounded-md bg-white/95 backdrop-blur-xs px-2.5 py-1 text-[11px] font-black uppercase tracking-wider text-slate-900 shadow-2xs">
                                    {{ $disciplina->categoria }}
                                </span>
                            </div>

                            <!-- Campeón Actual si existe -->
                            @if($disciplina->campeon_actual)
                                <div class="absolute bottom-3 left-3 right-3 flex items-center gap-1.5 rounded-md bg-amber-500/90 backdrop-blur-xs px-2.5 py-1 text-xs font-black text-slate-950">
                                    <svg class="size-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                                    <span class="truncate">Campeón: {{ $disciplina->campeon_actual }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Información y Detalles -->
                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="text-xl font-black text-slate-950 tracking-tight">
                                    {{ $disciplina->nombre }}
                                </h3>

                                <p class="mt-1 text-xs text-slate-600 line-clamp-2 leading-relaxed">
                                    {{ $disciplina->descripcion ?: 'Competencia oficial de ' . $disciplina->nombre }}
                                </p>
                            </div>

                            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                                <span class="font-bold text-slate-500">
                                    {{ $disciplina->series->count() }} {{ $disciplina->series->count() === 1 ? 'Serie' : 'Series' }}
                                </span>

                                <a
                                    href="{{ route('disciplina.show', $disciplina->slug) }}"
                                    class="inline-flex items-center gap-1.5 font-black text-slate-900 hover:text-blue-600 transition-colors"
                                >
                                    <span>Ver Fixture</span>
                                    <svg class="size-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-12 text-center">
                <svg class="mx-auto size-12 text-slate-400 mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                <h3 class="text-lg font-black text-slate-800">No hay disciplinas registradas</h3>
                <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">
                    Próximamente se publicará la programación de los deportes en competencia.
                </p>
            </div>
        @endif

        <!-- Banner Directo a Tabla de Posiciones -->
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="grid size-12 place-items-center rounded-xl bg-slate-900 text-white shrink-0">
                    <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-black text-slate-900">Tabla General de Posiciones</h3>
                    <p class="text-xs text-slate-500">Consulta el ranking oficial de puntajes acumulados por cada delegación.</p>
                </div>
            </div>

            <a
                href="{{ route('clasificacion') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-900 px-5 py-2.5 text-xs font-bold text-white hover:bg-slate-800 transition-colors shrink-0"
            >
                <span>Ver Clasificación Completa</span>
                <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </a>
        </div>

    </div>
</div>
@endsection
