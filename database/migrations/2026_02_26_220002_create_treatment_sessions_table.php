<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('treatment_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();

            $table->date('data_sessao');
            $table->string('procedimento');
            $table->string('marca_produto');
            $table->string('lote')->nullable();
            $table->decimal('quantidade_total', 8, 2);
            $table->text('observacoes_sessao')->nullable();
            $table->string('profissional_responsavel');

            // Auditoria
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('treatment_sessions');
    }
};
