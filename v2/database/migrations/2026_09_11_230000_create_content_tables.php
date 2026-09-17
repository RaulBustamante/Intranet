<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Contenido y comunicación (Fase 4): documentos, boletines, anuncios,
 * enlaces, reconocimientos, encuestas y ajustes.
 *
 * Todo lo que en la v1 estaba hardcodeado en HTML o servido con S3Client a
 * mano, aquí es administrable desde la interfaz y pasa por el disco s3.
 */
return new class extends Migration
{
    public function up(): void
    {
        // --- Documentos ---
        Schema::create('document_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name_es', 120);
            $table->string('name_en', 120);
            $table->string('slug', 140)->unique();
            $table->enum('visibility', ['all', 'role', 'location'])->default('all');
            $table->string('required_role', 40)->nullable();     // si visibility=role
            $table->smallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title_es', 200);
            $table->string('title_en', 200)->nullable();
            $table->foreignId('category_id')->nullable()->constrained('document_categories')->nullOnDelete();
            $table->string('s3_key', 500);
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('size_bytes')->nullable();
            $table->boolean('is_private')->default(false);       // URL firmada si true
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('category_id');
        });

        // --- Boletines ---
        Schema::create('bulletins', function (Blueprint $table) {
            $table->id();
            $table->string('title_es', 200);
            $table->string('title_en', 200)->nullable();
            $table->smallInteger('period_year');
            $table->unsignedTinyInteger('period_month');
            $table->string('s3_key', 500);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->unique(['period_year', 'period_month']);
        });

        // --- Anuncios ---
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title_es', 200);
            $table->string('title_en', 200)->nullable();
            $table->string('slug', 220)->unique();
            $table->text('excerpt_es')->nullable();
            $table->text('excerpt_en')->nullable();
            $table->longText('body_es')->nullable();
            $table->longText('body_en')->nullable();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('published_at')->nullable();       // futuro = programado
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['published_at', 'expires_at']);
        });

        // Audiencia de un anuncio: sin filas = toda la empresa
        Schema::create('announcement_audiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('announcement_id')->constrained()->cascadeOnDelete();
            $table->foreignId('location_id')->nullable()->constrained('locations')->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->cascadeOnDelete();
        });

        // --- Enlaces (las 20 apps internas, hoy hardcodeadas) ---
        Schema::create('links', function (Blueprint $table) {
            $table->id();
            $table->string('title_es', 120);
            $table->string('title_en', 120)->nullable();
            $table->string('url', 500);
            $table->string('icon_path', 255)->nullable();
            $table->string('category', 60)->nullable();
            $table->boolean('is_internal_only')->default(false); // avisar si solo abre en red
            $table->smallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // --- Reconocimientos (kudos) ---
        Schema::create('kudos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('to_employee_id')->constrained('employees')->cascadeOnDelete();
            $table->text('message');
            $table->string('value_tag', 40)->nullable();
            $table->timestamp('approved_at')->nullable();        // moderación
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index('to_employee_id');
        });

        // --- Encuestas ---
        Schema::create('polls', function (Blueprint $table) {
            $table->id();
            $table->string('question_es', 250);
            $table->string('question_en', 250)->nullable();
            $table->timestamp('opens_at')->nullable();
            $table->timestamp('closes_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('poll_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained()->cascadeOnDelete();
            $table->string('label_es', 200);
            $table->string('label_en', 200)->nullable();
            $table->smallInteger('sort_order')->default(0);
        });

        Schema::create('poll_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained()->cascadeOnDelete();
            $table->foreignId('option_id')->constrained('poll_options')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['poll_id', 'employee_id']);          // un voto por persona
        });

        // --- Ajustes (banner de aviso, tema de temporada) ---
        Schema::create('settings', function (Blueprint $table) {
            $table->string('key', 100)->primary();
            $table->json('value')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        foreach (['settings', 'poll_votes', 'poll_options', 'polls', 'kudos', 'links',
                  'announcement_audiences', 'announcements', 'bulletins', 'documents',
                  'document_categories'] as $t) {
            Schema::dropIfExists($t);
        }
    }
};
