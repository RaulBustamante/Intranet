<?php

namespace App\Domain\People\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    protected $fillable = [
        'code', 'name_es', 'name_en', 'city', 'country',
        'timezone', 'holiday_set', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(\App\Domain\Rooms\Models\Room::class);
    }


    /**
     * Nombre en el idioma activo, con caída al otro si falta (UX-07).
     * Nunca devuelve vacío: prefiere el código antes que una celda en blanco.
     */
    public function getNameAttribute(): string
    {
        $campo = 'name_' . app()->getLocale();

        return $this->{$campo} ?: $this->name_es ?: $this->name_en ?: $this->code;
    }
}
