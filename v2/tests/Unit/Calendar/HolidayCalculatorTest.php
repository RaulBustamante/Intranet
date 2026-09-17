<?php

namespace Tests\Unit\Calendar;

use App\Domain\Calendar\Services\HolidayCalculator;
use PHPUnit\Framework\TestCase;

/**
 * El motor de festivos, contra fechas REALES conocidas de varios años.
 *
 * Estas fechas están verificadas contra calendarios oficiales. Si alguna
 * cambia, el motor está mal. La v1 no podía pasar estas pruebas porque tenía
 * las fechas fijas y ya vencidas.
 */
class HolidayCalculatorTest extends TestCase
{
    private HolidayCalculator $calc;

    protected function setUp(): void
    {
        $this->calc = new HolidayCalculator();
    }

    private function fecha(array $regla, int $year): string
    {
        return $this->calc->dateFor($regla, $year)->format('Y-m-d');
    }

    // ---- Fecha fija -------------------------------------------------------

    public function test_ano_nuevo_es_siempre_1_de_enero(): void
    {
        $regla = ['rule_type' => 'fixed', 'month' => 1, 'day' => 1];
        $this->assertSame('2026-01-01', $this->fecha($regla, 2026));
        $this->assertSame('2030-01-01', $this->fecha($regla, 2030));
    }

    public function test_independencia_de_mexico_16_de_septiembre(): void
    {
        $regla = ['rule_type' => 'fixed', 'month' => 9, 'day' => 16];
        $this->assertSame('2026-09-16', $this->fecha($regla, 2026));
    }

    // ---- N-ésimo día de la semana: EL FIX del bug de Thanksgiving ---------

    public function test_thanksgiving_es_el_cuarto_jueves_de_noviembre(): void
    {
        // La v1 tenía 27-11 Y 28-11 hardcodeados. Aquí una regla, varios años:
        $regla = ['rule_type' => 'nth_weekday', 'month' => 11, 'nth' => 4, 'weekday' => 4];
        $this->assertSame('2024-11-28', $this->fecha($regla, 2024));  // fue el 28
        $this->assertSame('2025-11-27', $this->fecha($regla, 2025));  // fue el 27
        $this->assertSame('2026-11-26', $this->fecha($regla, 2026));  // será el 26
        $this->assertSame('2027-11-25', $this->fecha($regla, 2027));
    }

    public function test_labor_day_usa_primer_lunes_de_septiembre(): void
    {
        // La v1 tenía 02-09, correcto solo en 2024
        $regla = ['rule_type' => 'nth_weekday', 'month' => 9, 'nth' => 1, 'weekday' => 1];
        $this->assertSame('2024-09-02', $this->fecha($regla, 2024));
        $this->assertSame('2025-09-01', $this->fecha($regla, 2025));
        $this->assertSame('2026-09-07', $this->fecha($regla, 2026));
    }

    public function test_constitucion_mx_primer_lunes_de_febrero(): void
    {
        // La v1 tenía 05-02: acertó en 2024 por casualidad (5 feb 2024 fue lunes)
        $regla = ['rule_type' => 'nth_weekday', 'month' => 2, 'nth' => 1, 'weekday' => 1];
        $this->assertSame('2024-02-05', $this->fecha($regla, 2024));
        $this->assertSame('2026-02-02', $this->fecha($regla, 2026));
        $this->assertSame('2027-02-01', $this->fecha($regla, 2027));
    }

    public function test_benito_juarez_tercer_lunes_de_marzo(): void
    {
        // La v1 tenía 17-03, pero en 2024 el tercer lunes fue el 18: mal desde el inicio
        $regla = ['rule_type' => 'nth_weekday', 'month' => 3, 'nth' => 3, 'weekday' => 1];
        $this->assertSame('2024-03-18', $this->fecha($regla, 2024));
        $this->assertSame('2026-03-16', $this->fecha($regla, 2026));
    }

    public function test_revolucion_mexicana_tercer_lunes_de_noviembre(): void
    {
        $regla = ['rule_type' => 'nth_weekday', 'month' => 11, 'nth' => 3, 'weekday' => 1];
        $this->assertSame('2026-11-16', $this->fecha($regla, 2026));
    }

    // ---- Último día de la semana ------------------------------------------

    public function test_memorial_day_usa_ultimo_lunes_de_mayo(): void
    {
        // La v1 tenía 27-05, correcto solo en 2024
        $regla = ['rule_type' => 'last_weekday', 'month' => 5, 'weekday' => 1];
        $this->assertSame('2024-05-27', $this->fecha($regla, 2024));
        $this->assertSame('2025-05-26', $this->fecha($regla, 2025));
        $this->assertSame('2026-05-25', $this->fecha($regla, 2026));
    }

    // ---- Corrimiento por observancia --------------------------------------

    public function test_independence_day_se_observa_el_viernes_cuando_cae_sabado(): void
    {
        // 4 de julio de 2026 cae SÁBADO -> se observa el viernes 3
        $regla = ['rule_type' => 'fixed', 'month' => 7, 'day' => 4, 'observed_shift' => 'nearest_weekday'];
        $this->assertSame('2026-07-03', $this->fecha($regla, 2026));
    }

    public function test_independence_day_se_observa_el_lunes_cuando_cae_domingo(): void
    {
        // 4 de julio de 2027 cae DOMINGO -> se observa el lunes 5
        $regla = ['rule_type' => 'fixed', 'month' => 7, 'day' => 4, 'observed_shift' => 'nearest_weekday'];
        $this->assertSame('2027-07-05', $this->fecha($regla, 2027));
    }

    public function test_sin_corrimiento_no_se_mueve_en_dia_de_semana(): void
    {
        // 4 de julio de 2025 cae viernes -> no se mueve
        $regla = ['rule_type' => 'fixed', 'month' => 7, 'day' => 4, 'observed_shift' => 'nearest_weekday'];
        $this->assertSame('2025-07-04', $this->fecha($regla, 2025));
    }

    // ---- Año bisiesto -----------------------------------------------------

    public function test_funciona_en_ano_bisiesto(): void
    {
        // Último lunes de febrero de 2028 (bisiesto, 29 días)
        $regla = ['rule_type' => 'last_weekday', 'month' => 2, 'weekday' => 1];
        $this->assertSame('2028-02-28', $this->fecha($regla, 2028));
    }
}
