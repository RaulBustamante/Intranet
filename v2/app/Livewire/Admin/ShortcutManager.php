<?php

namespace App\Livewire\Admin;

use App\Domain\Content\Models\Shortcut;
use Illuminate\Support\Facades\Route as RouteFacade;
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * Panel para administrar los accesos rápidos de la landing (RH/comunicación).
 * Sin tocar código: agregar, editar, activar/desactivar, reordenar, color e icono.
 */
class ShortcutManager extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;

    public string $label_es = '';
    public string $label_en = '';
    public string $icon = 'link';
    public string $tint = 'slate';
    public string $target_type = 'route';
    public string $target = '';
    public bool $requires_auth = true;

    /** Iconos y colores que RH puede elegir (los que soporta <x-icon>). */
    public array $icons = ['users', 'calendar', 'door', 'file', 'news', 'inbox', 'badge', 'link', 'lifebuoy', 'list', 'settings', 'shield', 'info', 'image', 'globe', 'cake'];
    public array $tints = ['blue', 'green', 'orange', 'purple', 'red', 'teal', 'pink', 'indigo', 'slate'];

    protected function rules(): array
    {
        return [
            'label_es'    => 'required|string|max:80',
            'label_en'    => 'required|string|max:80',
            'icon'        => 'required|string',
            'tint'        => 'required|string',
            'target_type' => 'required|in:route,url',
            'target'      => 'required|string|max:500',
        ];
    }

    #[Computed]
    public function shortcuts()
    {
        return Shortcut::orderBy('sort_order')->get();
    }

    /** Rutas internas que RH puede elegir como destino (las públicas del menú). */
    #[Computed]
    public function routes(): array
    {
        return ['directory', 'calendar', 'rooms', 'documents', 'bulletins', 'requests', 'kudos', 'links', 'announcements'];
    }

    public function create(): void
    {
        $this->reset(['editingId', 'label_es', 'label_en', 'target']);
        $this->icon = 'link';
        $this->tint = 'slate';
        $this->target_type = 'route';
        $this->requires_auth = true;
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $s = Shortcut::findOrFail($id);
        $this->editingId = $s->id;
        $this->label_es = $s->label_es;
        $this->label_en = $s->label_en;
        $this->icon = $s->icon;
        $this->tint = $s->tint;
        $this->target_type = $s->target_type;
        $this->target = $s->target;
        $this->requires_auth = $s->requires_auth;
        $this->showForm = true;
    }

    public function save(): void
    {
        $data = $this->validate();
        $data['requires_auth'] = $this->target_type === 'route' ? $this->requires_auth : false;

        if ($this->editingId) {
            Shortcut::findOrFail($this->editingId)->update($data);
        } else {
            $data['sort_order'] = (Shortcut::max('sort_order') ?? 0) + 1;
            $data['is_active'] = true;
            Shortcut::create($data);
        }

        $this->showForm = false;
        $this->dispatch('notify', message: __('admin.people.updated'));
    }

    public function toggle(int $id): void
    {
        $s = Shortcut::findOrFail($id);
        $s->update(['is_active' => ! $s->is_active]);
    }

    public function move(int $id, string $dir): void
    {
        $s = Shortcut::findOrFail($id);
        $neighbor = Shortcut::where('sort_order', $dir === 'up' ? '<' : '>', $s->sort_order)
            ->orderBy('sort_order', $dir === 'up' ? 'desc' : 'asc')->first();
        if ($neighbor) {
            [$s->sort_order, $neighbor->sort_order] = [$neighbor->sort_order, $s->sort_order];
            $s->save();
            $neighbor->save();
        }
    }

    public function delete(int $id): void
    {
        Shortcut::findOrFail($id)->delete();
        $this->dispatch('notify', message: __('shortcuts.deleted'));
    }

    public function render()
    {
        return view('livewire.admin.shortcut-manager')
            ->layout('components.layouts.app', ['title' => __('shortcuts.title')]);
    }
}
