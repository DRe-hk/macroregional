@extends('layouts.app')

@section('title', 'Cuadro de Campeones · ' . $torneo->nombre)

@section('content')
<div class="bg-white min-h-screen pb-16">
    <!-- Cabecera -->
    <div class="border-b border-slate-200 bg-slate-50 py-8 px-4 sm:px-6">
        <div class="mx-auto max-w-[1360px]">
            <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                Palmarés Oficial
            </span>
            <h1 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight mt-0.5">
                Cuadro de Campeones
            </h1>
            <p class="text-xs sm:text-sm text-slate-600 font-medium mt-1">
                Ganadores oficiales por cada disciplina deportiva de la competencia macroregional.
            </p>
        </div>
    </div>

    <!-- Lista de Campeones -->
    <div class="mx-auto max-w-[1360px] px-4 sm:px-6 mt-8">
        @if($disciplinasConCampeon->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($disciplinasConCampeon as $d)
                    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-2xs hover:border-slate-300 transition-colors">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-3">
                            <div>
                                <span class="text-[10px] font-extrabold uppercase text-blue-600 block">
                                    {{ $d->categoria }}
                                </span>
                                <h3 class="font-black text-base text-slate-900">{{ $d->nombre }}</h3>
                            </div>
                            <a
                                href="{{ route('disciplina.show', $d->slug) }}"
                                class="text-xs font-bold text-blue-600 hover:underline flex items-center gap-1"
                            >
                                <span>Ver llave</span>
                                <svg class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </a>
                        </div>

                        <div class="flex items-center justify-between rounded-lg bg-amber-50 p-3 border border-amber-200">
                            <div class="flex items-center gap-2.5">
                                <svg class="size-5 text-amber-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                                <div>
                                    <span class="text-[10px] font-black uppercase tracking-wider text-amber-800 block">
                                        Campeón
                                    </span>
                                    <span class="font-black text-sm text-amber-950">
                                        {{ $d->campeon_actual }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-12 text-center">
                <svg class="mx-auto size-12 text-slate-300 mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                <h3 class="text-base font-black text-slate-800">
                    La competencia está en fase de eliminatorias
                </h3>
                <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-md mx-auto">
                    Aún no se han definido los campeones de las finales. Conforme se concluyan los
                    partidos y se confirmen los marcadores oficiales, aparecerán aquí.
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
@endsection
