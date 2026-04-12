<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Aluno extends Model
{
    protected $fillable = [
        'nome', 'telefone', 'email', 'endereco', 'bairro_id', 'cidade_id', 'pais_id', 'distrito',
        'turno_escola', 'turno_idioma', 'contato_emergencia', 'escola_id', 'data_nascimento',
        'tipo', 'origem', 'origem_servidor', 'responsavel_nome', 'responsavel_telefone',
        'responsavel_cpf', 'responsavel_email', 'responsavel_endereco', 'escola_estado', 'ano_escolar','aluno_cpf', 'raca_cor',           
    ];

    protected $casts = [
        'data_nascimento' => 'date',
    ];

    public function escola(): BelongsTo
    {
        return $this->belongsTo(Escola::class);
    }

    public function bairro(): BelongsTo
    {
        return $this->belongsTo(Bairro::class);
    }

    public function cidade(): BelongsTo
    {
        return $this->belongsTo(Cidade::class);
    }

    public function pais(): BelongsTo
    {
        return $this->belongsTo(Pais::class);
    }

    public function turmas(): BelongsToMany
    {
        return $this->belongsToMany(Turma::class, 'matriculas')
            ->withPivot('data_matricula', 'status')
            ->withTimestamps();
    }

    public function notas(): HasMany
    {
        return $this->hasMany(Nota::class);
    }

    public function frequencias(): HasMany
    {
        return $this->hasMany(Frequencia::class);
    }
      public function deficiencias(): BelongsToMany
    {
        return $this->belongsToMany(Deficiencia::class, 'aluno_deficiencia')
            ->withTimestamps();
    }

    public function fotos(): HasMany
    {
        return $this->hasMany(AlunoFoto::class);
    }
}
