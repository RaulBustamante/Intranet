<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Versiones de documentos (DOC-04) + texto extraído para buscar (SRCH-02).
 *
 * El registro en `documents` apunta SIEMPRE al archivo vigente; cada subida
 * (la primera y cada reemplazo) deja además una fila en `document_versions`
 * con ese archivo, así que el historial es completo y se puede descargar
 * cualquier versión anterior. `content_text` guarda el texto plano extraído
 * del PDF para que la búsqueda global mire dentro del contenido, no solo el
 * título.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->unsignedInteger('version_no')->default(1)->after('s3_key');
            $table->string('original_name', 255)->nullable()->after('mime_type');
            $table->longText('content_text')->nullable()->after('original_name');   // SRCH-02
        });

        Schema::create('document_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents')->cascadeOnDelete();
            $table->unsignedInteger('version_no');
            $table->string('s3_key', 500);
            $table->string('original_name', 255)->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('size_bytes')->nullable();
            $table->text('note')->nullable();                 // "por qué esta versión"
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['document_id', 'version_no']);
            $table->index('document_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_versions');
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn(['version_no', 'original_name', 'content_text']);
        });
    }
};
