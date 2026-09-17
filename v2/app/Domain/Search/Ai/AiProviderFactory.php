<?php

namespace App\Domain\Search\Ai;

/**
 * Devuelve el proveedor AI configurado. Hoy siempre Null (no hay drivers reales
 * ni credenciales). Cuando existan ClaudeAiProvider / OpenAiAiProvider, aquí se
 * resuelven según config('ai.provider').
 */
class AiProviderFactory
{
    public function make(): AiProvider
    {
        return match (config('ai.provider', 'null')) {
            // 'claude' => new ClaudeAiProvider(...),   // Fase 6, requiere llave
            // 'openai' => new OpenAiAiProvider(...),
            default => new NullAiProvider(),
        };
    }
}
