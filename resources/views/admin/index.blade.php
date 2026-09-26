@extends('layouts.app')

@section('title', 'Suite de Administración General · ' . $torneo->nombre)

@section('content')
<div class="min-h-screen bg-slate-50 py-8 px-4 sm:px-6" x-data="{ tab: 'torneo', modalPermisos: { open: false, user: null, name: '', disciplinas: [] } }">
    <div class="mx-auto max-w-[1360px] space-y-6">
        
        <!-- Cabecera de Administración -->
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="grid size-12 place-items-center rounded-xl bg-slate-900 text-white font-black text-lg shrink-0 shadow-xs">
                    <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="rounded bg-rose-100 px-2 py-0.5 text-[10px] font-black uppercase tracking-wider text-rose-900">
                            Administrador General
                        </span>
                        <span class="text-xs font-bold text-slate-400">·</span>
                        <span class="text-xs font-bold text-slate-500">Acceso Privado</span>
                    </div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight mt-0.5">
                        Panel de Control Deportivo JEDPA 2026
                    </h1>
                    <p class="text-xs text-slate-500 font-medium">
                        Gestión total del torneo, branding, carrusel hero, disciplinas, subcategorías, fixture por fechas y permisos granulares.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 self-start md:self-auto">
                <a
                    href="{{ route('home') }}"
                    target="_blank"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors shadow-2xs"
                >
                    <span>Ver Sitio Público</span>
                    <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" x2="21" y1="14" y2="3"/></svg>
                </a>
            </div>
        </div>

        <!-- Barra de Pestañas del Panel Admin -->
        <div class="flex items-center gap-1.5 border-b border-slate-200 pb-2 overflow-x-auto">
            <button
                type="button"
                @click="tab = 'torneo'"
                :class="tab === 'torneo' ? 'bg-slate-900 text-white shadow-2xs font-black' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200 font-bold'"
                class="px-4 py-2 text-xs uppercase tracking-wider rounded-lg transition-colors shrink-0 cursor-pointer"
            >
                🏆 Torneo & Web
            </button>
            <button
                type="button"
                @click="tab = 'deportes'"
                :class="tab === 'deportes' ? 'bg-slate-900 text-white shadow-2xs font-black' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200 font-bold'"
                class="px-4 py-2 text-xs uppercase tracking-wider rounded-lg transition-colors shrink-0 cursor-pointer"
            >
                ⚽ Deportes ({{ $disciplinas->count() }})
            </button>
            <button
                type="button"
                @click="tab = 'equipos'"
                :class="tab === 'equipos' ? 'bg-slate-900 text-white shadow-2xs font-black' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200 font-bold'"
                class="px-4 py-2 text-xs uppercase tracking-wider rounded-lg transition-colors shrink-0 cursor-pointer"
            >
                🏛️ Delegaciones ({{ $delegaciones->count() }})
            </button>
            <button
                type="button"
                @click="tab = 'fixture'"
                :class="tab === 'fixture' ? 'bg-slate-900 text-white shadow-2xs font-black' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200 font-bold'"
                class="px-4 py-2 text-xs uppercase tracking-wider rounded-lg transition-colors shrink-0 cursor-pointer"
            >
                📅 Fixture & Marcadores
            </button>
            <button
                type="button"
                @click="tab = 'delegados'"
                :class="tab === 'delegados' ? 'bg-slate-900 text-white shadow-2xs font-black' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200 font-bold'"
                class="px-4 py-2 text-xs uppercase tracking-wider rounded-lg transition-colors shrink-0 cursor-pointer"
            >
                🔑 Delegados & Permisos ({{ $usuarios->where('role', 'DELEGADO')->count() }})
            </button>
            <button
                type="button"
                @click="tab = 'reiniciar'"
                :class="tab === 'reiniciar' ? 'bg-rose-900 text-white shadow-2xs font-black' : 'bg-white text-rose-700 hover:bg-rose-50 border border-rose-200 font-bold'"
                class="px-4 py-2 text-xs uppercase tracking-wider rounded-lg transition-colors shrink-0 cursor-pointer"
            >
                🔄 Reiniciar BD
            </button>
        </div>

        <!-- 1. PESTAÑA: TORNEO & WEB -->
        <div x-show="tab === 'torneo'" class="space-y-6">
            <!-- Formulario de Torneo y Branding -->
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-2xs">
                <h2 class="text-base font-black text-slate-900 mb-1">Información General, Branding, Logo y Footer</h2>
                <p class="text-xs text-slate-500 mb-5">Configura los textos institucionales que se reflejan en el encabezado, pie de página y metadatos.</p>

                <form action="{{ route('admin.torneo.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5 max-w-4xl">
                    @csrf
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Nombre Oficial del Torneo</label>
                            <input type="text" name="nombre" value="{{ $torneo->nombre }}" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm font-bold text-slate-900 focus:border-slate-900 focus:outline-none" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Subtítulo / Denominación</label>
                            <input type="text" name="subtitulo" value="{{ $torneo->subtitulo }}" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-900 focus:border-slate-900 focus:outline-none" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-blue-50/50 p-4 rounded-xl border border-blue-200">
                        <div>
                            <label class="block text-xs font-black uppercase text-blue-900 mb-1">Texto Principal del Logo (Navbar)</label>
                            <input type="text" name="logo_texto" value="{{ $torneo->logo_texto ?? 'JEDPA 2026' }}" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm font-black text-slate-900 bg-white focus:border-slate-900 focus:outline-none" />
                            <p class="text-[10px] text-blue-700 mt-1">Ejemplo: JEDPA 2026 o MACROREGIONAL 2026</p>
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase text-blue-900 mb-1">Subtexto del Logo (Navbar)</label>
                            <input type="text" name="logo_subtexto" value="{{ $torneo->logo_subtexto ?? 'Macroregional Sede Puno' }}" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-900 bg-white focus:border-slate-900 focus:outline-none" />
                            <p class="text-[10px] text-blue-700 mt-1">Texto secundario en mayúsculas debajo del logo.</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Texto Institucional del Footer (Derechos Reservados)</label>
                        <textarea name="footer_texto" rows="2" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none">{{ $torneo->footer_texto }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 border-y border-slate-200 py-4 bg-slate-50/50 p-4 rounded-xl">
                        <!-- Logotipo del Torneo -->
                        <div>
                            <label class="block text-xs font-black uppercase text-slate-800 mb-1">Logotipo Institucional</label>
                            <p class="text-[11px] text-slate-500 mb-2">Sube una imagen o ingresa una URL directa (PNG, JPG, SVG).</p>
                            @if($torneo->logo_url)
                                <div class="mb-2 flex items-center gap-3 p-2 bg-white rounded-lg border border-slate-200">
                                    <img src="{{ $torneo->logo_url }}" alt="Logo actual" class="size-10 object-contain" />
                                    <span class="text-xs text-slate-600 font-semibold truncate">{{ $torneo->logo_url }}</span>
                                </div>
                            @endif
                            <div class="space-y-2">
                                <input type="file" name="logo_file" accept="image/*" class="w-full text-xs text-slate-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-black file:bg-slate-900 file:text-white hover:file:bg-slate-800 cursor-pointer" />
                                <input type="text" name="logo_url" value="{{ $torneo->logo_url }}" placeholder="O ingresa URL externa..." class="w-full rounded-lg border border-slate-300 px-3 py-1.5 text-xs text-slate-900 focus:border-slate-900 focus:outline-none" />
                            </div>
                        </div>

                        <!-- Imagen de Portada Principal -->
                        <div>
                            <label class="block text-xs font-black uppercase text-slate-800 mb-1">Portada Oficial (Hero Banner)</label>
                            <p class="text-[11px] text-slate-500 mb-2">Imagen destacada oficial de los Juegos Escolares.</p>
                            @if($torneo->portada_url)
                                <div class="mb-2 rounded-lg border border-slate-200 overflow-hidden h-20 bg-slate-100 relative">
                                    <img src="{{ $torneo->portada_url }}" alt="Portada actual" class="h-full w-full object-cover" />
                                </div>
                            @endif
                            <div class="space-y-2">
                                <input type="file" name="portada_file" accept="image/*" class="w-full text-xs text-slate-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-black file:bg-slate-900 file:text-white hover:file:bg-slate-800 cursor-pointer" />
                                <input type="text" name="portada_url" value="{{ $torneo->portada_url }}" placeholder="O ingresa URL externa..." class="w-full rounded-lg border border-slate-300 px-3 py-1.5 text-xs text-slate-900 focus:border-slate-900 focus:outline-none" />
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Entidad Organizadora</label>
                            <input type="text" name="organizador" value="{{ $torneo->organizador }}" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Sede Principal</label>
                            <input type="text" name="sede_principal" value="{{ $torneo->sede_principal }}" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Año</label>
                            <input type="number" name="anio" value="{{ $torneo->anio }}" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-700 mb-1">% de Avance</label>
                            <input type="number" name="avance_porcentaje" min="0" max="100" value="{{ $torneo->avance_porcentaje }}" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none" />
                        </div>
                    </div>

                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-5 py-2.5 text-xs font-black uppercase tracking-wider text-white hover:bg-slate-800 transition-colors cursor-pointer">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        <span>Guardar Cambios del Torneo</span>
                    </button>
                </form>
            </div>

            <!-- Gestor del Carrusel Hero -->
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-2xs space-y-5">
                <div class="border-b border-slate-100 pb-3">
                    <span class="text-[10px] font-black uppercase text-blue-600 tracking-wider block">Galería Dinámica</span>
                    <h2 class="text-base font-black text-slate-900">Gestor del Carrusel Hero (Portada)</h2>
                    <p class="text-xs text-slate-500">Añade o elimina diapositivas que rotan automáticamente en la página principal.</p>
                </div>

                <!-- Lista de Slides Actuales -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($torneo->getSlides() as $index => $slide)
                        <div class="rounded-xl border border-slate-200 overflow-hidden bg-slate-50 flex flex-col justify-between">
                            <div class="h-32 w-full bg-slate-200 relative overflow-hidden">
                                <img src="{{ $slide['imagen'] }}" alt="{{ $slide['titulo'] }}" class="h-full w-full object-cover" />
                                <div class="absolute bottom-2 left-2 right-2 bg-slate-950/70 backdrop-blur-xs p-1.5 rounded text-white text-[11px] truncate">
                                    <span class="font-extrabold truncate block">{{ $slide['titulo'] }}</span>
                                </div>
                            </div>
                            <div class="p-3 flex items-center justify-between text-xs border-t border-slate-200 bg-white">
                                <span class="text-slate-500 text-[10px] font-bold">Slide #{{ $index + 1 }}</span>
                                <form action="{{ route('admin.carrusel.eliminar', $index) }}" method="POST" onsubmit="return confirm('¿Eliminar este slide del carrusel?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold text-xs cursor-pointer">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Formulario para Añadir Nuevo Slide -->
                <div class="rounded-xl bg-slate-50 p-4 border border-slate-200">
                    <h3 class="text-xs font-black uppercase text-slate-800 mb-3">Añadir Nuevo Slide al Carrusel</h3>
                    <form action="{{ route('admin.carrusel.guardar') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                        @csrf
                        <div>
                            <label class="block text-[11px] font-bold uppercase text-slate-700 mb-1">Título del Slide</label>
                            <input type="text" name="titulo" placeholder="Ej. Estadio Enrique Torres Belón" required class="w-full rounded-lg border border-slate-300 px-3 py-1.5 text-xs text-slate-900 bg-white focus:outline-none" />
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold uppercase text-slate-700 mb-1">Subtítulo / Detalle</label>
                            <input type="text" name="subtitulo" placeholder="Ej. Ceremonia de Inauguración" class="w-full rounded-lg border border-slate-300 px-3 py-1.5 text-xs text-slate-900 bg-white focus:outline-none" />
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold uppercase text-slate-700 mb-1">Subir Archivo de Imagen</label>
                            <input type="file" name="imagen_file" accept="image/*" class="w-full text-xs text-slate-600 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-bold file:bg-slate-900 file:text-white" />
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 rounded-lg bg-blue-600 py-2 text-xs font-black uppercase text-white hover:bg-blue-700 transition cursor-pointer">
                                <span>+ Añadir al Carrusel</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- 2. PESTAÑA: DEPORTES -->
        <div x-show="tab === 'deportes'" class="space-y-6">
            <!-- Formulario Crear/Editar Deporte o Subcategoría -->
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-2xs">
                <h2 class="text-base font-black text-slate-900 mb-1">Añadir o Editar Disciplina Deportiva o Subcategoría</h2>
                <p class="text-xs text-slate-500 mb-4">Puedes crear un deporte principal o agruparlo como subcategoría (ej. Fútbol Cat. B Damas dentro de Fútbol).</p>

                <form action="{{ route('admin.deporte.guardar') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Nombre</label>
                        <input type="text" name="nombre" placeholder="Ej. Fútbol Cat. B Damas" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Deporte Padre (Subcategoría)</label>
                        <select name="parent_id" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs font-semibold text-slate-900 focus:border-slate-900 focus:outline-none">
                            <option value="">-- Ninguno (Es Deporte Principal) --</option>
                            @foreach($deportesPrincipales as $dp)
                                <option value="{{ $dp->id }}">{{ $dp->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Tipo de Deporte</label>
                        <select name="tipo" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs font-semibold text-slate-900 focus:border-slate-900 focus:outline-none">
                            <option value="COLECTIVO">COLECTIVO (Con partidos / fixture)</option>
                            <option value="INDIVIDUAL">INDIVIDUAL (Natación / Atletismo - Sin fixture)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Sistema de Puntuación (MINEDU)</label>
                        <select name="sistema_puntuacion" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs font-semibold text-slate-900 focus:border-slate-900 focus:outline-none">
                            <option value="FUTBOL">Fútbol / Futsal (3-1-0)</option>
                            <option value="BASQUET">Básquet (2-1-0)</option>
                            <option value="HANDBALL">Handball (2-1-0 / WO -2)</option>
                            <option value="VOLEIBOL">Voleibol (Sets 3-2-1-0)</option>
                            <option value="VOLEY_PLAYA">Vóley Playa (Sets 3-2-1-0)</option>
                            <option value="INDIVIDUAL">Individual (Podio directo)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Categoría</label>
                        <input type="text" name="categoria" placeholder="Ej. Cat. B" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Género / Rama</label>
                        <select name="genero" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs font-semibold text-slate-900 focus:border-slate-900 focus:outline-none">
                            <option value="">-- No especificado / Mixto --</option>
                            <option value="Varones">Varones</option>
                            <option value="Damas">Damas</option>
                            <option value="Mixto">Mixto</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Sede Principal / Escenario</label>
                        <input type="text" name="sede_principal" placeholder="Ej. Estadio Enrique Torres Belón" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Color de Acento</label>
                        <input type="color" name="color_acento" value="#2563eb" class="w-full h-9 rounded-lg border border-slate-300 p-1 cursor-pointer" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Fechas Cronograma</label>
                        <input type="text" name="fechas_cronograma" placeholder="Ej. 30-Set al 02-Oct" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Horario Oficial</label>
                        <input type="text" name="horario_cronograma" placeholder="Ej. 09:00 AM" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none" />
                    </div>

                    <!-- Foto Principal -->
                    <div class="sm:col-span-2 bg-slate-50 p-3 rounded-lg border border-slate-200">
                        <label class="block text-xs font-bold uppercase text-slate-800 mb-1">Foto Principal / Banner</label>
                        <input type="file" name="foto_file" accept="image/*" class="w-full text-xs text-slate-600 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-bold file:bg-slate-900 file:text-white" />
                    </div>

                    <div class="sm:col-span-2 md:col-span-4 flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-slate-900 px-5 py-2.5 text-xs font-black uppercase text-white hover:bg-slate-800 transition cursor-pointer">
                            <span>Guardar Deporte / Subcategoría</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Registro de Podio para Deportes Individuales (Natación y Atletismo) -->
            <div class="rounded-xl border border-amber-300 bg-amber-50/50 p-6 shadow-2xs space-y-4">
                <div class="flex items-center gap-2.5">
                    <span class="text-xl">🥇</span>
                    <div>
                        <h3 class="text-base font-black text-slate-900">Registrar Podio y Ganadores (Deportes Individuales)</h3>
                        <p class="text-xs text-slate-600">Para Atletismo o Natación: registra directamente los ganadores oficiales sin necesidad de fixture.</p>
                    </div>
                </div>

                @php
                    $deportesIndividuales = $disciplinas->where('tipo', 'INDIVIDUAL');
                @endphp

                @foreach($deportesIndividuales as $ind)
                    <div class="rounded-xl border border-slate-200 bg-white p-4 space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <span class="font-black text-sm text-slate-900">{{ $ind->nombre }} ({{ $ind->sede_principal }})</span>
                            <span class="text-xs font-bold text-amber-700">Campeón Actual: {{ $ind->campeon_actual ?: 'Sin proclamar' }}</span>
                        </div>

                        <form action="{{ route('admin.deporte.podio', $ind->id) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            @csrf
                            <!-- ORO -->
                            <div class="rounded-lg bg-amber-50 p-2.5 border border-amber-200 space-y-1.5">
                                <span class="text-[10px] font-black uppercase text-amber-900 block">🥇 1° Lugar / Oro</span>
                                <select name="oro_delegacion" required class="w-full text-xs rounded border border-amber-300 p-1 bg-white">
                                    <option value="">-- Delegación --</option>
                                    @foreach($delegaciones as $del)
                                        <option value="{{ $del->nombre }}" {{ (is_array($ind->podio) && isset($ind->podio['oro']['delegacion']) && $ind->podio['oro']['delegacion'] === $del->nombre) ? 'selected' : '' }}>{{ $del->nombre }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="oro_atleta" placeholder="Nombre del atleta" value="{{ is_array($ind->podio) ? ($ind->podio['oro']['atleta'] ?? '') : '' }}" required class="w-full text-xs rounded border border-amber-300 p-1 bg-white" />
                                <input type="text" name="oro_marca" placeholder="Marca / Tiempo (ej. 10.85s)" value="{{ is_array($ind->podio) ? ($ind->podio['oro']['marca'] ?? '') : '' }}" class="w-full text-xs rounded border border-amber-300 p-1 bg-white" />
                            </div>

                            <!-- PLATA -->
                            <div class="rounded-lg bg-slate-50 p-2.5 border border-slate-200 space-y-1.5">
                                <span class="text-[10px] font-black uppercase text-slate-700 block">🥈 2° Lugar / Plata</span>
                                <select name="plata_delegacion" class="w-full text-xs rounded border border-slate-300 p-1 bg-white">
                                    <option value="">-- Delegación --</option>
                                    @foreach($delegaciones as $del)
                                        <option value="{{ $del->nombre }}" {{ (is_array($ind->podio) && isset($ind->podio['plata']['delegacion']) && $ind->podio['plata']['delegacion'] === $del->nombre) ? 'selected' : '' }}>{{ $del->nombre }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="plata_atleta" placeholder="Nombre del atleta" value="{{ is_array($ind->podio) ? ($ind->podio['plata']['atleta'] ?? '') : '' }}" class="w-full text-xs rounded border border-slate-300 p-1 bg-white" />
                                <input type="text" name="plata_marca" placeholder="Marca / Tiempo" value="{{ is_array($ind->podio) ? ($ind->podio['plata']['marca'] ?? '') : '' }}" class="w-full text-xs rounded border border-slate-300 p-1 bg-white" />
                            </div>

                            <!-- BRONCE -->
                            <div class="rounded-lg bg-amber-900/5 p-2.5 border border-amber-800/20 space-y-1.5">
                                <span class="text-[10px] font-black uppercase text-amber-900 block">🥉 3° Lugar / Bronce</span>
                                <select name="bronce_delegacion" class="w-full text-xs rounded border border-slate-300 p-1 bg-white">
                                    <option value="">-- Delegación --</option>
                                    @foreach($delegaciones as $del)
                                        <option value="{{ $del->nombre }}" {{ (is_array($ind->podio) && isset($ind->podio['bronce']['delegacion']) && $ind->podio['bronce']['delegacion'] === $del->nombre) ? 'selected' : '' }}>{{ $del->nombre }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="bronce_atleta" placeholder="Nombre del atleta" value="{{ is_array($ind->podio) ? ($ind->podio['bronce']['atleta'] ?? '') : '' }}" class="w-full text-xs rounded border border-slate-300 p-1 bg-white" />
                                <input type="text" name="bronce_marca" placeholder="Marca / Tiempo" value="{{ is_array($ind->podio) ? ($ind->podio['bronce']['marca'] ?? '') : '' }}" class="w-full text-xs rounded border border-slate-300 p-1 bg-white" />
                            </div>

                            <div class="sm:col-span-3 flex justify-end">
                                <button type="submit" class="rounded bg-slate-900 px-4 py-1.5 text-xs font-black text-white hover:bg-slate-800 cursor-pointer">
                                    Guardar Ganadores de {{ $ind->nombre }}
                                </button>
                            </div>
                        </form>
                    </div>
                @endforeach
            </div>

            <!-- Tabla de Disciplinas Existentes -->
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xs">
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-slate-200 bg-slate-50 text-[11px] font-black uppercase text-slate-500">
                        <tr>
                            <th class="py-3 pl-4 pr-3">Nombre</th>
                            <th class="px-3 py-3">Tipo / Modalidad</th>
                            <th class="px-3 py-3">Deporte Padre</th>
                            <th class="px-3 py-3">Sede</th>
                            <th class="px-3 py-3">Fechas</th>
                            <th class="px-3 py-3">Campeón</th>
                            <th class="py-3 pl-3 pr-4 text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($disciplinas as $d)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 pl-4 pr-3 font-extrabold text-slate-900">
                                    {{ $d->nombre }}
                                </td>
                                <td class="px-3 py-3">
                                    <span class="rounded px-2 py-0.5 text-[10px] font-black uppercase {{ $d->tipo === 'INDIVIDUAL' ? 'bg-amber-100 text-amber-900' : 'bg-slate-100 text-slate-700' }}">
                                        {{ $d->tipo }}
                                    </span>
                                </td>
                                <td class="px-3 py-3 text-slate-500 font-bold">
                                    {{ $d->parent ? $d->parent->nombre : 'Deporte Principal' }}
                                </td>
                                <td class="px-3 py-3 text-slate-600 truncate max-w-[150px]">
                                    {{ $d->sede_principal }}
                                </td>
                                <td class="px-3 py-3 text-blue-700 font-bold">
                                    {{ $d->fechas_cronograma ?: 'Por definir' }}
                                </td>
                                <td class="px-3 py-3 font-bold text-amber-800">
                                    {{ $d->campeon_actual ?: '-' }}
                                </td>
                                <td class="py-3 pl-3 pr-4 text-right">
                                    <form action="{{ route('admin.deporte.eliminar', $d->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta disciplina?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold cursor-pointer">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 3. PESTAÑA: DELEGACIONES -->
        <div x-show="tab === 'equipos'" class="space-y-6">
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-2xs">
                <h2 class="text-base font-black text-slate-900 mb-1">Registrar Delegación / UGEL</h2>
                <p class="text-xs text-slate-500 mb-4">Crea una nueva delegación participante con sus siglas y logotipo oficial.</p>

                <form action="{{ route('admin.delegacion.guardar') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Nombre Completo</label>
                        <input type="text" name="nombre" placeholder="Ej. DRE Puno (Anfitrión)" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Siglas</label>
                        <input type="text" name="siglas" placeholder="Ej. PUNO" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Región / Provincia</label>
                        <input type="text" name="provincia" placeholder="Ej. Puno" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Subir Logotipo</label>
                        <input type="file" name="logo_file" accept="image/*" class="w-full text-xs text-slate-600 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-bold file:bg-slate-900 file:text-white" />
                    </div>
                    <div class="sm:col-span-2 md:col-span-4 flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-slate-900 px-5 py-2.5 text-xs font-black uppercase text-white hover:bg-slate-800 transition cursor-pointer">
                            <span>Guardar Delegación</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabla de Delegaciones -->
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xs">
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-slate-200 bg-slate-50 text-[11px] font-black uppercase text-slate-500">
                        <tr>
                            <th class="py-3 pl-4 pr-2 w-12 text-center">Escudo</th>
                            <th class="py-3 px-3">Delegación</th>
                            <th class="px-3 py-3">Siglas</th>
                            <th class="px-3 py-3">Provincia</th>
                            <th class="px-3 py-3 text-center">Partidos</th>
                            <th class="px-3 py-3 text-center">Puntos Acumulados</th>
                            <th class="py-3 pl-3 pr-4 text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($delegaciones as $del)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 pl-4 pr-2 text-center">
                                    @if($del->logo_url)
                                        <img src="{{ $del->logo_url }}" alt="{{ $del->siglas }}" class="size-7 rounded object-contain mx-auto" />
                                    @else
                                        <span class="grid size-7 place-items-center rounded bg-slate-100 text-slate-600 text-[10px] font-bold mx-auto">
                                            {{ substr($del->siglas, 0, 2) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 font-extrabold text-slate-900">
                                    {{ $del->nombre }}
                                </td>
                                <td class="px-3 py-3 font-bold text-blue-600">
                                    {{ $del->siglas }}
                                </td>
                                <td class="px-3 py-3 text-slate-500">
                                    {{ $del->provincia }}
                                </td>
                                <td class="px-3 py-3 text-center font-bold text-slate-700 tabular-nums">
                                    {{ $del->pj }}
                                </td>
                                <td class="px-3 py-3 text-center font-black text-slate-900 tabular-nums">
                                    {{ $del->puntos }}
                                </td>
                                <td class="py-3 pl-3 pr-4 text-right">
                                    <form action="{{ route('admin.delegacion.eliminar', $del->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta delegación?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold cursor-pointer">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 4. PESTAÑA: FIXTURE & MARCADORES -->
        <div x-show="tab === 'fixture'" class="space-y-6">
            <!-- Programar Encuentro en Fixture con Fecha y Hora -->
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-2xs">
                <h2 class="text-base font-black text-slate-900 mb-1">Programar Encuentro en Fixture</h2>
                <p class="text-xs text-slate-500 mb-4">Ingresa la fecha oficial del cronograma (30-Set al 02-Oct), horario y escenario.</p>

                <form action="{{ route('admin.partido.guardar') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Disciplina / Subcategoría</label>
                        <select name="disciplina_id" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs font-semibold text-slate-900 focus:border-slate-900 focus:outline-none">
                            @foreach($disciplinas->where('tipo', 'COLECTIVO') as $d)
                                <option value="{{ $d->id }}">{{ $d->nombre }} ({{ $d->parent ? $d->parent->nombre : $d->categoria }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Nombre de Ronda</label>
                        <input type="text" name="ronda_nombre" placeholder="Ej. Fecha 1 o Gran Final" value="Fecha 1" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Número de Ronda (Orden)</label>
                        <input type="number" name="ronda_numero" min="1" value="1" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Fecha del Partido</label>
                        <input type="date" name="fecha" value="2026-09-30" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Horario Oficial</label>
                        <input type="text" name="horario" placeholder="Ej. 09:00 AM" value="09:00 AM" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Equipo Local</label>
                        <select name="local_id" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs font-semibold text-slate-900 focus:border-slate-900 focus:outline-none">
                            <option value="">-- Por Definir --</option>
                            @foreach($delegaciones as $del)
                                <option value="{{ $del->id }}">{{ $del->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Equipo Visitante</label>
                        <select name="visitante_id" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs font-semibold text-slate-900 focus:border-slate-900 focus:outline-none">
                            <option value="">-- Por Definir --</option>
                            @foreach($delegaciones as $del)
                                <option value="{{ $del->id }}">{{ $del->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Cancha / Escenario</label>
                        <input type="text" name="cancha" placeholder="Ej. Cancha sintética UNA - Campo 1" value="Cancha Principal" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none" />
                    </div>

                    <div class="sm:col-span-2 md:col-span-4 flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-slate-900 px-5 py-2.5 text-xs font-bold text-white hover:bg-slate-800 transition cursor-pointer">
                            <span>+ Programar Partido en Fixture</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Listado y Edición de Marcadores -->
            <div class="space-y-4">
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">
                    Partidos Programados, Carga de Marcadores y Fotos de Evidencia
                </h3>

                @php
                    $todosPartidos = \App\Models\Partido::with(['disciplina', 'local', 'visitante', 'ganador'])->orderBy('fecha')->orderBy('horario')->get();
                @endphp

                @forelse($todosPartidos as $partido)
                    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-2xs">
                        <form action="{{ route('admin.marcador.update') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                            @csrf
                            <input type="hidden" name="partido_id" value="{{ $partido->id }}">

                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-2">
                                <div class="flex items-center gap-2">
                                    <span class="rounded bg-slate-900 px-2 py-0.5 text-[10px] font-black uppercase text-white">
                                        {{ $partido->disciplina?->nombre ?? 'Deporte' }}
                                    </span>
                                    <span class="text-xs font-bold text-slate-600">
                                        {{ $partido->ronda_nombre }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-3 text-xs text-slate-500 font-semibold">
                                    <span class="text-blue-700 font-bold">📅 {{ $partido->fecha ? $partido->fecha->translatedFormat('d M') : 'Sin fecha' }} · ⏰ {{ $partido->horario }}</span>
                                    <span>·</span>
                                    <span>📍 {{ $partido->cancha }}</span>
                                    <span class="rounded px-2 py-0.5 text-[10px] font-black uppercase {{ $partido->estado === 'FINALIZADO' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                                        {{ $partido->estado }}
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-center">
                                <div class="flex items-center justify-between p-3 rounded-lg border border-slate-200 bg-slate-50/50">
                                    <span class="text-xs font-extrabold text-slate-900 truncate pr-2">
                                        {{ $partido->local?->nombre ?? 'Por Definir' }}
                                    </span>
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <label class="text-[10px] font-bold text-slate-400">Score:</label>
                                        <input type="number" name="local_goles" min="0" value="{{ $partido->local_goles }}" class="w-14 rounded-lg border border-slate-300 bg-white p-1 text-center text-sm font-black text-slate-900 tabular-nums" />
                                    </div>
                                </div>

                                <div class="flex items-center justify-between p-3 rounded-lg border border-slate-200 bg-slate-50/50">
                                    <span class="text-xs font-extrabold text-slate-900 truncate pr-2">
                                        {{ $partido->visitante?->nombre ?? 'Por Definir' }}
                                    </span>
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <label class="text-[10px] font-bold text-slate-400">Score:</label>
                                        <input type="number" name="visitante_goles" min="0" value="{{ $partido->visitante_goles }}" class="w-14 rounded-lg border border-slate-300 bg-white p-1 text-center text-sm font-black text-slate-900 tabular-nums" />
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-center pt-2">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Subir Foto de Acta / Planilla (Evidencia)</label>
                                    <input type="file" name="evidencia_file" accept="image/*" class="w-full text-xs text-slate-600 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-bold file:bg-slate-800 file:text-white" />
                                    @if($partido->foto_evidencia)
                                        <a href="{{ $partido->foto_evidencia }}" target="_blank" class="text-[10px] text-blue-600 font-bold hover:underline block mt-0.5">
                                            📄 Ver acta actualmente guardada
                                        </a>
                                    @endif
                                </div>

                                <div class="flex items-center gap-2 pt-4">
                                    <label class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-700 cursor-pointer">
                                        <input type="checkbox" name="es_wo" value="1" {{ $partido->es_wo ? 'checked' : '' }} class="rounded border-slate-300 text-rose-600 size-4" />
                                        <span>Declarar W.O. (No se presentó)</span>
                                    </label>
                                </div>

                                <div class="flex items-center justify-end gap-2 pt-4">
                                    <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-slate-900 px-4 py-1.5 text-xs font-extrabold text-white hover:bg-slate-800 transition cursor-pointer">
                                        Guardar Marcador
                                    </button>
                        </form>
                                    <form action="{{ route('admin.partido.eliminar', $partido->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este partido?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-rose-600 hover:text-rose-800 font-bold text-xs cursor-pointer">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                    </div>
                @empty
                    <div class="rounded-xl border border-dashed border-slate-300 bg-white p-12 text-center text-slate-400 font-medium">
                        No hay partidos programados aún en el fixture.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- 5. PESTAÑA: DELEGADOS & CONTROL GRANULAR -->
        <div x-show="tab === 'delegados'" class="space-y-6">
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-2xs">
                <h2 class="text-base font-black text-slate-900 mb-1">Crear Credenciales de Acceso con Control Granular</h2>
                <p class="text-xs text-slate-500 mb-4">Puedes asignar a cada delegado uno o varios deportes específicos que tendrá permiso de gestionar.</p>

                <form action="{{ route('admin.delegado.guardar') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Nombre de Usuario</label>
                            <input type="text" name="username" placeholder="Ej. delegado.futbol" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Nombre Completo</label>
                            <input type="text" name="name" placeholder="Ej. Prof. Juan Pérez" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Contraseña</label>
                            <input type="text" name="clave" value="123456" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs text-slate-900 focus:border-slate-900 focus:outline-none" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Delegación / UGEL</label>
                            <select name="delegacion_id" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs font-semibold text-slate-900 focus:border-slate-900 focus:outline-none">
                                @foreach($delegaciones as $del)
                                    <option value="{{ $del->id }}">{{ $del->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Asignación Granular de Deportes -->
                    <div class="rounded-xl bg-slate-50 p-4 border border-slate-200">
                        <label class="block text-xs font-black uppercase text-slate-800 mb-2">Asignar Deportes Permitidos para este Delegado:</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                            @foreach($disciplinas as $d)
                                <label class="inline-flex items-center gap-1.5 text-xs text-slate-700 cursor-pointer">
                                    <input type="checkbox" name="disciplinas[]" value="{{ $d->id }}" class="rounded border-slate-300 text-blue-600 size-4" />
                                    <span class="truncate">{{ $d->nombre }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-5 py-2.5 text-xs font-black uppercase text-white hover:bg-blue-700 transition cursor-pointer">
                            <span>+ Crear Delegado con Permisos</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabla de Usuarios con Permisos Asignados -->
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xs">
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-slate-200 bg-slate-50 text-[11px] font-black uppercase text-slate-500">
                        <tr>
                            <th class="py-3 pl-4 pr-3">Usuario</th>
                            <th class="px-3 py-3">Nombre</th>
                            <th class="px-3 py-3">Rol</th>
                            <th class="px-3 py-3">Deportes Asignados (Permisos)</th>
                            <th class="py-3 pl-3 pr-4 text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($usuarios as $u)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 pl-4 pr-3 font-mono font-bold text-slate-900">
                                    {{ $u->username }}
                                </td>
                                <td class="px-3 py-3 font-extrabold text-slate-900">
                                    {{ $u->name }}
                                </td>
                                <td class="px-3 py-3">
                                    @if($u->role === 'ADMIN')
                                        <span class="rounded bg-slate-900 px-2 py-0.5 text-[10px] font-black text-white uppercase">
                                            ADMINISTRADOR
                                        </span>
                                    @else
                                        <span class="rounded bg-blue-100 px-2 py-0.5 text-[10px] font-black text-blue-800 uppercase">
                                            DELEGADO
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-3">
                                    @if($u->role === 'ADMIN')
                                        <span class="text-xs font-bold text-emerald-700">Acceso Total a todos los deportes</span>
                                    @else
                                        <div class="flex flex-wrap gap-1 max-w-md">
                                            @forelse($u->disciplinasAsignadas as $da)
                                                <span class="rounded bg-slate-100 px-2 py-0.5 text-[10px] font-bold text-slate-700">
                                                    {{ $da->nombre }}
                                                </span>
                                            @empty
                                                <span class="text-[11px] text-slate-400 italic">Sin restricciones específicas</span>
                                            @endforelse
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 pl-3 pr-4 text-right">
                                    @if($u->role !== 'ADMIN')
                                        <button
                                            type="button"
                                            @click="modalPermisos = {
                                                open: true,
                                                user: {{ $u->id }},
                                                name: '{{ addslashes($u->name) }}',
                                                disciplinas: {{ Js::from($u->disciplinasAsignadas->pluck('id')->toArray()) }}
                                            }"
                                            class="text-blue-600 hover:text-blue-800 font-bold mr-3 cursor-pointer"
                                        >
                                            Editar Permisos
                                        </button>
                                        <form action="{{ route('admin.delegado.eliminar', $u->id) }}" method="POST" onsubmit="return confirm('¿Eliminar cuenta de este delegado?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold cursor-pointer">
                                                Eliminar
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 6. PESTAÑA: REINICIAR BD -->
        <div x-show="tab === 'reiniciar'" class="rounded-xl border border-rose-200 bg-rose-50/50 p-6 shadow-2xs">
            <h2 class="text-base font-black text-rose-900 mb-2">Restablecer la Base de Datos al Estado Inicial</h2>
            <p class="text-xs text-rose-700 mb-6 max-w-2xl leading-relaxed">
                Esta acción ejecutará las migraciones y seeders con los 14 deportes oficiales del cronograma JEDPA 2026, las delegaciones oficiales, credenciales y fixture programado.
            </p>
            <form action="{{ route('admin.reiniciar') }}" method="POST" onsubmit="return confirm('¿Estás seguro de reiniciar la base de datos al estado oficial? Se restablecerán todos los marcadores.');">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-rose-600 px-5 py-2.5 text-xs font-black uppercase tracking-wider text-white hover:bg-rose-700 transition cursor-pointer shadow-xs">
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M3 21v-5h5"/></svg>
                    <span>Restablecer Base de Datos Oficial JEDPA</span>
                </button>
            </form>
        </div>

    </div>

    <!-- Modal Editar Permisos Granulares de Delegado -->
    <div
        x-show="modalPermisos.open"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-xs"
        @keydown.escape.window="modalPermisos.open = false"
    >
        <div
            @click.outside="modalPermisos.open = false"
            class="relative w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl border border-slate-200"
        >
            <div class="flex items-center justify-between p-4 border-b border-slate-200 bg-slate-50">
                <div>
                    <span class="text-[10px] font-black uppercase text-blue-600 block">Control Granular de Acceso</span>
                    <h3 class="text-base font-black text-slate-900" x-text="'Permisos para: ' + modalPermisos.name"></h3>
                </div>
                <button
                    type="button"
                    @click="modalPermisos.open = false"
                    class="size-8 rounded-lg bg-slate-200 hover:bg-slate-300 text-slate-700 flex items-center justify-center cursor-pointer transition"
                >
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>

            <form :action="'/admin/delegados/' + modalPermisos.user + '/permisos'" method="POST" class="p-6 space-y-4">
                @csrf
                <p class="text-xs text-slate-600">
                    Marca las disciplinas deportivas que este delegado podrá ver y editar sus marcadores:
                </p>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 max-h-64 overflow-y-auto p-2 bg-slate-50 rounded-xl border border-slate-200">
                    @foreach($disciplinas as $d)
                        <label class="inline-flex items-center gap-1.5 text-xs text-slate-700 cursor-pointer">
                            <input
                                type="checkbox"
                                name="disciplinas[]"
                                value="{{ $d->id }}"
                                :checked="modalPermisos.disciplinas.includes('{{ $d->id }}')"
                                class="rounded border-slate-300 text-blue-600 size-4"
                            />
                            <span class="truncate">{{ $d->nombre }}</span>
                        </label>
                    @endforeach
                </div>

                <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                    <button
                        type="button"
                        @click="modalPermisos.open = false"
                        class="rounded-lg bg-slate-100 hover:bg-slate-200 px-4 py-2 text-xs font-bold text-slate-700 cursor-pointer"
                    >
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        class="rounded-lg bg-slate-900 hover:bg-slate-800 px-5 py-2 text-xs font-black uppercase text-white cursor-pointer"
                    >
                        Guardar Permisos Granulares
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
