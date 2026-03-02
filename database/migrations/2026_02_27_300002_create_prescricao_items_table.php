<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescricao_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescricao_id')->constrained('prescricoes')->cascadeOnDelete();
            $table->string('medicamento');
            $table->string('dosagem');
            $table->string('via_administracao', 100);
            $table->string('frequencia');
            $table->string('duracao');
            $table->text('observacoes')->nullable();
            $table->unsignedSmallInteger('ordem')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescricao_items');
    }
};
