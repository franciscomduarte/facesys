<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos_assinaveis', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_documento', 50);
            $table->unsignedBigInteger('documento_id');
            $table->string('documento_type');
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('profissional_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['pendente', 'assinado_paciente', 'assinado_profissional', 'finalizado'])->default('pendente');
            $table->string('hash_documento', 64);
            $table->string('token_acesso', 64)->unique();
            $table->timestamp('token_expira_em');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['documento_type', 'documento_id']);
            $table->index(['patient_id', 'status']);
            $table->index('hash_documento');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos_assinaveis');
    }
};
