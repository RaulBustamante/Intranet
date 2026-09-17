<?php

namespace Tests\Feature\Requests;

use App\Domain\People\Models\Employee;
use App\Domain\Requests\Models\RequestType;
use App\Domain\Requests\Services\ApprovalEngine;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ApprovalEngineTest extends TestCase
{
    use RefreshDatabase;

    private ApprovalEngine $engine;

    protected function setUp(): void
    {
        parent::setUp();
        $this->engine = app(ApprovalEngine::class);
        foreach (['employee', 'hr_editor', 'admin'] as $r) {
            Role::findOrCreate($r, 'web');
        }
    }

    private function employee(string $email, ?int $managerId = null, ?string $role = null): Employee
    {
        $emp = Employee::create(['first_name' => 'E', 'last_name' => $email, 'email' => "$email@arielpremium.com", 'manager_id' => $managerId]);
        if ($role) {
            $user = User::create(['name' => $email, 'email' => "$email@arielpremium.com", 'password' => bcrypt('x'), 'is_active' => true, 'employee_id' => $emp->id]);
            $user->assignRole($role);
            $emp->update(['user_id' => $user->id]);
        }

        return $emp->fresh();
    }

    private function vacationType(): RequestType
    {
        return RequestType::create([
            'code' => 'vacation', 'name_es' => 'Vac', 'name_en' => 'Vac', 'sla_days' => 3,
            'field_schema' => [['key' => 'reason', 'label_es' => 'M', 'label_en' => 'R', 'type' => 'text', 'required' => true]],
            'approval_steps' => [['by' => 'manager'], ['by' => 'role', 'role' => 'hr_editor']],
        ]);
    }

    // ---- REQ-01/REQ-03: enviar y ver estatus ------------------------------

    public function test_enviar_crea_la_solicitud_pendiente_con_sla(): void
    {
        $jefe = $this->employee('jefe');
        $emp = $this->employee('ana', $jefe->id);

        $req = $this->engine->submit($this->vacationType(), $emp, ['reason' => 'Viaje']);

        $this->assertSame('pending', $req->status);
        $this->assertSame(0, $req->current_step);
        $this->assertNotNull($req->due_at);
        $this->assertSame('Viaje', $req->payload['reason']);
        // Se abrió el primer paso (manager)
        $this->assertSame($jefe->id, $req->approvals()->where('step', 0)->first()->approver_id);
    }

    // ---- REQ-04: flujo de varios pasos ------------------------------------

    public function test_aprobar_el_primer_paso_avanza_al_segundo(): void
    {
        $jefe = $this->employee('jefe');
        $rh = $this->employee('rh', null, 'hr_editor');
        $emp = $this->employee('ana', $jefe->id);

        $req = $this->engine->submit($this->vacationType(), $emp, ['reason' => 'x']);

        $this->engine->approve($req, $jefe);
        $req->refresh();

        $this->assertSame('pending', $req->status);   // aún no cierra
        $this->assertSame(1, $req->current_step);       // avanzó al paso 2 (RH)
    }

    public function test_aprobar_el_ultimo_paso_cierra_como_aprobada(): void
    {
        $jefe = $this->employee('jefe');
        $rh = $this->employee('rh', null, 'hr_editor');
        $emp = $this->employee('ana', $jefe->id);

        $req = $this->engine->submit($this->vacationType(), $emp, ['reason' => 'x']);
        $this->engine->approve($req, $jefe);        // paso 1
        $this->engine->approve($req->fresh(), $rh); // paso 2 (último)

        $this->assertSame('approved', $req->fresh()->status);
        $this->assertNotNull($req->fresh()->closed_at);
    }

    // ---- REQ-05: rechazar exige comentario --------------------------------

    public function test_rechazar_sin_comentario_falla(): void
    {
        $jefe = $this->employee('jefe');
        $emp = $this->employee('ana', $jefe->id);
        $req = $this->engine->submit($this->vacationType(), $emp, ['reason' => 'x']);

        $this->expectException(\RuntimeException::class);
        $this->engine->reject($req, $jefe, '   ');
    }

    public function test_rechazar_con_comentario_cierra_la_solicitud(): void
    {
        $jefe = $this->employee('jefe');
        $emp = $this->employee('ana', $jefe->id);
        $req = $this->engine->submit($this->vacationType(), $emp, ['reason' => 'x']);

        $this->engine->reject($req, $jefe, 'Sale del presupuesto');

        $this->assertSame('rejected', $req->fresh()->status);
        $this->assertSame('Sale del presupuesto', $req->approvals()->where('step', 0)->first()->comment);
    }

    // ---- REQ-05/REQ-10: solo el aprobador que toca puede decidir ----------

    public function test_alguien_que_no_es_el_aprobador_no_puede_decidir(): void
    {
        $jefe = $this->employee('jefe');
        $otro = $this->employee('otro');
        $emp = $this->employee('ana', $jefe->id);
        $req = $this->engine->submit($this->vacationType(), $emp, ['reason' => 'x']);

        $this->assertFalse($this->engine->canDecide($req, $otro));
        $this->expectException(\RuntimeException::class);
        $this->engine->approve($req, $otro);
    }

    public function test_el_jefe_del_paso_manager_es_quien_decide(): void
    {
        $jefe = $this->employee('jefe');
        $emp = $this->employee('ana', $jefe->id);
        $req = $this->engine->submit($this->vacationType(), $emp, ['reason' => 'x']);

        $this->assertTrue($this->engine->canDecide($req, $jefe));
    }

    // ---- REQ-08: vencimiento ----------------------------------------------

    public function test_una_solicitud_pasada_su_compromiso_esta_vencida(): void
    {
        $jefe = $this->employee('jefe');
        $emp = $this->employee('ana', $jefe->id);
        $req = $this->engine->submit($this->vacationType(), $emp, ['reason' => 'x']);
        $req->update(['due_at' => now()->subDay()]);

        $this->assertTrue($req->fresh()->isOverdue());
    }

    // ---- Cancelar ---------------------------------------------------------

    public function test_solo_el_solicitante_cancela(): void
    {
        $jefe = $this->employee('jefe');
        $emp = $this->employee('ana', $jefe->id);
        $req = $this->engine->submit($this->vacationType(), $emp, ['reason' => 'x']);

        $this->expectException(\RuntimeException::class);
        $this->engine->cancel($req, $jefe);   // el jefe no es el solicitante
    }
}
