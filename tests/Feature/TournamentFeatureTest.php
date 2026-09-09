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
}
