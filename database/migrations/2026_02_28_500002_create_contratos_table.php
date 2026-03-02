<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('treatment_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('profissional_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('template_contrato_id')->nullable()->constrained('template_contratos')->nullOnDelete();
            $table->enum('status', ['rascunho', 'gerado', 'assinado'])->default('rascunho');
            $table->longText('conteudo_renderizado');
            $table->string('hash_contrato', 64);
            $table->decimal('valor_total', 10, 2)->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamp('data_geracao')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['patient_id', 'status']);
            $table->index('treatment_session_id');
            $table->index('hash_contrato');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contratos');
    }
};
