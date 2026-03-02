<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('nome_fantasia', 255);
            $table->string('razao_social', 255)->nullable();
            $table->string('cnpj', 18)->unique()->nullable();
            $table->string('email')->nullable();
            $table->string('telefone')->nullable();
            $table->enum('status', ['ativa', 'inativa', 'suspensa'])->default('ativa');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
