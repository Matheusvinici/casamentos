<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Matricula extends Model
{
    protected $fillable = [
        'aluno_id', 
        'turma_id', 
        'calendario_id',
        'unidade_id', 
        'data_matricula', 
        'status'
    ];

    protected $casts = [
        'data_matricula' => 'date',
    ];

    public function aluno(): BelongsTo
    {
        return $this->belongsTo(Aluno::class);
    }

    public function turma(): BelongsTo
    {
        return $this->belongsTo(Turma::class);
    }

    public function calendario(): BelongsTo
    {
        return $this->belongsTo(Calendario::class);
    }

    public function unidade(): BelongsTo
    {
        return $this->belongsTo(Unidade::class);
    }
}