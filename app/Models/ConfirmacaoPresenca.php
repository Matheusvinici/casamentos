<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfirmacaoPresenca extends Model
{
    protected $fillable = [
        'user_id',
        'nome_completo',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
