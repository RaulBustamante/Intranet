<?php

namespace Tests\Feature\Rooms;

use App\Domain\People\Models\Employee;
use App\Domain\Rooms\Models\Room;
use App\Domain\Rooms\Models\RoomBooking;
use App\Livewire\Rooms\RoomScheduler;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class RoomSchedulerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Employee $empleado;
    private Room $sala;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();

        $this->empleado = Employee::create(['first_name' => 'Ana', 'last_name' => 'S', 'email' => 'ana@arielpremium.com']);
        $this->user = User::create(['name' => 'Ana', 'email' => 'ana@arielpremium.com', 'password' => bcrypt('x'), 'is_active' => true, 'employee_id' => $this->empleado->id]);
        $this->sala = Room::create(['name' => 'Sala A', 'is_active' => true]);
    }

    public function test_reserva_una_sala_y_manda_correo_de_confirmacion(): void
    {
        Livewire::actingAs($this->user)
            ->test(RoomScheduler::class)
            ->set('date', '2026-10-01')
            ->call('openForm', $this->sala->id)
            ->set('title', 'Junta de ventas')
            ->set('startTime', '10:00')
            ->set('endTime', '11:00')
            ->call('book')
            ->assertSet('showForm', false);

        $this->assertDatabaseHas('room_bookings', [
            'room_id' => $this->sala->id, 'employee_id' => $this->empleado->id,
            'title' => 'Junta de ventas', 'status' => 'confirmed',
        ]);

        Mail::assertSent(\App\Mail\BookingConfirmed::class);
    }

    public function test_no_deja_reservar_un_horario_ocupado(): void
    {
        RoomBooking::create([
            'room_id' => $this->sala->id, 'employee_id' => $this->empleado->id,
            'title' => 'Ocupado', 'starts_at' => '2026-10-01 10:00', 'ends_at' => '2026-10-01 11:00', 'status' => 'confirmed',
        ]);

        Livewire::actingAs($this->user)
            ->test(RoomScheduler::class)
            ->set('date', '2026-10-01')
            ->call('openForm', $this->sala->id)
            ->set('title', 'Choca')
            ->set('startTime', '10:30')
            ->set('endTime', '11:30')
            ->call('book')
            ->assertSet('showForm', true);   // el modal no se cierra: hubo error

        $this->assertDatabaseMissing('room_bookings', ['title' => 'Choca']);
    }

    public function test_rechaza_fin_antes_del_inicio(): void
    {
        Livewire::actingAs($this->user)
            ->test(RoomScheduler::class)
            ->set('date', '2026-10-01')
            ->call('openForm', $this->sala->id)
            ->set('title', 'Al revés')
            ->set('startTime', '11:00')
            ->set('endTime', '10:00')
            ->call('book');

        $this->assertDatabaseMissing('room_bookings', ['title' => 'Al revés']);
    }

    public function test_solo_el_dueno_cancela_su_reserva(): void
    {
        $otroEmp = Employee::create(['first_name' => 'Otro', 'last_name' => 'X', 'email' => 'otro@arielpremium.com']);
        $booking = RoomBooking::create([
            'room_id' => $this->sala->id, 'employee_id' => $otroEmp->id,
            'title' => 'De otro', 'starts_at' => '2026-10-01 10:00', 'ends_at' => '2026-10-01 11:00', 'status' => 'confirmed',
        ]);

        Livewire::actingAs($this->user)
            ->test(RoomScheduler::class)
            ->call('cancelBooking', $booking->id)
            ->assertStatus(403);
    }
}
