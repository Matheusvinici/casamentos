<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConteudoMinistrado extends Model
{
    use SoftDeletes;

    protected $table = 'conteudos_ministrados';

    protected $fillable = [
        'aulas_id',
        'conteudo',
        'observacao',
    ];

    protected $casts = [
        'conteudo' => 'string',
        'observacao' => 'string',
    ];

    public function aula()
    {
        return $this->belongsTo(Aula::class, 'aulas_id');
    }
}