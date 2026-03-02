<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('treatment_session_id')->constrained()->cascadeOnDelete();

            $table->string('regiao_musculo');
            $table->decimal('unidades_aplicadas', 6, 2);
            $table->text('observacoes')->nullable();
            $table->decimal('coord_x', 8, 4); // Percentual 0-100
            $table->decimal('coord_y', 8, 4); // Percentual 0-100

            // Auditoria
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_points');
    }
};
