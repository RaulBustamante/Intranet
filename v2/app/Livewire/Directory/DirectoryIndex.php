<?php

namespace App\Livewire\Directory;

use App\Domain\People\Models\Department;
use App\Domain\People\Models\Employee;
use App\Domain\People\Models\Location;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Directorio de empleados (PPL-04, PPL-05, PPL-09).
 *
 * Diferencias con la v1, que enviaba las 200 filas de golpe dentro del HTML:
 *   - busca mientras se escribe, en el servidor
 *   - pagina de 24 en 24 (UX-12)
 *   - dos vistas, y recuerda cuál eligió el usuario (PPL-05)
 *   - los filtros viven en la URL, así que una vista filtrada se puede compartir
 */
class DirectoryIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q', except: '')]
    public string $search = '';

    #[Url(as: 'sede', except: '')]
    public string $locationId = '';

    #[Url(as: 'depto', except: '')]
    public string $departmentId = '';

    #[Url(as: 'vista', except: 'cards')]
    public string $view = 'cards';   // cards | table

    public function updated(string $campo): void
    {
        if (in_array($campo, ['search', 'locationId', 'departmentId'], true)) {
            $this->resetPage();
        }
    }

    public function setView(string $vista): void
    {
        $this->view = in_array($vista, ['cards', 'table'], true) ? $vista : 'cards';
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'locationId', 'departmentId']);
        $this->resetPage();
    }

    /**
     * Exporta el directorio filtrado a CSV (PPL-09).
     *
     * Respeta los filtros actuales (búsqueda, sede, departamento), no solo la
     * página visible. Lleva BOM UTF-8 para que Excel abra bien los acentos.
     */
    public function export(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $locale = app()->getLocale();
        $rows = Employee::query()
            ->with(['department:id,name_es,name_en', 'location:id,code,name_es,name_en'])
            ->active()
            ->search($this->search)
            ->when($this->locationId !== '', fn ($q) => $q->where('location_id', $this->locationId))
            ->when($this->departmentId !== '', fn ($q) => $q->where('department_id', $this->departmentId))
            ->orderBy('last_name')->orderBy('first_name')
            ->get();

        $headers = [
            __('app.directory.first_name'), __('app.directory.last_name'),
            __('app.directory.job_title'), __('app.directory.department'),
            __('app.directory.location'), __('app.directory.extension'),
            __('app.directory.phone'), __('app.directory.email'),
        ];

        $filename = 'directorio-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($rows, $headers, $locale) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");   // BOM UTF-8 para Excel
            fputcsv($out, $headers);
            foreach ($rows as $e) {
                fputcsv($out, [
                    $e->first_name,
                    $e->last_name,
                    $locale === 'en' ? ($e->job_title_en ?: $e->job_title_es) : ($e->job_title_es ?: $e->job_title_en),
                    $e->department?->name ?? '',
                    $e->location?->name ?? '',
                    $e->extension,
                    $e->phone,
                    $e->email,
                ]);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    #[Computed]
    public function locations()
    {
        return Location::whereHas('employees')->orderBy('code')->get();
    }

    #[Computed]
    public function departments()
    {
        return Department::whereHas('employees')->orderBy('name_es')->get();
    }

    public function render()
    {
        $employees = Employee::query()
            ->with(['department:id,name_es,name_en', 'location:id,code,name_es,name_en'])
            ->active()
            ->search($this->search)
            ->when($this->locationId !== '', fn ($q) => $q->where('location_id', $this->locationId))
            ->when($this->departmentId !== '', fn ($q) => $q->where('department_id', $this->departmentId))
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(24);

        return view('livewire.directory.directory-index', compact('employees'))
            ->layout('components.layouts.app', ['title' => __('nav.directory')]);
    }
}
