<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Distrito extends Model
{
    use HasFactory;
    protected $table = 'distritos';
    protected $fillable = [
        'cidade_id',
        'nome',
        'codigo_ibge',

    ];
    

    public function cidades()
    {
        return $this->belongsTo('App\Models\Cidade', 'cidade_id');

    }

    public function bairros()
    {
        return $this->hasMany('App\Models\Bairro');
    }

    public function estados(){
        return $this->belongsTo('App\Models\Estado', 'estado_id');
    }
}
