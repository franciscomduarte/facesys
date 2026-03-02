<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('plano_id')->constrained('planos')->restrictOnDelete();
            $table->enum('status', ['trial', 'ativa', 'inadimplente', 'cancelada', 'expirada'])->default('trial');
            $table->enum('periodicidade', ['mensal', 'anual'])->default('mensal');
            $table->date('data_inicio');
            $table->date('data_fim')->nullable();
            $table->date('trial_termina_em')->nullable();
            $table->date('proxima_cobranca')->nullable();
            $table->string('gateway', 50)->nullable();
            $table->string('gateway_subscription_id')->nullable();
            $table->string('gateway_customer_id')->nullable();
            $table->decimal('valor_atual', 10, 2);
            $table->text('observacoes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('empresa_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
