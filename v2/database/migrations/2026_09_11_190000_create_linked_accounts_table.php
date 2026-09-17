<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Cuentas externas vinculadas (AUTH-09, base de la integración de calendario).
 *
 * La intranet NO obliga a nadie a usar un proveedor externo: la base son las
 * cuentas propias (empleado/contraseña). Encima, quien tenga cuenta de
 * Microsoft/Teams o de Google puede VINCULARLA para:
 *   - entrar con ella (opcional, en vez de contraseña)
 *   - sincronizar su calendario y su disponibilidad
 *
 * Ariel tiene poblaciones mezcladas: parte con Teams (planta México), parte con
 * Google, y parte sin cuenta (planta). Por eso es vinculación por-usuario y
 * multi-proveedor, no un SSO único.
 *
 * Los tokens se guardan cifrados (cast 'encrypted' en el modelo). Un token de
 * calendario da acceso a la agenda de una persona: no puede quedar en claro.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('linked_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('provider', 20);              // 'microsoft' | 'google'
            $table->string('provider_user_id', 191);     // id estable de la cuenta externa
            $table->string('email', 191)->nullable();
            $table->string('name', 191)->nullable();

            // Cifrados en el modelo. TEXT porque un token puede ser largo.
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->timestamp('token_expires_at')->nullable();
            $table->text('scopes')->nullable();

            $table->boolean('calendar_sync_enabled')->default(false);
            $table->timestamp('last_synced_at')->nullable();

            $table->timestamps();

            // Una cuenta de cada proveedor por usuario; y una cuenta externa
            // no se puede vincular a dos usuarios distintos.
            $table->unique(['user_id', 'provider']);
            $table->unique(['provider', 'provider_user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('linked_accounts');
    }
};
