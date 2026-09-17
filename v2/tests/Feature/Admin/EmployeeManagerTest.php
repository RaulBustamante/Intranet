<?php

namespace Tests\Feature\Admin;

use App\Domain\People\Models\Department;
use App\Domain\People\Models\Employee;
use App\Domain\People\Models\Location;
use App\Livewire\Admin\EmployeeManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Panel de RH — pruebas de los requisitos que definen "listo".
 *
 * Nota: el plan decía Pest, pero Pest 5 exige PHPUnit 13 y Laravel 13 fija
 * PHPUnit ^12.5. No vale la pena forzar la resolución de dependencias por
 * azúcar sintáctica: PHPUnit prueba exactamente lo mismo.
 */
class EmployeeManagerTest extends TestCase
{
    use RefreshDatabase;

    private Location $sede;
    private Department $depto;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sede  = Location::create(['code' => 'WC', 'name_es' => 'West Coast', 'name_en' => 'West Coast']);
        $this->depto = Department::create(['name_es' => 'Art', 'name_en' => 'Art']);
    }

    // ---- HRADM-01: alta, edición y baja sin tocar código -----------------

    public function test_da_de_alta_un_empleado_y_aparece_en_el_directorio(): void
    {
        Livewire::test(EmployeeManager::class)
            ->call('create')
            ->set('first_name', 'Ana')
            ->set('last_name', 'Sandoval')
            ->set('email', 'anas@arielpremium.com')
            ->set('extension', '3510')
            ->set('location_id', (string) $this->sede->id)
            ->set('department_id', (string) $this->depto->id)
            ->call('save')
            ->assertHasNoErrors();

        $e = Employee::where('email', 'anas@arielpremium.com')->first();

        $this->assertNotNull($e);
        $this->assertSame('Ana Sandoval', $e->full_name);
        $this->assertSame($this->sede->id, $e->location_id);
        $this->assertTrue($e->is_active);
    }

    public function test_normaliza_el_correo_a_minusculas(): void
    {
        Livewire::test(EmployeeManager::class)
            ->call('create')
            ->set('first_name', 'Luis')
            ->set('last_name', 'Maravilla')
            ->set('email', '  LuisM@ArielPremium.COM  ')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertTrue(Employee::where('email', 'luism@arielpremium.com')->exists());
    }

    public function test_edita_a_una_persona_y_el_cambio_queda_guardado(): void
    {
        $e = Employee::create([
            'first_name' => 'Jennifer', 'last_name' => 'Carroll',
            'email' => 'jenniferc@arielpremium.com', 'extension' => '1238',
        ]);

        Livewire::test(EmployeeManager::class)
            ->call('edit', $e->id)
            ->assertSet('first_name', 'Jennifer')
            ->set('extension', '9999')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('9999', $e->fresh()->extension);
    }

    // ---- HRADM-05: valida antes de guardar --------------------------------

    public function test_rechaza_un_correo_que_ya_existe(): void
    {
        Employee::create(['first_name' => 'Jose', 'last_name' => 'Camacho', 'email' => 'josec@arielpremium.com']);

        Livewire::test(EmployeeManager::class)
            ->call('create')
            ->set('first_name', 'Jose')
            ->set('last_name', 'Martinez')
            ->set('email', 'josec@arielpremium.com')
            ->call('save')
            ->assertHasErrors(['email']);

        $this->assertSame(1, Employee::where('email', 'josec@arielpremium.com')->count());
    }

    public function test_permite_guardar_a_la_misma_persona_sin_quejarse_de_su_propio_correo(): void
    {
        $e = Employee::create(['first_name' => 'Karen', 'last_name' => 'Villa', 'email' => 'karenv@arielpremium.com']);

        Livewire::test(EmployeeManager::class)
            ->call('edit', $e->id)
            ->set('extension', '1234')
            ->call('save')
            ->assertHasNoErrors();
    }

    public function test_exige_nombre_apellido_y_correo(): void
    {
        Livewire::test(EmployeeManager::class)
            ->call('create')
            ->call('save')
            ->assertHasErrors(['first_name', 'last_name', 'email']);
    }

    public function test_rechaza_un_mes_de_cumpleanos_fuera_de_rango(): void
    {
        Livewire::test(EmployeeManager::class)
            ->call('create')
            ->set('first_name', 'Test')
            ->set('last_name', 'Test')
            ->set('email', 't@arielpremium.com')
            ->set('birth_month', '13')
            ->call('save')
            ->assertHasErrors(['birth_month']);
    }

    // ---- PPL-10: nunca se guarda el año de nacimiento ---------------------

    public function test_no_existe_ninguna_columna_de_ano_de_nacimiento(): void
    {
        $columnas = Schema::getColumnListing('employees');

        $this->assertContains('birth_month', $columnas);
        $this->assertContains('birth_day', $columnas);

        foreach (['birth_year', 'birthdate', 'birth_date', 'date_of_birth'] as $prohibida) {
            $this->assertNotContains($prohibida, $columnas, "La columna {$prohibida} viola PPL-10");
        }
    }

    // ---- AUTH-08: la baja conserva el historial ---------------------------

    public function test_da_de_baja_sin_borrar_y_se_puede_reactivar(): void
    {
        $e = Employee::create(['first_name' => 'Said', 'last_name' => 'Medina', 'email' => 'saidm@arielpremium.com']);

        Livewire::test(EmployeeManager::class)->call('deactivate', $e->id);

        $this->assertNull(Employee::find($e->id));                        // sale del directorio
        $this->assertNotNull(Employee::withTrashed()->find($e->id));      // pero sigue existiendo
        $this->assertFalse(Employee::withTrashed()->find($e->id)->is_active);

        Livewire::test(EmployeeManager::class)->call('restore', $e->id);

        $this->assertNotNull(Employee::find($e->id));
        $this->assertTrue(Employee::find($e->id)->is_active);
    }

    // ---- PPL-04: búsqueda y filtros ---------------------------------------

    public function test_busca_por_nombre_apellido_extension_y_correo(): void
    {
        Employee::create(['first_name' => 'Elvira', 'last_name' => 'Nuno', 'email' => 'elvira@arielpremium.com', 'extension' => '4242']);
        Employee::create(['first_name' => 'Brandon', 'last_name' => 'Delgado', 'email' => 'brandond@arielpremium.com', 'extension' => '5151']);

        foreach ([['Elvira', 1], ['Delgado', 1], ['4242', 1], ['brandond', 1], ['zzzz', 0]] as [$termino, $esperados]) {
            Livewire::test(EmployeeManager::class)
                ->set('search', $termino)
                ->assertViewHas('employees', fn ($p) => $p->total() === $esperados);
        }
    }

    public function test_filtra_por_sede(): void
    {
        $otra = Location::create(['code' => 'TW', 'name_es' => 'Taiwan', 'name_en' => 'Taiwan']);

        Employee::create(['first_name' => 'A', 'last_name' => 'Uno', 'email' => 'a@arielpremium.com', 'location_id' => $this->sede->id]);
        Employee::create(['first_name' => 'B', 'last_name' => 'Dos', 'email' => 'b@arielpremium.com', 'location_id' => $otra->id]);

        Livewire::test(EmployeeManager::class)
            ->set('locationId', (string) $this->sede->id)
            ->assertViewHas('employees', fn ($p) => $p->total() === 1);
    }

    public function test_puede_ver_solo_a_los_dados_de_baja(): void
    {
        Employee::create(['first_name' => 'A', 'last_name' => 'Activo', 'email' => 'act@arielpremium.com']);
        $baja = Employee::create(['first_name' => 'B', 'last_name' => 'Baja', 'email' => 'baja@arielpremium.com']);

        Livewire::test(EmployeeManager::class)->call('deactivate', $baja->id);

        Livewire::test(EmployeeManager::class)
            ->set('status', 'inactive')
            ->assertViewHas('employees', fn ($p) => $p->total() === 1 && $p->first()->id === $baja->id);
    }
}
