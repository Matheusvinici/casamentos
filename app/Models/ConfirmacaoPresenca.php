<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfirmacaoPresenca extends Model
{
    protected $fillable = [
        'user_id',
        'nome_completo',
        'status',
        'senha_acesso',
    ];

    protected static function booted()
    {
        static::creating(function ($confirmacao) {
            if (empty($confirmacao->senha_acesso)) {
                $confirmacao->senha_acesso = strtoupper(\Illuminate\Support\Str::random(6));
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
