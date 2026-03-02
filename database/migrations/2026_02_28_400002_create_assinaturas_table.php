<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assinaturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('documento_assinavel_id')->constrained('documentos_assinaveis')->cascadeOnDelete();
            $table->enum('tipo_assinatura', ['paciente', 'profissional']);
            $table->string('nome_assinante');
            $table->string('documento_assinante', 14);
            $table->string('ip', 45);
            $table->text('user_agent');
            $table->timestamp('data_assinatura');
            $table->string('hash_assinatura', 64);
            $table->string('assinatura_imagem')->nullable();
            $table->timestamps();

            $table->unique(['documento_assinavel_id', 'tipo_assinatura']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assinaturas');
    }
};
