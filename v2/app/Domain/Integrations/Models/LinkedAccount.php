<?php

namespace App\Domain\Integrations\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Una cuenta externa (Microsoft/Teams o Google) vinculada a un usuario.
 */
class LinkedAccount extends Model
{
    protected $fillable = [
        'user_id', 'provider', 'provider_user_id', 'email', 'name',
        'access_token', 'refresh_token', 'token_expires_at', 'scopes',
        'calendar_sync_enabled', 'last_synced_at',
    ];

    protected function casts(): array
    {
        return [
            // Cifrado en reposo: un token de calendario abre la agenda de alguien.
            'access_token'          => 'encrypted',
            'refresh_token'         => 'encrypted',
            'token_expires_at'      => 'datetime',
            'last_synced_at'        => 'datetime',
            'calendar_sync_enabled' => 'boolean',
        ];
    }

    protected $hidden = ['access_token', 'refresh_token'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function needsRefresh(): bool
    {
        return $this->token_expires_at !== null
            && $this->token_expires_at->isPast();
    }

    /** Etiqueta legible del proveedor, para la interfaz. */
    public function providerLabel(): string
    {
        return match ($this->provider) {
            'microsoft' => 'Microsoft / Teams',
            'google'    => 'Google',
            default     => ucfirst($this->provider),
        };
    }
}
