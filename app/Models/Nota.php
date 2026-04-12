<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Nota extends Model
{
    use HasFactory;

    protected $table = 'notas';
    
    protected $fillable = [
        'ator_id',
        'tipo_avaliacao_id',
        'aluno_id',
        'turma_id',
        'calendario_id',
        'valor',
        'data_lancamento',
        'observacao',
        'lancado_por'
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'data_lancamento' => 'date'
    ];

    public function ator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ator_id');
    }

    public function tipoAvaliacao(): BelongsTo
    {
        return $this->belongsTo(TipoAvaliacao::class);
    }

    public function aluno(): BelongsTo
    {
        return $this->belongsTo(Aluno::class);
    }

    public function turma(): BelongsTo
    {
        return $this->belongsTo(Turma::class);
    }

    public function calendario(): BelongsTo
    {
        return $this->belongsTo(Calendario::class);
    }

    public function professor(): BelongsTo
    {
        return $this->belongsTo(Professor::class, 'lancado_por');
    }

    // Scopes
    public function scopePorTurma($query, $turmaId)
    {
        return $query->where('turma_id', $turmaId);
    }

    public function scopePorAluno($query, $alunoId)
    {
        return $query->where('aluno_id', $alunoId);
    }

    public function scopePorAvaliacao($query, $tipoAvaliacaoId)
    {
        return $query->where('tipo_avaliacao_id', $tipoAvaliacaoId);
    }

    public function scopePorCalendario($query, $calendarioId)
    {
        return $query->where('calendario_id', $calendarioId);
    }

    public function scopeDoProfessor($query, $professorId)
    {
        return $query->where('lancado_por', $professorId);
    }

 
    public function getValorFormatadoAttribute()
    {
        return number_format($this->valor, 1, ',', '.');
    }

    public function getDataLancamentoFormatadaAttribute()
    {
        return $this->data_lancamento ? $this->data_lancamento->format('d/m/Y') : 'Não informada';
    }

    public function getPercentualAttribute()
    {
        if (!$this->tipoAvaliacao || $this->tipoAvaliacao->valor_maximo <= 0) {
            return 0;
        }
        return round(($this->valor / $this->tipoAvaliacao->valor_maximo) * 100, 1);
    }

    public function getStatusAttribute()
    {
        if (!$this->tipoAvaliacao) {
            return 'indefinido';
        }
        
        $percentual = $this->percentual;
        
        if ($percentual >= 70) {
            return 'Aprovado';
        } elseif ($percentual >= 50) {
            return 'Recuperação';
        } else {
            return 'Reprovado';
        }
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'Aprovado' => 'success',
            'Recuperação' => 'warning',
            'Reprovado' => 'danger',
            default => 'secondary'
        };
    }
}