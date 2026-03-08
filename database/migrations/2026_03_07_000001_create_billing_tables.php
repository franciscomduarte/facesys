<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Modulos do sistema
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Relacao plano-modulos
        Schema::create('plan_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plano_id')->constrained('planos')->cascadeOnDelete();
            $table->foreignId('module_id')->constrained('modules')->cascadeOnDelete();
            $table->unique(['plano_id', 'module_id']);
        });

        // Feature flags
        Schema::create('feature_flags', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Relacao plano-features
        Schema::create('plan_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plano_id')->constrained('planos')->cascadeOnDelete();
            $table->foreignId('feature_flag_id')->constrained('feature_flags')->cascadeOnDelete();
            $table->boolean('enabled')->default(true);
            $table->unique(['plano_id', 'feature_flag_id']);
        });

        // Pagamentos
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('subscription_id')->constrained('subscriptions')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('BRL');
            $table->string('method'); // pix, credit_card
            $table->string('status'); // pending, paid, failed, refunded
            $table->string('gateway_payment_id')->nullable()->index();
            $table->string('gateway')->nullable();
            $table->json('gateway_data')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        // Faturas
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained('subscriptions')->cascadeOnDelete();
            $table->foreignId('payment_id')->nullable()->constrained('payments')->nullOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('status'); // pending, paid, overdue, cancelled
            $table->date('due_date');
            $table->timestamp('paid_at')->nullable();
            $table->string('invoice_url')->nullable();
            $table->string('gateway_invoice_id')->nullable()->index();
            $table->timestamps();
        });

        // Adicionar gateway_plan_id ao planos (para vincular com Asaas)
        Schema::table('planos', function (Blueprint $table) {
            $table->string('gateway_plan_id')->nullable()->after('ordem');
        });
    }

    public function down(): void
    {
        Schema::table('planos', function (Blueprint $table) {
            $table->dropColumn('gateway_plan_id');
        });
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('plan_features');
        Schema::dropIfExists('feature_flags');
        Schema::dropIfExists('plan_modules');
        Schema::dropIfExists('modules');
    }
};
