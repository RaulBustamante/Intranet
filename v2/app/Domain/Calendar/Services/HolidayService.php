<?php

namespace App\Domain\Calendar\Services;

use App\Domain\Calendar\Models\HolidayRule;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Festivos de un año, calculados por regla y CACHEADOS (rendimiento, Fase 7).
 *
 * Antes el cálculo estaba duplicado en la landing, la portada, el calendario y
 * el feed iCal, y cada uno re-consultaba las reglas y recalculaba. Aquí se hace
 * una vez por año y se cachea 24 h; se invalida al guardar una regla.
 */
class HolidayService
{
    /** @return Collection<int, array{date: CarbonImmutable, name: string, country: string}> */
    public function forYear(int $year, ?string $country = null): Collection
    {
        $locale = app()->getLocale();

        // Se cachean datos PRIMITIVOS (fecha como string), no objetos Carbon:
        // serializar CarbonImmutable en el caché lo devolvía como
        // __PHP_Incomplete_Class al deserializar. Se reconstruye al leer.
        $raw = Cache::remember("holidays.$year.$locale", now()->addDay(), function () use ($year) {
            return HolidayRule::where('is_active', true)->get()
                ->filter(fn ($r) => $r->appliesInYear($year))
                ->map(fn ($r) => [
                    'date'    => $r->dateFor($year)->format('Y-m-d'),
                    'name'    => $r->name,
                    'country' => $r->country,
                ])
                ->sortBy('date')
                ->values()
                ->all();
        });

        return collect($raw)
            ->map(fn ($h) => [
                'date'    => CarbonImmutable::parse($h['date']),
                'name'    => $h['name'],
                'country' => $h['country'],
            ])
            ->when($country, fn ($c) => $c->filter(fn ($h) => $h['country'] === 'BOTH' || $h['country'] === $country)->values());
    }

    /** Los próximos N festivos a partir de hoy (para la portada y la landing). */
    public function upcoming(int $limit = 4, ?string $country = null): Collection
    {
        $today = CarbonImmutable::now()->startOfDay();

        return collect([$today->year, $today->year + 1])
            ->flatMap(fn ($y) => $this->forYear($y, $country))
            ->filter(fn ($h) => $h['date']->greaterThanOrEqualTo($today))
            ->sortBy(fn ($h) => $h['date']->timestamp)
            ->take($limit)
            ->values();
    }

    public static function flush(): void
    {
        // Invalida todo el caché de festivos (al guardar/editar una regla)
        Cache::flush();
    }
}
