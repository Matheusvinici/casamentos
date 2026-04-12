<?php

namespace App\Livewire\Frequencias;

use Livewire\Component;
use App\Models\Frequencia;
use App\Models\Aula;
use App\Models\Aluno;
use App\Models\Turma;
use App\Models\Turno;
use App\Models\Letivo;
use App\Models\Calendario;
use App\Models\Matricula;
use Illuminate\Support\Facades\Auth;

class FrequenciasEdit extends Component
{
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
    public $alunos;
    public $turmas = [];
    public $turnos = [];
    public $letivos = [];
    public $calendarios = [];
    public $total_aulas_dia = 0;
    public $error_message = '';
    public $is_data_loaded = false;

    public function mount($turma_id = null, $data = null)
    {
        $this->turma_id = $turma_id ?? request()->query('turma_id');
        $this->data = $data ?? request()->query('data');

        if (Auth::guard('professor')->check()) {
            $professor = Auth::guard('professor')->user();
            $this->turmas = $professor->turmas;
        } else {
            $this->turmas = Turma::all();
        }

        if ($this->turma_id) {
            $this->turma = Turma::with('professor')->findOrFail($this->turma_id);
            if (Auth::guard('professor')->check() && $this->turma->professor_id != Auth::guard('professor')->id()) {
                session()->flash('error', 'Acesso não autorizado.');
                $this->redirectRoute('Listar-Frequencias-Professor');
                return;
            }
            $this->alunos = Aluno::whereIn('id', Matricula::where('turma_id', $this->turma_id)
                ->where('status', 'ativo')->pluck('aluno_id'))->get();
            $this->turno_id = $this->turma->turno_id;
        } else {
            $this->alunos = collect([]);
            $this->error_message = 'Nenhuma turma selecionada.';
        }

        $this->turnos = Turno::all();
        $this->letivos = Letivo::all();
        $this->calendarios = Calendario::all();
        $calendarioAtivo = Calendario::where('ativo', 1)->orderBy('ano', 'desc')->first();
        $this->calendario_id = $calendarioAtivo ? $calendarioAtivo->id : null;

        if ($this->turma_id && $this->data) {
            $this->loadData();
        } else {
            $this->error_message = 'Selecione uma turma e uma data.';
        }
    }

    public function updatedTurmaId($value)
    {
        $this->alunos = $value ? Aluno::whereIn('id', Matricula::where('turma_id', $value)->where('status', 'ativo')->pluck('aluno_id'))->get() : collect([]);
        $this->resetArrays();
        $this->is_data_loaded = false;
        $this->total_aulas_dia = 0;
        $this->error_message = 'Selecione uma data e clique em Carregar.';
        $this->turma = $value ? Turma::find($value) : null;
        $this->turno_id = $this->turma ? $this->turma->turno_id : null;
    }

    public function updatedData()
    {
        $this->is_data_loaded = false;
        $this->total_aulas_dia = 0;
        $this->error_message = 'Clique em Carregar para atualizar os dados.';
        $this->resetArrays();
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
        if ($this->turma_id && $this->data) {
            $dia_semana = \Carbon\Carbon::parse($this->data)->locale('pt_BR')->isoFormat('dddd');
            // Verificar aula ativa ou inativada
            $aula = Aula::withTrashed()->where('turma_id', $this->turma_id)->where('dia', $this->data)->first();

            if ($aula) {
                if ($aula->trashed()) {
                    // Restaura a aula inativada
                    $aula->restore();
                    // Restaura as frequências associadas
                    Frequencia::withTrashed()->where('aulas_id', $aula->id)->restore();
                }
                $this->total_aulas_dia = $aula->total_aulas;
                $this->letivo_id = $aula->letivo_id;
                $this->turno_id = $aula->turno_id;

                $frequencias = Frequencia::where('aulas_id', $aula->id)->get();
                foreach ($this->alunos as $aluno) {
                    $frequencia = $frequencias->where('aluno_id', $aluno->id)->first();
                    $this->presences[$aluno->id] = $frequencia ? ($frequencia->aulas_ausentes > 0 ? 0 : 1) : 1;
                    $this->aulas_ausentes[$aluno->id] = $frequencia ? $frequencia->aulas_ausentes : 0;
                    $this->observacoes[$aluno->id] = $frequencia ? $frequencia->observacao : '';
                    $this->justificativas[$aluno->id] = $frequencia ? $frequencia->justificativa : '';
                }
                $this->is_data_loaded = true;
                return;
            }

            $letivo = Letivo::where('turma_id', $this->turma_id)
                ->where('dia', $dia_semana)
                ->first();

            if ($letivo) {
                $this->total_aulas_dia = Letivo::where('turma_id', $this->turma_id)
                    ->where('dia', $dia_semana)
                    ->count();
                $this->letivo_id = $letivo->id;
            } else {
                $this->total_aulas_dia = 0;
                $this->error_message = 'Nenhum registro letivo encontrado para a turma e o dia selecionado.';
                $this->is_data_loaded = false;
                return;
            }

            $this->resetArrays();

            foreach ($this->alunos as $aluno) {
                $this->presences[$aluno->id] = 1;
                $this->aulas_ausentes[$aluno->id] = 0;
                $this->observacoes[$aluno->id] = '';
                $this->justificativas[$aluno->id] = '';
            }

            $this->is_data_loaded = true;
        } else {
            $this->error_message = 'Selecione uma turma e uma data.';
        }
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
        session()->flash('error', $this->error_message);
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
            'turno_id' => 'nullable|exists:turnos,id',
            'letivo_id' => 'nullable|exists:letivos,id',
            'calendario_id' => 'required|exists:calendarios,id',
            'presences' => 'required|array',
            'presences.*' => 'boolean',
            'aulas_ausentes' => 'array',
            'aulas_ausentes.*' => 'integer|min:0|max:' . $this->total_aulas_dia,
            'aulas_ausentes.*' => function ($attribute, $value, $fail) {
                $aluno_id = explode('.', $attribute)[1];
                if ($this->presences[$aluno_id] == 0 && $value < 1) {
                    $fail('O número de aulas ausentes deve ser pelo menos 1 para alunos marcados como ausentes.');
                }
            },
            'observacoes' => 'array',
            'observacoes.*' => 'nullable|string',
            'justificativas' => 'array',
            'justificativas.*' => 'nullable|in:Justificativa por escrito,Justificativa verbal,Não apresentou justificativa',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        throw $e;
    }

