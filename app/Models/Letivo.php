<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;  // Para ROW_NUMBER

class Letivo extends Model
{
    use HasFactory;

    protected $fillable = [
        'ator_id',
        'turma_id',
        'turno_id',     // Automático da turma
        'dia',          // Dia da semana
        'horario_inicio',
        'horario_saida',
    ];

    protected $casts = [
        'horario_inicio' => 'datetime:H:i:s',
        'horario_saida' => 'datetime:H:i:s',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Auth::check()) {
                $model->ator_id = Auth::id();
            }
            // Turno automático
            if (!$model->turno_id && $model->turma_id) {
                $turma = Turma::find($model->turma_id);
                $model->turno_id = $turma?->turno_id ?? null;
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->ator_id = Auth::id();
            }
            if (!$model->turno_id && $model->turma_id) {
                $turma = Turma::find($model->turma_id);
                $model->turno_id = $turma?->turno_id ?? null;
            }
        });
    }

    public function ator()
    {
        return $this->belongsTo(User::class, 'ator_id');
    }

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    public function turno()
    {
        return $this->belongsTo(Turno::class);
    }

    public function getDuracaoFormatadaAttribute()
    {
        $inicio = Carbon::parse($this->horario_inicio);
        $saida = Carbon::parse($this->horario_saida);
        $minutes = $inicio->diffInMinutes($saida);

        if ($minutes <= 0) return '0 minutos';

        $hours = intdiv($minutes, 60);
        $min = $minutes % 60;

        if ($hours && $min) {
            return "$hours hora" . ($hours > 1 ? 's' : '') . " e $min minuto" . ($min > 1 ? 's' : '');
        }
        return ($hours ? "$hours hora" . ($hours > 1 ? 's' : '') : "$min minuto" . ($min > 1 ? 's' : ''));
    }

    // Scope: Aulas por turma e dia (para contagem)
    public function scopePorTurma($query, $turmaId)
    {
        return $query->where('turma_id', $turmaId);
    }

    // Método: Gera "Aula X" dinamicamente (ROW_NUMBER por dia/turma)
    public function getNumeroAulaAttribute()
    {
        return DB::table('letivos')
            ->where('turma_id', $this->turma_id)
            ->where('dia', $this->dia)
            ->whereRaw('horario_inicio <= ?', [$this->horario_inicio])
            ->count();  // Conta quantas antes desta (simula ROW_NUMBER simples)
    }

    // Contagem total de aulas por turma
    public static function totalAulasPorTurma($turmaId)
    {
        return self::porTurma($turmaId)->count();  // Total simples
    }
}
