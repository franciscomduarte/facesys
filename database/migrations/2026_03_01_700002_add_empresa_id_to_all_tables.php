<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabelas que recebem empresa_id
        $tables = [
            'users',
            'patients',
            'treatment_sessions',
            'application_points',
            'procedimentos',
            'fotos_clinicas',
            'prescricoes',
            'contratos',
            'documentos_assinaveis',
            'template_contratos',
            'agendamentos',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->foreignId('empresa_id')->nullable()->after('id')
                    ->constrained('empresas')->nullOnDelete();
                $table->index('empresa_id');
            });
        }

        // Adicionar super_admin ao enum de roles (PostgreSQL)
        DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check");
        DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role::text = ANY (ARRAY['super_admin'::text, 'admin'::text, 'medico'::text, 'recepcionista'::text]))");
    }

    public function down(): void
    {
        $tables = [
            'users',
            'patients',
            'treatment_sessions',
            'application_points',
            'procedimentos',
            'fotos_clinicas',
            'prescricoes',
            'contratos',
            'documentos_assinaveis',
            'template_contratos',
            'agendamentos',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropConstrainedForeignId('empresa_id');
            });
        }

        DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check");
        DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role::text = ANY (ARRAY['admin'::text, 'medico'::text, 'recepcionista'::text]))");
    }
};
