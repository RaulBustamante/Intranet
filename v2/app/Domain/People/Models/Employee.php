<?php

namespace App\Domain\People\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'preferred_name', 'email',
        'extension', 'phone', 'fax', 'job_title_es', 'job_title_en',
        'department_id', 'location_id', 'manager_id',
        'birth_month', 'birth_day', 'hire_date', 'photo_path', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active'   => 'boolean',
            'hire_date'   => 'date',
            'birth_month' => 'integer',
            'birth_day'   => 'integer',
        ];
    }

    // ---- Relaciones ------------------------------------------------------

    /** La cuenta de acceso de esta persona (si tiene). Inversa de User::employee. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(self::class, 'manager_id');
    }

    /** Equipo directo. Alimenta el organigrama (PPL-07). */
    public function reports(): HasMany
    {
        return $this->hasMany(self::class, 'manager_id');
    }

    // ---- Presentación ----------------------------------------------------

    /** Nombre para mostrar: respeta el apodo si el empleado puso uno (PPL-08). */
    public function getDisplayNameAttribute(): string
    {
        return trim(($this->preferred_name ?: $this->first_name) . ' ' . $this->last_name);
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Cumpleaños legible, sin año (PPL-10).
     * Se formatea en el idioma activo; no se guarda texto en base de datos.
     */
    public function getBirthdayLabelAttribute(): ?string
    {
        if (! $this->birth_month || ! $this->birth_day) {
            return null;
        }

        return now()
            ->setDate(2000, $this->birth_month, $this->birth_day)
            ->locale(app()->getLocale())
            ->isoFormat('D [de] MMMM');
    }

    // ---- Ámbitos ---------------------------------------------------------

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    /** Cumpleañeros de un mes y día concretos. Alimenta CAL-03 y la portada. */
    public function scopeBirthdayOn(Builder $q, int $month, int $day): Builder
    {
        return $q->where('birth_month', $month)->where('birth_day', $day);
    }

    public function scopeBirthdayInMonth(Builder $q, int $month): Builder
    {
        return $q->where('birth_month', $month)->orderBy('birth_day');
    }

    /**
     * Búsqueda del directorio (PPL-04).
     *
     * Busca en nombre, apellido, apodo, correo, extensión y teléfono.
     * La tolerancia a acentos ("Nuno" encuentra "Nuño") se resuelve
     * normalizando el término y comparando contra una columna normalizada
     * en la Fase 6; aquí se apoya en la colación de la base de datos.
     */
    public function scopeSearch(Builder $q, ?string $term): Builder
    {
        $term = trim((string) $term);

        if ($term === '') {
            return $q;
        }

        $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $term) . '%';

        return $q->where(function (Builder $w) use ($like) {
            foreach (['first_name', 'last_name', 'preferred_name', 'email', 'extension', 'phone'] as $col) {
                $w->orWhere($col, 'like', $like);
            }
        });
    }
}
