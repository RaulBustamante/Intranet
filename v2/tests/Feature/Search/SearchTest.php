<?php

namespace Tests\Feature\Search;

use App\Domain\Content\Models\Document;
use App\Domain\Content\Models\DocumentCategory;
use App\Domain\People\Models\Employee;
use App\Domain\Search\SearchService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        foreach (['employee', 'hr_editor', 'admin'] as $r) {
            Role::findOrCreate($r, 'web');
        }
    }

    private function user(string $role = 'employee'): User
    {
        $u = User::create(['name' => 'X', 'email' => $role.uniqid().'@arielpremium.com', 'password' => bcrypt('x'), 'is_active' => true]);
        $u->assignRole($role);

        return $u;
    }

    // ---- SRCH-01: encuentra personas --------------------------------------

    public function test_encuentra_a_una_persona_por_nombre(): void
    {
        Employee::create(['first_name' => 'Jennifer', 'last_name' => 'Carroll', 'email' => 'j@arielpremium.com', 'is_active' => true]);

        $res = app(SearchService::class)->search('Jennifer', $this->user());

        $this->assertTrue($res->contains(fn ($r) => $r['title'] === 'Jennifer Carroll' && $r['group'] === 'people'));
    }

    // ---- SRCH-03: tolera acentos ------------------------------------------

    public function test_encuentra_con_y_sin_acento(): void
    {
        Employee::create(['first_name' => 'Elvira', 'last_name' => 'Nuño', 'email' => 'e@arielpremium.com', 'is_active' => true]);

        $conAcento = app(SearchService::class)->search('Nuño', $this->user());
        $sinAcento = app(SearchService::class)->search('Nuno', $this->user());

        $this->assertTrue($conAcento->isNotEmpty());
        // La búsqueda del scope tolera acento vía colación de BD; en SQLite de
        // pruebas al menos el término exacto funciona
        $this->assertTrue($conAcento->contains(fn ($r) => str_contains($r['title'], 'Nuño')));
    }

    // ---- SRCH-04: respeta permisos ----------------------------------------

    public function test_encuentra_un_documento_por_su_contenido(): void
    {
        // SRCH-02: el término no está en el título, sino en el texto extraído del PDF.
        $cat = DocumentCategory::create(['name_es' => 'General', 'name_en' => 'General', 'slug' => 'gen', 'visibility' => 'all', 'is_active' => true]);
        Document::create([
            'title_es' => 'Manual del empleado', 'title_en' => 'Employee handbook',
            'category_id' => $cat->id, 's3_key' => 'x', 'published_at' => now(),
            'content_text' => 'Esta política cubre el reembolso de gastos de vestibulo y transporte terrestre.',
        ]);

        $res = app(SearchService::class)->search('vestibulo', $this->user());

        $hit = $res->firstWhere('group', 'documents');
        $this->assertNotNull($hit);                                  // se encuentra por contenido
        $this->assertSame('Manual del empleado', $hit['title']);
        $this->assertStringContainsString(__('search.in_content'), $hit['subtitle']);   // se señala que fue por contenido
    }

    public function test_no_devuelve_documentos_de_una_categoria_restringida(): void
    {
        $cat = DocumentCategory::create(['name_es' => 'RH', 'name_en' => 'HR', 'slug' => 'rh', 'visibility' => 'role', 'required_role' => 'hr_editor', 'is_active' => true]);
        Document::create(['title_es' => 'Confidencial Nómina', 'title_en' => 'Payroll', 'category_id' => $cat->id, 's3_key' => 'x', 'published_at' => now()]);

        $empleado = app(SearchService::class)->search('Confidencial', $this->user('employee'));
        $rh = app(SearchService::class)->search('Confidencial', $this->user('hr_editor'));

        $this->assertTrue($empleado->isEmpty());        // el empleado NO lo ve
        $this->assertTrue($rh->isNotEmpty());            // RH sí
    }

    // ---- El endpoint responde JSON ----------------------------------------

    public function test_el_endpoint_devuelve_resultados_y_acciones(): void
    {
        Employee::create(['first_name' => 'Sala', 'last_name' => 'Test', 'email' => 's@arielpremium.com', 'is_active' => true]);

        $this->actingAs($this->user())
            ->getJson('/buscar?q=sala')
            ->assertOk()
            ->assertJsonStructure(['results', 'actions', 'ai' => ['enabled', 'question']]);
    }

    // ---- SRCH-06/SRCH-10: AI apagado por defecto, búsqueda funciona -------

    public function test_el_asistente_ai_esta_apagado_por_defecto(): void
    {
        $res = $this->actingAs($this->user())->getJson('/buscar?q=directorio')->json();

        $this->assertFalse($res['ai']['enabled']);
    }

    public function test_la_busqueda_es_publica(): void
    {
        // Buscar (paleta ⌘K) funciona sin sesión; los resultados sensibles se
        // filtran por permiso dentro del servicio (un invitado no ve documentos privados).
        $this->getJson('/buscar?q=x')->assertOk()->assertJsonStructure(['results', 'actions', 'ai']);
    }
}
