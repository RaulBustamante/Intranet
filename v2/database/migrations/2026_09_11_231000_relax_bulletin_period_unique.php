<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Quita el UNIQUE(year, month) de boletines y hace único el s3_key.
 *
 * Razón: varios boletines reales de la v1 caen en el mismo mes (o su nombre
 * no deja deducir el periodo), y el unique estricto los fusionaba, perdiendo
 * la referencia a archivos S3 reales. La identidad correcta de un boletín es
 * su archivo, no su periodo.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bulletins', function (Blueprint $table) {
            $table->dropUnique(['period_year', 'period_month']);
            $table->index(['period_year', 'period_month']);
            $table->string('s3_key', 500)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('bulletins', function (Blueprint $table) {
            $table->dropUnique(['s3_key']);
            $table->dropIndex(['period_year', 'period_month']);
            $table->unique(['period_year', 'period_month']);
        });
    }
};
