<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('procedimentos', function (Blueprint $table) {
            $table->id();

            $table->string('nome');
            $table->enum('categoria', ['facial', 'corporal', 'capilar', 'outro']);
            $table->text('descricao_clinica');
            $table->text('indicacoes');
            $table->text('contraindicacoes');
            $table->integer('duracao_media_minutos');
            $table->boolean('ativo')->default(true);

            // Campos opcionais
            $table->decimal('valor_padrao', 10, 2)->nullable();
            $table->text('observacoes_internas')->nullable();
            $table->text('termo_padrao')->nullable();

            // Auditoria
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('procedimentos');
    }
};
