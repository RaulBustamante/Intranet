<?php

namespace App\Domain\Rooms\Services;

use App\Domain\Rooms\Models\RoomBooking;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;

/**
 * Garantiza que no existan dos reservas solapadas en la misma sala (ROOM-02).
 *
 * Lo que corrige de la v1:
 *   - La v1 detectaba choques con TRES cláusulas OR y NO filtraba por status,
 *     así que una reserva rechazada seguía bloqueando la sala (ROOM-03).
 *   - Aquí la condición es una sola: dos intervalos [a,b) y [c,d) se solapan
 *     si y solo si  a < d  AND  b > c.  Cubre los cuatro casos (envolvente,
 *     envuelto y los dos parciales) sin enumerarlos.
 *   - Y se filtra por status='confirmed': una reserva cancelada libera el hueco.
 *
 * La garantía real vive en una transacción con bloqueo (ver reserve()), para
 * que dos peticiones simultáneas no puedan pasar las dos.
 */
class OverlapGuard
{
    /** ¿Hay ya una reserva confirmada que choque con [start, end) en esta sala? */
    public function hasConflict(int $roomId, CarbonInterface $start, CarbonInterface $end, ?int $ignoreId = null): bool
    {
        return $this->conflictQuery($roomId, $start, $end, $ignoreId)->exists();
    }

    /**
     * Crea la reserva de forma segura ante concurrencia.
     *
     * Envuelve la comprobación y el insert en una transacción con
     * lockForUpdate() sobre las reservas de esa sala, de modo que dos
     * peticiones idénticas al mismo tiempo se serializan y solo una gana.
     *
     * @throws RoomUnavailableException si el horario ya está ocupado
     */
    public function reserve(array $data): RoomBooking
    {
        return DB::transaction(function () use ($data) {
            $conflict = $this->conflictQuery(
                $data['room_id'],
                $data['starts_at'],
                $data['ends_at'],
                $data['id'] ?? null
            )->lockForUpdate()->exists();

            if ($conflict) {
                throw new RoomUnavailableException(__('rooms.unavailable'));
            }

            return RoomBooking::create($data + ['status' => 'confirmed']);
        });
    }

    private function conflictQuery(int $roomId, CarbonInterface $start, CarbonInterface $end, ?int $ignoreId)
    {
        return RoomBooking::query()
            ->where('room_id', $roomId)
            ->where('status', 'confirmed')                 // lo que la v1 NO hacía
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->where('starts_at', '<', $end)                // a < d  AND  b > c
            ->where('ends_at', '>', $start);
    }
}
