<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MeetingRoom;
use App\Models\Reservation;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller
{
    public function index()
    {
        $rooms = MeetingRoom::all();
        return view('reservations', compact('rooms'));
    }

    public function store(Request $request)
    {
        Log::info('Store request received:', $request->all());

        $request->validate([
            'room_id' => 'required|integer',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'reserver_name' => 'required|string',
            'meeting_title' => 'required|string',
        ]);

        $existingReservation = Reservation::where('room_id', $request->room_id)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                      ->orWhere(function($query) use ($request) {
                          $query->where('start_time', '<=', $request->start_time)
                                ->where('end_time', '>=', $request->end_time);
                      });
            })
            ->first();

        if ($existingReservation) {
            Log::info('Existing reservation found:', $existingReservation->toArray());
            return redirect()->back()->with('error', 'Ya existe una reserva para esta sala en el horario seleccionado.');
        }

        $reservation = Reservation::create([
            'room_id' => $request->room_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'reserver_name' => $request->reserver_name,
            'meeting_title' => $request->meeting_title,
            'status' => 'pendiente',
        ]);

        Log::info('Reservation created:', $reservation->toArray());

        return redirect()->route('reservations.index')->with('success', 'Reservación creada exitosamente.');
    }

    public function summary()
    {
        $rooms = MeetingRoom::all();
        $reservations = Reservation::all();
        $events = $reservations->map(function ($reservation) {
            return [
                'id' => $reservation->id,
                'title' => $reservation->meeting_title . ' - ' . $reservation->reserver_name,
                'start' => $reservation->start_time,
                'end' => $reservation->end_time,
                'color' => $this->getRoomColor($reservation->room_id),
            ];
        });

        return view('reservationsummary', compact('rooms', 'events'));
    }

    private function getRoomColor($roomId)
    {
        $colors = [
            1 => '#1E90FF',
            2 => '#32CD32',
            3 => '#FF8C00',
        ];

        return $colors[$roomId] ?? '#378006';
    }
}

