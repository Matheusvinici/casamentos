<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matriculas', function (Blueprint $table) {
            // Primeiro, adiciona a coluna como nullable
            $table->foreignId('calendario_id')->nullable()->after('turma_id');
        });

        // Aguarda um pouco para garantir que a coluna foi criada
        sleep(1);

        // Agora adiciona a constraint foreign key
        Schema::table('matriculas', function (Blueprint $table) {
            $table->foreign('calendario_id')->references('id')->on('calendarios')->onDelete('cascade');
        });

        // Popula as matrículas existentes com o primeiro calendário
        $calendarioPadrao = DB::table('calendarios')->first();
        if ($calendarioPadrao) {
            DB::table('matriculas')->whereNull('calendario_id')->update([
                'calendario_id' => $calendarioPadrao->id,
                'updated_at' => now()
            ]);
        }

        // Torna a coluna obrigatória
        Schema::table('matriculas', function (Blueprint $table) {
            $table->foreignId('calendario_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('matriculas', function (Blueprint $table) {
            $table->dropForeign(['calendario_id']);
            $table->dropColumn('calendario_id');
        });
    }
};