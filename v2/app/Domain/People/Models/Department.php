<?php

namespace App\Domain\People\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $fillable = ['parent_id', 'name_es', 'name_en', 'is_active', 'sort_order'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    /** Nombre en el idioma activo, con caída al otro si falta (UX-07). */
    public function getNameAttribute(): string
    {
        $campo = 'name_' . app()->getLocale();

        return $this->{$campo} ?: $this->name_es ?: $this->name_en ?: '';
    }
}
