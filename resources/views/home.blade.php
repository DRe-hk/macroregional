@extends('layouts.app')

@section('title', $torneo->nombre . ' · Plataforma Oficial')

@section('content')
<div class="bg-white min-h-screen">
    <!-- Hero Oficial Deportivo con Carrusel JEDPA -->
    <div class="relative bg-slate-50 border-b border-slate-200 overflow-hidden">
        <div class="mx-auto max-w-[1360px] px-4 py-8 sm:py-12 sm:px-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                
                <!-- Columna Izquierda: Información del Torneo -->
                <div class="lg:col-span-6 xl:col-span-7 flex flex-col justify-center">
                    <div class="inline-flex items-center gap-2 rounded-full bg-slate-200/80 px-3 py-1 text-xs font-black uppercase tracking-wider text-slate-800 w-fit mb-3">
                        <span class="size-2 rounded-full bg-blue-600 animate-pulse"></span>
                        <span>{{ $torneo->organizador }}</span>
                    </div>

                    <h1 class="text-3xl sm:text-5xl xl:text-6xl font-black tracking-tight text-slate-950 leading-[1.1]">
                        {{ $torneo->nombre }}
                    </h1>

                    <p class="mt-3 text-base sm:text-lg text-slate-600 font-medium max-w-2xl leading-relaxed">
                        {{ $torneo->subtitulo }}
                    </p>

                    <!-- Métricas Clave -->
                    <div class="mt-6 flex flex-wrap items-center gap-4 sm:gap-6 border-y border-slate-200 py-4">
                        <div class="text-left">
                            <span class="block text-2xl sm:text-3xl font-black text-slate-900 tabular-nums">
                                {{ $disciplinas->count() }}
                            </span>
                            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                Deportes
                            </span>
                        </div>

                        <div class="h-8 w-px bg-slate-200"></div>

                        <div class="text-left">
                            <span class="block text-2xl sm:text-3xl font-black text-slate-900 tabular-nums">
                                {{ $totalEquipos }}
                            </span>
                            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                Delegaciones
                            </span>
                        </div>

                        <div class="h-8 w-px bg-slate-200"></div>

                        <div class="text-left">
                            <span class="block text-2xl sm:text-3xl font-black text-slate-900 tabular-nums">
                                {{ $torneo->anio }}
                            </span>
                            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                Edición
                            </span>
                        </div>

                        <div class="h-8 w-px bg-slate-200"></div>

                        <div class="text-left">
                            <span class="block text-sm sm:text-base font-extrabold text-slate-900">
                                {{ $torneo->sede_principal }}
                            </span>
                            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                Sede Central
                            </span>
                        </div>
                    </div>

                    <!-- Botones de Acción Rápida -->
                    <div class="mt-6 flex flex-wrap items-center gap-3">
                        <a href="#deportes" class="inline-flex items-center gap-2 rounded-xl bg-slate-950 px-5 py-3 text-xs font-black uppercase tracking-wider text-white shadow-xs transition hover:bg-slate-800">
                            <span>Explorar Deportes</span>
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg>
                        </a>
                        <a href="{{ route('clasificacion') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-5 py-3 text-xs font-black uppercase tracking-wider text-slate-800 shadow-2xs transition hover:bg-slate-100">
                            <svg class="size-4 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                            <span>Tabla de Posiciones</span>
                        </a>
                        <a href="{{ route('campeones') }}" class="inline-flex items-center gap-2 rounded-xl border border-amber-300 bg-amber-50 px-5 py-3 text-xs font-black uppercase tracking-wider text-amber-900 shadow-2xs transition hover:bg-amber-100">
                            <svg class="size-4 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                            <span>Cuadro de Campeones</span>
                        </a>
                    </div>
                </div>

                <!-- Columna Derecha: Carrusel Interactivo de Portada Hero -->
                <div class="lg:col-span-6 xl:col-span-5">
                    <div
                        x-data="{
                            activeSlide: 0,
                            slides: {{ Js::from($torneo->getSlides()) }},
                            timer: null,
                            start() {
                                if (this.slides.length > 1) {
                                    this.timer = setInterval(() => { this.next() }, 5000);
                                }
                            },
                            stop() {
                                clearInterval(this.timer);
                            },
                            next() {
                                this.activeSlide = (this.activeSlide + 1) % this.slides.length;
                            },
                            prev() {
                                this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length;
                            }
                        }"
                        x-init="start()"
                        @mouseenter="stop()"
                        @mouseleave="start()"
                        class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md"
                    >
                        <div class="aspect-video w-full overflow-hidden bg-slate-950 relative">
                            <template x-for="(slide, idx) in slides" :key="idx">
                                <div
                                    x-show="activeSlide === idx"
                                    x-transition:enter="transition ease-out duration-700"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-400"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-105"
                                    class="absolute inset-0"
                                >
                                    <img :src="slide.imagen" :alt="slide.titulo" class="h-full w-full object-cover" />
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/20 to-transparent"></div>
                                    <div class="absolute bottom-3 left-4 right-4 text-white">
                                        <span class="text-[10px] font-black uppercase tracking-wider text-amber-400 block drop-shadow-sm" x-text="slide.subtitulo"></span>
                                        <h3 class="text-base sm:text-lg font-black leading-tight drop-shadow-sm" x-text="slide.titulo"></h3>
                                    </div>
                                </div>
                            </template>

                            <!-- Controles Anterior / Siguiente -->
                            <button
                                type="button"
                                @click="prev()"
                                class="absolute left-2.5 top-1/2 -translate-y-1/2 size-8 rounded-full bg-black/50 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition hover:bg-black/80 cursor-pointer shadow-md"
                                title="Anterior"
                            >
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                            </button>
                            <button
                                type="button"
                                @click="next()"
                                class="absolute right-2.5 top-1/2 -translate-y-1/2 size-8 rounded-full bg-black/50 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition hover:bg-black/80 cursor-pointer shadow-md"
                                title="Siguiente"
                            >
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
                            </button>
                        </div>

                        <!-- Barra Inferior del Carrusel con Indicadores -->
                        <div class="p-3 bg-white border-t border-slate-100 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-1.5">
                                <template x-for="(slide, idx) in slides" :key="idx">
                                    <button
                                        type="button"
                                        @click="activeSlide = idx"
                                        :class="activeSlide === idx ? 'w-6 bg-blue-600' : 'w-2 bg-slate-300 hover:bg-slate-400'"
                                        class="h-1.5 rounded-full transition-all cursor-pointer"
                                    ></button>
                                </template>
                            </div>
                            <div class="flex items-center gap-1.5 text-slate-500 font-bold text-[11px]">
                                <span class="size-2 rounded-full bg-emerald-500"></span>
                                <span>Galería Oficial JEDPA</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Contenedor de Disciplinas -->
    <div id="deportes" class="mx-auto max-w-[1360px] px-4 py-8 sm:px-6 space-y-10 scroll-mt-6">
        
        <!-- Barra de Sección -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                    <svg class="size-6 text-slate-800" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                    <span>Disciplinas Deportivas Oficiales</span>
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">
                    Consulta fixtures, subcategorías por género y tabla de posiciones oficial.
                </p>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3.5 py-1.5 text-xs font-extrabold text-slate-700">
                    {{ $disciplinas->count() }} Deportes Oficiales
                </span>
            </div>
        </div>

        <!-- Cuadrícula de Deportes Principales -->
        @if($disciplinas->count() > 0)
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($disciplinas as $disciplina)
                    <div class="group flex flex-col justify-between overflow-hidden rounded-xl border border-slate-200 bg-white transition-all hover:border-slate-400 hover:shadow-sm">
                        
                        <!-- Imagen Deportiva Superior -->
                        <div class="relative h-48 w-full overflow-hidden bg-slate-100">
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

                            <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/20 to-transparent"></div>

                            <!-- Badges Superiores -->
                            <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                                @if($disciplina->esIndividual())
                                    <span class="rounded-md bg-amber-400 px-2 py-0.5 text-[10px] font-black uppercase tracking-wider text-slate-950 shadow-2xs">
                                        Deporte Individual
                                    </span>
                                @else
                                    <span class="rounded-md bg-white/95 backdrop-blur-xs px-2 py-0.5 text-[10px] font-black uppercase tracking-wider text-slate-900 shadow-2xs">
                                        {{ $disciplina->categoria ?: 'Colectivo' }}
                                    </span>
                                @endif
                            </div>

                            <!-- Campeón Actual si existe -->
                            @if($disciplina->campeon_actual)
                                <div class="absolute bottom-3 left-3 right-3 flex items-center gap-1.5 rounded-md bg-amber-500/95 backdrop-blur-xs px-2.5 py-1 text-xs font-black text-slate-950 shadow-xs">
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

                                <div class="mt-1.5 flex flex-col gap-1 text-xs text-slate-500">
                                    @if($disciplina->sede_principal)
                                        <div class="flex items-center gap-1.5 font-medium truncate">
                                            <svg class="size-3.5 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                            <span class="truncate">{{ $disciplina->sede_principal }}</span>
                                        </div>
                                    @endif
                                    @if($disciplina->fechas_cronograma)
                                        <div class="flex items-center gap-1.5 font-bold text-blue-700">
                                            <svg class="size-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                            <span>{{ $disciplina->fechas_cronograma }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Subcategorías si existen -->
                                @if($disciplina->subcategorias->isNotEmpty())
                                    <div class="mt-3 flex flex-wrap gap-1">
                                        @foreach($disciplina->subcategorias as $sub)
                                            <a
                                                href="{{ route('disciplina.show', ['slug' => $disciplina->slug, 'sub' => $sub->slug]) }}"
                                                class="rounded bg-slate-100 hover:bg-slate-200 px-2 py-0.5 text-[10px] font-bold text-slate-700 transition"
                                            >
                                                {{ $sub->nombre }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                                <span class="font-bold text-slate-500">
                                    @if($disciplina->esIndividual())
                                        Medallero Directo
                                    @elseif($disciplina->subcategorias->isNotEmpty())
                                        {{ $disciplina->subcategorias->count() }} Subcategorías
                                    @else
                                        {{ $disciplina->partidos->count() }} Partidos
                                    @endif
                                </span>

                                <a
                                    href="{{ route('disciplina.show', $disciplina->slug) }}"
                                    class="inline-flex items-center gap-1.5 font-black text-slate-900 hover:text-blue-600 transition-colors"
                                >
                                    <span>{{ $disciplina->esIndividual() ? 'Ver Ganadores' : 'Ver Fixture' }}</span>
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
                    <h3 class="text-base font-black text-slate-900">Tabla General de Posiciones Acumulada</h3>
                    <p class="text-xs text-slate-500">Ranking oficial de puntajes acumulados según los partidos finalizados.</p>
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
