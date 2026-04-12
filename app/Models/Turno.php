<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Turno extends Model
{
    protected $fillable = ['nome', 'abreviacao'];

    public function turmas(): HasMany
    {
        return $this->hasMany(Turma::class);
    }
}