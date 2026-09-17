<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Auto-edicion del propio perfil (PPL-08).
 *
 * El empleado edita SOLO un conjunto acotado: foto, apodo, extension y
 * telefono. NO puede tocar su puesto, sede ni departamento — eso es de RH.
 */
class MyProfile extends Component
{
    use WithFileUploads;

    public string $preferred_name = '';
    public string $extension = '';
    public string $phone = '';
    public $photo = null;

    public function mount(): void
    {
        $e = auth()->user()->employee;
        $this->preferred_name = $e?->preferred_name ?? '';
        $this->extension = $e?->extension ?? '';
        $this->phone = $e?->phone ?? '';
    }

    public function save(): void
    {
        $e = auth()->user()->employee;
        if (! $e) {
            $this->addError('preferred_name', __('profile.no_employee'));

            return;
        }

        $this->validate([
            'preferred_name' => 'nullable|string|max:80',
            'extension'      => 'nullable|string|max:10',
            'phone'          => 'nullable|string|max:40',
            'photo'          => 'nullable|image|max:4096',
        ]);

        $datos = [
            'preferred_name' => $this->preferred_name ?: null,
            'extension'      => $this->extension ?: null,
            'phone'          => $this->phone ?: null,
        ];

        if ($this->photo) {
            $key = 'employees/photos/' . uniqid('emp_') . '.' . ($this->photo->getClientOriginalExtension() ?: 'jpg');
            Storage::disk('s3')->put($key, file_get_contents($this->photo->getRealPath()), 'public');
            $datos['photo_path'] = Storage::disk('s3')->url($key);
        }

        // Solo se actualizan los campos acotados; el resto ni se toca (PPL-08)
        $e->update($datos);

        $this->photo = null;
        $this->dispatch('notify', message: __('profile.saved'));
    }

    public function render()
    {
        return view('livewire.profile.my-profile')
            ->layout('components.layouts.app', ['title' => __('nav.profile')]);
    }
}
