<!DOCTYPE html>
<html lang="es" class="h-full bg-white text-slate-900 antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Competencia Deportiva Macroregional 2026')</title>
    <meta name="description" content="@yield('meta_description', 'Plataforma oficial de fixtures, resultados, árboles de eliminatorias y tabla de posiciones de la Competencia Deportiva Macroregional 2026.')">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (Vite Build & CDN Fallback for Laragon Out-of-the-box support) -->
    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                        }
                    }
                }
            }
        </script>
    @endif

    <!-- Alpine.js para interactividad liviana -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .tabular-nums { font-variant-numeric: tabular-nums; }
        [x-cloak] { display: none !important; }
        .bracket-scroll::-webkit-scrollbar { height: 6px; }
        .bracket-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
    </style>
</head>
<body class="flex min-h-full flex-col bg-white text-slate-900 selection:bg-slate-900 selection:text-white" x-data="{ mobileMenu: false }">

    <!-- Barra de Navegación Superior -->
    <header class="sticky top-0 z-50 bg-white border-b border-slate-200">
        <div class="mx-auto flex w-full max-w-[1360px] items-center justify-between gap-4 px-4 py-3 sm:px-6">
            
            <!-- Logo Institucional y Título -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0 group">
                <span class="grid size-9 sm:size-10 place-items-center rounded-lg bg-slate-900 text-white font-black text-base transition-transform group-hover:scale-105 shadow-xs">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/>
                        <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/>
                        <path d="M4 22h16"/>
                        <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/>
                        <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/>
                        <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/>
                    </svg>
                </span>
                <div class="flex flex-col">
                    <span class="font-extrabold tracking-tight text-base sm:text-lg text-slate-900 leading-tight">
                        MACROREGIONAL 2026
                    </span>
                    <span class="text-[10px] font-bold text-slate-500 tracking-wider uppercase">
                        Competencia Deportiva Oficial
                    </span>
                </div>
            </a>

            <!-- Menú Desktop -->
            <nav class="hidden md:flex items-center gap-1">
                <a href="{{ route('home') }}" class="px-3.5 py-1.5 text-xs font-bold rounded-lg transition-colors {{ request()->routeIs('home') || request()->routeIs('disciplina.show') ? 'text-slate-950 bg-slate-100 font-extrabold' : 'text-slate-600 hover:text-slate-950 hover:bg-slate-50' }}">
                    Deportes
                </a>
                <a href="{{ route('clasificacion') }}" class="px-3.5 py-1.5 text-xs font-bold rounded-lg transition-colors {{ request()->routeIs('clasificacion') ? 'text-slate-950 bg-slate-100 font-extrabold' : 'text-slate-600 hover:text-slate-950 hover:bg-slate-50' }}">
                    Tabla de Posiciones
                </a>
                <a href="{{ route('equipos') }}" class="px-3.5 py-1.5 text-xs font-bold rounded-lg transition-colors {{ request()->routeIs('equipos') ? 'text-slate-950 bg-slate-100 font-extrabold' : 'text-slate-600 hover:text-slate-950 hover:bg-slate-50' }}">
                    Delegaciones
                </a>
                <a href="{{ route('campeones') }}" class="px-3.5 py-1.5 text-xs font-bold rounded-lg transition-colors {{ request()->routeIs('campeones') ? 'text-slate-950 bg-slate-100 font-extrabold' : 'text-slate-600 hover:text-slate-950 hover:bg-slate-50' }}">
                    Campeones
                </a>
            </nav>

            <!-- Acceso / Perfil -->
            <div class="flex items-center gap-2">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.index') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-bold text-white hover:bg-slate-800 transition-colors">
                            <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                            <span>Panel Admin</span>
                        </a>
                    @else
                        <a href="{{ route('delegado.index') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-bold text-white hover:bg-blue-700 transition-colors">
                            <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <span>Mi Portal</span>
                        </a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="p-2 text-slate-400 hover:text-slate-800 transition-colors" title="Cerrar Sesión">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                        </button>
                    </form>
                @else
                    <!-- Botón Ingresar Oficial (Sin exponer links de admin) -->
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-3.5 py-2 text-xs font-bold text-white shadow-xs hover:bg-slate-800 transition-colors">
                        <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" x2="3" y1="12" y2="12"/></svg>
                        <span>Ingresar</span>
                    </a>
                @endauth

                <!-- Botón Menú Móvil -->
                <button type="button" @click="mobileMenu = !mobileMenu" class="md:hidden p-2 rounded-lg text-slate-700 hover:bg-slate-100" aria-label="Abrir menú">
                    <svg x-show="!mobileMenu" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
                    <svg x-show="mobileMenu" x-cloak class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                </button>
            </div>
        </div>

        <!-- Menú Desplegable Móvil -->
        <div x-show="mobileMenu" x-cloak class="md:hidden border-t border-slate-200 bg-white px-4 py-3 flex flex-col gap-1">
            <a href="{{ route('home') }}" @click="mobileMenu = false" class="px-3 py-2 rounded-lg text-sm font-bold text-slate-700 hover:bg-slate-50">
                Deportes
            </a>
            <a href="{{ route('clasificacion') }}" @click="mobileMenu = false" class="px-3 py-2 rounded-lg text-sm font-bold text-slate-700 hover:bg-slate-50">
                Tabla de Posiciones
            </a>
            <a href="{{ route('equipos') }}" @click="mobileMenu = false" class="px-3 py-2 rounded-lg text-sm font-bold text-slate-700 hover:bg-slate-50">
                Delegaciones
            </a>
            <a href="{{ route('campeones') }}" @click="mobileMenu = false" class="px-3 py-2 rounded-lg text-sm font-bold text-slate-700 hover:bg-slate-50">
                Campeones
            </a>
            <a href="{{ route('login') }}" @click="mobileMenu = false" class="mt-2 flex items-center justify-center gap-2 rounded-lg bg-slate-900 px-3.5 py-2.5 text-xs font-bold text-white hover:bg-slate-800">
                <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" x2="3" y1="12" y2="12"/></svg>
                <span>Ingresar al Sistema</span>
            </a>
        </div>
    </header>

    <!-- Alertas Flash -->
    @if(session('success'))
        <div class="bg-emerald-50 border-b border-emerald-200 py-3 px-4 sm:px-6">
            <div class="mx-auto max-w-[1360px] flex items-center gap-2.5 text-xs font-bold text-emerald-800">
                <svg class="size-4 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-50 border-b border-rose-200 py-3 px-4 sm:px-6">
            <div class="mx-auto max-w-[1360px] flex items-center gap-2.5 text-xs font-bold text-rose-800">
                <svg class="size-4 shrink-0 text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Contenido Principal -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- NOTA: El footer ha sido retirado completamente de todas las vistas a solicitud -->
</body>
</html>
