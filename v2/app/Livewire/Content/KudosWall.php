<?php

namespace App\Livewire\Content;

use App\Domain\Content\Models\Kudo;
use App\Domain\People\Models\Employee;
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * Muro de reconocimientos (COM-08, COM-09).
 *
 * Un empleado reconoce a un compañero; el kudo pasa por moderación antes de
 * aparecer en el muro. No puedes reconocerte a ti mismo (regla de negocio).
 */
class KudosWall extends Component
{
    public string $toEmployeeId = '';
    public string $message = '';

    #[Computed]
    public function colleagues()
    {
        $meId = auth()->user()?->employee_id;

        return Employee::active()
            ->when($meId, fn ($q) => $q->where('id', '!=', $meId))   // no aparezco yo
            ->orderBy('first_name')->get(['id', 'first_name', 'last_name', 'preferred_name']);
    }

    #[Computed]
    public function wall()
    {
        return Kudo::approved()
            ->with(['from:id,first_name,last_name,preferred_name', 'to:id,first_name,last_name,preferred_name'])
            ->latest()->limit(20)->get();
    }

    public function send(): void
    {
        // Publicar exige sesión: un invitado que intenta enviar va al login (REQ/AUTH).
        if (! auth()->check()) {
            $this->redirectRoute('login', navigate: true);
            return;
        }

        $this->validate([
            'toEmployeeId' => 'required|exists:employees,id',
            'message'      => 'required|string|min:3|max:500',
        ]);

        $fromId = auth()->user()->employee_id;
        if (! $fromId) {
            $this->addError('message', __('kudos.no_employee'));

            return;
        }
        if ((int) $this->toEmployeeId === $fromId) {
            $this->addError('toEmployeeId', __('kudos.not_self'));

            return;
        }

        // Sin moderación configurada, se aprueba directo. La moderación se
        // enciende con un ajuste (COM-09); por defecto, publicación inmediata.
        $modera = \App\Domain\Content\Services\Settings::get('kudos_moderate', false);

        Kudo::create([
            'from_employee_id' => $fromId,
            'to_employee_id'   => (int) $this->toEmployeeId,
            'message'          => $this->message,
            'approved_at'      => $modera ? null : now(),
        ]);

        $this->reset('toEmployeeId', 'message');
        unset($this->wall);
        $this->dispatch('notify', message: $modera ? __('kudos.pending') : __('kudos.sent'));
    }

    public function render()
    {
        return view('livewire.content.kudos-wall')
            ->layout('components.layouts.app', ['title' => __('kudos.title')]);
    }
}
