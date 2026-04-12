<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matriculas', function (Blueprint $table) {
            $table->foreignId('unidade_id')->nullable()->after('calendario_id');
            $table->foreign('unidade_id')->references('id')->on('unidades')->onDelete('cascade');
        });

        // Popula as matrículas existentes com a primeira unidade do seu calendário
        $unidadePadrao = \App\Models\Unidade::first();
        if ($unidadePadrao) {
            \Illuminate\Support\Facades\DB::table('matriculas')
                ->whereNull('unidade_id')
                ->update(['unidade_id' => $unidadePadrao->id]);
        }

        Schema::table('matriculas', function (Blueprint $table) {
            $table->foreignId('unidade_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('matriculas', function (Blueprint $table) {
            $table->dropForeign(['unidade_id']);
            $table->dropColumn('unidade_id');
        });
    }
};