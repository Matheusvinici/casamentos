<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TipoAvaliacao extends Model
{
    protected $table = 'tipo_avaliacaos';
    
    protected $fillable = [
        'calendario_id',
        'curso_id',
        'nome', 
        'abreviacao',
        'descricao',
        'peso',
        'valor_maximo',
        'ordem',
        'ativo'
    ];

    protected $casts = [
        'peso' => 'decimal:2',
        'valor_maximo' => 'decimal:2',
        'ativo' => 'boolean'
    ];

    // Relacionamentos
    public function calendario(): BelongsTo
    {
        return $this->belongsTo(Calendario::class);
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function notas(): HasMany
    {
        return $this->hasMany(Nota::class, 'tipo_avaliacao_id');
    }

    // Scopes
    public function scopeAtivo($query)
    {
        return $query->where('ativo', true);
    }

    public function scopePorCalendario($query, $calendarioId)
    {
        return $query->where('calendario_id', $calendarioId);
    }

    public function scopeOrdenado($query)
    {
        return $query->orderBy('ordem');
    }

    // Accessors
    public function getNomeCompletoAttribute()
    {
        return $this->abreviacao 
            ? "{$this->nome} ({$this->abreviacao})" 
            : $this->nome;
    }

    public function getValorFormatadoAttribute()
    {
        return number_format($this->valor_maximo, 1, ',', '.') . ' pontos';
    }

    public function getPesoFormatadoAttribute()
    {
        return number_format($this->peso, 1, ',', '.');
    }

    // Métodos estáticos
    public static function getProximaOrdem($calendarioId)
    {
        return self::where('calendario_id', $calendarioId)->max('ordem') + 1;
    }

    public static function totalPontosPorCalendario($calendarioId)
    {
        return self::where('calendario_id', $calendarioId)
            ->where('ativo', true)
            ->sum('valor_maximo');
    }

    public static function totalPesosPorCalendario($calendarioId)
    {
        return self::where('calendario_id', $calendarioId)
            ->where('ativo', true)
            ->sum('peso');
    }
}