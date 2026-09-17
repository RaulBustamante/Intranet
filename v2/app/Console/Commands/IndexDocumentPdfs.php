<?php

namespace App\Console\Commands;

use App\Domain\Content\Models\Document;
use App\Domain\Content\Services\PdfTextExtractor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Rellena `content_text` de los documentos que ya viven en S3 (SRCH-02).
 *
 * Los subidos por el panel ya se indexan al vuelo; esto es para los que existían
 * antes de que hubiera extracción, o para re-indexar. Descarga cada PDF a un
 * archivo temporal, extrae el texto y actualiza la fila. Idempotente: por
 * defecto salta los que ya tienen texto (--force reindexa todo).
 */
class IndexDocumentPdfs extends Command
{
    protected $signature = 'intranet:index-document-pdfs {--force : Reindexa incluso los que ya tienen texto}';

    protected $description = 'Extrae el texto de los PDFs en S3 para la búsqueda en contenido (SRCH-02)';

    public function handle(PdfTextExtractor $extractor): int
    {
        $query = Document::query()
            ->where(fn ($q) => $q->where('mime_type', 'like', '%pdf%')->orWhere('s3_key', 'like', '%.pdf'));

        if (! $this->option('force')) {
            $query->whereNull('content_text');
        }

        $docs = $query->get();
        if ($docs->isEmpty()) {
            $this->info('No hay documentos por indexar.');

            return self::SUCCESS;
        }

        $ok = 0;
        $sin = 0;
        foreach ($docs as $doc) {
            if (! $doc->s3_key || ! Storage::disk('s3')->exists($doc->s3_key)) {
                $this->warn("· {$doc->title}: sin archivo en S3, se salta.");
                continue;
            }

            $tmp = tempnam(sys_get_temp_dir(), 'docidx_') . '.pdf';
            file_put_contents($tmp, Storage::disk('s3')->get($doc->s3_key));

            $text = $extractor->fromPath($tmp, 'application/pdf');
            @unlink($tmp);

            $doc->update(['content_text' => $text]);
            $text ? $ok++ : $sin++;
            $this->line(($text ? '✓' : '·') . " {$doc->title}");
        }

        $this->info("Indexados con texto: {$ok}. Sin texto extraíble (escaneados/protegidos): {$sin}.");

        return self::SUCCESS;
    }
}
