<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conteudos_ministrados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('aulas_id');
            $table->text('conteudo')->nullable();
            $table->text('observacao')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('aulas_id')->references('id')->on('aulas')->onDelete('cascade');
            $table->unique('aulas_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conteudos_ministrados');
    }
};