<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planos', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100);
            $table->string('slug', 100)->unique();
            $table->text('descricao')->nullable();
            $table->decimal('valor_mensal', 10, 2);
            $table->decimal('valor_anual', 10, 2)->nullable();
            $table->enum('periodicidade_padrao', ['mensal', 'anual'])->default('mensal');
            $table->integer('limite_usuarios')->default(-1);
            $table->integer('limite_pacientes')->default(-1);
            $table->integer('limite_agendamentos_mes')->default(-1);
            $table->jsonb('funcionalidades')->default('{}');
            $table->integer('trial_dias')->default(0);
            $table->boolean('ativo')->default(true);
            $table->integer('ordem')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planos');
    }
};
