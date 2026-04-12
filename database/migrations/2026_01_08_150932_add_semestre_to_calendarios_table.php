<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('calendarios', function (Blueprint $table) {
            $table->string('semestre', 10)->nullable()->after('ano');
            $table->unique(['ano', 'semestre']); // Garante unicidade ano+semestre
        });

        // Atualiza os calendários existentes
        \Illuminate\Support\Facades\DB::table('calendarios')
            ->where('id', 1)
            ->update(['semestre' => '2']);
            
        \Illuminate\Support\Facades\DB::table('calendarios')
            ->where('id', 2)
            ->update(['semestre' => '1']);
    }

    public function down(): void
    {
        Schema::table('calendarios', function (Blueprint $table) {
            $table->dropUnique(['ano', 'semestre']);
            $table->dropColumn('semestre');
        });
    }
};