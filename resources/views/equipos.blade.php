@extends('layouts.app')

@section('title', 'Delegaciones Participantes · ' . $torneo->nombre)

@section('content')
<div class="bg-white min-h-screen pb-16">
    <!-- Cabecera -->
    <div class="border-b border-slate-200 bg-slate-50 py-8 px-4 sm:px-6">
        <div class="mx-auto max-w-[1360px] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                    Participantes Oficiales
                </span>
                <h1 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight mt-0.5">
                    Delegaciones Participantes
                </h1>
                <p class="text-xs sm:text-sm text-slate-600 font-medium mt-1">
                    {{ $delegaciones->count() }} delegaciones inscritas en la competencia macroregional.
                </p>
            </div>

            <div class="self-start sm:self-auto">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-white border border-slate-200 px-3.5 py-1.5 text-xs font-bold text-slate-700 shadow-2xs">
                    <svg class="size-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    <span>{{ $delegaciones->count() }} delegaciones participantes</span>
                </span>
            </div>
        </div>
    </div>

    <!-- Cuadrícula de Delegaciones -->
    <div class="mx-auto max-w-[1360px] px-4 sm:px-6 mt-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($delegaciones as $del)
                <div class="rounded-xl border border-slate-200 bg-white p-4 flex items-center justify-between hover:border-slate-300 transition-colors shadow-2xs">
                    <div>
                        <div class="flex items-center gap-1 text-[11px] text-slate-400 font-bold uppercase mb-0.5">
                            <svg class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            <span>{{ $del->provincia }}</span>
                        </div>
                        <h3 class="font-extrabold text-base text-slate-950">{{ $del->nombre }}</h3>
                        <span class="text-xs font-bold text-blue-600">Siglas: {{ $del->siglas }}</span>
                    </div>

                    <div class="text-right pl-3 border-l border-slate-100">
                        <span class="block text-xl font-black text-slate-900 tabular-nums">
                            {{ $del->puntos }}
                        </span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Puntos</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
