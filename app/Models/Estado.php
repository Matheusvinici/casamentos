<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Estado extends Model
{
    use HasFactory;
    protected $table = 'estados';
    protected $fillable = [
        'pais_id',
        'nome',
        'sigla',
        'codigo_ibge',

            ];

    public function cidades()
    {
        return $this->hasMany('App\Models\Cidade');
    }

    public function paises()
    {
        return $this->belongsTo('App\Models\Pais', 'pais_id');
    }
}
