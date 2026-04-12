<?php

namespace App\Livewire\Letivos;

use App\Models\Letivo;
use App\Models\Turma;
use App\Models\Calendario;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class LetivosIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $openTurmas = [];  // Array de IDs de turmas abertas
    
    // Propriedade para filtro de calendário
    public $calendarioId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'calendarioId' => ['except' => '']
    ];

    // Inicializa com o calendário da sessão
    public function mount()
    {
        $this->calendarioId = session('calendario_visualizacao_id');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingCalendarioId()
    {
        $this->resetPage();
    }

    public function toggleTurma($turmaId)
    {
        if (in_array($turmaId, $this->openTurmas)) {
            $this->openTurmas = array_diff($this->openTurmas, [$turmaId]);  // Fecha
        } else {
            $this->openTurmas[] = $turmaId;  // Abre
        }
    }

    // Expande todas as turmas
    public function openAll()
    {
        $this->openTurmas = $this->getTurmasIds();  // Pega todos os IDs atuais
    }

    // Fecha todas as turmas
    public function closeAll()
    {
        $this->openTurmas = [];  // Esvazia o array
    }

    // Helper privado: Retorna IDs das turmas carregadas (para openAll)
    private function getTurmasIds()
    {
        $turmas = Turma::when($this->search, function ($query) {
                $query->where('nome', 'like', '%' . $this->search . '%');
            })
            ->when($this->calendarioId, function ($query, $calendarioId) {
                $query->where('calendario_id', $calendarioId);
            })
            ->get('id');  // Só IDs, rápido
        return $turmas->pluck('id')->toArray();
    }

    // Método para alterar o calendário de visualização
    public function alterarCalendario($calendarioId)
    {
        $this->calendarioId = $calendarioId;
        
        // Atualiza a sessão
        session(['calendario_visualizacao_id' => $calendarioId]);
        
        // Limpa as turmas abertas ao mudar de calendário
        $this->openTurmas = [];
        
        // Emite evento para recarregar
        $this->dispatch('calendario-alterado');
    }

    public function delete($id)
    {
        try {
            $letivo = Letivo::findOrFail($id);
            $letivo->delete();
            session()->flash('success', 'Letivo excluído com sucesso!');
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao excluir letivo: ' . $e->getMessage());
        }
    }

    public function render()
    {
        // Obtém o calendário atual sendo visualizado
        $calendarioVisualizacao = Calendario::find($this->calendarioId);
        
        // Obtém todos os calendários para o select
        $calendarios = Calendario::orderBy('ano', 'DESC')
            ->orderBy('semestre', 'DESC')
            ->get();
        
        // Query: Carrega turmas com letivos ordenados (agrupado)
        $turmas = Turma::with([
                'unidade', 
                'turno', 
                'calendario',
                'letivos' => function($q) {
                    $q->orderBy('dia')->orderBy('horario_inicio');
                }
            ])
            ->when($this->search, function ($query) {
                $query->where('nome', 'like', '%' . $this->search . '%');
            })
            ->when($this->calendarioId, function ($query, $calendarioId) {
                $query->where('calendario_id', $calendarioId);
            })
            ->orderBy('nome')
            ->paginate(50);

        // Fallback para letivos vazios
        $turmas->getCollection()->transform(function ($turma) {
            if (!$turma->relationLoaded('letivos')) {
                $turma->setRelation('letivos', collect());
            }
            return $turma;
        });

        return view('livewire.letivos.letivos-index', 
            compact('turmas', 'calendarios', 'calendarioVisualizacao'));
    }
}