<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescricoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('treatment_session_id')->constrained()->cascadeOnDelete();
            $table->date('data_emissao')->nullable();
            $table->text('observacoes_gerais')->nullable();
            $table->enum('status', ['rascunho', 'emitida', 'assinada'])->default('rascunho');
            $table->string('profissional_responsavel');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['patient_id', 'status']);
            $table->index('treatment_session_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescricoes');
    }
};
