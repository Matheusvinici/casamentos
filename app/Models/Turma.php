<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Turma extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'letra',
        'capacidade',
        'vaga',
        'unidade_id',
        'curso_id',
        'categoria_id',
        'nivel_id',
        'turno_id',
        'professor_id',
        'calendario_id',
    ];

    public function letivos()
    {
        return $this->hasMany(Letivo::class);  // Assume foreign key 'turma_id' em letivos
    }

    public function unidade(): BelongsTo
    {
        return $this->belongsTo(Unidade::class);
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function nivel(): BelongsTo
    {
        return $this->belongsTo(Nivel::class);
    }

    public function turno(): BelongsTo
    {
        return $this->belongsTo(Turno::class);
    }

    public function professor(): BelongsTo
    {
        return $this->belongsTo(Professor::class);
    }

    public function alunos(): BelongsToMany
    {
        return $this->belongsToMany(Aluno::class, 'matriculas')
            ->withPivot('data_matricula', 'status')
            ->withTimestamps();
    }

    public function notas(): HasMany
    {
        return $this->hasMany(Nota::class);
    }

    public function frequencias(): HasMany
    {
        return $this->hasMany(Frequencia::class);
    }

    public function calendario(): BelongsTo
    {
        return $this->belongsTo(Calendario::class);
    }

    // Relação com Matricula (uma turma tem muitas matrículas)
    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'turma_id', 'id');
    }
}
