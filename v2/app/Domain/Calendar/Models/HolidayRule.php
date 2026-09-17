<?php

namespace App\Domain\Calendar\Models;

use App\Domain\Calendar\Services\HolidayCalculator;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;

class HolidayRule extends Model
{
    protected $fillable = [
        'name_es', 'name_en', 'country', 'rule_type', 'month', 'day',
        'nth', 'weekday', 'observed_shift', 'effective_from', 'effective_to', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function getNameAttribute(): string
    {
        $campo = 'name_' . app()->getLocale();

        return $this->{$campo} ?: $this->name_es;
    }

    /** ¿Aplica esta regla en este año? (por si un festivo se creó o derogó) */
    public function appliesInYear(int $year): bool
    {
        if ($this->effective_from && $year < $this->effective_from) {
            return false;
        }
        if ($this->effective_to && $year > $this->effective_to) {
            return false;
        }

        return $this->is_active;
    }

    /** La fecha de este festivo para el año dado. */
    public function dateFor(int $year): CarbonImmutable
    {
        return app(HolidayCalculator::class)->dateFor([
            'rule_type'      => $this->rule_type,
            'month'          => $this->month,
            'day'            => $this->day,
            'nth'            => $this->nth,
            'weekday'        => $this->weekday,
            'observed_shift' => $this->observed_shift,
        ], $year);
    }
}
