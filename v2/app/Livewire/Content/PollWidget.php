<?php

namespace App\Livewire\Content;

use App\Domain\Content\Models\Poll;
use App\Domain\Content\Models\PollVote;
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * Encuesta activa (COM-10): el empleado vota una sola vez y ve el resultado.
 * Se muestra en la portada; si no hay encuesta activa, no se pinta nada.
 */
class PollWidget extends Component
{
    public ?int $optionId = null;

    #[Computed]
    public function poll(): ?Poll
    {
        return Poll::query()
            ->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('opens_at')->orWhere('opens_at', '<=', now()))
            ->where(fn ($q) => $q->whereNull('closes_at')->orWhere('closes_at', '>', now()))
            ->with('options')
            ->latest()->first();
    }

    #[Computed]
    public function hasVoted(): bool
    {
        $emp = auth()->user()?->employee_id;

        return $this->poll && $emp && $this->poll->hasVoted($emp);
    }

    /** Conteo por opción (para las barras de resultado). */
    #[Computed]
    public function tally(): array
    {
        if (! $this->poll) {
            return [];
        }

        return PollVote::query()
            ->where('poll_id', $this->poll->id)
            ->selectRaw('option_id, COUNT(*) as n')
            ->groupBy('option_id')->pluck('n', 'option_id')->all();
    }

    public function vote(): void
    {
        // Votar exige sesión: el invitado ve la encuesta/resultado pero para
        // emitir voto se firma primero.
        if (! auth()->check()) {
            $this->redirectRoute('login', navigate: true);
            return;
        }

        $emp = auth()->user()->employee_id;
        if (! $emp || ! $this->poll || ! $this->optionId) {
            return;
        }

        // Un voto por persona, garantizado por el unique de la tabla (COM-10)
        PollVote::firstOrCreate(
            ['poll_id' => $this->poll->id, 'employee_id' => $emp],
            ['option_id' => $this->optionId]
        );

        unset($this->hasVoted, $this->tally);
        $this->dispatch('notify', message: __('polls.thanks'));
    }

    public function render()
    {
        return view('livewire.content.poll-widget');
    }
}
