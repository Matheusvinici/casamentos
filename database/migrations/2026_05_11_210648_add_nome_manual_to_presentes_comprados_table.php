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
        Schema::table('presentes_comprados', function (Blueprint $table) {
            $table->string('nome_manual')->nullable()->after('presente_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presentes_comprados', function (Blueprint $table) {
            $table->dropColumn('nome_manual');
        });
    }
};
