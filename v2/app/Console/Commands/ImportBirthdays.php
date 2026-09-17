<?php

namespace App\Console\Commands;

use App\Domain\People\Models\Employee;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Importa los ~250 cumpleaños del HTML de la v1 (PPL-02).
 *
 * El problema de fondo: la lista de cumpleaños de la v1 solo trae NOMBRE y DÍA.
 * No hay correo, no hay departamento, no hay sede. Así que el único enlace
 * posible con la tabla de empleados es el nombre, y eso falla en:
 *
 *   - homónimos (dos "Jose Martinez")
 *   - apodos ("Pepe" contra "Jose")
 *   - orden invertido ("Martinez Jose")
 *   - acentos inconsistentes ("Nuno" contra "Nuño")
 *
 * Por eso este comando NO adivina. Casa lo que puede con certeza, y todo lo
 * demás lo deja en un reporte para que RH lo resuelva (tarea 2.5).
 */
class ImportBirthdays extends Command
{
    protected $signature = 'intranet:import-birthdays
                            {--file= : Ruta del birthdays.blade.php de la v1}
                            {--dry-run : Solo reporta, no escribe nada}
                            {--report= : Ruta del CSV de no coincidencias}';

    protected $description = 'Importa los cumpleaños del HTML de la intranet v1 y los liga a empleados';

    private const MESES = [
        'enero' => 1, 'febrero' => 2, 'marzo' => 3, 'abril' => 4,
        'mayo' => 5, 'junio' => 6, 'julio' => 7, 'agosto' => 8,
        'septiembre' => 9, 'octubre' => 10, 'noviembre' => 11, 'diciembre' => 12,
    ];

    public function handle(): int
    {
        $archivo = $this->option('file') ?: base_path('../resources/views/birthdays.blade.php');
        $simulacro = (bool) $this->option('dry-run');

        if (! is_file($archivo)) {
            $this->error("No encontré el archivo: {$archivo}");

            return self::FAILURE;
        }

        $this->info($simulacro ? 'MODO SIMULACRO: no se escribirá nada.' : 'MODO REAL.');
        $this->line('Origen: ' . $archivo);
        $this->newLine();

        $entradas = $this->parsear(file_get_contents($archivo));
        $this->line('Cumpleaños encontrados en el HTML: <fg=cyan>' . count($entradas) . '</>');

        // Índice de empleados por nombre normalizado
        $empleados = Employee::withTrashed()->get();
        $indice = [];
        foreach ($empleados as $e) {
            $indice[$this->normalizar($e->full_name)][] = $e;
        }

        $casados = 0;
        $ambiguos = 0;
        $sinCoincidencia = 0;
        $conflictos = 0;
        $problemas = [];
        $aplicar = [];

        foreach ($entradas as $en) {
            $clave = $this->normalizar($en['nombre']);
            $candidatos = $indice[$clave] ?? [];

            if (count($candidatos) === 0) {
                $sinCoincidencia++;
                $problemas[] = [$en['nombre'], sprintf('%02d-%02d', $en['mes'], $en['dia']), 'sin coincidencia',
                    'Nadie en el directorio se llama asi. La lista de cumpleanos y el directorio cubren poblaciones distintas: el directorio es sobre todo personal de oficina con correo, y los cumpleanos incluyen planta y produccion', $en['depto_hint']];

                continue;
            }

            if (count($candidatos) > 1) {
                $ambiguos++;
                $problemas[] = [$en['nombre'], sprintf('%02d-%02d', $en['mes'], $en['dia']), 'ambiguo',
                    'Hay ' . count($candidatos) . ' personas con ese nombre. RH debe decidir cuál',
                    implode(' | ', array_map(fn ($c) => $c->email, $candidatos))];

                continue;
            }

            $e = $candidatos[0];

            // Ya tenía cumpleaños y no coincide: no se pisa en silencio
            if ($e->birth_month && $e->birth_day
                && ($e->birth_month !== $en['mes'] || $e->birth_day !== $en['dia'])) {
                $conflictos++;
                $problemas[] = [$en['nombre'], sprintf('%02d-%02d', $en['mes'], $en['dia']), 'conflicto',
                    'Ya tenía ' . sprintf('%02d-%02d', $e->birth_month, $e->birth_day) . ' y el HTML dice otra fecha. NO se sobrescribió', $e->email];

                continue;
            }

            $aplicar[$e->id] = ['mes' => $en['mes'], 'dia' => $en['dia']];
            $casados++;
        }

        $this->table(['Concepto', 'Cantidad'], [
            ['Cumpleaños en el HTML', count($entradas)],
            ['Casados con un empleado', $casados],
            ['Sin coincidencia', $sinCoincidencia],
            ['Nombre ambiguo', $ambiguos],
            ['Conflicto de fecha', $conflictos],
        ]);

        $rutaReporte = $this->option('report')
            ?: storage_path('app/importacion-cumpleanos-' . now()->format('Ymd_His') . '.csv');
        @mkdir(dirname($rutaReporte), 0775, true);
        $fh = fopen($rutaReporte, 'w');
        fwrite($fh, "\xEF\xBB\xBF");
        fputcsv($fh, ['Nombre en el HTML', 'Fecha', 'Problema', 'Qué se hizo', 'Dato']);
        foreach ($problemas as $p) {
            fputcsv($fh, $p);
        }
        fclose($fh);

        $this->line('Reporte de no coincidencias: <fg=cyan>' . $rutaReporte . '</>');

        if ($simulacro) {
            $this->newLine();
            $this->warn('Simulacro: no se escribió nada.');

            return self::SUCCESS;
        }

        DB::transaction(function () use ($aplicar) {
            foreach ($aplicar as $id => $f) {
                Employee::withTrashed()->where('id', $id)
                    ->update(['birth_month' => $f['mes'], 'birth_day' => $f['dia']]);
            }
        });

        $sinFecha = Employee::whereNull('birth_month')->count();

        $this->newLine();
        $this->info("Aplicados {$casados} cumpleaños.");
        $this->line("Empleados que siguen sin fecha: <fg=yellow>{$sinFecha}</>");
        $this->newLine();
        $this->warn('Los ' . count($problemas) . ' casos del reporte necesitan a una persona de RH. No los inventé.');

        return self::SUCCESS;
    }

