<?php

namespace App\Domain\Content\Models;

use App\Domain\Content\Models\Concerns\HasLocalizedName;
use App\Domain\People\Models\Department;
use App\Domain\People\Models\Employee;
use App\Domain\People\Models\Location;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use SoftDeletes, HasLocalizedName;

    protected $fillable = [
        'title_es', 'title_en', 'slug', 'excerpt_es', 'excerpt_en',
        'body_es', 'body_en', 'author_id', 'published_at', 'expires_at', 'is_pinned',
    ];

    protected function casts(): array
    {
        return ['published_at' => 'datetime', 'expires_at' => 'datetime', 'is_pinned' => 'boolean'];
    }

    public function author(): BelongsTo    { return $this->belongsTo(User::class, 'author_id'); }
    public function audiences(): HasMany   { return $this->hasMany(AnnouncementAudience::class); }

    public function getTitleAttribute(): string   { return $this->localized('title'); }
    public function getExcerptAttribute(): string { return $this->localized('excerpt'); }
    public function getBodyAttribute(): string    { return $this->localized('body'); }

    /** Publicado ahora: fecha llegada y no expirado (COM-02). */
    public function scopeLive(Builder $q): Builder
    {
        return $q->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where(fn ($w) => $w->whereNull('expires_at')->orWhere('expires_at', '>', now()));
    }

    /**
     * Visible para un empleado según la audiencia (COM-03).
     * Sin filas de audiencia = toda la empresa.
     */
    public function visibleTo(?Employee $employee): bool
    {
        $auds = $this->audiences;
        if ($auds->isEmpty()) {
            return true;
        }
        if (! $employee) {
            return false;
        }

        return $auds->contains(function (AnnouncementAudience $a) use ($employee) {
            return ($a->location_id && $a->location_id === $employee->location_id)
                || ($a->department_id && $a->department_id === $employee->department_id);
        });
    }
}
