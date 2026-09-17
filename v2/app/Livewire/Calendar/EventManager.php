<?php

namespace App\Livewire\Calendar;

use App\Domain\Calendar\Models\Event;
use App\Domain\People\Models\Location;
use Carbon\CarbonImmutable;
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * Alta y baja de eventos de empresa (CAL-05).
 *
 * Los eventos capturados (Posada, Paseo de verano...) van aquí, separados de
 * los festivos de ley (que se calculan por regla). En la v1 estaban mezclados
 * en el mismo array, por eso "Posada (aún no confirmado)" terminó como texto
 * dentro de una vista.
 */
class EventManager extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;

    public string $title_es = '';
    public string $title_en = '';
    public string $type = 'company';
    public string $starts_at = '';
    public string $ends_at = '';
    public string $location_id = '';

    protected function rules(): array
    {
        return [
            'title_es'    => 'required|string|max:200',
            'title_en'    => 'nullable|string|max:200',
            'type'        => 'required|in:company,training,maintenance,other',
            'starts_at'   => 'required|date',
            'ends_at'     => 'nullable|date|after_or_equal:starts_at',
            'location_id' => 'nullable|exists:locations,id',
        ];
    }

    #[Computed]
    public function events()
    {
        return Event::query()
            ->where('starts_at', '>=', now()->startOfMonth())
            ->with('location')
            ->orderBy('starts_at')->get();
    }

    #[Computed]
    public function locations()
    {
        return Location::orderBy('code')->get();
    }

    public function create(): void
    {
        $this->reset(['editingId', 'title_es', 'title_en', 'ends_at', 'location_id']);
        $this->type = 'company';
        $this->starts_at = CarbonImmutable::now()->format('Y-m-d\TH:i');
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $e = Event::findOrFail($id);
        $this->editingId = $e->id;
        $this->title_es = $e->title_es;
        $this->title_en = $e->title_en ?? '';
        $this->type = $e->type;
        $this->starts_at = $e->starts_at->format('Y-m-d\TH:i');
        $this->ends_at = $e->ends_at?->format('Y-m-d\TH:i') ?? '';
        $this->location_id = (string) ($e->location_id ?? '');
        $this->showForm = true;
    }

    public function save(): void
    {
        $data = $this->validate();
        $data['location_id'] = $data['location_id'] ?: null;
        $data['ends_at'] = $data['ends_at'] ?: null;

        if ($this->editingId) {
            Event::findOrFail($this->editingId)->update($data);
        } else {
            Event::create($data + ['created_by' => auth()->id()]);
        }

        \App\Domain\Calendar\Services\HolidayService::flush();
        $this->showForm = false;
        $this->dispatch('notify', message: __('admin.people.updated'));
    }

    public function delete(int $id): void
    {
        Event::findOrFail($id)->delete();
        $this->dispatch('notify', message: __('events.deleted'));
    }

    public function render()
    {
        return view('livewire.calendar.event-manager')
            ->layout('components.layouts.app', ['title' => __('events.title')]);
    }
}
