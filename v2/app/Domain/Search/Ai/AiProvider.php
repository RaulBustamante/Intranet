<?php

namespace App\Domain\Search\Ai;

/**
 * Asistente AI opcional (SRCH-06..SRCH-10).
 *
 * Interfaz, no dependencia: la búsqueda global funciona COMPLETA sin AI
 * (SRCH-10). Cuando se ponga AI_PROVIDER=claude|openai y su llave en el .env,
 * el asistente se enciende sin tocar código. Por defecto: null (apagado, cero
 * costo, cero llamadas externas).
 *
 * La recuperación de contexto (qué documentos ve el modelo) respeta permisos
 * ANTES de armar el prompt, así SRCH-09 se cumple por construcción: el modelo
 * no puede citar lo que el usuario no tiene derecho a ver.
 */
interface AiProvider
{
    public function isEnabled(): bool;

    /**
     * Responde una pregunta citando el documento fuente. Si no hay respaldo en
     * los documentos indexados, lo dice en vez de inventar (SRCH-08).
     *
     * @return array{answer: string, sources: array<int, array{title: string, url: string}>}
     */
    public function answer(string $question, string $locale, array $allowedDocumentIds): array;
}
