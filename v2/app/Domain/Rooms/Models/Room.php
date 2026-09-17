<?php

namespace App\Domain\Rooms\Models;

use App\Domain\People\Models\Location;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = [
        'name', 'location_id', 'capacity', 'has_video', 'has_whiteboard',
        'color', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return ['has_video' => 'boolean', 'has_whiteboard' => 'boolean', 'is_active' => 'boolean'];
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(RoomBooking::class);
    }
}