    // Encontrar ou criar a aula
    $aula = Aula::withTrashed()->updateOrCreate(
        [
            'dia' => $this->data,
            'turma_id' => $this->turma_id,
        ],
        [
            'total_aulas' => $this->total_aulas_dia,
            'turno_id' => $this->turno_id,
            // 'professor_id' => Auth::guard('professor')->check() ? Auth::guard('professor')->id() : null,
            'professor_id' => $this->turma->professor_id,
            'letivo_id' => $this->letivo_id,
            'calendario_id' => $this->calendario_id,
            'deleted_at' => null, // Garante que a aula seja restaurada se estava inativada
        ]
    );

    $updated = false;
    foreach ($this->alunos as $aluno) {
        $aulas_ausentes_aluno = $this->aulas_ausentes[$aluno->id] ?? 0;

        $matricula = Matricula::where('aluno_id', $aluno->id)
            ->where('turma_id', $this->turma_id)
            ->where('status', 'ativo')
            ->first();

        if (!$matricula && $aulas_ausentes_aluno > 0) {
            session()->flash('error', "Nenhuma matrícula ativa encontrada para o aluno {$aluno->nome}.");
            continue;
        }

        // Verificar se existe uma frequência inativada ou ativa
        $existingFrequencia = Frequencia::withTrashed()
            ->where('aulas_id', $aula->id)
            ->where('aluno_id', $aluno->id)
            ->first();

        // Se o aluno está presente (aulas_ausentes == 0), remover o registro de frequência, se existir
        if ($aulas_ausentes_aluno == 0) {
            if ($existingFrequencia) {
                $existingFrequencia->delete(); // Soft delete do registro de frequência
                $updated = true;
            }
            continue; // Não criar registro para alunos presentes
        }

        // Criar ou atualizar frequência apenas para alunos com faltas (aulas_ausentes > 0)
        if ($existingFrequencia) {
            $existingFrequencia->restore(); // Restaurar se estava inativada
            $existingFrequencia->update([
                'matricula_id' => $matricula->id,
                'letivo_id' => $this->letivo_id,
                'calendario_id' => $this->calendario_id,
                'aulas_ausentes' => $aulas_ausentes_aluno,
                'justificativa' => $this->justificativas[$aluno->id] ?? $existingFrequencia->justificativa,
                'observacao' => $this->observacoes[$aluno->id] ?? $existingFrequencia->observacao,
            ]);
        } else {
            $newFrequencia = Frequencia::create([
                'aulas_id' => $aula->id,
                'aluno_id' => $aluno->id,
                'matricula_id' => $matricula->id,
                'letivo_id' => $this->letivo_id,
                'calendario_id' => $this->calendario_id,
                'aulas_ausentes' => $aulas_ausentes_aluno,
                'justificativa' => $this->justificativas[$aluno->id] ?? '',
                'observacao' => $this->observacoes[$aluno->id] ?? '',
            ]);
        }
        $updated = true;
    }

    session()->flash('success', $updated ? 'Frequências atualizadas com sucesso!' : 'Nenhuma ausência registrada. Frequências atualizadas com sucesso!');
    $this->redirectRoute(Auth::guard('web')->check() ? 'Listar-Frequencias' : 'Listar-Frequencias-Professor');
}

    public function render()
    {
        return view('livewire.frequencias.frequencias-edit', [
            'turma' => $this->turma,
            'alunos' => $this->alunos,
            'turnos' => $this->turnos,
            'letivos' => $this->letivos,
            'calendarios' => $this->calendarios,
            'total_aulas_dia' => $this->total_aulas_dia,
            'error_message' => $this->error_message,
            'is_data_loaded' => $this->is_data_loaded,
        ]);
    }
}