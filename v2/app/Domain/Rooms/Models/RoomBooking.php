<?php

namespace App\Domain\Rooms\Models;

use App\Domain\People\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomBooking extends Model
{
    protected $fillable = [
        'room_id', 'employee_id', 'title', 'starts_at', 'ends_at',
        'status', 'attendees_count', 'series_id',
    ];

    protected function casts(): array
    {
        return ['starts_at' => 'datetime', 'ends_at' => 'datetime'];
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function scopeConfirmed($q)
    {
        return $q->where('status', 'confirmed');
    }
}
