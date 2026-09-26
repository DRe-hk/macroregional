<?php

namespace App\Http\Controllers;

use App\Models\Delegacion;
use App\Models\Disciplina;
use App\Models\Partido;
use App\Models\Torneo;
use App\Models\User;
use App\Services\TournamentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct(protected TournamentService $tournamentService) {}

    public function index(): View
    {
        $torneo = Torneo::actual();
        $disciplinas = Disciplina::with([
            'parent',
            'subcategorias',
            'partidos.local',
            'partidos.visitante',
            'partidos.ganador',
        ])->get();

        $deportesPrincipales = $disciplinas->whereNull('parent_id');
        $delegaciones = Delegacion::orderBy('nombre')->get();
        $usuarios = User::with(['delegacion', 'disciplinasAsignadas'])->orderBy('role')->orderBy('name')->get();

        return view('admin.index', compact('torneo', 'disciplinas', 'deportesPrincipales', 'delegaciones', 'usuarios'));
    }

    public function actualizarTorneo(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'subtitulo' => ['required', 'string', 'max:255'],
            'organizador' => ['required', 'string', 'max:255'],
            'sede_principal' => ['required', 'string', 'max:255'],
            'logo_texto' => ['required', 'string', 'max:100'],
            'logo_subtexto' => ['nullable', 'string', 'max:150'],
            'footer_texto' => ['nullable', 'string', 'max:500'],
            'logo_url' => ['nullable', 'string', 'max:500'],
            'logo_file' => ['nullable', 'file', 'mimes:jpeg,png,jpg,webp,avif', 'max:5120'],
            'portada_url' => ['nullable', 'string', 'max:500'],
            'portada_file' => ['nullable', 'file', 'mimes:jpeg,png,jpg,webp,avif', 'max:10240'],
            'anio' => ['required', 'integer', 'min:2020', 'max:2050'],
            'avance_porcentaje' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        if ($request->hasFile('logo_file')) {
            $data['logo_url'] = $this->almacenarImagenSegura($request->file('logo_file'), 'torneo', 'logo');
        }

        if ($request->hasFile('portada_file')) {
            $data['portada_url'] = $this->almacenarImagenSegura($request->file('portada_file'), 'torneo', 'portada');
        }

        unset($data['logo_file'], $data['portada_file']);

        $torneo = Torneo::actual();
        $torneo->update($data);

        return back()->with('success', 'Configuración institucional del torneo, logo y footer actualizados.');
    }

    public function guardarSlideCarrusel(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'titulo' => ['required', 'string', 'max:150'],
            'subtitulo' => ['nullable', 'string', 'max:255'],
            'imagen_url' => ['nullable', 'string', 'max:500'],
            'imagen_file' => ['nullable', 'file', 'mimes:jpeg,png,jpg,webp,avif', 'max:10240'],
        ]);

        $imagenUrl = $data['imagen_url'] ?? null;
        if ($request->hasFile('imagen_file')) {
            $imagenUrl = $this->almacenarImagenSegura($request->file('imagen_file'), 'torneo', 'slide');
        }

        if (! $imagenUrl) {
            return back()->with('error', 'Debes proporcionar una URL o subir un archivo de imagen para el slide.');
        }

        $torneo = Torneo::actual();
        $slides = $torneo->getSlides();

        $slides[] = [
            'imagen' => $imagenUrl,
            'titulo' => $data['titulo'],
            'subtitulo' => $data['subtitulo'] ?? '',
        ];

        $torneo->update(['carrusel_slides' => $slides]);

        return back()->with('success', 'Slide añadido al carrusel Hero con éxito.');
    }

    public function eliminarSlideCarrusel(int $index): RedirectResponse
    {
        $torneo = Torneo::actual();
        $slides = $torneo->getSlides();

        if (isset($slides[$index])) {
            array_splice($slides, $index, 1);
            $torneo->update(['carrusel_slides' => $slides]);
        }

        return back()->with('success', 'Slide eliminado del carrusel.');
    }

    public function guardarDeporte(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'string', 'exists:disciplinas,id'],
            'nombre' => ['required', 'string', 'max:100'],
            'categoria' => ['nullable', 'string', 'max:50'],
            'genero' => ['nullable', 'string', 'max:30'],
            'tipo' => ['required', 'string', 'in:COLECTIVO,INDIVIDUAL'],
            'sistema_puntuacion' => ['required', 'string', 'in:FUTBOL,FUTSAL,BASQUET,HANDBALL,VOLEIBOL,VOLEY_PLAYA,INDIVIDUAL'],
            'color_acento' => ['required', 'string', 'max:20'],
            'foto_url' => ['nullable', 'string', 'max:500'],
            'foto_file' => ['nullable', 'file', 'mimes:jpeg,png,jpg,webp,avif', 'max:5120'],
            'foto_referencia_url' => ['nullable', 'string', 'max:500'],
            'foto_referencia_file' => ['nullable', 'file', 'mimes:jpeg,png,jpg,webp,avif', 'max:5120'],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'sede_principal' => ['required', 'string', 'max:150'],
            'fechas_cronograma' => ['nullable', 'string', 'max:150'],
            'horario_cronograma' => ['nullable', 'string', 'max:100'],
        ]);

        $disciplinaExistente = ! empty($data['id']) ? Disciplina::find($data['id']) : null;

        if ($disciplinaExistente) {
            $id = $disciplinaExistente->id;
            $slug = $disciplinaExistente->slug;
        } else {
            $slugBase = ($data['parent_id'] ? $data['parent_id'].'-' : '').$data['nombre'].(! empty($data['categoria']) ? '-'.$data['categoria'] : '').(! empty($data['genero']) ? '-'.$data['genero'] : '');
            $slug = Str::slug($slugBase);
            $id = ! empty($data['id']) ? $data['id'] : $slug;
        }

        $fotoUrl = $data['foto_url'] ?? null;
        if ($request->hasFile('foto_file')) {
            $fotoUrl = $this->almacenarImagenSegura($request->file('foto_file'), 'disciplinas', 'deporte_'.$slug);
        }

        $fotoRefUrl = $data['foto_referencia_url'] ?? null;
        if ($request->hasFile('foto_referencia_file')) {
            $fotoRefUrl = $this->almacenarImagenSegura($request->file('foto_referencia_file'), 'disciplinas', 'ref_'.$slug);
        }

        Disciplina::updateOrCreate(
            ['id' => $id],
            [
                'parent_id' => $data['parent_id'] ?: null,
                'slug' => $slug,
                'nombre' => $data['nombre'],
                'categoria' => $data['categoria'] ?? null,
                'genero' => $data['genero'] ?? null,
                'tipo' => $data['tipo'],
                'sistema_puntuacion' => $data['sistema_puntuacion'],
                'color_acento' => $data['color_acento'],
                'foto_url' => $fotoUrl ?? ($disciplinaExistente?->foto_url ?? 'https://images.unsplash.com/photo-1508098682722-e99c43a406b2?w=800&auto=format&fit=crop&q=80'),
                'foto_referencia_url' => $fotoRefUrl ?? $disciplinaExistente?->foto_referencia_url,
                'descripcion' => $data['descripcion'] ?? '',
                'sede_principal' => $data['sede_principal'],
                'fechas_cronograma' => $data['fechas_cronograma'] ?? null,
                'horario_cronograma' => $data['horario_cronograma'] ?? null,
            ]
        );

        return back()->with('success', 'Disciplina deportiva guardada correctamente.');
    }

    public function guardarPodioIndividual(Request $request, string $id): RedirectResponse
    {
        $disciplina = Disciplina::findOrFail($id);

        $data = $request->validate([
            'oro_delegacion' => ['required', 'string', 'max:150'],
            'oro_atleta' => ['required', 'string', 'max:150'],
            'oro_marca' => ['nullable', 'string', 'max:100'],
            'plata_delegacion' => ['nullable', 'string', 'max:150'],
            'plata_atleta' => ['nullable', 'string', 'max:150'],
            'plata_marca' => ['nullable', 'string', 'max:100'],
            'bronce_delegacion' => ['nullable', 'string', 'max:150'],
            'bronce_atleta' => ['nullable', 'string', 'max:150'],
            'bronce_marca' => ['nullable', 'string', 'max:100'],
        ]);

        $podio = [
            'oro' => [
                'delegacion' => $data['oro_delegacion'],
                'atleta' => $data['oro_atleta'],
                'marca' => $data['oro_marca'] ?? '',
            ],
        ];

        if (! empty($data['plata_delegacion'])) {
            $podio['plata'] = [
                'delegacion' => $data['plata_delegacion'],
                'atleta' => $data['plata_atleta'] ?? '',
                'marca' => $data['plata_marca'] ?? '',
            ];
        }

        if (! empty($data['bronce_delegacion'])) {
            $podio['bronce'] = [
                'delegacion' => $data['bronce_delegacion'],
                'atleta' => $data['bronce_atleta'] ?? '',
                'marca' => $data['bronce_marca'] ?? '',
            ];
        }

        $this->tournamentService->actualizarPodioIndividual($disciplina, $podio);

        return back()->with('success', 'Ganador y podio oficial registrados para '.$disciplina->nombre);
    }

    public function eliminarDeporte(string $id): RedirectResponse
    {
        $disciplina = Disciplina::findOrFail($id);
        $disciplina->delete();

        return back()->with('success', 'Disciplina deportiva eliminada.');
    }

    public function guardarDelegacion(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id' => ['nullable', 'string'],
            'nombre' => ['required', 'string', 'max:100'],
            'siglas' => ['required', 'string', 'max:30'],
            'provincia' => ['required', 'string', 'max:100'],
            'logo_url' => ['nullable', 'string', 'max:500'],
            'logo_file' => ['nullable', 'file', 'mimes:jpeg,png,jpg,webp,avif', 'max:5120'],
        ]);

        $id = ! empty($data['id']) ? $data['id'] : Str::slug($data['siglas']);

        $logoUrl = $data['logo_url'] ?? null;
        if ($request->hasFile('logo_file')) {
            $logoUrl = $this->almacenarImagenSegura($request->file('logo_file'), 'logos', 'logo_'.$id);
        }

        $delExistente = Delegacion::find($id);

        Delegacion::updateOrCreate(
            ['id' => $id],
            [
                'nombre' => $data['nombre'],
                'siglas' => $data['siglas'],
                'provincia' => $data['provincia'],
                'logo_url' => $logoUrl ?? $delExistente?->logo_url,
            ]
        );

        return back()->with('success', 'Delegación y logotipo guardados con éxito.');
    }

    public function eliminarDelegacion(string $id): RedirectResponse
    {
        $del = Delegacion::findOrFail($id);
        $del->delete();

        return back()->with('success', 'Delegación eliminada.');
    }

    public function guardarPartido(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'disciplina_id' => ['required', 'string', 'exists:disciplinas,id'],
            'ronda_numero' => ['required', 'integer', 'min:1'],
            'ronda_nombre' => ['required', 'string', 'max:50'],
            'local_id' => ['nullable', 'string', 'exists:delegaciones,id'],
            'visitante_id' => ['nullable', 'string', 'exists:delegaciones,id'],
            'fecha' => ['nullable', 'date'],
            'horario' => ['nullable', 'string', 'max:50'],
            'cancha' => ['nullable', 'string', 'max:100'],
        ]);

        $partidoId = 'match-'.Str::random(8);

        Partido::create([
            'id' => $partidoId,
            'disciplina_id' => $data['disciplina_id'],
            'ronda_numero' => $data['ronda_numero'],
            'ronda_nombre' => $data['ronda_nombre'],
            'local_id' => $data['local_id'],
            'visitante_id' => $data['visitante_id'],
            'fecha' => $data['fecha'] ?? null,
            'horario' => $data['horario'] ?? '09:00 AM',
            'cancha' => $data['cancha'] ?? 'Cancha Principal',
            'estado' => 'PROGRAMADO',
        ]);

        return back()->with('success', 'Partido programado en el fixture correctamente con fecha y hora.');
    }

    public function actualizarMarcador(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'partido_id' => ['required', 'string', 'exists:partidos,id'],
            'local_goles' => ['nullable', 'integer', 'min:0'],
            'visitante_goles' => ['nullable', 'integer', 'min:0'],
            'ganador_id' => ['nullable', 'string', 'exists:delegaciones,id'],
            'es_wo' => ['nullable', 'boolean'],
            'evidencia_file' => ['nullable', 'file', 'mimes:jpeg,png,jpg,webp,avif', 'max:10240'],
            'observaciones' => ['nullable', 'string', 'max:500'],
        ]);

        $partido = Partido::findOrFail($data['partido_id']);

        $fotoEvidencia = null;
        if ($request->hasFile('evidencia_file')) {
            $fotoEvidencia = $this->almacenarImagenSegura($request->file('evidencia_file'), 'evidencias', 'acta_'.$partido->id);
        }

        $localGoles = $data['local_goles'] !== null ? (int) $data['local_goles'] : null;
        $visitanteGoles = $data['visitante_goles'] !== null ? (int) $data['visitante_goles'] : null;

        $this->tournamentService->actualizarMarcador(
            $partido,
            $localGoles,
            $visitanteGoles,
            $data['ganador_id'] ?? null,
            $data['observaciones'] ?? null,
            (bool) ($data['es_wo'] ?? false),
            $fotoEvidencia
        );

        return back()->with('success', 'Marcador, evidencia fotográfica y tabla de posiciones actualizados.');
    }

    public function eliminarPartido(string $id): RedirectResponse
    {
        $partido = Partido::findOrFail($id);
        $partido->delete();

        $this->tournamentService->recalcularTablaPosiciones();

        return back()->with('success', 'Partido eliminado del fixture.');
    }

    public function guardarDelegado(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:users,username'],
            'name' => ['required', 'string', 'max:150'],
            'clave' => ['required', 'string', 'min:4'],
            'delegacion_id' => ['required', 'string', 'exists:delegaciones,id'],
            'disciplinas' => ['nullable', 'array'],
            'disciplinas.*' => ['string', 'exists:disciplinas,id'],
        ], [
            'username.unique' => 'El nombre de usuario ya está registrado en el sistema.',
        ]);

        $user = User::create([
            'username' => $data['username'],
            'name' => $data['name'],
            'email' => $data['username'].'@macroregional.pe',
            'password' => Hash::make($data['clave']),
            'role' => 'DELEGADO',
            'delegacion_id' => $data['delegacion_id'],
            'activo' => true,
        ]);

        if (! empty($data['disciplinas'])) {
            $user->disciplinasAsignadas()->sync($data['disciplinas']);
        }

        return back()->with('success', 'Credenciales y permisos de delegado configurados exitosamente.');
    }

    public function actualizarPermisosDelegado(Request $request, int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'disciplinas' => ['nullable', 'array'],
            'disciplinas.*' => ['string', 'exists:disciplinas,id'],
        ]);

        $user->disciplinasAsignadas()->sync($data['disciplinas'] ?? []);

        return back()->with('success', 'Permisos granulares del delegado actualizados.');
    }

    public function eliminarDelegado(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        if ($user->role === 'ADMIN') {
            return back()->with('error', 'No se puede eliminar la cuenta principal de administrador.');
        }

        $user->delete();

        return back()->with('success', 'Cuenta de delegado eliminada.');
    }

    public function reiniciarBd(): RedirectResponse
    {
        if (app()->environment('production')) {
            return back()->with('error', 'El reinicio total de la base de datos está inhabilitado en entorno de producción por medidas de seguridad.');
        }

        Artisan::call('migrate:fresh', [
            '--seed' => true,
            '--force' => true,
        ]);

        return back()->with('success', 'Base de datos reiniciada al estado base inicial con 0 resultados.');
    }

    /**
     * Valida y almacena de forma segura un archivo de imagen en uploads.
     */
    public function almacenarImagenSegura(mixed $file, string $subdirectorio, string $prefijo): string
    {
        $extension = strtolower($file->extension() ?: $file->guessExtension() ?: 'jpg');
        $permitidas = ['jpeg', 'jpg', 'png', 'webp', 'avif'];

        if (! in_array($extension, $permitidas, true)) {
            abort(422, 'Formato de imagen no permitido. Solo se aceptan JPG, PNG, WEBP o AVIF.');
        }

        $nombreArchivo = $prefijo.'_'.bin2hex(random_bytes(8)).'.'.$extension;
        $destino = public_path('uploads/'.$subdirectorio);

        if (! file_exists($destino)) {
            mkdir($destino, 0755, true);
        }

        $file->move($destino, $nombreArchivo);

        return '/uploads/'.$subdirectorio.'/'.$nombreArchivo;
    }
}
