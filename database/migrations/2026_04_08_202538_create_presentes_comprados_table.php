<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presentes_comprados', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('presente_id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('metodo_pagamento'); // pix ou cartao
            $table->string('comprovante_path')->nullable();
            $table->timestamps();

            $table->unique('presente_id'); // Cada presente só pode ser comprado 1 vez
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presentes_comprados');
    }
};
