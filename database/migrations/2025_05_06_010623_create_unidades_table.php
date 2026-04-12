<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calendario_id')->constrained('calendarios')->onDelete('cascade');
            $table->string('nome');
            $table->string('sigla', 10);
            $table->date('data_inicio');
            $table->date('data_final');
            $table->date('data_limite_lancamento')->nullable();
            $table->integer('qtd_dias_letivos');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->unique(['calendario_id', 'nome']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unidades');
    }
};