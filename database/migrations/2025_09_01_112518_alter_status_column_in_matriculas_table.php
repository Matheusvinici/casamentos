<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Atualizar registros com status 'transferido' para 'desistente'
        DB::table('matriculas')
            ->where('status', 'transferido')
            ->update(['status' => 'desistente']);

        // Modificar a coluna status para o novo ENUM
        Schema::table('matriculas', function (Blueprint $table) {
            $table->enum('status', ['ativo', 'inativo', 'desistente'])->default('ativo')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverter a coluna para o estado anterior (ENUM com 'transferido')
        Schema::table('matriculas', function (Blueprint $table) {
            $table->enum('status', ['ativo', 'inativo', 'transferido'])->default('ativo')->change();
        });

        // Reverter os dados, se necessário
        DB::table('matriculas')
            ->where('status', 'desistente')
            ->update(['status' => 'transferido']);
    }
};