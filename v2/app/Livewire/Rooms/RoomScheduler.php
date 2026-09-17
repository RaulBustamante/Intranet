<?php

namespace App\Livewire\Rooms;

use App\Domain\People\Models\Location;
use App\Domain\Rooms\Models\Room;
use App\Domain\Rooms\Models\RoomBooking;
use App\Domain\Rooms\Services\OverlapGuard;
use App\Domain\Rooms\Services\RoomUnavailableException;
use Carbon\CarbonImmutable;
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * Disponibilidad y reserva de salas (ROOM-01, ROOM-04, ROOM-05).
 *
 * Muestra el día por sala con las franjas ocupadas, y reserva contra el
 * OverlapGuard. La reserva se liga al empleado del usuario autenticado: ya no
 * se teclea el nombre a mano como en la v1.
 */
class RoomScheduler extends Component
{
    public string $date;
    public string $locationId = '';

    // Formulario de reserva
    public bool $showForm = false;
    public ?int $roomId = null;
    public string $title = '';
    public string $startTime = '09:00';
    public string $endTime = '10:00';
    public string $attendees = '';
    public ?string $bookingError = null;

    /** Horario laboral que se pinta en la rejilla. */
    private const HOUR_START = 8;
    private const HOUR_END = 19;

    public function mount(): void
    {
        $this->date = CarbonImmutable::now()->format('Y-m-d');
    }

    public function prevDay(): void { $this->date = CarbonImmutable::parse($this->date)->subDay()->format('Y-m-d'); }
    public function nextDay(): void { $this->date = CarbonImmutable::parse($this->date)->addDay()->format('Y-m-d'); }
    public function today(): void   { $this->date = CarbonImmutable::now()->format('Y-m-d'); }

    #[Computed]
    public function locations()
    {
        return Location::whereHas('rooms')->orderBy('code')->get();
    }

    #[Computed]
    public function rooms()
    {
        return Room::query()->where('is_active', true)
            ->when($this->locationId !== '', fn ($q) => $q->where('location_id', $this->locationId))
            ->with('location')
            ->orderBy('sort_order')->get();
    }

    /** Reservas confirmadas del día, agrupadas por sala. */
    #[Computed]
    public function bookingsByRoom()
    {
        $day = CarbonImmutable::parse($this->date);

        return RoomBooking::query()
            ->confirmed()
            ->whereBetween('starts_at', [$day->startOfDay(), $day->endOfDay()])
            ->with('employee:id,first_name,last_name,preferred_name')
            ->get()
            ->groupBy('room_id');
    }

    /** Las horas que se pintan como columnas (8..18). */
    public function hours(): array
    {
        return range(self::HOUR_START, self::HOUR_END - 1);
    }

    /** Franjas ocupadas de una sala, como % para posicionar las barras. */
    public function busyBlocks(int $roomId): array
    {
        $total = (self::HOUR_END - self::HOUR_START) * 60;
        $blocks = [];

        foreach ($this->bookingsByRoom[$roomId] ?? [] as $b) {
            $startMin = ($b->starts_at->hour - self::HOUR_START) * 60 + $b->starts_at->minute;
            $endMin   = ($b->ends_at->hour - self::HOUR_START) * 60 + $b->ends_at->minute;
            $startMin = max(0, $startMin);
            $endMin   = min($total, $endMin);
            if ($endMin <= $startMin) {
                continue;
            }
            $blocks[] = [
                'left'  => round($startMin / $total * 100, 2),
                'width' => round(($endMin - $startMin) / $total * 100, 2),
                'label' => $b->title,
                'who'   => $b->employee?->display_name ?? '',
                'time'  => $b->starts_at->format('H:i') . '–' . $b->ends_at->format('H:i'),
            ];
        }

        return $blocks;
    }

    public function openForm(int $roomId): void
    {
        $this->reset(['title', 'attendees', 'bookingError']);
        $this->roomId = $roomId;
        $this->startTime = '09:00';
        $this->endTime = '10:00';
        $this->showForm = true;
    }

    public function book(): void
    {
        // Reservar exige sesión: el invitado ve la disponibilidad pero para
        // apartar una sala se firma primero (ROOM-05).
        if (! auth()->check()) {
            $this->redirectRoute('login', navigate: true);
            return;
        }

        $this->validate([
            'title'     => 'required|string|max:200',
            'startTime' => 'required',
            'endTime'   => 'required',
        ]);

        $employeeId = auth()->user()->employee_id;
        if (! $employeeId) {
            $this->bookingError = __('rooms.no_employee');

            return;
        }

        $start = CarbonImmutable::parse($this->date . ' ' . $this->startTime);
        $end   = CarbonImmutable::parse($this->date . ' ' . $this->endTime);

        if ($end->lessThanOrEqualTo($start)) {
            $this->bookingError = __('rooms.bad_range');

            return;
        }

        try {
            $booking = app(OverlapGuard::class)->reserve([
                'room_id'         => $this->roomId,
                'employee_id'     => $employeeId,
                'title'           => $this->title,
                'starts_at'       => $start,
                'ends_at'         => $end,
                'attendees_count' => $this->attendees !== '' ? (int) $this->attendees : null,
            ]);
        } catch (RoomUnavailableException $e) {
            $this->bookingError = $e->getMessage();

            return;
        }

        // Confirmación por correo con .ics (ROOM-07), solo si hay a quién.
        // En cola cuando el sistema de colas esté supervisado (Fase 4); por
        // ahora se manda directo y sin romper la reserva si el correo falla.
        $correo = auth()->user()->email;
        if ($correo) {
            try {
                \Mail::to($correo)->send(new \App\Mail\BookingConfirmed($booking->load('room')));
            } catch (\Throwable $e) {
                report($e);   // la reserva ya está hecha; un fallo de correo no la deshace
            }
        }

        $this->showForm = false;
        unset($this->bookingsByRoom);
        $this->dispatch('notify', message: __('rooms.booked'));
    }

    public function cancelBooking(int $id): void
    {
        if (! auth()->check()) {
            $this->redirectRoute('login', navigate: true);
            return;
        }

        $booking = RoomBooking::findOrFail($id);

        // Solo el dueño (o admin) puede cancelar (ROOM-05)
        if ($booking->employee_id !== auth()->user()->employee_id && ! auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $booking->update(['status' => 'cancelled']);
        unset($this->bookingsByRoom, $this->myBookings);
        $this->dispatch('notify', message: __('rooms.cancelled_ok'));
    }

    #[Computed]
    public function myBookings()
    {
        $employeeId = auth()->user()?->employee_id;
        if (! $employeeId) {
            return collect();
        }

        return RoomBooking::query()
            ->confirmed()
            ->where('employee_id', $employeeId)
            ->where('ends_at', '>=', now())
            ->with('room')
            ->orderBy('starts_at')->limit(5)->get();
    }

    public function render()
    {
        return view('livewire.rooms.room-scheduler')
            ->layout('components.layouts.app', ['title' => __('rooms.title')]);
    }
}
