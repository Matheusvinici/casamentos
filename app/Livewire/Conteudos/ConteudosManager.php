<?php

namespace App\Livewire\Conteudos;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ConteudoMinistrado;
use App\Models\Aula;
use App\Models\Turma;
use App\Models\Letivo;
use App\Models\Calendario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class ConteudosManager extends Component
{
    use WithPagination;

    // Propriedades para listagem
    public $search = '';
    public $filterData = '';
    public $perPage = 10;

    // Propriedades para criação/edição
    public $turmas = [];
    public $turma_id;
    public $turma;
    public $data;
    public $conteudo = '';
    public $observacao = '';
    public $error_message = '';
    public $is_data_loaded = false;
    public $aula_id = null;
    public $existing_conteudo = null;
    public $existing_conteudo_id = null;
    public $total_aulas_dia = 0;
    public $turno_id = null;
    public $letivo_id = null;
    public $calendario_id = null;

    // Modo de visualização: 'list', 'form'
    public $mode = 'list';
    
    // Para edição/visualização individual
    public $viewing_aula_id = null;
    public $isEditing = false;

    protected $queryString = ['search', 'filterData'];

    public function mount($turma_id = null, $aulas_id = null)
    {
        // Carregar calendário ativo
        $calendarioAtivo = Calendario::where('ativo', 1)->orderBy('ano', 'desc')->first();
        $this->calendario_id = $calendarioAtivo ? $calendarioAtivo->id : null;
        
        // Se veio com parâmetros, vai para o formulário
        if ($turma_id || $aulas_id) {
            $this->mode = 'form';
            
            if ($aulas_id) {
                // Modo edição/visualização
                $this->viewing_aula_id = $aulas_id;
                $this->loadExistingConteudo($aulas_id);
            } elseif ($turma_id) {
                // Modo criação com turma pré-selecionada
                $this->turma_id = $turma_id;
                $this->data = now()->format('Y-m-d');
                $this->loadTurmas();
                $this->turma = Turma::with('professor', 'unidade', 'curso', 'nivel', 'turno')->find($this->turma_id);
            }
        } else {
            $this->mode = 'list';
            $this->loadTurmas();
        }
    }

    protected function loadTurmas()
    {
        if (Auth::guard('professor')->check()) {
            $professor = Auth::guard('professor')->user();
            $this->turmas = $professor->turmas;
        } else {
            $this->turmas = Turma::all();
        }
    }

    protected function loadExistingConteudo($aulas_id)
    {
        $aula = Aula::with(['turma', 'turma.professor', 'turma.unidade', 'turma.curso', 'turma.nivel', 'turma.turno', 'conteudoMinistrado'])
            ->findOrFail($aulas_id);
        
        // Verificar permissão
        if (Auth::guard('professor')->check() && $aula->turma->professor_id != Auth::guard('professor')->id()) {
            abort(403, 'Acesso não autorizado.');
        }
        
        $this->turma_id = $aula->turma_id;
        $this->turma = $aula->turma;
        $this->data = $aula->dia;
        $this->aula_id = $aula->id;
        $this->total_aulas_dia = $aula->total_aulas;
        $this->turno_id = $aula->turno_id;
        $this->letivo_id = $aula->letivo_id;
        
        // Verificar se já existe conteúdo ministrado para esta aula
        if ($aula->conteudoMinistrado) {
            $this->existing_conteudo = $aula->conteudoMinistrado;
            $this->existing_conteudo_id = $aula->conteudoMinistrado->id;
            $this->conteudo = $aula->conteudoMinistrado->conteudo;
            $this->observacao = $aula->conteudoMinistrado->observacao;
        } else {
            $this->existing_conteudo = null;
            $this->existing_conteudo_id = null;
            $this->conteudo = '';
            $this->observacao = '';
        }
        
        $this->is_data_loaded = true;
        $this->loadTurmas();
    }

    public function updatedTurmaId($value)
    {
        $this->resetForm();
        $this->is_data_loaded = false;
        $this->error_message = '';
        $this->turma = $value ? Turma::with('professor', 'unidade', 'curso', 'nivel', 'turno')->find($value) : null;
        $this->aula_id = null;
        $this->existing_conteudo = null;
        $this->existing_conteudo_id = null;
        $this->viewing_aula_id = null;
        $this->total_aulas_dia = 0;
    }

    public function updatedData()
    {
        $this->is_data_loaded = false;
        $this->error_message = '';
        $this->resetForm();
        $this->aula_id = null;
        $this->existing_conteudo = null;
        $this->existing_conteudo_id = null;
        $this->total_aulas_dia = 0;
    }

    public function resetForm()
    {
        $this->conteudo = '';
        $this->observacao = '';
    }

    public function loadData()
    {
        $this->error_message = '';
        
        if (!$this->turma_id) {
            $this->error_message = 'Selecione uma turma.';
            return;
        }
        
        if (!$this->data) {
            $this->error_message = 'Selecione uma data.';
            return;
        }

        // Verificar se existe uma aula para a turma e data
        $aula = Aula::where('turma_id', $this->turma_id)
            ->where('dia', $this->data)
            ->first();

        if ($aula) {
            // Aula já existe
            $this->aula_id = $aula->id;
            $this->total_aulas_dia = $aula->total_aulas;
            $this->turno_id = $aula->turno_id;
            $this->letivo_id = $aula->letivo_id;
            
            // Verificar se já existe conteúdo ministrado
            $this->existing_conteudo = ConteudoMinistrado::where('aulas_id', $this->aula_id)->first();
            
            if ($this->existing_conteudo) {
                $this->existing_conteudo_id = $this->existing_conteudo->id;
                $this->conteudo = $this->existing_conteudo->conteudo;
                $this->observacao = $this->existing_conteudo->observacao;
            } else {
                $this->existing_conteudo_id = null;
                $this->conteudo = '';
                $this->observacao = '';
            }
            
            $this->is_data_loaded = true;
            return;
        }

        // Aula não existe - criar baseado nos letivos
        $dia_semana = Carbon::parse($this->data)->locale('pt_BR')->isoFormat('dddd');
        
        // Buscar letivo para esta turma e dia da semana
        $letivo = Letivo::where('turma_id', $this->turma_id)
            ->where('dia', $dia_semana)
            ->first();

        if (!$letivo) {
            $this->error_message = "Nenhum registro letivo encontrado para a turma no dia {$dia_semana}. Verifique os dias letivos da turma.";
            $this->is_data_loaded = false;
            $this->total_aulas_dia = 0;
            return;
        }

        // Calcular total de aulas do dia
        $total_aulas = Letivo::where('turma_id', $this->turma_id)
            ->where('dia', $dia_semana)
            ->count();
        
        if ($total_aulas == 0) {
            $this->error_message = "Total de aulas não configurado para este dia.";
            $this->is_data_loaded = false;
            return;
        }

        try {
            // Criar a aula automaticamente
            $novaAula = Aula::create([
                'dia' => $this->data,
                'total_aulas' => $total_aulas,
                'turma_id' => $this->turma_id,
                'turno_id' => $this->turma->turno_id ?? $letivo->turno_id,
                'professor_id' => $this->turma->professor_id,
                'letivo_id' => $letivo->id,
                'calendario_id' => $this->calendario_id,
            ]);
            
            $this->aula_id = $novaAula->id;
            $this->total_aulas_dia = $total_aulas;
            $this->turno_id = $novaAula->turno_id;
            $this->letivo_id = $letivo->id;
            $this->existing_conteudo = null;
            $this->existing_conteudo_id = null;
            $this->conteudo = '';
            $this->observacao = '';
            $this->is_data_loaded = true;
            
            session()->flash('info', 'Aula criada automaticamente. Agora registre o conteúdo ministrado.');
            
        } catch (\Exception $e) {
            $this->error_message = 'Erro ao criar a aula: ' . $e->getMessage();
            $this->is_data_loaded = false;
        }
    }

    public function save()
    {
        if (!$this->aula_id) {
            session()->flash('error', 'Nenhuma aula encontrada para registrar o conteúdo.');
            return;
        }

        try {
            $this->validate([
                'conteudo' => 'required|string|min:3',
                'observacao' => 'nullable|string',
            ]);

            // Verificar novamente se já existe conteúdo para esta aula
            $existing = ConteudoMinistrado::where('aulas_id', $this->aula_id)->first();
            
            if ($existing) {
                // ATUALIZAR conteúdo existente
                $existing->update([
                    'conteudo' => $this->conteudo,
                    'observacao' => $this->observacao,
                ]);
                $this->existing_conteudo = $existing;
                $this->existing_conteudo_id = $existing->id;
                session()->flash('success', 'Conteúdo ministrado atualizado com sucesso!');
            } else {
                // CRIAR novo conteúdo
                $novo = ConteudoMinistrado::create([
                    'aulas_id' => $this->aula_id,
                    'conteudo' => $this->conteudo,
                    'observacao' => $this->observacao,
                ]);
                $this->existing_conteudo = $novo;
                $this->existing_conteudo_id = $novo->id;
                session()->flash('success', 'Conteúdo ministrado registrado com sucesso!');
            }

            $this->mode = 'list';
            $this->resetForm();
            $this->resetPage();
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao salvar: ' . $e->getMessage());
        }
    }

    public function delete($aula_id)
    {
        try {
            $conteudo = ConteudoMinistrado::where('aulas_id', $aula_id)->first();
            
            if ($conteudo) {
                $conteudo->delete();
                session()->flash('success', 'Conteúdo ministrado deletado com sucesso!');
            } else {
                session()->flash('error', 'Conteúdo não encontrado.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao deletar: ' . $e->getMessage());
        }
        
        $this->resetPage();
    }

    public function edit($aulas_id)
    {
        $this->mode = 'form';
        $this->viewing_aula_id = $aulas_id;
        $this->isEditing = true;
        $this->loadExistingConteudo($aulas_id);
    }

    public function view($aulas_id)
    {
        $this->mode = 'form';
        $this->viewing_aula_id = $aulas_id;
        $this->isEditing = false;
        $this->loadExistingConteudo($aulas_id);
        $this->is_data_loaded = true;
    }

    public function create()
    {
        $this->mode = 'form';
        $this->resetForm();
        $this->turma_id = null;
        $this->turma = null;
        $this->data = now()->format('Y-m-d');
        $this->aula_id = null;
        $this->existing_conteudo = null;
        $this->existing_conteudo_id = null;
        $this->is_data_loaded = false;
        $this->error_message = '';
        $this->viewing_aula_id = null;
        $this->total_aulas_dia = 0;
        $this->isEditing = false;
        $this->loadTurmas();
    }

    public function backToList()
    {
        $this->mode = 'list';
        $this->resetForm();
        $this->resetPage();
    }

    public function getConteudosProperty()
    {
        $query = Aula::query()
            ->with(['turma', 'turma.professor', 'turno', 'conteudoMinistrado'])
            ->whereHas('conteudoMinistrado', function($q) {
                $q->whereNotNull('conteudo');
            });

        // Filtro por turma ou professor
        if ($this->search) {
            $query->whereHas('turma', function($q) {
                $q->where('nome', 'like', '%' . $this->search . '%')
                  ->orWhereHas('professor', function($q2) {
                      $q2->where('nome', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Filtro por data
        if ($this->filterData) {
            $query->whereDate('dia', $this->filterData);
        }

        // Filtro por professor logado
        if (Auth::guard('professor')->check()) {
            $professor_id = Auth::guard('professor')->id();
            $query->whereHas('turma', function($q) use ($professor_id) {
                $q->where('professor_id', $professor_id);
            });
        }

        return $query->orderBy('dia', 'desc')
            ->paginate($this->perPage);
    }

    public function render()
    {
        if ($this->mode === 'list') {
            return view('livewire.conteudos.conteudos-manager-list', [
                'conteudos' => $this->conteudos,
            ]);
        }
        
        return view('livewire.conteudos.conteudos-manager-form', [
            'turma' => $this->turma,
            'total_aulas_dia' => $this->total_aulas_dia,
            'error_message' => $this->error_message,
            'is_data_loaded' => $this->is_data_loaded,
            'existing_conteudo' => $this->existing_conteudo,
            'viewing_aula_id' => $this->viewing_aula_id,
        ]);
    }
}