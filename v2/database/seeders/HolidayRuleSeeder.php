<?php

namespace Database\Seeders;

use App\Domain\Calendar\Models\HolidayRule;
use Illuminate\Database\Seeder;

/**
 * Festivos oficiales de México y USA como reglas.
 *
 * Corrige las CINCO que la v1 tenía mal por usarlas como fecha fija cuando son
 * de día de semana variable: Constitución, Benito Juárez, Memorial Day, Labor
 * Day y Revolución. Y elimina el Thanksgiving duplicado.
 *
 * weekday: 0=domingo, 1=lunes, ... 4=jueves.
 */
class HolidayRuleSeeder extends Seeder
{
    public function run(): void
    {
        $reglas = [
            // --- Ambos ---
            ['Año Nuevo', 'New Year\'s Day', 'BOTH', 'fixed', 1, 1, null, null, 'none'],
            ['Navidad', 'Christmas Day', 'BOTH', 'fixed', 12, 25, null, null, 'none'],

            // --- México ---
            ['Día de la Constitución', 'Constitution Day', 'MX', 'nth_weekday', 2, null, 1, 1, 'none'],
            ['Natalicio de Benito Juárez', 'Benito Juárez\'s Birthday', 'MX', 'nth_weekday', 3, null, 3, 1, 'none'],
            ['Día del Trabajo', 'Labor Day', 'MX', 'fixed', 5, 1, null, null, 'none'],
            ['Día de la Independencia', 'Independence Day', 'MX', 'fixed', 9, 16, null, null, 'none'],
            ['Día de la Revolución', 'Revolution Day', 'MX', 'nth_weekday', 11, null, 3, 1, 'none'],

            // --- USA ---
            ['Día de los Caídos', 'Memorial Day', 'US', 'last_weekday', 5, null, null, 1, 'none'],
            ['Día de la Independencia (EUA)', 'Independence Day', 'US', 'fixed', 7, 4, null, null, 'nearest_weekday'],
            ['Día del Trabajo (EUA)', 'Labor Day', 'US', 'nth_weekday', 9, null, 1, 1, 'none'],
            ['Día de los Veteranos', 'Veterans Day', 'US', 'fixed', 11, 11, null, null, 'nearest_weekday'],
            ['Día de Acción de Gracias', 'Thanksgiving Day', 'US', 'nth_weekday', 11, null, 4, 4, 'none'],
        ];

        foreach ($reglas as [$es, $en, $country, $type, $month, $day, $nth, $weekday, $shift]) {
            HolidayRule::updateOrCreate(
                ['name_es' => $es, 'country' => $country],
                [
                    'name_en'        => $en,
                    'rule_type'      => $type,
                    'month'          => $month,
                    'day'            => $day,
                    'nth'            => $nth,
                    'weekday'        => $weekday,
                    'observed_shift' => $shift,
                    'is_active'      => true,
                ]
            );
        }

        $this->command->info(count($reglas) . ' reglas de festivo (MX + USA).');
    }
}
