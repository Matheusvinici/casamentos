<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('letivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ator_id')->nullable()->constrained('users');
            $table->foreignId('turma_id')->constrained('turmas');
            $table->foreignId('turno_id')->constrained('turnos');
            $table->enum('dia', [
                'segunda-feira',
                'terça-feira',
                'quarta-feira',
                'quinta-feira',
                'sexta-feira',
                'sábado',
                'domingo'
            ]);
            // $table->integer('numero_aulas')->default(1);
            $table->time('horario_inicio'); // Formato 24h (ex: 14:30:00)
            $table->time('horario_saida');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('letivos');
    }
};
