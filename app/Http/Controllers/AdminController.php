<?php

namespace App\Http\Controllers;

use App\Models\Delegacion;
use App\Models\Disciplina;
use App\Models\Partido;
use App\Models\Serie;
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
            'series.partidos.local',
            'series.partidos.visitante',
            'series.partidos.ganador',
        ])->get();
        $delegaciones = Delegacion::orderBy('nombre')->get();
        $usuarios = User::with('delegacion')->orderBy('role')->orderBy('name')->get();

        return view('admin.index', compact('torneo', 'disciplinas', 'delegaciones', 'usuarios'));
    }

    public function actualizarTorneo(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'subtitulo' => ['required', 'string', 'max:255'],
            'organizador' => ['required', 'string', 'max:255'],
            'sede_principal' => ['required', 'string', 'max:255'],
            'logo_url' => ['nullable', 'string', 'max:500'],
            'logo_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:5120'],
            'portada_url' => ['nullable', 'string', 'max:500'],
            'portada_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:10240'],
            'anio' => ['required', 'integer', 'min:2020', 'max:2050'],
            'avance_porcentaje' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        if ($request->hasFile('logo_file')) {
            $file = $request->file('logo_file');
            $fileName = 'torneo_logo_'.time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/torneo'), $fileName);
            $data['logo_url'] = '/uploads/torneo/'.$fileName;
        }

        if ($request->hasFile('portada_file')) {
            $file = $request->file('portada_file');
            $fileName = 'torneo_portada_'.time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/torneo'), $fileName);
            $data['portada_url'] = '/uploads/torneo/'.$fileName;
        }

        unset($data['logo_file'], $data['portada_file']);

        $torneo = Torneo::actual();
        $torneo->update($data);

        return back()->with('success', 'Información general del torneo y portadas actualizadas.');
    }

    public function guardarDeporte(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id' => ['nullable', 'string'],
            'nombre' => ['required', 'string', 'max:100'],
            'categoria' => ['required', 'string', 'max:50'],
            'color_acento' => ['required', 'string', 'max:20'],
            'foto_url' => ['nullable', 'string', 'max:500'],
            'foto_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:5120'],
            'foto_referencia_url' => ['nullable', 'string', 'max:500'],
            'foto_referencia_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:5120'],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'sede_principal' => ['required', 'string', 'max:150'],
        ]);

        $slug = Str::slug($data['nombre']);
        $id = ! empty($data['id']) ? $data['id'] : $slug;

        $fotoUrl = $data['foto_url'] ?? null;
        if ($request->hasFile('foto_file')) {
            $file = $request->file('foto_file');
            $fileName = 'deporte_'.$slug.'_'.time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/disciplinas'), $fileName);
            $fotoUrl = '/uploads/disciplinas/'.$fileName;
        }

        $fotoRefUrl = $data['foto_referencia_url'] ?? null;
        if ($request->hasFile('foto_referencia_file')) {
            $file = $request->file('foto_referencia_file');
            $fileName = 'ref_'.$slug.'_'.time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/disciplinas'), $fileName);
            $fotoRefUrl = '/uploads/disciplinas/'.$fileName;
        }

        $disciplinaExistente = Disciplina::find($id);

        $disciplina = Disciplina::updateOrCreate(
            ['id' => $id],
            [
                'slug' => $slug,
                'nombre' => $data['nombre'],
                'categoria' => $data['categoria'],
                'color_acento' => $data['color_acento'],
                'foto_url' => $fotoUrl ?? ($disciplinaExistente?->foto_url ?? 'https://images.unsplash.com/photo-1508098682722-e99c43a406b2?w=800&auto=format&fit=crop&q=80'),
                'foto_referencia_url' => $fotoRefUrl ?? $disciplinaExistente?->foto_referencia_url,
                'descripcion' => $data['descripcion'] ?? '',
                'sede_principal' => $data['sede_principal'],
            ]
        );

        // Crear al menos Serie A si no tiene series
        if ($disciplina->series()->count() === 0) {
            Serie::create([
                'disciplina_id' => $disciplina->id,
                'letra' => 'A',
                'nombre' => 'Serie A',
                'sede_nombre' => $data['sede_principal'],
            ]);
        }

        return back()->with('success', 'Disciplina deportiva e imágenes guardadas correctamente.');
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
            'logo_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:5120'],
        ]);

        $id = ! empty($data['id']) ? $data['id'] : Str::slug($data['siglas']);

        $logoUrl = $data['logo_url'] ?? null;
        if ($request->hasFile('logo_file')) {
            $file = $request->file('logo_file');
            $fileName = 'logo_'.$id.'_'.time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/logos'), $fileName);
            $logoUrl = '/uploads/logos/'.$fileName;
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
            'serie_id' => ['required', 'integer', 'exists:series,id'],
            'ronda_numero' => ['required', 'integer', 'min:1'],
            'ronda_nombre' => ['required', 'string', 'max:50'],
            'local_id' => ['nullable', 'string', 'exists:delegaciones,id'],
            'visitante_id' => ['nullable', 'string', 'exists:delegaciones,id'],
            'horario' => ['nullable', 'string', 'max:50'],
            'cancha' => ['nullable', 'string', 'max:100'],
        ]);

        $partidoId = 'match-'.Str::random(8);

        Partido::create([
            'id' => $partidoId,
            'serie_id' => $data['serie_id'],
            'ronda_numero' => $data['ronda_numero'],
            'ronda_nombre' => $data['ronda_nombre'],
            'local_id' => $data['local_id'],
            'visitante_id' => $data['visitante_id'],
            'horario' => $data['horario'] ?? '09:00 AM',
            'cancha' => $data['cancha'] ?? 'Cancha Principal',
            'estado' => 'PROGRAMADO',
        ]);

        return back()->with('success', 'Partido programado en el fixture correctamente.');
    }

    public function actualizarMarcador(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'partido_id' => ['required', 'string', 'exists:partidos,id'],
            'local_goles' => ['nullable', 'integer', 'min:0'],
            'visitante_goles' => ['nullable', 'integer', 'min:0'],
            'ganador_id' => ['nullable', 'string', 'exists:delegaciones,id'],
            'observaciones' => ['nullable', 'string', 'max:500'],
        ]);

        $partido = Partido::findOrFail($data['partido_id']);

        $localGoles = $data['local_goles'] !== null ? (int) $data['local_goles'] : null;
        $visitanteGoles = $data['visitante_goles'] !== null ? (int) $data['visitante_goles'] : null;

        $this->tournamentService->actualizarMarcador(
            $partido,
            $localGoles,
            $visitanteGoles,
            $data['ganador_id'] ?? null,
            $data['observaciones'] ?? null
        );

        return back()->with('success', 'Marcador y tabla de posiciones actualizados.');
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
        ], [
            'username.unique' => 'El nombre de usuario ya está registrado en el sistema.',
        ]);

        User::create([
            'username' => $data['username'],
            'name' => $data['name'],
            'email' => $data['username'].'@macroregional.pe',
            'password' => Hash::make($data['clave']),
            'role' => 'DELEGADO',
            'delegacion_id' => $data['delegacion_id'],
            'activo' => true,
        ]);

        return back()->with('success', 'Credenciales de delegado creadas exitosamente.');
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
        Artisan::call('migrate:fresh', [
            '--seed' => true,
            '--force' => true,
        ]);

        return back()->with('success', 'Base de datos reiniciada al estado base inicial con 0 resultados.');
    }
}