    /**
     * Lee las 12 listas del HTML. Cada <li> trae "Nombre Apellido - Mes Día".
     *
     * @return array<int, array{nombre: string, mes: int, dia: int, depto_hint: string}>
     */
    private function parsear(string $html): array
    {
        $salida = [];

        $doc = new \DOMDocument();
        $previo = libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        libxml_clear_errors();
        libxml_use_internal_errors($previo);

        $xpath = new \DOMXPath($doc);

        // Cada mes es un <ul id="january" class="birthday-list">. El id en
        // inglés es la única pista fiable del mes, porque el TERCER formato
        // (ver abajo) no lo trae en el texto de la línea.
        $idsMes = [
            'january' => 1, 'february' => 2, 'march' => 3, 'april' => 4,
            'may' => 5, 'june' => 6, 'july' => 7, 'august' => 8,
            'september' => 9, 'october' => 10, 'november' => 11, 'december' => 12,
        ];

        foreach ($xpath->query('//ul[@class="birthday-list"]') as $ul) {
            $mesDelBloque = $idsMes[mb_strtolower($ul->getAttribute('id'))] ?? null;

            foreach ($xpath->query('./li', $ul) as $li) {
                $texto = trim(preg_replace('/\s+/u', ' ', $li->textContent));

                if ($texto === '') {
                    continue;
                }

                $nombre = null;
                $mes = null;
                $dia = null;
                $depto = '';

                // La v1 mezcla TRES formatos en la misma lista:
                //
                //   1. "Samuel Vazquez - Enero 3"
                //   2. "Tomas Camacho - Marzo 2 (Almacén)"
                //   3. "Jesus Erasmo Santos - 5 - Produccion"   <- sin mes
                //
                // El tercero saca el mes del <ul> que lo contiene. Los tres
                // conviven porque la lista se fue editando a mano durante
                // años, cada quien con su estilo.

                // Formato 3: nombre - día - departamento
                if (preg_match('/^(.+?)\s*[-–]\s*(\d{1,2})\s*[-–]\s*(.+?)$/u', $texto, $p)) {
                    $nombre = trim($p[1]);
                    $dia = (int) $p[2];
                    $depto = trim($p[3]);
                    $mes = $mesDelBloque;
                }
                // Formatos 1 y 2: nombre - Mes día [(departamento)]
                elseif (preg_match('/^(.+?)\s*[-–]\s*([A-Za-zÁÉÍÓÚáéíóúñÑ]+)\s+(\d{1,2})\s*(?:\(([^)]*)\))?\s*$/u', $texto, $p)) {
                    $nombre = trim($p[1]);
                    $mes = self::MESES[mb_strtolower($p[2])] ?? $mesDelBloque;
                    $dia = (int) $p[3];
                    $depto = isset($p[4]) ? trim($p[4]) : '';
                }

                if (! $nombre || ! $mes || ! $dia || $dia < 1 || $dia > 31) {
                    continue;
                }

                $salida[] = [
                    'nombre'     => $nombre,
                    'mes'        => $mes,
                    'dia'        => $dia,
                    'depto_hint' => $depto,
                ];
            }
        }

        return $salida;
    }

    /** Minúsculas, sin acentos, espacios colapsados. Para casar nombres. */
    private function normalizar(string $s): string
    {
        $s = mb_strtolower(trim($s));
        $s = strtr($s, [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'ü' => 'u', 'ñ' => 'n', 'ç' => 'c',
            '’' => '', "'" => '',
        ]);

        return preg_replace('/\s+/', ' ', $s);
    }
}
