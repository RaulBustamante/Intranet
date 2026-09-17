<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Sedes (PPL-03).
 *
 * Reemplaza los 17 códigos hardcodeados en el <select> de directory.blade.php
 * de la v1: 8825, 1920, WC, TW, AZ, CA, CAN, CN, DE, FL, IL, ME, NJ, OH, PA, STL.
 *
 * Varios de esos códigos nadie sabe qué significan (pendiente P1: preguntar a RH
 * qué son 1920, 8825 y BSU). Por eso `name_es`/`name_en` admiten quedarse igual
 * al código hasta que RH los aclare, en lugar de inventar nombres.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('name_es', 120);
            $table->string('name_en', 120);
            $table->string('city', 120)->nullable();
            $table->char('country', 2)->nullable();          // ISO: MX, US, TW, CN, DE, CA
            $table->string('timezone', 64)->nullable();      // horario local por sede
            $table->enum('holiday_set', ['MX', 'US', 'BOTH'])->default('BOTH'); // CAL-02
            $table->boolean('is_active')->default(true);
            $table->smallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
