<?php

namespace App\Livewire\Frequencias;

use Livewire\Component;
use App\Models\Frequencia;
use App\Models\Aula;
use App\Models\Aluno;
use App\Models\Turma;
use App\Models\Matricula;
use App\Models\Turno;
use App\Models\Letivo;
use App\Models\Calendario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class FrequenciasCreate extends Component
{
    public $turmas = [];
    public $alunos;
    public $turma_id;
    public $turma;
    public $data;
    public $turno_id;
    public $letivo_id;
    public $calendario_id;
    public $presences = [];
    public $aulas_ausentes = [];
    public $observacoes = [];
    public $justificativas = [];
    public $turnos = [];
    public $letivos = [];
    public $calendarios = [];
    public $total_aulas_dia = 0;
    public $error_message = '';
    public $is_data_loaded = false;
    public $existing_aula_id = null; // Guarda o ID da aula existente

    public function mount($turma_id = null)
    {
        $this->alunos = collect([]);
        $this->turma_id = $turma_id ?? request()->query('turma_id');
        $this->data = now()->format('Y-m-d');

        if (Auth::guard('professor')->check()) {
            $professor = Auth::guard('professor')->user();
            $this->turmas = $professor->turmas;
            if ($this->turma_id) {
                $this->turma = Turma::with('professor')->findOrFail($this->turma_id);
                $this->updatedTurmaId($this->turma_id);
            }
        } else {
            $this->turmas = Turma::all();
            if ($this->turma_id) {
                $this->turma = Turma::with('professor')->findOrFail($this->turma_id);
                $this->updatedTurmaId($this->turma_id);
            }
        }

        $this->turnos = Turno::all();
        $this->letivos = Letivo::all();
        $this->calendarios = Calendario::all();
        $calendarioAtivo = Calendario::where('ativo', 1)->orderBy('ano', 'desc')->first();
        $this->calendario_id = $calendarioAtivo ? $calendarioAtivo->id : null;
    }

    public function updatedTurmaId($value)
    {
        $this->alunos = $value ? Aluno::whereIn('id', Matricula::where('turma_id', $value)->where('status', 'ativo')->pluck('aluno_id'))->with('fotos')->get() : collect([]);
        $this->resetArrays();
        $this->is_data_loaded = false;
        $this->total_aulas_dia = 0;
        $this->error_message = 'Selecione uma data e clique em Carregar.';
        $this->turma = $value ? Turma::find($value) : null;
        $this->turno_id = $this->turma ? $this->turma->turno_id : null;
        $this->existing_aula_id = null;
    }

    public function updatedData()
    {
        $this->is_data_loaded = false;
        $this->total_aulas_dia = 0;
        $this->error_message = 'Clique em Carregar para atualizar os dados.';
        $this->resetArrays();
        $this->existing_aula_id = null;
    }

    public function updatedPresences($value, $aluno_id)
    {
        if ($value == 1) {
            $this->aulas_ausentes[$aluno_id] = 0;
        } elseif ($value == 0 && $this->total_aulas_dia > 0) {
            $this->aulas_ausentes[$aluno_id] = $this->aulas_ausentes[$aluno_id] ?: $this->total_aulas_dia;
        }
        $this->dispatch('presence-updated', aluno_id: $aluno_id);
    }

    public function updatedAulasAusentes($value, $aluno_id)
    {
        $this->presences[$aluno_id] = ($value > 0) ? 0 : 1;
        $this->dispatch('presence-updated', aluno_id: $aluno_id);
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
        $existingAula = Aula::withTrashed()->where('turma_id', $this->turma_id)
            ->where('dia', $this->data)
            ->first();

        if ($existingAula) {
            // Aula existe - carregar dados existentes
            if ($existingAula->trashed()) {
                // Se estava inativada, restaura
                $existingAula->restore();
                Frequencia::withTrashed()->where('aulas_id', $existingAula->id)->restore();
            }
            
            $this->existing_aula_id = $existingAula->id;
            $this->total_aulas_dia = $existingAula->total_aulas;
            $this->letivo_id = $existingAula->letivo_id;
            $this->turno_id = $existingAula->turno_id;
            
            // Carrega os dados das frequências existentes
            $frequencias = Frequencia::where('aulas_id', $existingAula->id)->get();
            
            if ($frequencias->isNotEmpty()) {
                // Se há frequências, carrega para edição
                foreach ($frequencias as $frequencia) {
                    $this->presences[$frequencia->aluno_id] = $frequencia->aulas_ausentes > 0 ? 0 : 1;
                    $this->aulas_ausentes[$frequencia->aluno_id] = $frequencia->aulas_ausentes;
                    $this->justificativas[$frequencia->aluno_id] = $frequencia->justificativa;
                    $this->observacoes[$frequencia->aluno_id] = $frequencia->observacao;
                }
                
                // Para os alunos sem frequência (presentes), definir como presentes
                foreach ($this->alunos as $aluno) {
                    if (!isset($this->aulas_ausentes[$aluno->id])) {
                        $this->presences[$aluno->id] = 1;
                        $this->aulas_ausentes[$aluno->id] = 0;
                        $this->observacoes[$aluno->id] = '';
                        $this->justificativas[$aluno->id] = '';
                    }
                }
                
                session()->flash('info', 'Carregando frequência existente para edição.');
            } else {
                // Aula existe mas não tem frequências - criar novos registros
                $this->resetArrays();
                foreach ($this->alunos as $aluno) {
                    $this->presences[$aluno->id] = 1;
                    $this->aulas_ausentes[$aluno->id] = 0;
                    $this->observacoes[$aluno->id] = '';
                    $this->justificativas[$aluno->id] = '';
                }
            }
            
            $this->is_data_loaded = true;
            return;
        }

        // Aula não existe - criar nova
        $dia_semana = \Carbon\Carbon::parse($this->data)->locale('pt_BR')->isoFormat('dddd');
        $letivo = Letivo::where('turma_id', $this->turma_id)
            ->where('dia', $dia_semana)
            ->first();

        if (!$letivo) {
            $this->error_message = 'Nenhum registro letivo encontrado para a turma e o dia selecionado.';
            $this->is_data_loaded = false;
            $this->total_aulas_dia = 0;
            return;
        }

        $this->total_aulas_dia = Letivo::where('turma_id', $this->turma_id)
            ->where('dia', $dia_semana)
            ->count();
        $this->letivo_id = $letivo->id;
        $this->turma = Turma::find($this->turma_id);
        $this->turno_id = $this->turma ? $this->turma->turno_id : null;

        $this->resetArrays();

        foreach ($this->alunos as $aluno) {
            $this->presences[$aluno->id] = 1;
            $this->aulas_ausentes[$aluno->id] = 0;
            $this->observacoes[$aluno->id] = '';
            $this->justificativas[$aluno->id] = '';
        }

        $this->is_data_loaded = true;
    }

    public function resetArrays()
    {
        $this->presences = [];
        $this->aulas_ausentes = [];
        $this->observacoes = [];
        $this->justificativas = [];
    }

    public function save()
    {
        if ($this->total_aulas_dia == 0) {
            session()->flash('error', 'O total de aulas no dia deve ser maior que zero.');
            return;
        }

        // Garante que $this->turma está carregado
        if (!$this->turma) {
            $this->turma = Turma::findOrFail($this->turma_id);
        }

        try {
            $this->validate([
                'turma_id' => 'required|exists:turmas,id',
                'data' => 'required|date',
                'turno_id' => 'required|exists:turnos,id',
                'letivo_id' => 'required|exists:letivos,id',
                'calendario_id' => 'required|exists:calendarios,id',
                'presences' => 'required|array',
                'presences.*' => 'boolean',
                'aulas_ausentes' => 'array',
                'aulas_ausentes.*' => 'integer|min:0|max:' . $this->total_aulas_dia,
                'observacoes' => 'array',
                'justificativas' => 'array',
                'justificativas.*' => 'nullable|in:Justificativa por escrito,Justificativa verbal,Não apresentou justificativa',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        }

        // Verificar se existe uma aula (ativa ou inativada) para a turma e data
        $existingAula = Aula::withTrashed()->where('turma_id', $this->turma_id)
            ->where('dia', $this->data)
            ->first();

        if ($existingAula) {
            if ($existingAula->trashed()) {
                $existingAula->restore();
            }
            // Atualizar a aula existente
            $existingAula->update([
                'total_aulas' => $this->total_aulas_dia,
                'turno_id' => $this->turno_id,
                'professor_id' => $this->turma->professor_id,
                'letivo_id' => $this->letivo_id,
                'calendario_id' => $this->calendario_id,
            ]);
            $aula = $existingAula;
        } else {
            // Criar uma nova aula se não existir
            $aula = Aula::create([
                'dia' => $this->data,
                'total_aulas' => $this->total_aulas_dia,
                'turma_id' => $this->turma_id,
                'turno_id' => $this->turno_id,
                'professor_id' => $this->turma->professor_id,
                'letivo_id' => $this->letivo_id,
                'calendario_id' => $this->calendario_id,
            ]);
        }

        $created = false;
        foreach ($this->alunos as $aluno) {
            $aulas_ausentes_aluno = $this->aulas_ausentes[$aluno->id] ?? 0;

            // Verificar matrícula ativa
            $matricula = Matricula::where('aluno_id', $aluno->id)
                ->where('turma_id', $this->turma_id)
                ->where('status', 'ativo')
                ->first();

            if (!$matricula && $aulas_ausentes_aluno > 0) {
                continue;
            }

            // Verificar se existe uma frequência existente
            $existingFrequencia = Frequencia::withTrashed()
                ->where('aulas_id', $aula->id)
                ->where('aluno_id', $aluno->id)
                ->first();

            // Se o aluno está presente (aulas_ausentes == 0)
            if ($aulas_ausentes_aluno == 0) {
                if ($existingFrequencia) {
                    $existingFrequencia->forceDelete(); // Remove permanentemente
                }
                continue;
            }

            // Aluno com falta - criar ou atualizar
            if ($existingFrequencia) {
                if ($existingFrequencia->trashed()) {
                    $existingFrequencia->restore();
                }
                $existingFrequencia->update([
                    'matricula_id' => $matricula->id,
                    'letivo_id' => $this->letivo_id,
                    'calendario_id' => $this->calendario_id,
                    'aulas_ausentes' => $aulas_ausentes_aluno,
                    'justificativa' => $this->justificativas[$aluno->id] ?? null,
                    'observacao' => $this->observacoes[$aluno->id] ?? null,
                ]);
            } else {
                Frequencia::create([
                    'aulas_id' => $aula->id,
                    'aluno_id' => $aluno->id,
                    'matricula_id' => $matricula->id,
                    'letivo_id' => $this->letivo_id,
                    'calendario_id' => $this->calendario_id,
                    'aulas_ausentes' => $aulas_ausentes_aluno,
                    'justificativa' => $this->justificativas[$aluno->id] ?? null,
                    'observacao' => $this->observacoes[$aluno->id] ?? null,
                ]);
            }
            $created = true;
        }

        session()->flash('success', 'Frequências registradas com sucesso!');
        
        // Redirecionar para a listagem
        return redirect()->route(Auth::guard('web')->check() ? 'Listar-Frequencias' : 'Listar-Frequencias-Professor');
    }

    public function render()
    {
        return view('livewire.frequencias.frequencias-create', [
            'turma' => $this->turma,
            'turnos' => $this->turnos,
            'letivos' => $this->letivos,
            'calendarios' => $this->calendarios,
            'total_aulas_dia' => $this->total_aulas_dia,
            'error_message' => $this->error_message,
            'is_data_loaded' => $this->is_data_loaded,
        ]);
    }
}