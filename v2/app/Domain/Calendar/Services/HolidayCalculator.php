<?php

namespace App\Domain\Calendar\Services;

use Carbon\CarbonImmutable;

/**
 * Calcula la fecha de un festivo para un año dado, a partir de una REGLA.
 *
 * Este es el núcleo que arregla el defecto de raíz de la v1 (ver DATA_SOURCES):
 * la v1 tenía las fechas capturadas a mano, ya vencidas en 2024, con cinco mal
 * (Constitución, Benito Juárez, Memorial Day, Labor Day, Revolución son de día
 * de semana variable, no fecha fija) y Thanksgiving DUPLICADO (27-11 y 28-11)
 * porque el modelo no permitía expresar "cuarto jueves de noviembre".
 *
 * Aquí una regla se evalúa para cualquier año. Cero mantenimiento anual.
 *
 * Convención de weekday: 0=domingo, 1=lunes, ... 6=sábado (igual que Carbon).
 */
class HolidayCalculator
{
    /**
     * @param array{rule_type:string, month:int, day?:int|null, nth?:int|null, weekday?:int|null, observed_shift?:string} $rule
     */
    public function dateFor(array $rule, int $year): CarbonImmutable
    {
        $date = match ($rule['rule_type']) {
            'fixed'        => CarbonImmutable::create($year, $rule['month'], $rule['day']),
            'nth_weekday'  => $this->nthWeekday($year, $rule['month'], $rule['nth'], $rule['weekday']),
            'last_weekday' => $this->lastWeekday($year, $rule['month'], $rule['weekday']),
            default        => throw new \InvalidArgumentException("Tipo de regla desconocido: {$rule['rule_type']}"),
        };

        if (($rule['observed_shift'] ?? 'none') === 'nearest_weekday') {
            $date = $this->observedShift($date);
        }

        return $date;
    }

    /** El n-ésimo <weekday> del mes. nth=1 es el primero, nth=4 el cuarto. */
    private function nthWeekday(int $year, int $month, int $nth, int $weekday): CarbonImmutable
    {
        $first = CarbonImmutable::create($year, $month, 1);

        // Cuántos días hay que avanzar desde el día 1 hasta el primer <weekday>
        $offset = ($weekday - $first->dayOfWeek + 7) % 7;

        return $first->addDays($offset + ($nth - 1) * 7);
    }

    /** El último <weekday> del mes (p.ej. Memorial Day = último lunes de mayo). */
    private function lastWeekday(int $year, int $month, int $weekday): CarbonImmutable
    {
        $last = CarbonImmutable::create($year, $month, 1)->endOfMonth()->startOfDay();

        // Retroceder desde el último día hasta el <weekday> buscado
        $offset = ($last->dayOfWeek - $weekday + 7) % 7;

        return $last->subDays($offset);
    }

    /**
     * Corrimiento por observancia (regla federal de USA): si el festivo cae
     * sábado se observa el viernes; si cae domingo, el lunes.
     */
    private function observedShift(CarbonImmutable $date): CarbonImmutable
    {
        return match ($date->dayOfWeek) {
            6 => $date->subDay(),   // sábado -> viernes
            0 => $date->addDay(),   // domingo -> lunes
            default => $date,
        };
    }
}
