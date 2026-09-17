<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Calendario: reglas de festivo (CAL-01) y eventos de empresa (CAL-05).
 *
 * Los festivos NO se guardan como fechas: se guardan como reglas y el
 * HolidayCalculator las evalúa para cualquier año. Los cumpleaños y
 * aniversarios tampoco se guardan aquí: se derivan de employees en tiempo de
 * consulta, para no tener dos fuentes de verdad que se contradigan (que es
 * justo el problema de la v1).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('holiday_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name_es', 140);
            $table->string('name_en', 140);
            $table->enum('country', ['MX', 'US', 'BOTH']);
            $table->enum('rule_type', ['fixed', 'nth_weekday', 'last_weekday']);
            $table->unsignedTinyInteger('month');
            $table->unsignedTinyInteger('day')->nullable();       // solo fixed
            $table->tinyInteger('nth')->nullable();               // solo nth_weekday
            $table->unsignedTinyInteger('weekday')->nullable();   // 0=domingo..6=sábado
            $table->enum('observed_shift', ['none', 'nearest_weekday'])->default('none');
            $table->smallInteger('effective_from')->nullable();
            $table->smallInteger('effective_to')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title_es', 200);
            $table->string('title_en', 200)->nullable();
            $table->text('description_es')->nullable();
            $table->text('description_en')->nullable();
            $table->enum('type', ['company', 'training', 'maintenance', 'other'])->default('company');
            $table->dateTime('starts_at');
            $table->dateTime('ends_at')->nullable();
            $table->boolean('all_day')->default(false);
            $table->foreignId('location_id')->nullable()->constrained('locations')->nullOnDelete(); // NULL = todas las sedes
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['starts_at', 'ends_at']);
            $table->index('location_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
        Schema::dropIfExists('holiday_rules');
    }
};
