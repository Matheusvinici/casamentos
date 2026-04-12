<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Importa o trait SoftDeletes

class Frequencia extends Model
{
    use SoftDeletes; // Usa o trait SoftDeletes para habilitar soft deletes

    protected $fillable = [
        'aulas_id',
        'aluno_id',
        'matricula_id',
        'letivo_id',
        'calendario_id',
        'aulas_ausentes',
        'justificativa',
        'observacao',
    ];

    protected $casts = [
        'aulas_ausentes' => 'integer',
    ];

    public function aula()
    {
        return $this->belongsTo(Aula::class, 'aulas_id');
    }

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }

    public function matricula()
    {
        return $this->belongsTo(Matricula::class);
    }

    public function letivo()
    {
        return $this->belongsTo(Letivo::class);
    }

    public function calendario()
    {
        return $this->belongsTo(Calendario::class);
    }
}