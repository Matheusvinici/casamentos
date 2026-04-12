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
        Schema::table('tipo_avaliacaos', function (Blueprint $table) {
            $table->unsignedBigInteger('calendario_id')->nullable()->after('id');
            $table->text('descricao')->nullable()->after('abreviacao');
            $table->decimal('peso', 5, 2)->nullable()->default(1.00)->after('descricao');
            $table->decimal('valor_maximo', 5, 2)->nullable()->default(10.00)->after('peso');
            $table->integer('ordem')->nullable()->default(0)->after('valor_maximo');
            $table->boolean('ativo')->nullable()->default(true)->after('ordem');
            
            $table->foreign('calendario_id')
                  ->references('id')
                  ->on('calendarios')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tipo_avaliacaos', function (Blueprint $table) {
            // Remove a foreign key primeiro se existir
            $table->dropForeign(['calendario_id']);
            
            // Remove as colunas adicionadas
            $table->dropColumn([
                'calendario_id',
                'descricao',
                'peso',
                'valor_maximo',
                'ordem',
                'ativo'
            ]);
        });
    }
};