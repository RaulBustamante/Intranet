<?php

namespace Tests\Feature\Profile;

use App\Domain\People\Models\Department;
use App\Domain\People\Models\Employee;
use App\Livewire\Profile\MyProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MyProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_el_empleado_edita_solo_sus_datos_acotados(): void
    {
        $depto = Department::create(['name_es' => 'Arte', 'name_en' => 'Art']);
        $emp = Employee::create(['first_name' => 'Ana', 'last_name' => 'S', 'email' => 'ana@arielpremium.com', 'department_id' => $depto->id, 'job_title_es' => 'Disenadora']);
        $user = User::create(['name' => 'Ana', 'email' => 'ana@arielpremium.com', 'password' => bcrypt('x'), 'is_active' => true, 'employee_id' => $emp->id]);

        Livewire::actingAs($user)->test(MyProfile::class)
            ->set('preferred_name', 'Anita')
            ->set('extension', '9999')
            ->call('save')
            ->assertHasNoErrors();

        $emp->refresh();
        $this->assertSame('Anita', $emp->preferred_name);
        $this->assertSame('9999', $emp->extension);
        // Su puesto y departamento NO cambiaron (PPL-08)
        $this->assertSame('Disenadora', $emp->job_title_es);
        $this->assertSame($depto->id, $emp->department_id);
    }
}
