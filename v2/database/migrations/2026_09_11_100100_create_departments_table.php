<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Departamentos, con jerarquía (PPL-03).
 *
 * En la v1 son cadenas de texto repetidas en cada fila del HTML: 'Art',
 * 'PROD Art', 'Manager-Art', 'Accounting', 'BSU'... Sin catálogo no hay forma
 * de renombrar un área sin editar 200 filas, ni de saber qué cuelga de qué.
 *
 * `parent_id` permite que 'PROD Art' y 'Manager-Art' cuelguen de 'Art' cuando
 * RH lo decida, sin forzarlo ahora: el importador los crea planos y RH los
 * organiza después desde el panel.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()
                  ->constrained('departments')->nullOnDelete();
            $table->string('name_es', 120);
            $table->string('name_en', 120);
            $table->boolean('is_active')->default(true);
            $table->smallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
