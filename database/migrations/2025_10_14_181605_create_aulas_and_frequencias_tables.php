<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        // Criar a tabela aulas
        Schema::create('aulas', function (Blueprint $table) {
            $table->id();
            $table->date('dia');
            $table->unsignedInteger('total_aulas')->default(0);
            $table->foreignId('turma_id')->constrained('turmas');
            $table->foreignId('turno_id')->nullable()->constrained('turnos');
            $table->foreignId('professor_id')->nullable()->constrained('professores');
            $table->foreignId('letivo_id')->nullable()->constrained('letivos');
            $table->foreignId('calendario_id')->constrained('calendarios');
            $table->softDeletes(); // Adiciona a coluna deleted_at para soft deletes
            $table->timestamps();
        });
        // Dropar a tabela frequencias existente, se houver
        Schema::dropIfExists('frequencias');
        
        // Criar a tabela frequencias
        Schema::create('frequencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aulas_id')->constrained('aulas');
            $table->foreignId('aluno_id')->constrained('alunos');
            $table->foreignId('matricula_id')->constrained('matriculas');
            $table->foreignId('letivo_id')->nullable()->constrained('letivos');
            $table->foreignId('calendario_id')->constrained('calendarios');
            $table->unsignedInteger('aulas_ausentes')->default(0);
            $table->string('justificativa')->nullable();
            $table->text('observacao')->nullable();
            $table->softDeletes(); // Adiciona a coluna deleted_at para soft deletes
            $table->timestamps();

            // Índices para otimizar consultas
            $table->index(['aulas_id', 'aluno_id']);
            $table->index('letivo_id');
            $table->index('calendario_id');
        });
    }
    public function down(): void
    {
        // Dropar a tabela frequencias
        Schema::dropIfExists('frequencias');
        // Dropar a tabela aulas
        Schema::dropIfExists('aulas');
    }
};