<?php

namespace App\Domain\Integrations;

use App\Domain\Integrations\Contracts\CalendarProvider;
use App\Domain\Integrations\Providers\NullCalendarProvider;

/**
 * Sabe qué proveedores existen y cuáles están encendidos.
 *
 * En la Fase 3, cuando TI provea credenciales, aquí se resolverán los drivers
 * reales (MicrosoftCalendarProvider, GoogleCalendarProvider). Hoy todos caen
 * al Null porque no hay credenciales: la interfaz lo refleja honestamente.
 */
class ProviderRegistry
{
    /** @return array<string, CalendarProvider> */
    public function all(): array
    {
        $out = [];

        foreach (config('integrations', []) as $key => $conf) {
            $out[$key] = $this->resolve($key, $conf);
        }

        return $out;
    }

    public function get(string $key): ?CalendarProvider
    {
        $conf = config("integrations.{$key}");

        return $conf ? $this->resolve($key, $conf) : null;
    }

    /** Solo los que TI ya configuró. */
    public function enabled(): array
    {
        return array_filter($this->all(), fn (CalendarProvider $p) => $p->isEnabled());
    }

    public function anyEnabled(): bool
    {
        return $this->enabled() !== [];
    }

    private function resolve(string $key, array $conf): CalendarProvider
    {
        $configurado = ! empty($conf['client_id']) && ! empty($conf['client_secret']);

        // Cuando existan, aquí se devuelven los drivers reales:
        //   return match ($key) {
        //       'microsoft' => new Providers\MicrosoftCalendarProvider($conf),
        //       'google'    => new Providers\GoogleCalendarProvider($conf),
        //   };
        // Por ahora, sin credenciales, todo es Null (isEnabled()==false).
        if (! $configurado) {
            return new NullCalendarProvider($key, $conf['label'] ?? ucfirst($key));
        }

        // Marcador temporal hasta implementar los drivers reales (Fase 3):
        // aunque haya credenciales, se comporta como Null para no prometer
        // lo que aún no está construido.
        return new NullCalendarProvider($key, $conf['label'] ?? ucfirst($key));
    }
}
