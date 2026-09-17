<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Salas de juntas y reservas (ROOM-01..08).
 *
 * Corrige dos defectos reales de la v1:
 *   1. El status pasa de pendiente|aprobado|rechazado a confirmed|cancelled.
 *      En la v1 la consulta de choques no filtraba por status, así que una
 *      reserva rechazada seguía bloqueando la sala. Y como nunca se construyó
 *      pantalla de aprobación, todas nacían "pendiente" y ninguna se aprobaba:
 *      el flujo de aprobación no existía en la práctica.
 *   2. La reserva se liga al empleado (employee_id), no a un nombre de texto
 *      libre que había que teclear.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->foreignId('location_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->unsignedSmallInteger('capacity')->nullable();
            $table->boolean('has_video')->default(false);
            $table->boolean('has_whiteboard')->default(false);
            $table->char('color', 7)->default('#ED2228');
            $table->boolean('is_active')->default(true);
            $table->smallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('room_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('employees');
            $table->string('title', 200);
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->enum('status', ['confirmed', 'cancelled'])->default('confirmed');
            $table->unsignedSmallInteger('attendees_count')->nullable();
            $table->uuid('series_id')->nullable();      // agrupa una serie recurrente
            $table->timestamps();

            $table->index(['room_id', 'starts_at', 'ends_at']);
            $table->index('employee_id');
            $table->index('series_id');
        });

        // La condición ends_at > starts_at a nivel de base (solo MySQL).
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE room_bookings ADD CONSTRAINT chk_booking_range CHECK (ends_at > starts_at)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('room_bookings');
        Schema::dropIfExists('rooms');
    }
};
