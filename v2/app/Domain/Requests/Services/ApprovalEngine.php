<?php

namespace App\Domain\Requests\Services;

use App\Domain\People\Models\Employee;
use App\Domain\Requests\Models\RequestApproval;
use App\Domain\Requests\Models\RequestType;
use App\Domain\Requests\Models\ServiceRequest;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Motor de aprobación (REQ-04, REQ-05).
 *
 * Los pasos vienen de request_type.approval_steps, p.ej.:
 *   [{"by": "manager"}, {"by": "role", "role": "hr_editor"}]
 *
 * "manager"  -> el jefe directo del solicitante
 * "role"     -> cualquier empleado cuyo usuario tenga ese rol
 *
 * Rechazar en cualquier paso cierra la solicitud como rejected y exige
 * comentario. Aprobar el último paso la cierra como approved.
 */
class ApprovalEngine
{
    /** Crea la solicitud, calcula el SLA y prepara el primer paso. */
    public function submit(RequestType $type, Employee $requester, array $payload): ServiceRequest
    {
        return DB::transaction(function () use ($type, $requester, $payload) {
            $request = ServiceRequest::create([
                'type_id'      => $type->id,
                'requester_id' => $requester->id,
                'payload'      => $payload,
                'status'       => 'pending',
                'current_step' => 0,
                'submitted_at' => now(),
                'due_at'       => now()->addDays($type->sla_days),
            ]);

            $this->openStep($request, 0);

            return $request;
        });
    }

    /** Aprueba el paso actual y avanza (o cierra si era el último). */
    public function approve(ServiceRequest $request, Employee $approver, ?string $comment = null): void
    {
        $this->assertCanDecide($request, $approver);

        DB::transaction(function () use ($request, $approver, $comment) {
            $request->approvals()
                ->where('step', $request->current_step)
                ->update(['approver_id' => $approver->id, 'decision' => 'approved', 'comment' => $comment, 'decided_at' => now()]);

            $steps = $request->type->approval_steps;
            $next = $request->current_step + 1;

            if ($next >= count($steps)) {
                $request->update(['status' => 'approved', 'closed_at' => now()]);
            } else {
                $request->update(['current_step' => $next]);
                $this->openStep($request, $next);
            }
        });
    }

    /** Rechaza: cierra la solicitud. El comentario es OBLIGATORIO (REQ-05). */
    public function reject(ServiceRequest $request, Employee $approver, string $comment): void
    {
        $this->assertCanDecide($request, $approver);

        if (trim($comment) === '') {
            throw new RuntimeException(__('requests.reject_needs_comment'));
        }

        DB::transaction(function () use ($request, $approver, $comment) {
            $request->approvals()
                ->where('step', $request->current_step)
                ->update(['approver_id' => $approver->id, 'decision' => 'rejected', 'comment' => $comment, 'decided_at' => now()]);

            $request->update(['status' => 'rejected', 'closed_at' => now()]);
        });
    }

    public function cancel(ServiceRequest $request, Employee $who): void
    {
        if ($request->requester_id !== $who->id) {
            throw new RuntimeException(__('requests.only_requester_cancels'));
        }
        $request->update(['status' => 'cancelled', 'closed_at' => now()]);
    }

    /** ¿Puede este empleado decidir el paso actual? */
    public function canDecide(ServiceRequest $request, Employee $approver): bool
    {
        if (! $request->isOpen()) {
            return false;
        }

        $step = $request->type->approval_steps[$request->current_step] ?? null;
        if (! $step) {
            return false;
        }

        if (($step['by'] ?? null) === 'manager') {
            return $request->requester->manager_id === $approver->id;
        }

        if (($step['by'] ?? null) === 'role') {
            return $approver->user && $approver->user->hasRole($step['role']);
        }

        return false;
    }

    private function assertCanDecide(ServiceRequest $request, Employee $approver): void
    {
        if (! $this->canDecide($request, $approver)) {
            throw new RuntimeException(__('requests.not_your_turn'));
        }
    }

    /** Crea el registro de aprobación del paso, resolviendo el aprobador. */
    private function openStep(ServiceRequest $request, int $stepIndex): void
    {
        $step = $request->type->approval_steps[$stepIndex] ?? null;
        if (! $step) {
            // Sin pasos definidos: se aprueba sola
            $request->update(['status' => 'approved', 'closed_at' => now()]);

            return;
        }

        RequestApproval::create([
            'request_id'    => $request->id,
            'step'          => $stepIndex,
            'approver_id'   => ($step['by'] ?? null) === 'manager' ? $request->requester->manager_id : null,
            'approver_role' => ($step['by'] ?? null) === 'role' ? ($step['role'] ?? null) : null,
            'decision'      => 'pending',
        ]);
    }
}
