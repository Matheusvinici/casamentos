<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cidade extends Model
{
    use HasFactory;
    protected $table = 'cidades';
    protected $fillable = [
    'estado_id',
    'nome',
    'codigo_ibge',

        ];
        
        public function estados()
        {
            return $this->belongsTo('App\Models\Estado', 'estado_id');
        }

        public function distritos()
        {
            return $this->hasMany('App\Models\Distrito');
        }

}
