<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresenteComprado extends Model
{
    protected $table = 'presentes_comprados';

    protected $fillable = [
        'presente_id',
        'user_id',
        'metodo_pagamento',
        'comprovante_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
