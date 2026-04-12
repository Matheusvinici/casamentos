<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Responsavel extends Model
{
    protected $fillable = ['nome', 'telefone', 'cpf', 'email', 'endereco'];

    public function alunos(): HasMany
    {
        return $this->hasMany(Aluno::class);
    }
}