<?php

namespace App\Livewire\Calendar;

use App\Domain\Calendar\Services\CalendarService;
use Carbon\CarbonImmutable;
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * Calendario mensual (CAL-01, CAL-03, CAL-06).
 *
 * A diferencia de la v1, cambiar el año recalcula los festivos por regla:
 * nunca se vencen. Los cumpleaños y aniversarios se derivan de employees.
 */
class CalendarView extends Component
{
    public int $year;
    public int $month;
    public string $country = 'BOTH';   // BOTH | MX | US
    public string $view = 'month';     // month | list

    public function mount(): void
    {
        $now = CarbonImmutable::now();
        $this->year = $now->year;
        $this->month = $now->month;
    }

    public function prev(): void
    {
        $d = CarbonImmutable::create($this->year, $this->month, 1)->subMonth();
        $this->year = $d->year;
        $this->month = $d->month;
    }

    public function next(): void
    {
        $d = CarbonImmutable::create($this->year, $this->month, 1)->addMonth();
        $this->year = $d->year;
        $this->month = $d->month;
    }

    public function today(): void
    {
        $now = CarbonImmutable::now();
        $this->year = $now->year;
        $this->month = $now->month;
    }

    #[Computed]
    public function items()
    {
        $country = $this->country === 'BOTH' ? null : $this->country;

        return app(CalendarService::class)
            ->itemsForMonth($this->year, $this->month, $country)
            ->groupBy('date');
    }

    /** La rejilla de semanas del mes, para pintar el calendario. */
    #[Computed]
    public function weeks(): array
    {
        $first = CarbonImmutable::create($this->year, $this->month, 1);
        $start = $first->subDays($first->dayOfWeek);          // arranca en domingo
        $weeks = [];

        for ($w = 0; $w < 6; $w++) {
            $row = [];
            for ($d = 0; $d < 7; $d++) {
                $day = $start->addDays($w * 7 + $d);
                $row[] = [
                    'date'          => $day->format('Y-m-d'),
                    'day'           => $day->day,
                    'in_month'      => $day->month === $this->month,
                    'is_today'      => $day->isToday(),
                ];
            }
            $weeks[] = $row;
        }

        return $weeks;
    }

    public function getMonthLabelProperty(): string
    {
        return CarbonImmutable::create($this->year, $this->month, 1)
            ->locale(app()->getLocale())->isoFormat('MMMM YYYY');
    }

    public function render()
    {
        return view('livewire.calendar.calendar-view')
            ->layout('components.layouts.app', ['title' => __('calendar.title')]);
    }
}
