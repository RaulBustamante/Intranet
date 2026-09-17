<?php

namespace App\Domain\Calendar\Services;

use App\Domain\Calendar\Models\Event;
use App\Domain\Calendar\Models\HolidayRule;
use App\Domain\People\Models\Employee;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

/**
 * Reúne en una sola lista todo lo que va al calendario de un mes:
 * festivos (calculados por regla), cumpleaños y aniversarios (derivados de
 * employees, sin lista aparte) y eventos de empresa capturados.
 *
 * Los cumpleaños y aniversarios NO se guardan como eventos: se derivan aquí.
 * Así nunca hay dos fuentes de verdad que se contradigan (el problema de la v1).
 */
class CalendarService
{
    /**
     * @return Collection<int, array{date:string, title:string, type:string, country?:string}>
     */
    public function itemsForMonth(int $year, int $month, ?string $country = null): Collection
    {
        $items = collect();

        // --- Festivos (calculados) ---
        foreach (HolidayRule::where('is_active', true)->get() as $rule) {
            if (! $rule->appliesInYear($year)) {
                continue;
            }
            if ($country && $rule->country !== 'BOTH' && $rule->country !== $country) {
                continue;
            }

            $date = $rule->dateFor($year);
            if ($date->month === $month) {
                $items->push([
                    'date'    => $date->format('Y-m-d'),
                    'title'   => $rule->name,
                    'type'    => 'holiday',
                    'country' => $rule->country,
                ]);
            }
        }

        // --- Cumpleaños (derivados de employees) ---
        Employee::query()->active()->whereNotNull('birth_month')
            ->where('birth_month', $month)->get()
            ->each(function (Employee $e) use ($year, $items) {
                $items->push([
                    'date'  => sprintf('%04d-%02d-%02d', $year, $e->birth_month, $e->birth_day),
                    'title' => $e->display_name,
                    'type'  => 'birthday',
                ]);
            });

        // --- Aniversarios laborales (derivados de hire_date) ---
        Employee::query()->active()->whereNotNull('hire_date')
            ->whereMonth('hire_date', $month)->get()   // whereMonth: portable (MySQL y SQLite)
            ->each(function (Employee $e) use ($year, $items) {
                $anios = $year - (int) $e->hire_date->format('Y');
                if ($anios > 0) {
                    $items->push([
                        'date'  => sprintf('%04d-%02d-%02d', $year, $month, (int) $e->hire_date->format('d')),
                        'title' => trans_choice('calendar.anniversary', $anios, ['name' => $e->display_name, 'years' => $anios]),
                        'type'  => 'anniversary',
                    ]);
                }
            });

        // --- Eventos de empresa (capturados) ---
        Event::query()
            ->whereYear('starts_at', $year)->whereMonth('starts_at', $month)
            ->get()
            ->each(fn (Event $ev) => $items->push([
                'date'  => $ev->starts_at->format('Y-m-d'),
                'title' => $ev->title,
                'type'  => 'event',
            ]));

        return $items->sortBy('date')->values();
    }
}
