<?php

namespace App\Livewire\Letivos;

use App\Models\Turma;
use App\Models\Letivo;
use App\Models\Calendario;
use Livewire\Component;
use Carbon\Carbon;

class LetivosCreate extends Component
{
    public $turma_id;
    public $turmas;
    public $turma_selecionada_info = '';  // Mudado de semestre_selecionado para info da turma
    public $sections = [];
    public $calendarioId;

    protected $rules = [
        'turma_id' => 'required|exists:turmas,id',
    ];

    // Regras dinâmicas por seção (validadas no save)
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

    // Mensagens dinâmicas por seção
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

    public function mount($turmaId = null)
    {
        // Obtém o calendário da sessão ou ativo
        $this->calendarioId = session('calendario_visualizacao_id');
        if (!$this->calendarioId) {
            $calendarioAtivo = Calendario::where('ativo', true)->first();
            $this->calendarioId = $calendarioAtivo->id ?? null;
        }
        
        // Carrega turmas do calendário selecionado
        $this->loadTurmas();
        
       $this->turma_id = $turmaId ?? '';
        if ($turmaId) {
            $this->updatedTurmaId();  // Pré-preenche info da turma se for passada
        }
        $this->addSection();
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

     public function updatedTurmaId()
    {
        $turma = $this->turmas->find($this->turma_id);
        if ($turma) {
            $this->turma_selecionada_info = $this->formatarInfoTurma($turma);
        } else {
            $this->turma_selecionada_info = '';
        }
    }
      protected function formatarInfoTurma($turma)
    {
        $info = [];
        
        // Nome e letra da turma
        $info[] = "{$turma->nome} ({$turma->letra})";
        
        // Curso
        if ($turma->curso) {
            $info[] = $turma->curso->nome;
        }
        
        // Nível
        if ($turma->nivel) {
            $info[] = $turma->nivel->nome;
        }
        
        // Turno
        if ($turma->turno) {
            $info[] = $turma->turno->nome;
        }
        
        // Unidade (semestre)
        if ($turma->unidade) {
            $info[] = "Unidade: {$turma->unidade->nome}";
        }
        
        // Calendário
        if ($turma->calendario) {
            $info[] = "Calendário: {$turma->calendario->nomeCompleto}";
        }
        
        return implode(' • ', $info);
    }

    // Detecta updates em seções específicas (ex: sections.0.horario_inicio)
    public function updated($propertyName)
    {
        if ($propertyName === 'turma_id') {
            $this->updatedTurmaId();
        }
        if (str_starts_with($propertyName, 'sections.') && preg_match('/sections\.(\d+)\.(horario_inicio|horario_saida)/', $propertyName, $matches)) {
            $index = $matches[1];
            $field = $matches[2];
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
        if (count($this->sections) > 1) {  // Não remove a última
            unset($this->sections[$index]);
            $this->sections = array_values($this->sections);  // Reindexa
        }
    }

    public function calculateHours($index)
    {
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

        $createdCount = 0;
        foreach ($this->sections as $section) {
            if ($section['dia'] && $section['horario_inicio'] && $section['horario_saida']) {
                Letivo::create([
                    'dia' => $section['dia'],
                    'horario_inicio' => $section['horario_inicio'] . ':00',
                    'horario_saida' => $section['horario_saida'] . ':00',
                    'turma_id' => $this->turma_id,
                ]);
                $createdCount++;
            }
        }

        session()->flash('success', "$createdCount aula(s) adicionada(s) com sucesso! Esta turma agora tem " . Letivo::totalAulasPorTurma($this->turma_id) . ' aulas.');

        // Reset: Mantém só uma seção vazia, preserva turma
        $this->sections = [['dia' => '', 'horario_inicio' => '', 'horario_saida' => '', 'quantidade_horas' => '']];
        return redirect()->route('Listar-Letivos');
    }

    public function render()
    {
        // Obtém o calendário atual para mostrar na view
        $calendarioVisualizacao = Calendario::find($this->calendarioId);
        
        return view('livewire.letivos.letivos-create', [
            'calendarioVisualizacao' => $calendarioVisualizacao
        ]);
    }
}