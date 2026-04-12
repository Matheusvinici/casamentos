<?php
// app/Livewire/Notas/NotasIndex.php

namespace App\Livewire\Notas;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Turma;
use App\Models\Aluno;
use App\Models\TipoAvaliacao;
use App\Models\Nota;
use App\Models\Calendario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotasIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filterTurma = '';
    public $calendarioAtual;
    public $calendarios = [];
    public $totalPontos = 0;
    public $totalAlunos = 0;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterTurma' => ['except' => '']
    ];

    public function mount()
    {
        $this->calendarios = Calendario::ordenado()->get();
        $this->calendarioAtual = $this->getCalendarioAtual();
        $this->calcularTotais();
    }

    /**
     * Obtém o calendário atual (da sessão ou ativo)
     */
    private function getCalendarioAtual()
    {
        $calendarioId = session('calendario_visualizacao_id');
        
        if (!$calendarioId) {
            $calendarioAtivo = Calendario::where('ativo', true)->first();
            if ($calendarioAtivo) {
                $calendarioId = $calendarioAtivo->id;
                session(['calendario_visualizacao_id' => $calendarioId]);
            }
        }
        
        return Calendario::find($calendarioId);
    }

    /**
     * Calcula totais do período
     */
    private function calcularTotais()
    {
        if ($this->calendarioAtual) {
            $this->totalPontos = TipoAvaliacao::where('calendario_id', $this->calendarioAtual->id)
                ->where('ativo', true)
                ->sum('valor_maximo');
        }
    }

    public function updatedCalendarioAtual()
    {
        if ($this->calendarioAtual) {
            session(['calendario_visualizacao_id' => $this->calendarioAtual->id]);
            $this->calcularTotais();
            $this->resetPage();
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }


        public function render()
    {
        // Turmas para o filtro
        $turmas = collect([]);
        $tiposAvaliacao = collect([]);

        if (Auth::guard('professor')->check()) {
            $professorId = Auth::guard('professor')->id();
            
            // Turmas do professor
            $turmas = Turma::where('professor_id', $professorId)
                ->when($this->calendarioAtual, function($q) {
                    return $q->where('turmas.calendario_id', $this->calendarioAtual->id);
                })
                ->orderBy('nome')
                ->get();

            // Tipos de avaliação do calendário, filtrando pelos cursos das turmas
            if ($this->calendarioAtual) {
                $queryTipos = TipoAvaliacao::where('calendario_id', $this->calendarioAtual->id)
                    ->where('ativo', true);

                if ($this->filterTurma) {
                    $turmaSelecionada = $turmas->firstWhere('id', $this->filterTurma);
                    if ($turmaSelecionada && $turmaSelecionada->curso_id) {
                        $queryTipos->where('curso_id', $turmaSelecionada->curso_id);
                    }
                } else {
                    $cursosIds = $turmas->pluck('curso_id')->filter()->unique()->toArray();
                    $queryTipos->whereIn('curso_id', $cursosIds);
                }

                $tiposAvaliacao = $queryTipos->orderBy('ordem')->get();
            }
        }

        // Query para buscar alunos com suas notas - CORRIGIDA
        $query = Aluno::query();
        
        // Filtro por professor
        if (Auth::guard('professor')->check()) {
            $professorId = Auth::guard('professor')->id();
            $query->whereHas('turmas', function($q) use ($professorId) {
                $q->where('professor_id', $professorId);
                if ($this->calendarioAtual) {
                    // ESPECIFICA que é da tabela turmas
                    $q->where('turmas.calendario_id', $this->calendarioAtual->id);
                }
                if ($this->filterTurma) {
                    $q->where('turmas.id', $this->filterTurma);
                }
            });
        }

        // Filtro de busca por nome
        if ($this->search) {
            $query->where('alunos.nome', 'like', '%' . $this->search . '%');
        }

        // Total de alunos para o contador
        $this->totalAlunos = $query->count();

        // Paginação
        $alunos = $query->orderBy('alunos.nome')->paginate(15);

        // Para cada aluno, buscar suas notas - CORRIGIDO
        $notasPorAluno = [];
        $totaisPorAluno = [];
        
        foreach ($alunos as $aluno) {
            $notasQuery = Nota::where('aluno_id', $aluno->id)
                ->whereHas('turma', function($q) {
                    if (Auth::guard('professor')->check()) {
                        $q->where('professor_id', Auth::guard('professor')->id());
                    }
                    if ($this->calendarioAtual) {
                        // ESPECIFICA que é da tabela turmas
                        $q->where('turmas.calendario_id', $this->calendarioAtual->id);
                    }
                    if ($this->filterTurma) {
                        $q->where('turmas.id', $this->filterTurma);
                    }
                });

            $notas = $notasQuery->get()->keyBy('tipo_avaliacao_id');
            $notasPorAluno[$aluno->id] = $notas;
            
            // Calcular total de pontos do aluno
            $totalAluno = 0;
            foreach ($notas as $nota) {
                $totalAluno += $nota->valor;
            }
            $totaisPorAluno[$aluno->id] = $totalAluno;
        }

        return view('livewire.notas.notas-index', compact('alunos', 'turmas', 'tiposAvaliacao', 'notasPorAluno', 'totaisPorAluno'));
    }
}