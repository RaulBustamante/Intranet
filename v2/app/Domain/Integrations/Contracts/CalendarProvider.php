<?php

namespace App\Domain\Integrations\Contracts;

use App\Domain\Integrations\Models\LinkedAccount;
use App\Models\User;
use Carbon\CarbonInterface;

/**
 * Un proveedor de identidad + calendario que se puede VINCULAR a una cuenta
 * de la intranet (Microsoft/Teams, Google...).
 *
 * Es una interfaz, no una dependencia: la intranet funciona completa sin
 * ninguno conectado. Cuando TI provee credenciales OAuth, el proveedor
 * correspondiente se "enciende" (isEnabled()==true) y aparece la opción de
 * vincular. Mismo patrón que la capa de AI: null por defecto, cero costo y
 * cero llamadas externas hasta que alguien lo configure.
 */
interface CalendarProvider
{
    /** Nombre corto estable: 'microsoft' | 'google'. */
    public function key(): string;

    /** Etiqueta para la interfaz: 'Microsoft / Teams'. */
    public function label(): string;

    /**
     * ¿Está configurado? False si faltan las credenciales OAuth en el .env.
     * Mientras sea false, la opción de vincular se muestra deshabilitada con
     * un aviso, en lugar de fallar.
     */
    public function isEnabled(): bool;

    /** URL a la que se manda al usuario para autorizar la vinculación (OAuth). */
    public function authorizationUrl(User $user): string;

    /** Procesa el regreso del OAuth y crea o actualiza la cuenta vinculada. */
    public function handleCallback(array $query): LinkedAccount;

    /**
     * Disponibilidad (libre/ocupado) de la persona en un rango. Es la base del
     * "estilo Teams": no expone el detalle de cada cita, solo los bloques
     * ocupados, para poder decidir cuándo hay hueco para una junta.
     *
     * @return array<int, array{start: CarbonInterface, end: CarbonInterface, status: string}>
     */
    public function freeBusy(LinkedAccount $account, CarbonInterface $from, CarbonInterface $to): array;
}
