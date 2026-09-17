<?php

namespace App\Livewire\Requests;

use App\Domain\Requests\Models\RequestType;
use App\Domain\Requests\Models\ServiceRequest;
use App\Domain\Requests\Services\ApprovalEngine;
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * Autoservicio del empleado: nueva solicitud + mis solicitudes + bandeja.
 * El formulario se genera desde field_schema del tipo (REQ-02).
 */
class RequestsIndex extends Component
{
    public string $tab = 'mine';         // mine | inbox
    public ?int $typeId = null;          // tipo elegido para nueva solicitud
    public array $form = [];             // valores del formulario dinámico
    public ?int $viewingId = null;       // solicitud abierta en detalle
    public string $decisionComment = '';

    #[Computed]
    public function types()
    {
        return RequestType::where('is_active', true)->orderBy('sort_order')->get();
    }

    #[Computed]
    public function myRequests()
    {
        $me = auth()->user()?->employee_id;
        if (! $me) {
            return collect();   // invitado: no tiene solicitudes que mostrar
        }

        return ServiceRequest::query()
            ->where('requester_id', $me)
            ->with('type', 'approvals.approver')
            ->latest()->get();
    }

    /** Bandeja del aprobador: solicitudes cuyo paso actual me toca (REQ-05). */
    #[Computed]
    public function inbox()
    {
        $emp = auth()->user()?->employee;
        if (! $emp) {
            return collect();
        }
        $engine = app(ApprovalEngine::class);

        return ServiceRequest::query()
            ->where('status', 'pending')
            ->with('type', 'requester', 'approvals')
            ->get()
            ->filter(fn ($r) => $engine->canDecide($r, $emp))
            ->values();
    }

    public function startNew(int $typeId): void
    {
        $this->typeId = $typeId;
        $this->form = [];
        $this->resetErrorBag();
    }

    public function submit(): void
    {
        // Enviar una solicitud exige sesión: el invitado ve los tipos y el
        // formulario, pero al enviar se le pide firmarse (el candado de captura).
        if (! auth()->check()) {
            $this->redirectRoute('login', navigate: true);
            return;
        }

        $type = RequestType::findOrFail($this->typeId);

        // Validación derivada del schema
        $rules = [];
        foreach ($type->field_schema as $field) {
            $r = [];
            $r[] = ($field['required'] ?? false) ? 'required' : 'nullable';
            if (($field['type'] ?? '') === 'number') {
                $r[] = 'numeric';
            }
            if (($field['type'] ?? '') === 'date') {
                $r[] = 'date';
            }
            $rules["form.{$field['key']}"] = $r;
        }
        $this->validate($rules);

        $emp = auth()->user()->employee;
        if (! $emp) {
            $this->addError('form', __('requests.no_employee'));

            return;
        }

        app(ApprovalEngine::class)->submit($type, $emp, $this->form);

        $this->typeId = null;
        $this->form = [];
        unset($this->myRequests);
        $this->tab = 'mine';
        $this->dispatch('notify', message: __('requests.submitted'));
    }

    public function decide(int $requestId, string $action): void
    {
        if (! auth()->check()) {
            $this->redirectRoute('login', navigate: true);
            return;
        }

        $req = ServiceRequest::findOrFail($requestId);
        $emp = auth()->user()->employee;
        $engine = app(ApprovalEngine::class);

        try {
            if ($action === 'approve') {
                $engine->approve($req, $emp, $this->decisionComment ?: null);
            } else {
                $engine->reject($req, $emp, $this->decisionComment);
            }
        } catch (\RuntimeException $e) {
            $this->addError('decision', $e->getMessage());

            return;
        }

        $this->decisionComment = '';
        $this->viewingId = null;
        unset($this->inbox, $this->myRequests);
        $this->dispatch('notify', message: __('requests.decided'));
    }

    public function cancel(int $requestId): void
    {
        if (! auth()->check()) {
            $this->redirectRoute('login', navigate: true);
            return;
        }

        $req = ServiceRequest::findOrFail($requestId);
        try {
            app(ApprovalEngine::class)->cancel($req, auth()->user()->employee);
        } catch (\RuntimeException $e) {
            return;
        }
        unset($this->myRequests);
        $this->dispatch('notify', message: __('requests.cancelled'));
    }

    public function render()
    {
        return view('livewire.requests.requests-index')
            ->layout('components.layouts.app', ['title' => __('nav.requests')]);
    }
}
