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
        Schema::create('turmas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unidade_id')->constrained('unidades');
            $table->foreignId('curso_id')->constrained('cursos'); 
            $table->foreignId('nivel_id')->constrained('nivels');
            $table->foreignId('turno_id')->constrained('turnos');
            $table->foreignId('professor_id')->constrained('professores'); 
             $table->foreignId('categoria_id')->constrained('categorias'); 
             $table->foreignId('calendario_id')->constrained('calendarios'); 


            $table->string('nome');
            $table->string('letra');

            $table->integer('capacidade');
            $table->integer('vaga');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turmas');
    }
};