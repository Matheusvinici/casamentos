<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlunoFoto extends Model
{
    protected $table = 'aluno_fotos';
    
    protected $fillable = [
        'aluno_id',
        'foto_path',
        'face_descriptor',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function aluno(): BelongsTo
    {
        return $this->belongsTo(Aluno::class);
    }
}
