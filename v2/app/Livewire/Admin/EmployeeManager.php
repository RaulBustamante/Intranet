<?php

namespace App\Livewire\Admin;

use App\Domain\People\Models\Department;
use App\Domain\People\Models\Employee;
use App\Domain\People\Models\Location;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

/**
 * Panel de RH — alta, edición y baja de empleados (HRADM-01).
 *
 * Es EL entregable del proyecto: que RH mantenga el directorio sin pedirle
 * nada a TI. Por eso la interfaz no habla de base de datos: dice "Sede", no
 * "location_id"; avisa del correo repetido al salir del campo, no al enviar;
 * y da de baja en lugar de borrar, para no perder el historial (AUTH-08).
 */
class EmployeeManager extends Component
{
    use WithFileUploads, WithPagination;

    public $photo = null;   // foto subida temporalmente (HRADM-03)

    // ---- Filtros (en la URL, para poder compartir una vista filtrada) ----
    public string $search = '';
    public string $locationId = '';
    public string $departmentId = '';
    public string $status = 'active';

    // ---- Formulario ------------------------------------------------------
    public bool $showForm = false;
    public ?int $editingId = null;

    public string $first_name = '';
    public string $last_name = '';
    public string $preferred_name = '';
    public string $email = '';
    public string $extension = '';
    public string $phone = '';
    public string $job_title_es = '';
    public string $job_title_en = '';
    public string $department_id = '';
    public string $location_id = '';
    public string $manager_id = '';
    public string $birth_month = '';
    public string $birth_day = '';
    public string $hire_date = '';

    protected function rules(): array
    {
        return [
            'first_name'     => ['required', 'string', 'max:80'],
            'last_name'      => ['required', 'string', 'max:80'],
            'preferred_name' => ['nullable', 'string', 'max:80'],
            'email'          => [
                'required', 'email', 'max:180',
                Rule::unique('employees', 'email')->ignore($this->editingId)->withoutTrashed(),
            ],
            'extension'      => ['nullable', 'string', 'max:10'],
            'phone'          => ['nullable', 'string', 'max:40'],
            'job_title_es'   => ['nullable', 'string', 'max:140'],
            'job_title_en'   => ['nullable', 'string', 'max:140'],
            'department_id'  => ['nullable', 'exists:departments,id'],
            'location_id'    => ['nullable', 'exists:locations,id'],
            'manager_id'     => ['nullable', 'exists:employees,id'],
            'birth_month'    => ['nullable', 'integer', 'between:1,12'],
            'birth_day'      => ['nullable', 'integer', 'between:1,31'],
            'hire_date'      => ['nullable', 'date'],
        ];
    }

    protected function messages(): array
    {
        return [
            'email.unique'   => __('admin.validation.email_taken'),
            'first_name.required' => __('admin.validation.required'),
            'last_name.required'  => __('admin.validation.required'),
            'email.required' => __('admin.validation.required'),
            'email.email'    => __('admin.validation.email_format'),
        ];
    }

    /**
     * Normaliza el correo en cuanto se escribe, ANTES de validar.
     *
     * Si se normaliza dentro de save() —como estaba al principio— un correo
     * pegado con espacios alrededor se rechaza por "formato inválido", que
     * para quien captura es un mensaje absurdo: lo que pegó se ve bien.
     */
    public function updatedEmail(): void
    {
        $this->email = mb_strtolower(trim($this->email));
    }

    /** Valida al salir del campo, no al enviar: el error aparece donde ocurrió. */
    public function updated(string $campo): void
    {
        if (in_array($campo, ['search', 'locationId', 'departmentId', 'status'], true)) {
            $this->resetPage();

            return;
        }

        if ($this->showForm) {
            $this->validateOnly($campo);
        }
    }

    // ---- Acciones --------------------------------------------------------

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $e = Employee::findOrFail($id);

        $this->editingId      = $e->id;
        $this->first_name     = $e->first_name ?? '';
        $this->last_name      = $e->last_name ?? '';
        $this->preferred_name = $e->preferred_name ?? '';
        $this->email          = $e->email ?? '';
        $this->extension      = $e->extension ?? '';
        $this->phone          = $e->phone ?? '';
        $this->job_title_es   = $e->job_title_es ?? '';
        $this->job_title_en   = $e->job_title_en ?? '';
        $this->department_id  = (string) ($e->department_id ?? '');
        $this->location_id    = (string) ($e->location_id ?? '');
        $this->manager_id     = (string) ($e->manager_id ?? '');
        $this->birth_month    = (string) ($e->birth_month ?? '');
        $this->birth_day      = (string) ($e->birth_day ?? '');
        $this->hire_date      = $e->hire_date?->format('Y-m-d') ?? '';

