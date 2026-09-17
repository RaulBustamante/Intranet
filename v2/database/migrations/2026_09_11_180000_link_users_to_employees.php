<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Liga la cuenta de acceso (users) con la persona del directorio (employees).
 *
 * Se mantienen como tablas separadas a propósito (ver DATA_SOURCES.md): no todo
 * empleado necesita cuenta, y la cuenta puede sobrevivir a cambios de puesto.
 * Aquí solo se añade la llave foránea que las conecta, más los campos de
 * preferencia y seguridad que la Fase 1 dejó anticipados en el diseño.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('employee_id')->nullable()->after('id')
                  ->constrained('employees')->nullOnDelete();

            $table->string('locale', 2)->default('es')->after('password');
            $table->enum('theme', ['system', 'light', 'dark'])->default('system')->after('locale');

            $table->boolean('must_change_password')->default(false)->after('theme');
            $table->timestamp('last_login_at')->nullable()->after('must_change_password');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            $table->boolean('is_active')->default(true)->after('last_login_ip');

            $table->index('is_active');
        });

        // El otro extremo: employees.user_id ya existe como columna (Fase 2),
        // aquí se le añade la llave foránea que no se pudo poner antes porque
        // users aún no tenía su forma final.
        //
        // Solo en MySQL: SQLite —donde corren las pruebas— no admite añadir una
        // foreign key con ALTER TABLE. La integridad en pruebas la dan las
        // relaciones de Eloquent; la garantía dura vive en producción (MySQL).
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            Schema::table('employees', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            Schema::table('employees', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->dropColumn([
                'employee_id', 'locale', 'theme',
                'must_change_password', 'last_login_at', 'last_login_ip', 'is_active',
            ]);
        });
    }
};
