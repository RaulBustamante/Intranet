<?php

namespace App\Domain\Content\Services;

use Smalot\PdfParser\Parser;

/**
 * Extrae el texto plano de un PDF para la búsqueda en contenido (SRCH-02).
 *
 * Es tolerante: si el archivo no es PDF, está protegido, es escaneado (imagen
 * sin capa de texto) o el parser falla, devuelve null en vez de romper la
 * subida. La búsqueda simplemente caerá al título en esos casos.
 */
class PdfTextExtractor
{
    /** Tope de caracteres que guardamos (un PDF enorme no debe inflar la fila). */
    private const MAX_CHARS = 200_000;

    public function fromPath(string $path, ?string $mime = null): ?string
    {
        // Solo PDFs. Otros formatos (docx, xlsx) quedan para una fase futura.
        if ($mime !== null && stripos($mime, 'pdf') === false) {
            return null;
        }

        try {
            $text = (new Parser())->parseFile($path)->getText();
        } catch (\Throwable $e) {
            report($e);   // no rompe la subida; solo no habrá búsqueda en contenido

            return null;
        }

        $text = trim(preg_replace('/\s+/u', ' ', $text) ?? '');
        if ($text === '') {
            return null;
        }

        return mb_substr($text, 0, self::MAX_CHARS);
    }
}
