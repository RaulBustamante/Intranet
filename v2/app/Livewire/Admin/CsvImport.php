<?php

namespace App\Livewire\Admin;

use App\Domain\People\Models\Department;
use App\Domain\People\Models\Employee;
use App\Domain\People\Models\Location;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Carga masiva de empleados por CSV, con VISTA PREVIA antes de aplicar (HRADM-04).
 *
 * Es como RH mete a los ~130 de planta que el directorio de la v1 no tenia.
 * La vista previa muestra cuantas altas, cuantas actualizaciones y cuantas
 * filas con error (y por que) ANTES de escribir nada. RH aplica o cancela.
 *
 * Columnas esperadas (encabezado, en espanol o ingles):
 *   nombre, apellido, correo, extension, telefono, departamento, sede,
 *   puesto, mes_cumple, dia_cumple
 */
class CsvImport extends Component
{
    use WithFileUploads;

    public $file = null;
    public array $preview = [];
    public array $summary = [];
    public bool $analyzed = false;

    private const MAP = [
        'nombre' => 'first_name', 'first_name' => 'first_name', 'first name' => 'first_name',
        'apellido' => 'last_name', 'last_name' => 'last_name', 'last name' => 'last_name',
        'correo' => 'email', 'email' => 'email', 'e-mail' => 'email',
        'extension' => 'extension', 'ext' => 'extension',
        'telefono' => 'phone', 'phone' => 'phone',
        'departamento' => 'department', 'department' => 'department', 'depto' => 'department',
        'sede' => 'location', 'location' => 'location', 'ubicacion' => 'location',
        'puesto' => 'job_title', 'job_title' => 'job_title', 'title' => 'job_title',
        'mes_cumple' => 'birth_month', 'birth_month' => 'birth_month',
        'dia_cumple' => 'birth_day', 'birth_day' => 'birth_day',
    ];

    public function analyze(): void
    {
        $this->validate(['file' => 'required|file|mimes:csv,txt|max:4096']);

        $rows = $this->readCsv($this->file->getRealPath());
        $this->preview = [];
        $counts = ['alta' => 0, 'update' => 0, 'error' => 0];
        $vistos = [];

        foreach ($rows as $n => $row) {
            $email = mb_strtolower(trim($row['email'] ?? ''));
            $nombre = trim(($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? '')) ?: '(sin nombre)';
            $accion = null;
            $motivo = '';

            if (($row['first_name'] ?? '') === '' || ($row['last_name'] ?? '') === '') {
                $accion = 'error';
                $motivo = __('csv.err_name');
            } elseif ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $accion = 'error';
                $motivo = __('csv.err_email');
            } elseif (isset($vistos[$email])) {
                $accion = 'error';
                $motivo = __('csv.err_dup');
            } else {
                $vistos[$email] = true;
                $accion = Employee::withTrashed()->where('email', $email)->exists() ? 'update' : 'alta';
            }

            $counts[$accion]++;
            $this->preview[] = ['fila' => $n + 2, 'nombre' => $nombre, 'email' => $email, 'accion' => $accion, 'motivo' => $motivo, 'row' => $row];
        }

        $this->summary = $counts;
        $this->analyzed = true;
    }

    public function apply(): void
    {
        DB::transaction(function () {
            foreach ($this->preview as $p) {
                if ($p['accion'] === 'error') {
                    continue;
                }
                $row = $p['row'];

                $deptoId = null;
                if (! empty($row['department'])) {
                    $deptoId = Department::firstOrCreate(['name_es' => $row['department']], ['name_en' => $row['department']])->id;
                }
                $sedeId = null;
                if (! empty($row['location'])) {
                    $sedeId = Location::firstOrCreate(['code' => $row['location']], ['name_es' => $row['location'], 'name_en' => $row['location']])->id;
                }

                Employee::withTrashed()->updateOrCreate(
                    ['email' => mb_strtolower(trim($row['email']))],
                    array_filter([
                        'first_name'    => $row['first_name'] ?? null,
                        'last_name'     => $row['last_name'] ?? null,
                        'extension'     => $row['extension'] ?? null,
                        'phone'         => $row['phone'] ?? null,
                        'job_title_es'  => $row['job_title'] ?? null,
                        'department_id' => $deptoId,
                        'location_id'   => $sedeId,
                        'birth_month'   => is_numeric($row['birth_month'] ?? null) ? (int) $row['birth_month'] : null,
                        'birth_day'     => is_numeric($row['birth_day'] ?? null) ? (int) $row['birth_day'] : null,
                        'is_active'     => true,
                    ], fn ($v) => $v !== null)
                );
            }
        });

        $this->dispatch('notify', message: __('csv.applied', ['n' => $this->summary['alta'] + $this->summary['update']]));
        $this->reset(['file', 'preview', 'summary', 'analyzed']);
    }

    public function cancel(): void
    {
        $this->reset(['file', 'preview', 'summary', 'analyzed']);
    }

    /** @return array<int, array<string, string>> */
    private function readCsv(string $path): array
    {
        $out = [];
        if (($h = fopen($path, 'r')) === false) {
            return $out;
        }

        $header = fgetcsv($h);
        if (! $header) {
            return $out;
        }
        $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);
        $cols = array_map(fn ($c) => self::MAP[mb_strtolower(trim($c))] ?? null, $header);

        while (($data = fgetcsv($h)) !== false) {
            $row = [];
            foreach ($cols as $i => $key) {
                if ($key) {
                    $row[$key] = trim($data[$i] ?? '');
                }
            }
            if (array_filter($row)) {
                $out[] = $row;
            }
        }
        fclose($h);

        return $out;
    }

    public function render()
    {
        return view('livewire.admin.csv-import')
            ->layout('components.layouts.app', ['title' => __('csv.title')]);
    }
}