        $this->resetErrorBag();
        $this->showForm = true;
    }

    public function save(): void
    {
        $datos = $this->validate();

        // Los <select> vacíos llegan como '' y la columna es nullable.
        foreach (['department_id', 'location_id', 'manager_id', 'birth_month', 'birth_day', 'hire_date', 'preferred_name', 'extension', 'phone', 'job_title_es', 'job_title_en'] as $k) {
            if (($datos[$k] ?? '') === '') {
                $datos[$k] = null;
            }
        }

        $datos['email'] = mb_strtolower(trim($datos['email']));

        // Foto a S3 si se subió una (HRADM-03). Clave única por empleado.
        if ($this->photo) {
            $this->validate(['photo' => 'image|max:4096']);   // hasta 4 MB
            $ext = $this->photo->getClientOriginalExtension() ?: 'jpg';
            $key = 'employees/photos/' . uniqid('emp_') . '.' . $ext;
            Storage::disk('s3')->put($key, file_get_contents($this->photo->getRealPath()), 'public');
            $datos['photo_path'] = Storage::disk('s3')->url($key);
        }

        if ($this->editingId) {
            Employee::findOrFail($this->editingId)->update($datos);
            $mensaje = __('admin.people.updated');
        } else {
            Employee::create($datos + ['is_active' => true]);
            $mensaje = __('admin.people.created');
        }

        $this->showForm = false;
        $this->resetForm();
        $this->dispatch('notify', message: $mensaje);
    }

    /**
     * Baja lógica (AUTH-08): sale del directorio y de cumpleaños, pero su
     * historial de reservas y solicitudes se conserva. Nunca se borra.
     */
    public function deactivate(int $id): void
    {
        $e = Employee::findOrFail($id);
        $e->update(['is_active' => false]);
        $e->delete();

        $this->dispatch('notify', message: __('admin.people.deactivated', ['name' => $e->full_name]));
    }

    public function restore(int $id): void
    {
        $e = Employee::withTrashed()->findOrFail($id);
        $e->restore();
        $e->update(['is_active' => true]);

        $this->dispatch('notify', message: __('admin.people.restored', ['name' => $e->full_name]));
    }

    public function cancel(): void
    {
        $this->showForm = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset([
            'editingId', 'first_name', 'last_name', 'preferred_name', 'email',
            'extension', 'phone', 'job_title_es', 'job_title_en',
            'department_id', 'location_id', 'manager_id',
            'birth_month', 'birth_day', 'hire_date', 'photo',
        ]);
        $this->resetErrorBag();
    }

    // ---- Datos para la vista --------------------------------------------

    #[Computed]
    public function locations()
    {
        return Location::orderBy('code')->get();
    }

    #[Computed]
    public function departments()
    {
        return Department::orderBy('name_es')->get();
    }

    /**
     * Avisos de calidad de datos. Convierten la migración sucia en una lista
     * de pendientes accionable, en lugar de un problema invisible.
     */
    #[Computed]
    public function dataWarnings(): array
    {
        $sinCumple = Employee::whereNull('birth_month')->orWhereNull('birth_day');
        $sinDepto  = Employee::whereNull('department_id');
        $sinSede   = Employee::whereNull('location_id');

        return [
            'sin_cumpleanos' => $sinCumple->count(),
            'sin_departamento' => $sinDepto->count(),
            'sin_sede' => $sinSede->count(),
        ];
    }

    public function render()
    {
        $q = Employee::query()
            ->with(['department', 'location'])
            ->search($this->search);

        if ($this->locationId !== '') {
            $q->where('location_id', $this->locationId);
        }

        if ($this->departmentId !== '') {
            $q->where('department_id', $this->departmentId);
        }

        match ($this->status) {
            'inactive' => $q->onlyTrashed(),
            'all'      => $q->withTrashed(),
            default    => $q,
        };

        return view('livewire.admin.employee-manager', [
            'employees' => $q->orderBy('last_name')->orderBy('first_name')->paginate(20),
        ])->layout('components.layouts.app', ['title' => __('nav.admin_people')]);
    }
}
