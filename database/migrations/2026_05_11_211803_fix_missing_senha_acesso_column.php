<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('confirmacao_presencas', 'senha_acesso')) {
            Schema::table('confirmacao_presencas', function (Blueprint $table) {
                $table->string('senha_acesso', 10)->nullable()->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('confirmacao_presencas', 'senha_acesso')) {
            Schema::table('confirmacao_presencas', function (Blueprint $table) {
                $table->dropColumn('senha_acesso');
            });
        }
    }
};
