<?php
// database/migrations/2024_02_27_160000_create_notas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notas', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('ator_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->foreignId('tipo_avaliacao_id')->constrained()->cascadeOnDelete();
            $table->foreignId('aluno_id')->constrained()->cascadeOnDelete();
            $table->foreignId('turma_id')->constrained()->cascadeOnDelete();
            $table->foreignId('calendario_id')->constrained()->cascadeOnDelete();
            
            $table->decimal('valor', 5, 2); 
            $table->date('data_lancamento')->nullable();
            $table->text('observacao')->nullable();
            
            $table->foreignId('lancado_por')->nullable()->constrained('professores')->nullOnDelete();
            
            $table->timestamps();
            
            $table->index(['aluno_id', 'turma_id'], 'idx_notas_aluno_turma');
            $table->index(['tipo_avaliacao_id', 'calendario_id'], 'idx_notas_avaliacao_calendario');
            $table->index('data_lancamento', 'idx_notas_data_lancamento');
            
          
            $table->unique(['aluno_id', 'tipo_avaliacao_id', 'calendario_id'], 'unique_nota_aluno_avaliacao');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notas');
    }
};