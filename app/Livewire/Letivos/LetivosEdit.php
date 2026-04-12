<?php

namespace App\Livewire\Letivos;

use App\Models\Letivo;
use App\Models\Turma;
use App\Models\Calendario;
use Livewire\Component;
use Carbon\Carbon;

class LetivosEdit extends Component
{
    public $letivoId;
    public $turma_id;
    public $turmas;
    public $semestre_selecionado = '';
    public $sections = [];  // Inicia com seção pré-preenchida do letivo
    public $calendarioId;  // Filtro por calendário

    protected $rules = [
        'turma_id' => 'required|exists:turmas,id',
    ];

    // Regras dinâmicas por seção (igual ao create)
    protected function getValidationRules()
    {
        $rules = [];
        foreach ($this->sections as $index => $section) {
            $rules["sections.{$index}.dia"] = 'required|in:segunda-feira,terça-feira,quarta-feira,quinta-feira,sexta-feira,sábado,domingo';
            $rules["sections.{$index}.horario_inicio"] = 'required|date_format:H:i';
            $rules["sections.{$index}.horario_saida"] = 'required|date_format:H:i|after:sections.{$index}.horario_inicio';
        }
        return array_merge($this->rules, $rules);
    }

    protected $messages = [
        'turma_id.required' => 'O campo turma é obrigatório.',
        'turma_id.exists' => 'A turma selecionada não existe.',
    ];

    // Mensagens dinâmicas por seção (igual ao create)
    protected function getValidationMessages()
    {
        $messages = $this->messages;
        foreach ($this->sections as $index => $section) {
            $num = $index + 1;
            $messages["sections.{$index}.dia.required"] = "O dia da aula {$num} é obrigatório.";
            $messages["sections.{$index}.dia.in"] = "O dia selecionado da aula {$num} é inválido.";
            $messages["sections.{$index}.horario_inicio.required"] = "O horário de início da aula {$num} é obrigatório.";
            $messages["sections.{$index}.horario_inicio.date_format"] = "O horário de início da aula {$num} deve estar no formato HH:MM.";
            $messages["sections.{$index}.horario_saida.required"] = "O horário de saída da aula {$num} é obrigatório.";
            $messages["sections.{$index}.horario_saida.date_format"] = "O horário de saída da aula {$num} deve estar no formato HH:MM.";
            $messages["sections.{$index}.horario_saida.after"] = "O horário de saída da aula {$num} deve ser após o horário de início.";
        }
        return $messages;
    }

    public function mount($id)
    {
        $this->letivoId = $id;
        $letivo = Letivo::with('turma.unidade')->findOrFail($id);
        
        // Obtém o calendário da sessão ou da turma do letivo
        $this->calendarioId = session('calendario_visualizacao_id') ?? $letivo->turma->calendario_id;
        
        // Carrega turmas do calendário selecionado
        $this->loadTurmas();
        
        $this->turma_id = $letivo->turma_id;
        $this->updatedTurmaId();  // Pré-preenche semestre
        
        // Inicializa seção 0 com dados do letivo
        $this->sections = [[
            'dia' => $letivo->dia,
            'horario_inicio' => Carbon::parse($letivo->horario_inicio)->format('H:i'),
            'horario_saida' => Carbon::parse($letivo->horario_saida)->format('H:i'),
            'quantidade_horas' => ''  // Calcula no updated
        ]];
        $this->calculateHours(0);  // Calcula duração inicial
    }

    // Carrega turmas filtradas por calendário
    protected function loadTurmas()
    {
        $query = Turma::with(['unidade', 'curso', 'nivel', 'turno', 'calendario']);
        
        if ($this->calendarioId) {
            $query->where('calendario_id', $this->calendarioId);
        }
        
        $this->turmas = $query->orderBy('nome')->get();
    }

    // Atualiza semestre (igual ao create)
    public function updatedTurmaId()
    {
        $turma = $this->turmas->find($this->turma_id);
        $this->semestre_selecionado = $turma ? ($turma->unidade->nome ?? 'N/A') : '';
    }

