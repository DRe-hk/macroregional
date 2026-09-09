@extends('layouts.app')

@section('title', 'Acceso al Sistema · Competencia Deportiva Macroregional')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center py-12 px-4 sm:px-6 bg-slate-50/50">
    <div class="w-full max-w-md">
        
        <!-- Cabecera del formulario -->
        <div class="text-center mb-8">
            <a
                href="{{ route('home') }}"
                class="inline-flex items-center gap-2 mb-6 text-xs font-bold text-slate-500 hover:text-slate-900 transition-colors"
            >
                <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                <span>Volver al inicio</span>
            </a>

            <div class="mx-auto size-12 rounded-xl bg-slate-900 text-white flex items-center justify-center shadow-xs mb-3">
                <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
            </div>

            <h1 class="text-2xl font-black text-slate-900 tracking-tight">
                Acceso al Sistema
            </h1>
            <p class="mt-1 text-xs sm:text-sm text-slate-500 font-medium">
                Ingreso oficial para delegaciones de la Competencia Macroregional
            </p>
        </div>

        <!-- Tarjeta del Formulario -->
        <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-xs">
            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf

                @if($errors->any())
                    <div class="flex items-start gap-2.5 rounded-lg border border-red-200 bg-red-50 p-3 text-xs text-red-700 font-medium">
                        <svg class="size-4 shrink-0 mt-0.5 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" x2="12"/><line x1="12" x2="12.01" y1="16" x2="16"/></svg>
                        <div>
                            @foreach($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div>
                    <label for="usuario" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Usuario
                    </label>
                    <input
                        id="usuario"
                        name="usuario"
                        type="text"
                        value="{{ old('usuario') }}"
                        placeholder="Ej. delegado_puno o admin"
                        autocomplete="username"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 transition-colors"
                        required
                        autofocus
                    />
                </div>

                <div>
                    <label for="clave" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Contraseña
                    </label>
                    <input
                        id="clave"
                        name="clave"
                        type="password"
                        placeholder="••••••••"
                        autocomplete="current-password"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 transition-colors"
                        required
                    />
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" value="1" checked class="rounded border-slate-300 text-slate-900 focus:ring-slate-900">
                        <span class="text-xs font-semibold text-slate-600">Mantener sesión activa</span>
                    </label>
                </div>

                <button
                    type="submit"
                    class="w-full mt-2 inline-flex items-center justify-center gap-2 rounded-lg bg-slate-900 py-3 text-xs font-extrabold uppercase tracking-wider text-white shadow-xs hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2 transition-colors cursor-pointer"
                >
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" x2="3" y1="12" y2="12"/></svg>
                    <span>Ingresar</span>
                </button>
            </form>

            <div class="mt-6 pt-4 border-t border-slate-100 text-center">
                <p class="text-[11px] text-slate-400 font-medium leading-relaxed">
                    Las credenciales de acceso son emitidas por la comisión organizadora del torneo.
                    El sistema redirigirá automáticamente a su portal según su perfil autorizado.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
