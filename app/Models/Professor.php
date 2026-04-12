<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Professor extends Authenticatable
{
    use Notifiable;

    protected $table = 'professores';

    protected $fillable = ['nome', 'telefone', 'cpf', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    public function turmas(): HasMany
    {
        return $this->hasMany(Turma::class);
    }
}
