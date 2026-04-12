<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Importa o trait SoftDeletes

class Aula extends Model
{

    use SoftDeletes; // Usa o trait SoftDeletes para habilitar soft deletes

    protected $fillable = [
        'dia',
        'total_aulas',
        'turma_id',
        'turno_id',
        'professor_id',
        'letivo_id',
        'calendario_id',
    ];

    protected $casts = [
        'dia' => 'date',
        'total_aulas' => 'integer',
    ];

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    public function turno()
    {
        return $this->belongsTo(Turno::class);
    }

    public function professor()
    {
        return $this->belongsTo(Professor::class);
    }

    public function letivo()
    {
        return $this->belongsTo(Letivo::class);
    }

    public function calendario()
    {
        return $this->belongsTo(Calendario::class);
    }

    public function frequencias()
    {
        return $this->hasMany(Frequencia::class, 'aulas_id');
    }
    
     public function conteudoMinistrado()
    {
        return $this->hasOne(ConteudoMinistrado::class, 'aulas_id');
    }
}