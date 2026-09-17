<?php

namespace Tests\Feature\Rooms;

use App\Domain\People\Models\Employee;
use App\Domain\Rooms\Models\Room;
use App\Domain\Rooms\Models\RoomBooking;
use App\Domain\Rooms\Services\OverlapGuard;
use App\Domain\Rooms\Services\RoomUnavailableException;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OverlapGuardTest extends TestCase
{
    use RefreshDatabase;

    private Room $sala;
    private Employee $empleado;
    private OverlapGuard $guard;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sala = Room::create(['name' => 'Sala A']);
        $this->empleado = Employee::create(['first_name' => 'Ana', 'last_name' => 'S', 'email' => 'ana@arielpremium.com']);
        $this->guard = new OverlapGuard();
    }

    private function reservar(string $desde, string $hasta, string $status = 'confirmed'): RoomBooking
    {
        return RoomBooking::create([
            'room_id' => $this->sala->id, 'employee_id' => $this->empleado->id,
            'title' => 'Junta', 'starts_at' => Carbon::parse($desde),
            'ends_at' => Carbon::parse($hasta), 'status' => $status,
        ]);
    }

    // ---- ROOM-02: no se puede solapar -------------------------------------

    public function test_detecta_los_cuatro_tipos_de_solapamiento(): void
    {
        $this->reservar('2026-10-01 10:00', '2026-10-01 11:00');

        $s = fn ($d) => Carbon::parse("2026-10-01 $d");

        // envolvente, envuelto, parcial-izquierda, parcial-derecha
        $this->assertTrue($this->guard->hasConflict($this->sala->id, $s('09:30'), $s('11:30')));
        $this->assertTrue($this->guard->hasConflict($this->sala->id, $s('10:15'), $s('10:45')));
        $this->assertTrue($this->guard->hasConflict($this->sala->id, $s('09:30'), $s('10:30')));
        $this->assertTrue($this->guard->hasConflict($this->sala->id, $s('10:30'), $s('11:30')));
    }

    public function test_reservas_adyacentes_no_chocan(): void
    {
        $this->reservar('2026-10-01 10:00', '2026-10-01 11:00');
        $s = fn ($d) => Carbon::parse("2026-10-01 $d");

        // Una junta que empieza justo cuando la otra termina: NO choca
        $this->assertFalse($this->guard->hasConflict($this->sala->id, $s('11:00'), $s('12:00')));
        $this->assertFalse($this->guard->hasConflict($this->sala->id, $s('09:00'), $s('10:00')));
    }

    public function test_reserve_lanza_excepcion_si_choca(): void
    {
        $this->reservar('2026-10-01 10:00', '2026-10-01 11:00');

        $this->expectException(RoomUnavailableException::class);
        $this->guard->reserve([
            'room_id' => $this->sala->id, 'employee_id' => $this->empleado->id,
            'title' => 'Otra', 'starts_at' => Carbon::parse('2026-10-01 10:30'),
            'ends_at' => Carbon::parse('2026-10-01 11:30'),
        ]);
    }

    // ---- ROOM-03: EL BUG DE LA v1 -----------------------------------------

    public function test_una_reserva_cancelada_libera_el_horario(): void
    {
        // Este es el defecto exacto de la v1: una reserva no-activa seguía
        // bloqueando la sala.
        $this->reservar('2026-10-01 10:00', '2026-10-01 11:00', 'cancelled');

        $s = fn ($d) => Carbon::parse("2026-10-01 $d");

        // Con la reserva cancelada, el horario está LIBRE
        $this->assertFalse($this->guard->hasConflict($this->sala->id, $s('10:00'), $s('11:00')));

        // Y se puede reservar sin problema
        $nueva = $this->guard->reserve([
            'room_id' => $this->sala->id, 'employee_id' => $this->empleado->id,
            'title' => 'Nueva', 'starts_at' => $s('10:00'), 'ends_at' => $s('11:00'),
        ]);
        $this->assertSame('confirmed', $nueva->status);
    }

    // ---- Salas distintas son independientes -------------------------------

    public function test_dos_salas_distintas_no_interfieren(): void
    {
        $salaB = Room::create(['name' => 'Sala B']);
        $this->reservar('2026-10-01 10:00', '2026-10-01 11:00');

        $this->assertFalse($this->guard->hasConflict(
            $salaB->id, Carbon::parse('2026-10-01 10:00'), Carbon::parse('2026-10-01 11:00')
        ));
    }

    // ---- Editar la propia reserva no choca consigo misma ------------------

    public function test_editar_la_propia_reserva_no_choca_consigo_misma(): void
    {
        $r = $this->reservar('2026-10-01 10:00', '2026-10-01 11:00');

        $this->assertFalse($this->guard->hasConflict(
            $this->sala->id, $r->starts_at, $r->ends_at, ignoreId: $r->id
        ));
    }
}
