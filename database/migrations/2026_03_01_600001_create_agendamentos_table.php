<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agendamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('profissional_id')->constrained('users')->nullOnDelete();
            $table->foreignId('treatment_session_id')->nullable()->constrained('treatment_sessions')->nullOnDelete();
            $table->date('data_agendamento');
            $table->time('hora_inicio');
            $table->time('hora_fim');
            $table->enum('status', ['agendado', 'confirmado', 'cancelado', 'realizado', 'nao_compareceu'])->default('agendado');
            $table->enum('tipo_atendimento', ['consulta', 'procedimento'])->default('procedimento');
            $table->enum('origem', ['manual', 'remarcacao'])->default('manual');
            $table->text('observacoes')->nullable();
            $table->string('motivo_cancelamento', 255)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['data_agendamento', 'profissional_id']);
            $table->index(['patient_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agendamentos');
    }
};
