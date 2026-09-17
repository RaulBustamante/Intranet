<?php

namespace App\Models;

use App\Domain\Integrations\Models\LinkedAccount;
use App\Domain\People\Models\Employee;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'employee_id', 'locale', 'theme', 'is_active', 'calendar_token'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at'    => 'datetime',
            'password'             => 'hashed',
            'last_login_at'        => 'datetime',
            'is_active'            => 'boolean',
            'must_change_password' => 'boolean',
        ];
    }


    protected static function booted(): void
    {
        static::creating(function (User $u) {
            $u->calendar_token ??= \Illuminate\Support\Str::random(48);
        });
    }

    /** URL del feed iCal para suscribir en Outlook/Google (CAL-07). */
    public function calendarFeedUrl(): string
    {
        return route('calendar.feed', $this->calendar_token);
    }

    /** La persona del directorio a la que pertenece esta cuenta. */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /** Cuentas externas (Microsoft/Teams, Google) vinculadas a este usuario. */
    public function linkedAccounts(): HasMany
    {
        return $this->hasMany(LinkedAccount::class);
    }

    public function linkedAccount(string $provider): ?LinkedAccount
    {
        return $this->linkedAccounts->firstWhere('provider', $provider);
    }

    /**
     * Nombre para mostrar: el de la persona si está ligada, si no el de la
     * cuenta. Así el saludo de la portada sale bien aunque falte el empleado.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->employee?->display_name ?: $this->name;
    }

    /** Una cuenta desactivada no puede entrar, aunque la contraseña sea correcta (AUTH-08). */
    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }
}