    // Updated (igual ao create, com turma)
    public function updated($propertyName)
    {
        if ($propertyName === 'turma_id') {
            $this->updatedTurmaId();
        }
        if (str_starts_with($propertyName, 'sections.') && preg_match('/sections\.(\d+)\.(horario_inicio|horario_saida)/', $propertyName, $matches)) {
            $index = $matches[1];
            if ($this->sections[$index]['horario_inicio'] && $this->sections[$index]['horario_saida']) {
                $this->calculateHours($index);
            }
        }
    }

    public function addSection()
    {
        $this->sections[] = [
            'dia' => '',
            'horario_inicio' => '',
            'horario_saida' => '',
            'quantidade_horas' => ''
        ];
    }

    public function removeSection($index)
    {
        if (count($this->sections) > 1) {
            unset($this->sections[$index]);
            $this->sections = array_values($this->sections);
        }
    }

    public function calculateHours($index)
    {
        // Igual ao create
        $section = &$this->sections[$index];
        if ($section['horario_inicio'] && $section['horario_saida']) {
            try {
                $inicio = Carbon::createFromFormat('H:i', $section['horario_inicio']);
                $saida = Carbon::createFromFormat('H:i', $section['horario_saida']);
                $totalMinutes = $inicio->diffInMinutes($saida);
                $hours = intdiv($totalMinutes, 60);
                $minutes = $totalMinutes % 60;

                if ($hours > 0 && $minutes > 0) {
                    $section['quantidade_horas'] = "$hours hora" . ($hours > 1 ? 's' : '') . " e $minutes minuto" . ($minutes > 1 ? 's' : '');
                } elseif ($hours > 0) {
                    $section['quantidade_horas'] = "$hours hora" . ($hours > 1 ? 's' : '');
                } elseif ($minutes > 0) {
                    $section['quantidade_horas'] = "$minutes minuto" . ($minutes > 1 ? 's' : '');
                } else {
                    $section['quantidade_horas'] = '0 minutos';
                }
            } catch (\Exception $e) {
                $section['quantidade_horas'] = '';
            }
        } else {
            $section['quantidade_horas'] = '';
        }
    }

    public function save()
    {
        $this->validate($this->getValidationRules(), $this->getValidationMessages());

        // Verifica se a turma pertence ao calendário ativo
        $turma = Turma::with('calendario')->findOrFail($this->turma_id);
        $calendarioAtivo = Calendario::where('ativo', true)->first();
        
        if ($turma->calendario_id != $calendarioAtivo->id) {
            session()->flash('error', 'Esta turma não pertence ao calendário ativo.');
            return;
        }

        $updatedCount = 0;
        $letivoOriginal = Letivo::findOrFail($this->letivoId);
        foreach ($this->sections as $index => $section) {
            if ($section['dia'] && $section['horario_inicio'] && $section['horario_saida']) {
                if ($index === 0) {
                    // Atualiza o letivo original
                    $letivoOriginal->update([
                        'dia' => $section['dia'],
                        'horario_inicio' => $section['horario_inicio'] . ':00',
                        'horario_saida' => $section['horario_saida'] . ':00',
                        'turma_id' => $this->turma_id,
                    ]);
                    $updatedCount++;
                } else {
                    // Cria novos letivos para seções extras
                    Letivo::create([
                        'dia' => $section['dia'],
                        'horario_inicio' => $section['horario_inicio'] . ':00',
                        'horario_saida' => $section['horario_saida'] . ':00',
                        'turma_id' => $this->turma_id,
                    ]);
                    $updatedCount++;
                }
            }
        }

        session()->flash('success', "$updatedCount aula(s) atualizada(s)/criada(s) com sucesso!");
        return redirect()->route('Listar-Letivos');
    }

    public function render()
    {
        // Obtém o calendário atual para mostrar na view
        $calendarioVisualizacao = Calendario::find($this->calendarioId);
        
        return view('livewire.letivos.letivos-edit', [
            'calendarioVisualizacao' => $calendarioVisualizacao
        ]);
    }
}