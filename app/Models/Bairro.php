<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bairro extends Model
{
    use HasFactory;
    protected $table = 'bairros';
    
    protected $fillable = [

        'nome',

    ];

    
}
