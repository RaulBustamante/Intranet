<?php

namespace App\Domain\Search\Ai;

/** Asistente apagado. isEnabled()=false: la interfaz nunca ofrece preguntar. */
class NullAiProvider implements AiProvider
{
    public function isEnabled(): bool
    {
        return false;
    }

    public function answer(string $question, string $locale, array $allowedDocumentIds): array
    {
        return ['answer' => '', 'sources' => []];
    }
}
