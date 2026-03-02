<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();

            // Dados pessoais
            $table->string('nome_completo');
            $table->date('data_nascimento');
            $table->enum('sexo', ['masculino', 'feminino', 'outro']);
            $table->string('cpf', 14)->unique();
            $table->string('telefone', 20);
            $table->string('email')->nullable();
            $table->text('endereco')->nullable();
            $table->string('profissao')->nullable();
            $table->text('observacoes_gerais')->nullable();

            // Dados clinicos
            $table->text('historico_medico')->nullable();
            $table->text('medicamentos_continuo')->nullable();
            $table->text('alergias')->nullable();
            $table->text('doencas_preexistentes')->nullable();
            $table->text('contraindicacoes_esteticas')->nullable();
            $table->text('observacoes_medicas')->nullable();

            // Auditoria
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
