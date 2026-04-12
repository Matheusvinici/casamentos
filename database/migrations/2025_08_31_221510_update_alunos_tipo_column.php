<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAlunosTipoColumn extends Migration
{
    public function up()
    {
        Schema::table('alunos', function (Blueprint $table) {
            $table->enum('tipo', ['aluno_rede', 'aluno_estado', 'servidor', 'outros'])->default('aluno_rede')->change();
        });
    }

    public function down()
    {
        Schema::table('alunos', function (Blueprint $table) {
            $table->enum('tipo', ['aluno_rede', 'servidor', 'outros'])->default('aluno_rede')->change();
        });
    }
}