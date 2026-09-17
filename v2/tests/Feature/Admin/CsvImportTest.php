<?php

namespace Tests\Feature\Admin;

use App\Domain\People\Models\Employee;
use App\Livewire\Admin\CsvImport;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CsvImportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        foreach (['employee', 'hr_editor', 'admin'] as $r) {
            Role::findOrCreate($r, 'web');
        }
    }

    private function hr(): User
    {
        $u = User::create(['name' => 'RH', 'email' => 'rh@arielpremium.com', 'password' => bcrypt('x'), 'is_active' => true]);
        $u->assignRole('hr_editor');

        return $u;
    }

    private function csv(string $content): UploadedFile
    {
        return UploadedFile::fake()->createWithContent('empleados.csv', $content);
    }

    public function test_la_vista_previa_cuenta_altas_actualizaciones_y_errores(): void
    {
        // Ya existe uno, para probar "update"
        Employee::create(['first_name' => 'Ana', 'last_name' => 'Existe', 'email' => 'ana@arielpremium.com']);

        $csv = "nombre,apellido,correo,departamento,sede\n"
             . "Ana,Existe,ana@arielpremium.com,Prod,WC\n"        // update
             . "Luis,Nuevo,luis@arielpremium.com,Prod,WC\n"        // alta
             . "SinCorreo,X,,Prod,WC\n"                            // error: correo
             . ",SinNombre,x@arielpremium.com,Prod,WC\n";          // error: nombre

        Livewire::actingAs($this->hr())->test(CsvImport::class)
            ->set('file', $this->csv($csv))
            ->call('analyze')
            ->assertSet('analyzed', true)
            ->assertSet('summary.alta', 1)
            ->assertSet('summary.update', 1)
            ->assertSet('summary.error', 2);
    }

    public function test_aplicar_crea_y_actualiza_pero_no_los_errores(): void
    {
        $csv = "nombre,apellido,correo,sede\n"
             . "Luis,Nuevo,luis@arielpremium.com,WC\n"
             . "SinCorreo,X,,WC\n";

        Livewire::actingAs($this->hr())->test(CsvImport::class)
            ->set('file', $this->csv($csv))
            ->call('analyze')
            ->call('apply');

        $this->assertDatabaseHas('employees', ['email' => 'luis@arielpremium.com']);
        $this->assertDatabaseMissing('employees', ['first_name' => 'SinCorreo']);
    }

    public function test_un_empleado_no_entra_a_la_importacion(): void
    {
        $u = User::create(['name' => 'E', 'email' => 'e@arielpremium.com', 'password' => bcrypt('x'), 'is_active' => true]);
        $u->assignRole('employee');

        $this->actingAs($u)->get(route('admin.import'))->assertForbidden();
    }
}
