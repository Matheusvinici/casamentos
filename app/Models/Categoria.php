<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = ['curso_id', 'nome', 'abreviacao'];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function turmas()
    {
        return $this->hasMany(Turma::class);
    }
}