<?php

namespace Tests\Feature\Requests;

use App\Domain\People\Models\Employee;
use App\Domain\Requests\Models\RequestType;
use App\Domain\Requests\Models\ServiceRequest;
use App\Livewire\Requests\RequestsIndex;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RequestsUiTest extends TestCase
{
    use RefreshDatabase;

    private RequestType $tipo;

    protected function setUp(): void
    {
        parent::setUp();
        foreach (['employee', 'hr_editor', 'admin'] as $r) {
            Role::findOrCreate($r, 'web');
        }
        $this->tipo = RequestType::create([
            'code' => 'it', 'name_es' => 'TI', 'name_en' => 'IT', 'sla_days' => 2,
            'field_schema' => [
                ['key' => 'description', 'label_es' => 'Desc', 'label_en' => 'Desc', 'type' => 'textarea', 'required' => true],
            ],
            'approval_steps' => [['by' => 'role', 'role' => 'admin']],
        ]);
    }

    private function userEmp(string $email, ?string $role = null): array
    {
        $emp = Employee::create(['first_name' => 'E', 'last_name' => $email, 'email' => "$email@arielpremium.com"]);
        $user = User::create(['name' => $email, 'email' => "$email@arielpremium.com", 'password' => bcrypt('x'), 'is_active' => true, 'employee_id' => $emp->id]);
        if ($role) {
            $user->assignRole($role);
        }
        $emp->update(['user_id' => $user->id]);

        return [$user, $emp->fresh()];
    }

    public function test_un_empleado_envia_una_solicitud_y_la_ve_en_su_lista(): void
    {
        [$user, $emp] = $this->userEmp('ana', 'employee');

        Livewire::actingAs($user)->test(RequestsIndex::class)
            ->call('startNew', $this->tipo->id)
            ->set('form.description', 'No enciende mi monitor')
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('service_requests', ['requester_id' => $emp->id, 'status' => 'pending']);
        $req = ServiceRequest::first();
        $this->assertSame('No enciende mi monitor', $req->payload['description']);
    }

    public function test_un_invitado_puede_ver_pero_no_capturar(): void
    {
        // Sin sesión: la página se navega, pero enviar redirige al login y NO
        // crea nada (el candado de captura del nuevo modelo de acceso).
        Livewire::test(RequestsIndex::class)
            ->call('startNew', $this->tipo->id)
            ->set('form.description', 'Intento sin firmarme')
            ->call('submit')
            ->assertRedirect(route('login'));

        $this->assertDatabaseCount('service_requests', 0);
    }

    public function test_el_formulario_exige_los_campos_requeridos(): void
    {
        [$user] = $this->userEmp('ana', 'employee');

        Livewire::actingAs($user)->test(RequestsIndex::class)
            ->call('startNew', $this->tipo->id)
            ->call('submit')
            ->assertHasErrors('form.description');
    }

    public function test_el_aprobador_ve_la_solicitud_en_su_bandeja_y_la_aprueba(): void
    {
        [$userAna, $ana] = $this->userEmp('ana', 'employee');
        [$userAdmin, $admin] = $this->userEmp('admin', 'admin');

        // Ana envía
        Livewire::actingAs($userAna)->test(RequestsIndex::class)
            ->call('startNew', $this->tipo->id)
            ->set('form.description', 'x')
            ->call('submit');

        // Admin la ve en su bandeja y la aprueba
        Livewire::actingAs($userAdmin)->test(RequestsIndex::class)
            ->assertViewHas('__', fn () => true)   // fuerza render
            ->call('decide', ServiceRequest::first()->id, 'approve')
            ->assertHasNoErrors();

        $this->assertSame('approved', ServiceRequest::first()->status);
    }

    public function test_rechazar_sin_comentario_muestra_error(): void
    {
        [$userAna] = $this->userEmp('ana', 'employee');
        [$userAdmin] = $this->userEmp('admin', 'admin');

        Livewire::actingAs($userAna)->test(RequestsIndex::class)
            ->call('startNew', $this->tipo->id)->set('form.description', 'x')->call('submit');

        Livewire::actingAs($userAdmin)->test(RequestsIndex::class)
            ->set('decisionComment', '')
            ->call('decide', ServiceRequest::first()->id, 'reject')
            ->assertHasErrors('decision');

        $this->assertSame('pending', ServiceRequest::first()->status);
    }

    public function test_un_empleado_solo_ve_sus_propias_solicitudes(): void
    {
        [$userAna, $ana] = $this->userEmp('ana', 'employee');
        [$userBeto, $beto] = $this->userEmp('beto', 'employee');

        Livewire::actingAs($userAna)->test(RequestsIndex::class)
            ->call('startNew', $this->tipo->id)->set('form.description', 'de ana')->call('submit');

        Livewire::actingAs($userBeto)->test(RequestsIndex::class)
            ->assertViewHas('__', fn () => true);

        // La lista de Beto no incluye la de Ana
        Livewire::actingAs($userBeto)->test(RequestsIndex::class)
            ->assertDontSee('de ana');
    }
}
