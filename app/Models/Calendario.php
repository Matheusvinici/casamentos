<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Calendario extends Model
{
    use HasFactory;

    protected $table = 'calendarios';

    protected $fillable = [
        'ano',
        'semestre',
        'inicio',
        'fim',
        'ativo',
        'total_dias_letivos',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'inicio' => 'date',
        'fim' => 'date',
    ];

    // Scope para ordenar por ano e semestre
    public function scopeOrdenado($query)
    {
        return $query->orderBy('ano', 'DESC')
                    ->orderByRaw("CASE WHEN semestre = '1' THEN 1 WHEN semestre = '2' THEN 2 ELSE 3 END");
    }

    // Nome completo (ano + semestre)
    public function getNomeCompletoAttribute()
    {
        return $this->ano . ($this->semestre ? '.' . $this->semestre : '');
    }

    public function unidades(): HasMany
    {
        return $this->hasMany(Unidade::class);
    }
}