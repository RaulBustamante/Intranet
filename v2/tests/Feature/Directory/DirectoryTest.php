<?php

namespace Tests\Feature\Directory;

use App\Domain\People\Models\Department;
use App\Domain\People\Models\Employee;
use App\Domain\People\Models\Location;
use App\Livewire\Directory\DirectoryIndex;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DirectoryTest extends TestCase
{
    use RefreshDatabase;

    private Location $wc;
    private Location $tw;
    private Department $art;

    protected function setUp(): void
    {
        parent::setUp();

        // El directorio exige sesión (AUTH). Entramos como un empleado normal.
        $this->actingAs(User::create([
            'name' => 'Tester', 'email' => 'tester@arielpremium.com',
            'password' => bcrypt('x'), 'is_active' => true,
        ]));

        $this->wc  = Location::create(['code' => 'WC', 'name_es' => 'West Coast', 'name_en' => 'West Coast']);
        $this->tw  = Location::create(['code' => 'TW', 'name_es' => 'Taiwán', 'name_en' => 'Taiwan']);
        $this->art = Department::create(['name_es' => 'Arte', 'name_en' => 'Art']);
    }

    private function persona(array $attrs = []): Employee
    {
        return Employee::create(array_merge([
            'first_name' => 'Jennifer',
            'last_name'  => 'Carroll',
            'email'      => 'jenniferc@arielpremium.com',
            'extension'  => '1238',
            'location_id'=> $this->wc->id,
            'department_id' => $this->art->id,
        ], $attrs));
    }

    // ---- PPL-09: exportar el directorio filtrado a CSV -------------------

    public function test_exporta_el_directorio_a_csv_respetando_filtros(): void
    {
        $this->persona(['first_name' => 'Jennifer', 'last_name' => 'Carroll', 'email' => 'jc@arielpremium.com']);
        // Otra persona en OTRA sede, para probar que el filtro se respeta
        $this->persona([
            'first_name' => 'Ming', 'last_name' => 'Chen', 'email' => 'ming@arielpremium.com',
            'location_id' => $this->tw->id,
        ]);

        $comp = new DirectoryIndex();
        $comp->locationId = (string) $this->wc->id;   // solo West Coast

        $response = $comp->export();
        ob_start();
        $response->sendContent();
        $csv = ob_get_clean();

        $this->assertStringContainsString('Jennifer', $csv);
        $this->assertStringContainsString('Carroll', $csv);
        $this->assertStringNotContainsString('Ming', $csv);        // filtrado fuera
        $this->assertStringContainsString(__('app.directory.email'), $csv);   // cabecera
    }

    // ---- PPL-04: búsqueda ------------------------------------------------

    public function test_el_directorio_carga_y_lista_a_la_gente(): void
    {
        $this->persona();

        $this->get(route('directory'))
            ->assertOk()
            ->assertSee('Jennifer Carroll');
    }

    public function test_busca_mientras_se_escribe(): void
    {
        $this->persona();
        $this->persona(['first_name' => 'Sandra', 'last_name' => 'Guevara', 'email' => 'sandrag@arielpremium.com', 'extension' => '3561']);

        Livewire::test(DirectoryIndex::class)
            ->set('search', 'Sandra')
            ->assertSee('Sandra Guevara')
            ->assertDontSee('Jennifer Carroll');
    }

    public function test_busca_por_extension_y_por_correo(): void
    {
        $this->persona();

        foreach (['1238', 'jenniferc'] as $termino) {
            Livewire::test(DirectoryIndex::class)
                ->set('search', $termino)
                ->assertSee('Jennifer Carroll');
        }
    }

    public function test_filtra_por_sede(): void
    {
        $this->persona();
        $this->persona(['first_name' => 'Miles', 'last_name' => 'Lin', 'email' => 'milesl@arielpremium.com', 'location_id' => $this->tw->id]);

        Livewire::test(DirectoryIndex::class)
            ->set('locationId', (string) $this->tw->id)
            ->assertSee('Miles Lin')
            ->assertDontSee('Jennifer Carroll');
    }

    public function test_muestra_estado_vacio_cuando_no_encuentra_a_nadie(): void
    {
        $this->persona();

        Livewire::test(DirectoryIndex::class)
            ->set('search', 'zzzzzz')
            ->assertDontSee('Jennifer Carroll')
            ->assertSee(__('app.states.no_results_hint'));
    }

    public function test_no_muestra_a_quien_esta_dado_de_baja(): void
    {
        $e = $this->persona();
        $e->update(['is_active' => false]);

        Livewire::test(DirectoryIndex::class)->assertDontSee('Jennifer Carroll');
    }

    // ---- PPL-05: recuerda la vista elegida --------------------------------

    public function test_alterna_entre_tarjetas_y_tabla(): void
    {
        $this->persona();

        Livewire::test(DirectoryIndex::class)
            ->assertSet('view', 'cards')
            ->call('setView', 'table')
            ->assertSet('view', 'table')
            ->call('setView', 'cualquier-cosa')
            ->assertSet('view', 'cards');       // valor inválido cae al seguro
    }

    // ---- PPL-06 y PPL-07: ficha y organigrama -----------------------------

    public function test_la_ficha_muestra_los_datos_de_contacto(): void
    {
        $e = $this->persona();

        $this->get(route('directory.show', $e))
            ->assertOk()
            ->assertSee('Jennifer Carroll')
            ->assertSee('jenniferc@arielpremium.com')
            ->assertSee('1238');
    }

    public function test_la_ficha_muestra_al_jefe_y_al_equipo_directo(): void
    {
        $jefa = $this->persona(['first_name' => 'Jackie', 'last_name' => 'Dillard', 'email' => 'jackied@arielpremium.com']);
        $sub1 = $this->persona(['manager_id' => $jefa->id]);
        $sub2 = $this->persona(['first_name' => 'Kimberly', 'last_name' => 'OConnor', 'email' => 'kim@arielpremium.com', 'manager_id' => $jefa->id]);

        // Desde la ficha de la jefa se ve su equipo
        $this->get(route('directory.show', $jefa))
            ->assertOk()
            ->assertSee('Jennifer Carroll')
            ->assertSee('Kimberly OConnor')
            ->assertSee(__('app.directory.team_count', ['count' => 2]));

        // Y desde la del subordinado se ve a su jefa
        $this->get(route('directory.show', $sub1))
            ->assertOk()
            ->assertSee('Jackie Dillard');
    }

    public function test_la_ficha_no_revela_el_ano_de_nacimiento(): void
    {
        $e = $this->persona(['birth_month' => 3, 'birth_day' => 14]);

        $html = $this->get(route('directory.show', $e))->assertOk()->getContent();

        // Muestra el día y el mes...
        $this->assertStringContainsString('14', $html);
        // ...y no hay ningún año de nacimiento porque la columna no existe
        $this->assertNotContains('birth_year', \Schema::getColumnListing('employees'));
    }

    public function test_usa_el_apodo_cuando_existe(): void
    {
        $e = $this->persona(['preferred_name' => 'Jenny']);

        $this->get(route('directory.show', $e))
            ->assertOk()
            ->assertSee('Jenny Carroll')     // nombre para mostrar
            ->assertSee('Jennifer Carroll'); // y el completo, como aclaración
    }
}
