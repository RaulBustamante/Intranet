<?php

namespace App\Console\Commands;

use App\Domain\Content\Models\Bulletin;
use App\Domain\Content\Models\Document;
use App\Domain\Content\Models\DocumentCategory;
use App\Domain\Content\Models\Link;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Importa el contenido de la v1 a la base de datos (Fase 4):
 *  - boletines y documentos que ya están en S3 (uploads/*)
 *  - los 20 enlaces internos hardcodeados en enlaces.blade.php
 *
 * Idempotente. El título de cada archivo se deriva del nombre; RH lo corrige
 * después desde el panel.
 */
class ImportContent extends Command
{
    protected $signature = 'intranet:import-content {--dry-run}';
    protected $description = 'Importa boletines, documentos y enlaces de la v1';

    /** Los 20 enlaces de la v1 (enlaces.blade.php). */
    private const LINKS = [
        ['Ariel Premium', 'https://www.arielpremium.com', false],
        ['Ariel Asset Management', 'http://191.168.0.222/login', true],
        ['Ariel Dashboards', 'http://191.168.50.33/', true],
        ['Ariel Helpdesk', 'https://helpme.arielapps.net/scp', false],
        ['Ariel Knowledge', 'http://191.168.8.201/shelves', true],
        ['Ariel Wiki', 'http://191.168.0.65/index.php/Main_Page', true],
        ['ChatGPT', 'https://chat.openai.com/', false],
        ['Dropbox', 'https://www.dropbox.com', false],
        ['Goblin Tools', 'https://goblin.tools/', false],
        ['Google Meet', 'https://meet.google.com', false],
        ['Language Tool', 'https://www.languagetool.org', false],
        ['Más Orden', 'http://masorden.com', false],
        ['Metabase', 'http://192.168.1.228:3000', true],
        ['Monday', 'https://arielpremiumsupply.monday.com', false],
        ['Nextcloud', 'https://cloud.arielpremium.com/', false],
        ['Qualityweb 360', 'https://system.qualityweb360.com/', false],
        ['Sodexo - Pluxee', 'https://www.sodexo.es/pluxee/', false],
        ['Trello', 'https://www.trello.com', false],
        ['Tress Revolution', 'https://login.tressrevolution.com/Revolution/', false],
        ['Zoom', 'https://www.zoom.com', false],
    ];

    public function handle(): int
    {
        $dry = (bool) $this->option('dry-run');
        $this->info($dry ? 'SIMULACRO' : 'MODO REAL');

        // --- Enlaces ---
        $this->line('Enlaces: ' . count(self::LINKS));
        if (! $dry) {
            foreach (self::LINKS as $i => [$title, $url, $internal]) {
                Link::updateOrCreate(['url' => $url], [
                    'title_es' => $title, 'title_en' => $title,
                    'is_internal_only' => $internal, 'sort_order' => $i, 'is_active' => true,
                ]);
            }
        }

        // --- Boletines desde S3 ---
        $boletines = collect(Storage::disk('s3')->files('uploads/boletines'))
            ->filter(fn ($k) => Str::endsWith(strtolower($k), '.pdf'));
        $this->line('Boletines en S3: ' . $boletines->count());

        if (! $dry) {
            foreach ($boletines as $key) {
                [$year, $month] = $this->guessPeriod(basename($key));
                Bulletin::updateOrCreate(
                    ['period_year' => $year, 'period_month' => $month],
                    ['title_es' => $this->titleFrom(basename($key)), 's3_key' => $key, 'published_at' => now()]
                );
            }
        }

        // --- Documentos desde S3 ---
        $docs = collect(Storage::disk('s3')->files('uploads/documentos'));
        $this->line('Documentos en S3: ' . $docs->count());

        if (! $dry) {
            $cat = DocumentCategory::firstOrCreate(
                ['slug' => 'general'],
                ['name_es' => 'General', 'name_en' => 'General', 'visibility' => 'all']
            );
            foreach ($docs as $key) {
                Document::updateOrCreate(['s3_key' => $key], [
                    'title_es' => $this->titleFrom(basename($key)),
                    'category_id' => $cat->id,
                    'mime_type' => 'application/pdf',
                    'published_at' => now(),
                ]);
            }
        }

        if ($dry) {
            $this->warn('Simulacro: no se escribió nada.');
        } else {
            $this->info('Listo. Enlaces: ' . Link::count() . ' · Boletines: ' . Bulletin::count() . ' · Documentos: ' . Document::count());
        }

        return self::SUCCESS;
    }

    /**
     * Deriva año y mes del nombre. Reconoce dos formatos de la v1:
     *   "Comunicado.05.24.pdf"     (mes.año numérico)
     *   "COMUNICADOS ABRIL 2025"   (nombre de mes + año)
     * Ya no colisiona: la identidad del boletín es su s3_key, no el periodo.
     */
    private function guessPeriod(string $name): array
    {
        $meses = [
            'enero' => 1, 'febrero' => 2, 'marzo' => 3, 'abril' => 4, 'mayo' => 5, 'junio' => 6,
            'julio' => 7, 'agosto' => 8, 'septiembre' => 9, 'octubre' => 10, 'noviembre' => 11, 'diciembre' => 12,
            'january' => 1, 'february' => 2, 'march' => 3, 'april' => 4, 'may' => 5, 'june' => 6,
            'july' => 7, 'august' => 8, 'september' => 9, 'october' => 10, 'november' => 11, 'december' => 12,
        ];
        $low = mb_strtolower($name);
        $month = null;
        $year = null;

        foreach ($meses as $nombre => $num) {
            if (str_contains($low, $nombre)) {
                $month = $num;
                break;
            }
        }
        if (preg_match('/(20\d{2})/', $name, $m)) {
            $year = (int) $m[1];
        }
        if ($month === null && preg_match('/(\d{1,2})[.\-_ ](\d{2,4})/', $name, $m)) {
            $mm = (int) $m[1];
            $yy = (int) $m[2];
            if ($mm >= 1 && $mm <= 12) {
                $month = $mm;
                $year = $year ?? ($yy < 100 ? 2000 + $yy : $yy);
            }
        }

        return [$year ?? now()->year, $month ?? 1];
    }

    private function titleFrom(string $name): string
    {
        $t = preg_replace('/\.(pdf|docx?|pptx?)$/i', '', $name);

        return Str::of($t)->replace(['.', '_', '-'], ' ')->squish()->title()->toString();
    }
}
