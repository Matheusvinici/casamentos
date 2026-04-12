<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aluno_deficiencia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained()->onDelete('cascade');
            $table->foreignId('deficiencia_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['aluno_id', 'deficiencia_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aluno_deficiencia');
    }
};