<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alunos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('telefone')->nullable();
            $table->string('email')->nullable();
            $table->string('endereco')->nullable();
            $table->string('distrito')->nullable();
            $table->string('turno_escola')->nullable();
            $table->string('turno_idioma')->nullable();
            $table->string('contato_emergencia')->nullable();
            $table->date('data_nascimento');
            $table->enum('tipo', ['aluno_rede', 'servidor', 'outros'])->default('aluno_rede');
            $table->enum('origem', ['municipal', 'estadual'])->nullable();
            $table->string('origem_servidor')->nullable();
            $table->foreignId('escola_id')->nullable();
            $table->foreignId('bairro_id')->nullable();
            $table->foreignId('cidade_id')->nullable();
            $table->foreignId('pais_id')->nullable();
            $table->string('responsavel_nome')->nullable();
            $table->string('responsavel_telefone')->nullable();
            $table->string('responsavel_cpf')->nullable();
            $table->string('responsavel_email')->nullable();
            $table->string('responsavel_endereco')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alunos');
    }
};