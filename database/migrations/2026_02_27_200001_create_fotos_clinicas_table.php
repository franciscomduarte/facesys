<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fotos_clinicas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('treatment_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('procedimento_id')->constrained('procedimentos')->cascadeOnDelete();
            $table->enum('tipo', ['antes', 'depois']);
            $table->string('caminho_arquivo');
            $table->string('nome_original');
            $table->string('mime_type', 50);
            $table->unsignedInteger('tamanho_bytes');
            $table->date('data_registro');
            $table->string('profissional_responsavel');
            $table->text('observacoes')->nullable();
            $table->string('regiao_facial')->nullable();
            $table->unsignedSmallInteger('ordem')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['patient_id', 'tipo']);
            $table->index(['treatment_session_id', 'procedimento_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fotos_clinicas');
    }
};
