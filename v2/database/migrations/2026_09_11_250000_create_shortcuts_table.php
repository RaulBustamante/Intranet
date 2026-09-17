<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Accesos rápidos de la landing pública (los cuadros de colores).
 *
 * En la v1 eran 16 cuadros hardcodeados en welcome.blade.php. Aquí son
 * administrables: RH decide cuáles aparecen, en qué orden, con qué icono y
 * color, y si requieren sesión — sin tocar código.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shortcuts', function (Blueprint $table) {
            $table->id();
            $table->string('label_es', 80);
            $table->string('label_en', 80);
            $table->string('icon', 40)->default('link');
            $table->string('tint', 20)->default('slate');       // color de la tarjeta
            $table->enum('target_type', ['route', 'url'])->default('route');
            $table->string('target', 500);                       // nombre de ruta o URL
            $table->boolean('requires_auth')->default(true);     // lleva al login si hace falta
            $table->boolean('is_active')->default(true);
            $table->smallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shortcuts');
    }
};
