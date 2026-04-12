<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('alunos', function (Blueprint $table) {
            $table->string('ano_escolar')->nullable()->after('turno_idioma');
            $table->string('aluno_cpf', 14)->nullable()->after('ano_escolar');
        });
    }

    public function down()
    {
        Schema::table('alunos', function (Blueprint $table) {
            $table->dropColumn(['ano_escolar', 'aluno_cpf']);
        });
    }
};