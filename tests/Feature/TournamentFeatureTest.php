<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TournamentFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_public_pages_load_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('MACROREGIONAL 2026');

        $response = $this->get('/clasificacion');
        $response->assertStatus(200);
        $response->assertSee('Tabla de Posiciones');

        $response = $this->get('/equipos');
        $response->assertStatus(200);
        $response->assertSee('Delegaciones Participantes');

        $response = $this->get('/campeones');
        $response->assertStatus(200);
        $response->assertSee('Cuadro de Campeones');

        $response = $this->get('/entrar');
        $response->assertStatus(200);
        $response->assertSee('Acceso al Sistema');
    }

    public function test_guest_is_redirected_from_protected_routes(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/entrar');

        $response = $this->get('/delegado');
        $response->assertRedirect('/entrar');
    }

    public function test_admin_can_access_admin_portal(): void
    {
        $admin = User::where('username', 'admin')->first();

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Panel de Control Deportivo');
    }

    public function test_delegate_can_access_delegate_portal_but_not_admin(): void
    {
        $delegado = User::where('username', 'delegado_puno')->first();

        $response = $this->actingAs($delegado)->get('/delegado');
        $response->assertStatus(200);
        $response->assertSee('Portal Oficial de Delegado');

        // Intentar ingresar a admin debe redirigir a su portal o dar 403
        $responseAdmin = $this->actingAs($delegado)->get('/admin');
        $responseAdmin->assertRedirect('/delegado');
    }

    public function test_login_as_admin_redirects_to_admin(): void
    {
        $response = $this->post('/entrar', [
            'usuario' => 'admin',
            'clave' => 'drep2026',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticated();
    }

    public function test_login_as_delegate_redirects_to_delegado(): void
    {
        $response = $this->post('/entrar', [
            'usuario' => 'delegado_puno',
            'clave' => 'puno2026',
        ]);

        $response->assertRedirect('/delegado');
        $this->assertAuthenticated();
    }

    public function test_discipline_individual_standings_and_reference_photo_render(): void
    {
        $response = $this->get('/d/futbol-libre');
        $response->assertStatus(200);
        $response->assertSee('Tabla de Posiciones');
        $response->assertSee('Fútbol Libre');

        $responseClasif = $this->get('/clasificacion?deporte=futbol-libre');
        $responseClasif->assertStatus(200);
        $responseClasif->assertSee('Tabla de Posiciones · Fútbol Libre');
    }

    public function test_admin_can_save_deporte_with_reference_photo_and_delegacion_with_logo(): void
    {
        $admin = User::where('username', 'admin')->first();

        // 1. Guardar deporte con foto de referencia
        $respDeporte = $this->actingAs($admin)->post('/admin/deportes', [
            'nombre' => 'Ajedrez Olímpico',
            'categoria' => 'Ajedrez',
            'color_acento' => '#10b981',
            'sede_principal' => 'Auditorio DREP',
            'foto_url' => 'https://example.com/chess.jpg',
            'foto_referencia_url' => 'https://example.com/chess-ref.jpg',
            'descripcion' => 'Torneo de ajedrez rápido ritmo suizo',
        ]);
        $respDeporte->assertSessionHas('success');
        $this->assertDatabaseHas('disciplinas', [
            'slug' => 'ajedrez-olimpico',
            'foto_referencia_url' => 'https://example.com/chess-ref.jpg',
        ]);

        // 2. Guardar delegación con logo
        $respDel = $this->actingAs($admin)->post('/admin/delegaciones', [
            'nombre' => 'UGEL San Antonio de Putina',
            'siglas' => 'Putina Especial',
            'provincia' => 'San Antonio de Putina',
            'logo_url' => 'https://example.com/putina-shield.png',
        ]);
        $respDel->assertSessionHas('success');
        $this->assertDatabaseHas('delegaciones', [
            'siglas' => 'Putina Especial',
            'logo_url' => 'https://example.com/putina-shield.png',
        ]);
    }

    public function test_admin_can_schedule_match_by_discipline(): void
    {
        $admin = User::where('username', 'admin')->first();

        $response = $this->actingAs($admin)->post('/admin/partidos', [
            'disciplina_id' => 'futbol-libre',
            'ronda_numero' => 1,
            'ronda_nombre' => 'Cuartos de Final',
            'local_id' => 'puno',
            'visitante_id' => 'san-roman',
            'horario' => '10:00 AM',
            'cancha' => 'Estadio Principal',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('partidos', [
            'disciplina_id' => 'futbol-libre',
            'ronda_nombre' => 'Cuartos de Final',
            'local_id' => 'puno',
            'visitante_id' => 'san-roman',
        ]);
    }
}
