<?php

namespace App\Domain\Calendar\Models;

use App\Domain\People\Models\Location;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title_es', 'title_en', 'description_es', 'description_en',
        'type', 'starts_at', 'ends_at', 'all_day', 'location_id', 'created_by',
    ];

    protected function casts(): array
    {
        return ['starts_at' => 'datetime', 'ends_at' => 'datetime', 'all_day' => 'boolean'];
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function getTitleAttribute(): string
    {
        $campo = 'title_' . app()->getLocale();

        return $this->{$campo} ?: $this->title_es;
    }
}
