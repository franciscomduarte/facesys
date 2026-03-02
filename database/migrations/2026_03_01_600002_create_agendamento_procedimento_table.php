<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agendamento_procedimento', function (Blueprint $table) {
            $table->foreignId('agendamento_id')->constrained('agendamentos')->cascadeOnDelete();
            $table->foreignId('procedimento_id')->constrained('procedimentos')->cascadeOnDelete();
            $table->decimal('quantidade', 8, 2)->nullable();
            $table->text('observacoes')->nullable();
            $table->primary(['agendamento_id', 'procedimento_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agendamento_procedimento');
    }
};
