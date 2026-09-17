<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Empleados — la tabla que sostiene todo el proyecto.
 *
 * Es la fuente de verdad del directorio (PPL-04), de los cumpleaños (CAL-03),
 * de los aniversarios (CAL-04) y del organigrama (PPL-07). En la v1 esos tres
 * vivían en listas separadas que ya no coincidían entre sí.
 *
 * PRIVACIDAD (PPL-10): se guarda mes y día de cumpleaños, NUNCA el año.
 * Los datos de la v1 no lo traen, la funcionalidad no lo necesita, y es menos
 * PII en una aplicación expuesta a internet. Si algún día alguien propone
 * agregar `birth_year`, la respuesta por defecto es no.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            // Cuenta de acceso. Nullable a propósito: no todo empleado del
            // directorio necesita poder entrar (planta, personal sin correo).
            $table->foreignId('user_id')->nullable();

            $table->string('first_name', 80);
            $table->string('last_name', 80);
            $table->string('preferred_name', 80)->nullable();   // apodo, editable por el empleado (PPL-08)
            $table->string('email', 180)->nullable()->unique();

            $table->string('extension', 10)->nullable();
            $table->string('phone', 40)->nullable();
            $table->string('fax', 40)->nullable();

            $table->string('job_title_es', 140)->nullable();
            $table->string('job_title_en', 140)->nullable();

            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('location_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->foreignId('manager_id')->nullable()->constrained('employees')->nullOnDelete();

            // Cumpleaños sin año (PPL-10)
            $table->unsignedTinyInteger('birth_month')->nullable();
            $table->unsignedTinyInteger('birth_day')->nullable();

            $table->date('hire_date')->nullable();              // aniversarios laborales (CAL-04)
            $table->string('photo_path', 255)->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();                              // baja lógica: conserva historial (AUTH-08)

            $table->index(['last_name', 'first_name']);
            $table->index(['birth_month', 'birth_day']);        // cumpleaños del mes
            $table->index(['is_active', 'deleted_at']);
        });

        // Rango de mes y día a nivel de base de datos.
        //
        // Solo en MySQL: SQLite no admite ALTER TABLE ADD CONSTRAINT, así que en
        // desarrollo la garantía la da la validación de la aplicación (HRADM-05),
        // que de todos modos hace falta para dar un mensaje entendible a RH.
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE employees ADD CONSTRAINT chk_employees_birth_month
                           CHECK (birth_month IS NULL OR birth_month BETWEEN 1 AND 12)');
            DB::statement('ALTER TABLE employees ADD CONSTRAINT chk_employees_birth_day
                           CHECK (birth_day IS NULL OR birth_day BETWEEN 1 AND 31)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
