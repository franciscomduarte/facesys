<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('procedimento_sessao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('treatment_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('procedimento_id')->constrained('procedimentos')->cascadeOnDelete();

            $table->decimal('quantidade', 8, 2)->nullable();
            $table->text('observacoes')->nullable();

            $table->timestamps();

            $table->unique(['treatment_session_id', 'procedimento_id'], 'sessao_procedimento_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('procedimento_sessao');
    }
};
