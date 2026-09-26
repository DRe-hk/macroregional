<?php

namespace Tests\Feature;

use App\Models\Disciplina;
use App\Models\Partido;
use App\Models\Torneo;
use App\Models\User;
use App\Services\TournamentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
        $response->assertSee('JEDPA 2026');

        $response = $this->get('/clasificacion');
        $response->assertStatus(200);
        $response->assertSee('Tabla de Posiciones');

        $response = $this->get('/clasificacion?deporte=futbol');
        $response->assertStatus(200);
        $response->assertSee('Subcategoría:');
        $response->assertSee('Fútbol Cat. B Damas');

        $response = $this->get('/clasificacion?deporte=futbol&sub=futbol-b-varones');
        $response->assertStatus(200);
        $response->assertSee('Fútbol Cat. B Varones');

        $response = $this->get('/equipos');
        $response->assertStatus(200);
        $response->assertSee('Delegaciones Participantes');

        $response = $this->get('/campeones');
        $response->assertStatus(200);
        $response->assertSee('Cuadro Oficial de Campeones');

        $response = $this->get('/entrar');
        $response->assertStatus(200);
        $response->assertSee('Acceso al Sistema');

        $response = $this->get('/d/futbol');
        $response->assertStatus(200);
        $response->assertSee('Fútbol');

        $response = $this->get('/d/natacion');
        $response->assertStatus(200);
        $response->assertSee('Natación');
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
        $delegado = User::where('username', 'delegado.futbol')->first();

        $response = $this->actingAs($delegado)->get('/delegado');
        $response->assertStatus(200);
        $response->assertSee('Portal Oficial de Delegado');

        // Intentar ingresar a admin debe redirigir a su portal
        $responseAdmin = $this->actingAs($delegado)->get('/admin');
        $responseAdmin->assertRedirect('/delegado');
    }

    public function test_login_as_admin_redirects_to_admin(): void
    {
        $response = $this->post('/entrar', [
            'usuario' => 'admin',
            'clave' => 'admin123',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticated();
    }

    public function test_login_as_delegate_redirects_to_delegado(): void
    {
        $response = $this->post('/entrar', [
            'usuario' => 'delegado.futbol',
            'clave' => '123456',
        ]);

        $response->assertRedirect('/delegado');
        $this->assertAuthenticated();
    }

    public function test_cuadro_campeones_funciona_bien(): void
    {
        // 1. Verificar que deportes individuales muestren su podio oficial y campeones
        $response = $this->get('/campeones');
        $response->assertStatus(200);
        $response->assertSee('Natación');
        $response->assertSee('Atletismo');
        $response->assertSee('DRE Puno (Anfitrión)');
        $response->assertSee('DRE Arequipa');

        // 2. Simular final de un deporte colectivo
        $service = app(TournamentService::class);
        $partidoFinal = Partido::where('disciplina_id', 'futbol-b-varones')
            ->where('ronda_nombre', 'Gran Final')
            ->first();

        $this->assertNotNull($partidoFinal);

        // Actualizar marcador de la final: DRE Puno 2 - 1 DRE Cusco
        $service->actualizarMarcador($partidoFinal, 2, 1, 'dre-puno', 'Final jugada en UNA Puno');

        // La disciplina 'futbol-b-varones' debe coronar a DRE Puno como campeon_actual
        $disciplina = Disciplina::find('futbol-b-varones');
        $this->assertEquals('DRE Puno (Anfitrión)', $disciplina->campeon_actual);

        // Comprobar que en la vista de campeones aparezca el campeón
        $responseAfter = $this->get('/campeones');
        $responseAfter->assertStatus(200);
        $responseAfter->assertSee('DRE Puno (Anfitrión)');
    }

    public function test_reglas_puntuacion_especificas_por_deporte(): void
    {
        $service = app(TournamentService::class);

        // FUTBOL: Victoria = 3, Empate = 1, Derrota = 0, WO = 0
        $partFutVictoria = new Partido(['local_goles' => 2, 'visitante_goles' => 0, 'es_wo' => false]);
        $ptsFutVictoria = $service->calcularPuntosPartido($partFutVictoria, 'FUTBOL');
        $this->assertEquals(3, $ptsFutVictoria['local_pts']);
        $this->assertEquals(0, $ptsFutVictoria['vis_pts']);

        $partFutEmpate = new Partido(['local_goles' => 1, 'visitante_goles' => 1, 'es_wo' => false]);
        $ptsFutEmpate = $service->calcularPuntosPartido($partFutEmpate, 'FUTBOL');
        $this->assertEquals(1, $ptsFutEmpate['local_pts']);
        $this->assertEquals(1, $ptsFutEmpate['vis_pts']);

        $partFutWO = new Partido(['local_goles' => 3, 'visitante_goles' => 0, 'es_wo' => true]);
        $ptsFutWO = $service->calcularPuntosPartido($partFutWO, 'FUTBOL');
        $this->assertEquals(3, $ptsFutWO['local_pts']);
        $this->assertEquals(0, $ptsFutWO['vis_pts']);

        // BASQUET: Victoria = 2, Derrota = 1, WO = 0
        $partBasqVictoria = new Partido(['local_goles' => 65, 'visitante_goles' => 60, 'es_wo' => false]);
        $ptsBasqVictoria = $service->calcularPuntosPartido($partBasqVictoria, 'BASQUET');
        $this->assertEquals(2, $ptsBasqVictoria['local_pts']);
        $this->assertEquals(1, $ptsBasqVictoria['vis_pts']);

        $partBasqWO = new Partido(['local_goles' => 20, 'visitante_goles' => 0, 'es_wo' => true]);
        $ptsBasqWO = $service->calcularPuntosPartido($partBasqWO, 'BASQUET');
        $this->assertEquals(2, $ptsBasqWO['local_pts']);
        $this->assertEquals(0, $ptsBasqWO['vis_pts']);

        // HANDBALL: Victoria = 2, Empate = 1, Derrota = 0, WO = -2
        $partHandEmpate = new Partido(['local_goles' => 22, 'visitante_goles' => 22, 'es_wo' => false]);
        $ptsHandEmpate = $service->calcularPuntosPartido($partHandEmpate, 'HANDBALL');
        $this->assertEquals(1, $ptsHandEmpate['local_pts']);
        $this->assertEquals(1, $ptsHandEmpate['vis_pts']);

        $partHandWO = new Partido(['local_goles' => 10, 'visitante_goles' => 0, 'es_wo' => true]);
        $ptsHandWO = $service->calcularPuntosPartido($partHandWO, 'HANDBALL');
        $this->assertEquals(2, $ptsHandWO['local_pts']);
        $this->assertEquals(-2, $ptsHandWO['vis_pts']);

        // VOLEIBOL: 2-0 = 3 pts / 0 pts; 2-1 = 2 pts / 1 pt
        $partVol20 = new Partido(['local_goles' => 2, 'visitante_goles' => 0, 'es_wo' => false]);
        $ptsVol20 = $service->calcularPuntosPartido($partVol20, 'VOLEIBOL');
        $this->assertEquals(3, $ptsVol20['local_pts']);
        $this->assertEquals(0, $ptsVol20['vis_pts']);

        $partVol21 = new Partido(['local_goles' => 2, 'visitante_goles' => 1, 'es_wo' => false]);
        $ptsVol21 = $service->calcularPuntosPartido($partVol21, 'VOLEIBOL');
        $this->assertEquals(2, $ptsVol21['local_pts']);
        $this->assertEquals(1, $ptsVol21['vis_pts']);

        $partVol12 = new Partido(['local_goles' => 1, 'visitante_goles' => 2, 'es_wo' => false]);
        $ptsVol12 = $service->calcularPuntosPartido($partVol12, 'VOLEIBOL');
        $this->assertEquals(1, $ptsVol12['local_pts']);
        $this->assertEquals(2, $ptsVol12['vis_pts']);
    }

    public function test_deportes_individuales_sin_partidos(): void
    {
        // Natación y Atletismo son individuales
        $natacion = Disciplina::find('natacion');
        $this->assertTrue($natacion->esIndividual());

        $atletismo = Disciplina::find('atletismo');
        $this->assertTrue($atletismo->esIndividual());

        // La vista de natación no debe tener fixtures/partidos sino ficha técnica y podio
        $response = $this->get('/d/natacion');
        $response->assertStatus(200);
        $response->assertSee('Podio y Ganadores Oficiales');
        $response->assertSee('Medalla de Oro / Ganador');
        $response->assertSee('Piscina Municipal de Puno');
    }

    public function test_control_acceso_granular_delegado(): void
    {
        $delegadoFutbol = User::where('username', 'delegado.futbol')->first();
        $partidoFutbol = Partido::where('disciplina_id', 'futbol-b-varones')->first();
        $partidoBasquet = Partido::where('disciplina_id', 'basquet-b-varones')->first();

        // 1. Delegado de fútbol TIENE permiso para editar el partido de fútbol
        $this->assertTrue($delegadoFutbol->puedeEditarPartido($partidoFutbol));

        $responseOk = $this->actingAs($delegadoFutbol)->post('/delegado/marcador', [
            'partido_id' => $partidoFutbol->id,
            'local_goles' => 2,
            'visitante_goles' => 0,
            'observaciones' => 'Resultado registrado por delegado',
        ]);
        $responseOk->assertSessionHas('success');

        // 2. Delegado de fútbol NO TIENE permiso para editar el partido de básquet -> 403 Forbidden
        $this->assertFalse($delegadoFutbol->puedeEditarPartido($partidoBasquet));

        $responseForbidden = $this->actingAs($delegadoFutbol)->post('/delegado/marcador', [
            'partido_id' => $partidoBasquet->id,
            'local_goles' => 50,
            'visitante_goles' => 40,
        ]);
        $responseForbidden->assertStatus(403);
    }

    public function test_subida_evidencia_partido(): void
    {
        Storage::fake('public');

        $delegadoFutbol = User::where('username', 'delegado.futbol')->first();
        $partidoFutbol = Partido::where('disciplina_id', 'futbol-b-varones')->first();

        $fakeImage = UploadedFile::fake()->image('acta_oficial.jpg', 600, 800);

        $response = $this->actingAs($delegadoFutbol)->post('/delegado/marcador', [
            'partido_id' => $partidoFutbol->id,
            'local_goles' => 3,
            'visitante_goles' => 2,
            'evidencia_file' => $fakeImage,
            'observaciones' => 'Acta firmada por árbitros',
        ]);

        $response->assertSessionHas('success');

        $partidoActualizado = Partido::find($partidoFutbol->id);
        $this->assertNotNull($partidoActualizado->foto_evidencia);
        $this->assertEquals('FINALIZADO', $partidoActualizado->estado);

        // Comprobar que en la vista pública de fútbol se muestre el botón de acta
        $responsePublic = $this->get('/d/futbol?sub=futbol-b-varones');
        $responsePublic->assertStatus(200);
        $responsePublic->assertSee('Ver Acta Oficial / Evidencia');
    }

    public function test_tabla_posiciones_puntos_reales(): void
    {
        $service = app(TournamentService::class);
        $disciplina = Disciplina::find('futbol-b-varones');
        $tabla = $service->obtenerTablaPorDisciplina($disciplina);

        $this->assertIsArray($tabla);
        $this->assertNotEmpty($tabla);

        // Las posiciones deben estar ordenadas descendentemente por puntos reales
        $primerEquipo = $tabla[0];
        $this->assertArrayHasKey('puntos', $primerEquipo);
        $this->assertArrayHasKey('dg', $primerEquipo);

        if (count($tabla) > 1) {
            $this->assertGreaterThanOrEqual($tabla[1]['puntos'], $primerEquipo['puntos']);
        }
    }

    public function test_admin_can_manage_branding_and_carousel(): void
    {
        $admin = User::where('username', 'admin')->first();

        // 1. Actualizar branding (logo texto, subtexto, footer)
        $responseTorneo = $this->actingAs($admin)->post('/admin/torneo', [
            'nombre' => 'JUEGOS ESCOLARES MACROREGIONALES PUNO 2026',
            'subtitulo' => 'Etapa Macroregional Sur',
            'organizador' => 'Dirección Regional de Educación Puno',
            'sede_principal' => 'Puno, Perú',
            'logo_texto' => 'JEDPA PUNO 2026',
            'logo_subtexto' => 'Etapa Macroregional Sur',
            'footer_texto' => 'Dirección Regional de Educación Puno - Oficina de Informática',
            'anio' => 2026,
            'avance_porcentaje' => 35,
        ]);

        $responseTorneo->assertSessionHas('success');
        $this->assertDatabaseHas('torneos', [
            'logo_texto' => 'JEDPA PUNO 2026',
            'logo_subtexto' => 'Etapa Macroregional Sur',
            'footer_texto' => 'Dirección Regional de Educación Puno - Oficina de Informática',
        ]);

        // 2. Agregar un slide al carrusel
        $responseSlide = $this->actingAs($admin)->post('/admin/torneo/carrusel', [
            'titulo' => 'Gran Inauguración Torres Belón',
            'subtitulo' => 'Desfile de las 8 delegaciones del sur del Perú',
            'imagen_url' => 'https://example.com/inauguracion.jpg',
        ]);

        $responseSlide->assertSessionHas('success');
        $torneo = Torneo::first();
        $this->assertNotEmpty($torneo->carrusel_slides);
    }
}
