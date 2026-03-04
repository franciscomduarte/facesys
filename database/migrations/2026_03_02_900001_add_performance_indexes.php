<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ============================================================
        // PATIENTS — filtros por empresa + ordenacao por data
        // ============================================================
        Schema::table('patients', function (Blueprint $table) {
            $table->index(['empresa_id', 'created_at'], 'patients_empresa_created_idx');
        });

        // ============================================================
        // TREATMENT_SESSIONS — busca por paciente dentro da empresa
        // ============================================================
        Schema::table('treatment_sessions', function (Blueprint $table) {
            $table->index(['empresa_id', 'patient_id'], 'sessions_empresa_patient_idx');
            $table->index(['empresa_id', 'created_at'], 'sessions_empresa_created_idx');
        });

        // ============================================================
        // AGENDAMENTOS — queries mais frequentes do sistema
        // ============================================================
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->index(['empresa_id', 'data_agendamento'], 'agendamentos_empresa_data_idx');
            $table->index(['empresa_id', 'profissional_id', 'data_agendamento'], 'agendamentos_empresa_prof_data_idx');
            $table->index(['empresa_id', 'status', 'data_agendamento'], 'agendamentos_empresa_status_data_idx');
        });

        // ============================================================
        // FOTOS_CLINICAS — busca por paciente dentro da empresa
        // ============================================================
        Schema::table('fotos_clinicas', function (Blueprint $table) {
            $table->index(['empresa_id', 'patient_id'], 'fotos_empresa_patient_idx');
        });

        // ============================================================
        // CONTRATOS — filtro por status dentro da empresa
        // ============================================================
        Schema::table('contratos', function (Blueprint $table) {
            $table->index(['empresa_id', 'status'], 'contratos_empresa_status_idx');
        });

        // ============================================================
        // PRESCRICOES — busca por paciente dentro da empresa
        // ============================================================
        Schema::table('prescricoes', function (Blueprint $table) {
            $table->index(['empresa_id', 'patient_id'], 'prescricoes_empresa_patient_idx');
        });

        // ============================================================
        // DOCUMENTOS_ASSINAVEIS — filtro por status dentro da empresa
        // ============================================================
        Schema::table('documentos_assinaveis', function (Blueprint $table) {
            $table->index(['empresa_id', 'status'], 'docs_assinaveis_empresa_status_idx');
        });

        // ============================================================
        // SUBSCRIPTIONS — queries do dashboard admin
        // ============================================================
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->index(['status', 'proxima_cobranca'], 'subscriptions_status_cobranca_idx');
            $table->index(['status', 'trial_termina_em'], 'subscriptions_status_trial_idx');
        });

        // ============================================================
        // ACTIVITY_LOG — viewer do super admin (filtros frequentes)
        // ============================================================
        Schema::table('activity_log', function (Blueprint $table) {
            $table->index(['subject_type', 'event'], 'activity_subject_event_idx');
            $table->index('created_at', 'activity_created_idx');
        });

        // ============================================================
        // USERS — busca por empresa + role (dropdowns de profissionais)
        // ============================================================
        Schema::table('users', function (Blueprint $table) {
            $table->index(['empresa_id', 'role'], 'users_empresa_role_idx');
        });

        // ============================================================
        // PROCEDIMENTOS — busca por empresa + ativo (dropdowns)
        // ============================================================
        Schema::table('procedimentos', function (Blueprint $table) {
            $table->index(['empresa_id', 'ativo'], 'procedimentos_empresa_ativo_idx');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropIndex('patients_empresa_created_idx');
        });

        Schema::table('treatment_sessions', function (Blueprint $table) {
            $table->dropIndex('sessions_empresa_patient_idx');
            $table->dropIndex('sessions_empresa_created_idx');
        });

        Schema::table('agendamentos', function (Blueprint $table) {
            $table->dropIndex('agendamentos_empresa_data_idx');
            $table->dropIndex('agendamentos_empresa_prof_data_idx');
            $table->dropIndex('agendamentos_empresa_status_data_idx');
        });

        Schema::table('fotos_clinicas', function (Blueprint $table) {
            $table->dropIndex('fotos_empresa_patient_idx');
        });

        Schema::table('contratos', function (Blueprint $table) {
            $table->dropIndex('contratos_empresa_status_idx');
        });

        Schema::table('prescricoes', function (Blueprint $table) {
            $table->dropIndex('prescricoes_empresa_patient_idx');
        });

        Schema::table('documentos_assinaveis', function (Blueprint $table) {
            $table->dropIndex('docs_assinaveis_empresa_status_idx');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropIndex('subscriptions_status_cobranca_idx');
            $table->dropIndex('subscriptions_status_trial_idx');
        });

        Schema::table('activity_log', function (Blueprint $table) {
            $table->dropIndex('activity_subject_event_idx');
            $table->dropIndex('activity_created_idx');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_empresa_role_idx');
        });

        Schema::table('procedimentos', function (Blueprint $table) {
            $table->dropIndex('procedimentos_empresa_ativo_idx');
        });
    }
};
