<?php

namespace App\Livewire\Notas;

use Livewire\Component;
use App\Models\Turma;
use App\Models\Aluno;
use App\Models\TipoAvaliacao;
use App\Models\Nota;
use App\Models\Calendario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NotasCreateEditShow extends Component
{
    // Parâmetros recebidos
    public $turma_id;
    public $mode; // create, edit, show

    // Dados carregados
    public $turma;
    public $aluno;
    public $nota;
    public $alunos = [];
    public $tiposAvaliacao = [];
    public $notas = []; // [aluno_id][tipo_avaliacao_id] => valor
    public $calendarios = [];
    public $calendario_id;
    public $data_lancamento;

    // Campos para edit/show
    public $valor;
    public $tipo_avaliacao_id; // Para edit/show

    // Controle de estado
    public $error_message = '';
    public $success_message = '';
    public $is_data_loaded = false;

    protected $rules = [
        'data_lancamento' => 'required|date',
        'valor' => 'required|numeric|min:0|max:999.99',
        'notas.*.*' => 'nullable|numeric|min:0|max:999.99',
    ];

    public function mount($turma_id, $tipo_avaliacao_id = null, $aluno_id = null, $mode = 'create')
    {
        $this->turma_id = $turma_id;
        $this->tipo_avaliacao_id = $tipo_avaliacao_id;
        $this->aluno_id = $aluno_id;
        $this->mode = $mode;
        $this->data_lancamento = Carbon::today()->format('Y-m-d');
        
        $this->loadInitialData();
    }

    public function loadInitialData()
    {
        // Carrega a turma
        $this->turma = Turma::with(['unidade', 'curso', 'nivel', 'turno', 'professor', 'calendario'])
            ->findOrFail($this->turma_id);

        // Verifica permissão do professor
        if (Auth::guard('professor')->check() && $this->turma->professor_id != Auth::guard('professor')->id()) {
            abort(403, 'Acesso não autorizado.');
        }

        $this->calendario_id = $this->turma->calendario_id;
        
        // Carrega calendários para o filtro
        $this->calendarios = Calendario::ordenado()->get();

        // Carrega os tipos de avaliação do curso da turma referente ao calendário
        $this->tiposAvaliacao = TipoAvaliacao::where('calendario_id', $this->calendario_id)
            ->where('curso_id', $this->turma->curso_id)
            ->where('ativo', true)
            ->orderBy('ordem')
            ->get();

        // Carrega dados específicos do modo
        if ($this->mode == 'create') {
            $this->loadCreateData();
        } elseif ($this->mode == 'edit' || $this->mode == 'show') {
            $this->loadEditShowData();
        }
    }

    public function loadCreateData()
    {
        // Carrega alunos ativos da turma
        $this->alunos = Aluno::whereHas('turmas', function($q) {
                $q->where('turma_id', $this->turma_id)
                  ->where('matriculas.status', 'ativo');
            })
            ->orderBy('nome')
            ->get();

        // Garante que é uma Collection mesmo se vazio
        if (!$this->alunos) {
            $this->alunos = collect([]);
        }

        // Inicializa arrays para notas (multidimensional)
        foreach ($this->alunos as $aluno) {
            foreach ($this->tiposAvaliacao as $tipo) {
                $this->notas[$aluno->id][$tipo->id] = '';
            }
        }

        // Carrega notas existentes se houver
        $notasExistentes = Nota::where('turma_id', $this->turma_id)
            ->whereIn('tipo_avaliacao_id', $this->tiposAvaliacao->pluck('id'))
            ->get();

        foreach ($notasExistentes as $nota) {
            if (isset($this->notas[$nota->aluno_id][$nota->tipo_avaliacao_id])) {
                $this->notas[$nota->aluno_id][$nota->tipo_avaliacao_id] = $nota->valor;
            }
        }

        $this->is_data_loaded = true;
    }

    public function loadEditShowData()
    {
        // Carrega aluno específico
        $this->aluno = Aluno::findOrFail($this->aluno_id);
        
        // Carrega a nota específica
        $this->nota = Nota::with(['professor', 'ator'])
            ->where('turma_id', $this->turma_id)
            ->where('aluno_id', $this->aluno_id)
            ->where('tipo_avaliacao_id', $this->tipo_avaliacao_id)
            ->firstOrFail();

        // Carrega o tipo de avaliação específico
        $this->tipoAvaliacao = TipoAvaliacao::find($this->tipo_avaliacao_id);

        $this->valor = $this->nota->valor;
        $this->data_lancamento = $this->nota->data_lancamento?->format('Y-m-d') ?? Carbon::today()->format('Y-m-d');
        
        $this->is_data_loaded = true;
    }

    public function saveCreate()
    {
        $this->validate([
            'data_lancamento' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $count = 0;
            foreach ($this->alunos as $aluno) {
                foreach ($this->tiposAvaliacao as $tipo) {
                    $valor = $this->notas[$aluno->id][$tipo->id] ?? null;
                    
                    if ($valor !== '' && $valor !== null) {
                        // Verifica se já existe
                        $nota = Nota::where('aluno_id', $aluno->id)
                            ->where('tipo_avaliacao_id', $tipo->id)
                            ->where('calendario_id', $this->calendario_id)
                            ->first();

                        $data = [
                            'ator_id' => Auth::id(),
                            'tipo_avaliacao_id' => $tipo->id,
                            'aluno_id' => $aluno->id,
                            'turma_id' => $this->turma_id,
                            'calendario_id' => $this->calendario_id,
                            'valor' => $valor,
                            'data_lancamento' => $this->data_lancamento,
                        ];

                        if (Auth::guard('professor')->check()) {
                            $data['lancado_por'] = Auth::guard('professor')->id();
                        }

                        if ($nota) {
                            $nota->update($data);
                        } else {
                            Nota::create($data);
                        }
                        $count++;
                    }
                }
            }

            DB::commit();

            session()->flash('success', "$count nota(s) lançada(s) com sucesso!");
            
            // REDIRECIONA PARA A PÁGINA DE CARDS DE TURMAS (igual à frequência)
            return redirect()->route('Notas-Turmas-Professor');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error_message = 'Erro ao salvar notas: ' . $e->getMessage();
        }
    }

    public function saveEdit()
    {
        $this->validate([
            'valor' => 'required|numeric|min:0|max:999.99',
            'data_lancamento' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $this->nota->update([
                'ator_id' => Auth::id(),
                'valor' => $this->valor,
                'data_lancamento' => $this->data_lancamento,
            ]);

            DB::commit();

            session()->flash('success', 'Nota atualizada com sucesso!');
            
            // REDIRECIONA PARA A PÁGINA DE CARDS DE TURMAS (igual à frequência)
            return redirect()->route('Notas-Turmas-Professor');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error_message = 'Erro ao atualizar nota: ' . $e->getMessage();
        }
    }
    public function save()
    {
        if ($this->mode == 'create') {
            return $this->saveCreate();
        } elseif ($this->mode == 'edit') {
            return $this->saveEdit();
        }
    }

    public function render()
    {
        return view('livewire.notas.notas-create-edit-show');
    }
}