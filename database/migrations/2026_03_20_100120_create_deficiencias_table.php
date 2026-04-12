<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deficiencias', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        // Inserir deficiências conforme sua lista
        DB::table('deficiencias')->insert([
            ['nome' => 'Baixa visão', 'descricao' => 'Baixa visão', 'ativo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Deficiência física', 'descricao' => 'Deficiência física', 'ativo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Surdocegueira', 'descricao' => 'Surdocegueira', 'ativo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Cegueira', 'descricao' => 'Cegueira', 'ativo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Deficiência intelectual', 'descricao' => 'Deficiência intelectual', 'ativo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Deficiência auditiva', 'descricao' => 'Deficiência auditiva', 'ativo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Surdez', 'descricao' => 'Surdez', 'ativo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Autismo', 'descricao' => 'Transtorno do Espectro Autista', 'ativo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Transtorno desintegrativo da infância', 'descricao' => 'Transtorno desintegrativo da infância', 'ativo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Altas habilidades/Superdotação', 'descricao' => 'Altas habilidades/Superdotação', 'ativo' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('deficiencias');
    }
};