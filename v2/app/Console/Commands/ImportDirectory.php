<?php

namespace App\Console\Commands;

use App\Domain\People\Import\DirectoryHtmlParser;
use App\Domain\People\Models\Department;
use App\Domain\People\Models\Employee;
use App\Domain\People\Models\Location;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Importa el directorio de la v1 a base de datos (PPL-01).
 *
 * Es idempotente: correrlo dos veces no duplica. La clave natural es el
 * correo; las filas sin correo se reportan y NO se importan, porque sin
 * clave no hay forma de volver a correr el importador sin duplicarlas.
 *
 *   php artisan intranet:import-directory --dry-run   (no escribe nada)
 *   php artisan intranet:import-directory             (aplica)
 */
class ImportDirectory extends Command
{
    protected $signature = 'intranet:import-directory
                            {--file= : Ruta del directory.blade.php de la v1}
                            {--dry-run : Solo reporta, no escribe nada}
                            {--report= : Ruta del CSV de discrepancias}';

    protected $description = 'Importa los empleados del HTML de la intranet v1 a la base de datos';

    public function handle(DirectoryHtmlParser $parser): int
    {
        $archivo = $this->option('file') ?: base_path('../resources/views/directory.blade.php');
        $simulacro = (bool) $this->option('dry-run');

        $this->info($simulacro ? 'MODO SIMULACRO: no se escribirá nada.' : 'MODO REAL: se escribirá en la base de datos.');
        $this->line('Origen: ' . $archivo);
        $this->newLine();

        try {
            ['filas' => $filas, 'avisos' => $avisos] = $parser->parse($archivo);
        } catch (\Throwable $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->line('Filas encontradas en el HTML: <fg=cyan>' . count($filas) . '</>');
        foreach ($avisos as $a) {
            $this->warn('  ' . $a);
        }
        $this->newLine();

        // ---- Análisis previo, antes de tocar nada --------------------------

        $problemas = [];
        $correosVistos = [];

        foreach ($filas as $f) {
            $etiqueta = trim(($f['first_name'] ?? '') . ' ' . ($f['last_name'] ?? '')) ?: '(sin nombre)';

            if (! empty($f['_sin_nombre'])) {
                $problemas[] = [$f['_fila'], $etiqueta, 'sin nombre o apellido', 'No se importa', ''];
            }

            if (! empty($f['_sin_correo'])) {
                $problemas[] = [$f['_fila'], $etiqueta, 'sin correo', 'No se importa: sin correo no hay clave para reimportar sin duplicar', ''];
            } elseif (! empty($f['_correo_invalido'])) {
                $problemas[] = [$f['_fila'], $etiqueta, 'correo con formato inválido', 'No se importa', $f['email']];
            } else {
                $correo = mb_strtolower($f['email']);
                if (isset($correosVistos[$correo])) {
                    $problemas[] = [$f['_fila'], $etiqueta, 'correo duplicado', 'Solo se importa la primera aparición (fila ' . $correosVistos[$correo] . ')', $f['email']];
                } else {
                    $correosVistos[$correo] = $f['_fila'];
                }
            }

            if (! empty($f['_divergencia_sede'])) {
                $problemas[] = [
                    $f['_fila'], $etiqueta, 'sede inconsistente',
                    'El filtro usa "' . $f['location_attr'] . '" y la tabla muestra "' . $f['location_cell'] . '". Se importa la de la CELDA VISIBLE, que es la que la gente lee. RH debe confirmar',
                    $f['location_attr'] . ' vs ' . $f['location_cell'],
                ];
            }
        }

        // Extensiones repetidas dentro de la misma sede (valida HRADM-05)
        $porSede = [];
        foreach ($filas as $f) {
            $ext = $f['extension'] ?? '';
            $sede = $f['location_cell'] ?: $f['location_attr'];
            if ($ext === '' || $sede === '') {
                continue;
            }
            $porSede[$sede][$ext][] = trim($f['first_name'] . ' ' . $f['last_name']);
        }
        foreach ($porSede as $sede => $exts) {
            foreach ($exts as $ext => $personas) {
                if (count($personas) > 1) {
                    $problemas[] = [
                        '—', implode(' | ', $personas), 'extensión compartida',
                        'La extensión ' . $ext . ' en la sede ' . $sede . ' la traen ' . count($personas) . ' personas. Puede ser legítimo (extensión de área) o error de captura. RH decide',
                        $ext,
                    ];
                }
            }
        }

        // ---- Resumen -------------------------------------------------------

        $importables = array_filter($filas, fn ($f) => empty($f['_sin_nombre']) && empty($f['_sin_correo']) && empty($f['_correo_invalido']));
        $unicos = [];
        foreach ($importables as $f) {
            $unicos[mb_strtolower($f['email'])] = $f;
        }

        $this->table(
            ['Concepto', 'Cantidad'],
            [
                ['Filas en el HTML',            count($filas)],
                ['Importables (con correo)',    count($unicos)],
                ['Sin correo',                  count(array_filter($filas, fn ($f) => ! empty($f['_sin_correo'])))],
                ['Correo inválido',             count(array_filter($filas, fn ($f) => ! empty($f['_correo_invalido'])))],
                ['Correos duplicados',          count($importables) - count($unicos)],
                ['Sede inconsistente',          count(array_filter($filas, fn ($f) => ! empty($f['_divergencia_sede'])))],
                ['Discrepancias totales',       count($problemas)],
            ]
        );

        // ---- Reporte CSV ---------------------------------------------------

        $rutaReporte = $this->option('report')
            ?: storage_path('app/importacion-directorio-' . now()->format('Ymd_His') . '.csv');

        @mkdir(dirname($rutaReporte), 0775, true);
        $fh = fopen($rutaReporte, 'w');
        fwrite($fh, "\xEF\xBB\xBF"); // BOM: para que Excel abra bien los acentos
        fputcsv($fh, ['Fila', 'Persona', 'Problema', 'Qué se hizo', 'Dato']);
        foreach ($problemas as $p) {
            fputcsv($fh, $p);
        }
        fclose($fh);

        $this->line('Reporte de discrepancias: <fg=cyan>' . $rutaReporte . '</>');
        $this->newLine();

        if ($simulacro) {
            $this->warn('Simulacro: no se escribió nada. Revisa el reporte con RH antes de aplicar.');

            return self::SUCCESS;
        }

        // ---- Escritura -----------------------------------------------------

        $creados = 0;
        $actualizados = 0;

        DB::transaction(function () use ($unicos, &$creados, &$actualizados) {
            $sedes = [];
            $deptos = [];

            foreach ($unicos as $f) {
                $codigoSede = $f['location_cell'] ?: $f['location_attr'];

                if ($codigoSede !== '' && ! isset($sedes[$codigoSede])) {
                    // Nombre = código hasta que RH aclare qué significan
                    // 1920, 8825 y BSU (pendiente P1).
                    $sedes[$codigoSede] = Location::firstOrCreate(
                        ['code' => $codigoSede],
                        ['name_es' => $codigoSede, 'name_en' => $codigoSede]
                    );
                }

                $nombreDepto = $f['department'];
                if ($nombreDepto !== '' && ! isset($deptos[$nombreDepto])) {
                    $deptos[$nombreDepto] = Department::firstOrCreate(
                        ['name_es' => $nombreDepto],
                        ['name_en' => $nombreDepto]
                    );
                }

                $empleado = Employee::withTrashed()->firstOrNew(['email' => mb_strtolower($f['email'])]);
                $existia = $empleado->exists;

                $empleado->fill([
                    'first_name'    => $f['first_name'],
                    'last_name'     => $f['last_name'],
                    'extension'     => $f['extension'] ?: null,
                    'phone'         => $f['phone'] ?: null,
                    'fax'           => $f['fax'] ?: null,
                    'department_id' => $nombreDepto !== '' ? $deptos[$nombreDepto]->id : null,
                    'location_id'   => $codigoSede !== '' ? $sedes[$codigoSede]->id : null,
                    'is_active'     => true,
                ])->save();

                $existia ? $actualizados++ : $creados++;
            }
        });

        $this->info("Listo. Creados: {$creados} · Actualizados: {$actualizados}");
        $this->line('Sedes en catálogo: ' . Location::count() . ' · Departamentos: ' . Department::count());
        $this->newLine();
        $this->warn('SIGUIENTE PASO OBLIGATORIO: revisar el reporte de discrepancias con RH (tarea 2.5).');
        $this->line('El importador no inventó datos. Lo que no cuadraba quedó en el CSV para que una persona lo decida.');

        return self::SUCCESS;
    }
}
