<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Unidade extends Model
{
    use HasFactory;

    protected $table = 'unidades';

    protected $fillable = [
        'calendario_id',
        'nome',
        'sigla',
        'data_inicio',
        'data_final',
        'data_limite_lancamento',
        'qtd_dias_letivos',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'data_inicio' => 'date',
        'data_final' => 'date',
        'data_limite_lancamento' => 'date',
    ];

    public function calendario(): BelongsTo
    {
        return $this->belongsTo(Calendario::class);
    }
}