<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Token estable para el feed iCal por usuario (CAL-07).
 *
 * Dedicado, no el remember_token (que cambia al cerrar sesión e invalidaría
 * la suscripción de calendario). Va en la URL del feed; identifica sin exponer
 * la sesión, porque los clientes de calendario no envían cookies.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('calendar_token', 64)->nullable()->unique()->after('remember_token');
        });

        // Genera token para las cuentas existentes
        DB::table('users')->whereNull('calendar_token')->orderBy('id')->each(function ($u) {
            DB::table('users')->where('id', $u->id)->update(['calendar_token' => Str::random(48)]);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('calendar_token');
        });
    }
};
