<?php

namespace App\Domain\Integrations\Providers;

use App\Domain\Integrations\Contracts\CalendarProvider;
use App\Domain\Integrations\Models\LinkedAccount;
use App\Models\User;
use Carbon\CarbonInterface;
use RuntimeException;

/**
 * Proveedor inerte: se usa cuando un proveedor no está configurado.
 *
 * isEnabled() es false, así que la interfaz nunca ofrece vincularlo y nunca
 * se llega a llamar a los demás métodos. Si algo los llamara por error, fallan
 * ruidosamente en vez de fingir que funcionan.
 */
class NullCalendarProvider implements CalendarProvider
{
    public function __construct(private string $key, private string $label) {}

    public function key(): string { return $this->key; }

    public function label(): string { return $this->label; }

    public function isEnabled(): bool { return false; }

    public function authorizationUrl(User $user): string
    {
        throw new RuntimeException("El proveedor {$this->key} no está configurado.");
    }

    public function handleCallback(array $query): LinkedAccount
    {
        throw new RuntimeException("El proveedor {$this->key} no está configurado.");
    }

    public function freeBusy(LinkedAccount $account, CarbonInterface $from, CarbonInterface $to): array
    {
        return [];
    }
}
