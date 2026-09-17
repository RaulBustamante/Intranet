<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Autoservicio del empleado (Fase 5): solicitudes con flujo de aprobación.
 *
 * Reemplaza los enlaces a WorkForms/Monday de la v1, donde el empleado enviaba
 * un formulario y no volvía a saber nada. Aquí ve su estatus y su historial.
 *
 * El truco de diseño (REQ-02): los campos de cada tipo viven en `field_schema`
 * (JSON) y los pasos de aprobación en `approval_steps` (JSON). Agregar un tipo
 * nuevo o cambiar un flujo NO requiere programar una pantalla ni migrar: se
 * edita la definición del tipo.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('request_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 40)->unique();          // vacation | it | maintenance | purchase
            $table->string('name_es', 120);
            $table->string('name_en', 120);
            $table->string('icon', 40)->default('inbox');
            $table->json('field_schema');                  // campos del formulario
            $table->json('approval_steps');                // pasos: por rol o por jefe directo
            $table->unsignedSmallInteger('sla_days')->default(3);
            $table->boolean('is_active')->default(true);
            $table->smallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')->constrained('request_types');
            $table->foreignId('requester_id')->constrained('employees');
            $table->json('payload');                       // valores capturados
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled', 'completed'])->default('pending');
            $table->unsignedSmallInteger('current_step')->default(0);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('due_at')->nullable();       // compromiso de tiempo (SLA)
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            $table->index(['requester_id', 'status']);
            $table->index(['status', 'due_at']);
        });

        Schema::create('request_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('service_requests')->cascadeOnDelete();
            $table->unsignedSmallInteger('step');
            $table->foreignId('approver_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('approver_role', 40)->nullable();   // si el paso es por rol
            $table->enum('decision', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('comment')->nullable();
            $table->timestamp('decided_at')->nullable();
            $table->timestamps();

            $table->index(['request_id', 'step']);
        });

        Schema::create('request_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('service_requests')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('employees');
            $table->text('body');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_comments');
        Schema::dropIfExists('request_approvals');
        Schema::dropIfExists('service_requests');
        Schema::dropIfExists('request_types');
    }
};
