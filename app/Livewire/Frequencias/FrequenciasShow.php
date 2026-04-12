<?php

namespace App\Livewire\Frequencias;

use Livewire\Component;
use App\Models\Frequencia;
use App\Models\Aula;
use App\Models\Turma;
use App\Models\Matricula;
use Illuminate\Support\Facades\Auth;

class FrequenciasShow extends Component
{
    public $frequencias;
    public $aula;
    public $turma;

    public function mount($aulas_id, $turma_id)
    {
        // Carregar a aula com relações
        $this->aula = Aula::with('turma.unidade', 'turma.curso', 'turma.nivel', 'turma.turno', 'turma.professor')
            ->where('id', $aulas_id)
            ->where('turma_id', $turma_id)
            ->firstOrFail();

        // Verificar permissão para professores
        if (Auth::guard('professor')->check() && $this->aula->professor_id != Auth::guard('professor')->id()) {
            session()->flash('error', 'Acesso não autorizado.');
            return $this->redirectRoute('Listar-Frequencias-Professor');
        }

        // Carregar a turma
        $this->turma = Turma::with(['unidade', 'curso', 'nivel', 'turno', 'professor'])->findOrFail($turma_id);

        // Carregar todos os alunos matriculados na turma
        $matriculas = Matricula::where('turma_id', $turma_id)->where('status', 'ativo')->with('aluno')->get();

        // Criar uma coleção com frequências (ou valores padrão) para todos os alunos matriculados
        $this->frequencias = $matriculas->map(function ($matricula) use ($aulas_id) {
            $frequencia = Frequencia::where('aulas_id', $aulas_id)->where('aluno_id', $matricula->aluno_id)->first();
            $total_aulas = $this->aula->total_aulas;
            $aulas_ausentes = $frequencia ? $frequencia->aulas_ausentes : 0;

            // Determinar o status
            $status = $aulas_ausentes == $total_aulas ? 'ausente' : ($aulas_ausentes == 0 ? 'presente' : 'parcial');

            return (object) [
                'aluno' => $matricula->aluno,
                'aulas_ausentes' => $aulas_ausentes,
                'justificativa' => $frequencia ? ($frequencia->justificativa ?: 'Nenhuma') : 'Nenhuma',
                'observacao' => $frequencia ? ($frequencia->observacao ?: 'Nenhuma') : 'Nenhuma',
                'status' => $status,
                'has_record' => $frequencia ? true : false, // Indica se há registro explícito
            ];
        });
    }

    public function render()
    {
        return view('livewire.frequencias.frequencias-show', [
            'frequencias' => $this->frequencias,
            'aula' => $this->aula,
            'turma' => $this->turma,
        ]);
    }
}