<?php
// app/Livewire/Frequencias/FrequenciasCardTurmas.php

namespace App\Livewire\Frequencias;

use Livewire\Component;
use App\Models\Turma;
use App\Models\Calendario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FrequenciasCardTurmas extends Component
{
    public $turmas;
    public $calendarioVisualizacao;

    public function mount()
    {
        // Obtém o calendário de visualização da sessão ou o ativo
        $this->calendarioVisualizacao = $this->getCalendarioVisualizacao();
        
        $this->loadTurmas();
        
        Log::info('FrequenciasCardTurmas - Dados carregados:', [
            'calendario_id' => $this->calendarioVisualizacao?->id,
            'turmas_count' => $this->turmas->count()
        ]);
    }

    /**
     * Obtém o calendário de visualização da sessão ou o ativo
     */
    private function getCalendarioVisualizacao()
    {
        // Primeiro tenta pegar da sessão
        $calendarioVisualizacaoId = session('calendario_visualizacao_id');
        
        // Se não tiver na sessão, pega o calendário ativo
        if (!$calendarioVisualizacaoId) {
            $calendarioAtivo = Calendario::where('ativo', true)->first();
            
            if ($calendarioAtivo) {
                $calendarioVisualizacaoId = $calendarioAtivo->id;
                // Salva na sessão para manter consistência
                session(['calendario_visualizacao_id' => $calendarioVisualizacaoId]);
                session(['calendario_visualizacao_nome' => $calendarioAtivo->nomeCompleto]);
                session()->save();
            }
        }
        
        return $calendarioVisualizacaoId ? Calendario::find($calendarioVisualizacaoId) : null;
    }

    /**
     * Carrega as turmas filtradas pelo calendário de visualização
     */
    public function loadTurmas()
    {
        if (Auth::guard('professor')->check()) {
            $professor = Auth::guard('professor')->user();
            
            // Query base das turmas do professor
            $query = $professor->turmas()->with(['curso', 'turno', 'calendario']);
            
            // Filtra pelo calendário de visualização se existir
            if ($this->calendarioVisualizacao) {
                $query->where('calendario_id', $this->calendarioVisualizacao->id);
            }
            
            // Filtra apenas turmas com matrículas ativas
            $this->turmas = $query->whereHas('matriculas', function ($q) {
                    $q->where('status', 'ativo');
                })
                ->orderBy('nome')
                ->get();
        } else {
            // Para admin (caso necessário)
            $query = Turma::with(['curso', 'turno', 'calendario']);
            
            // Filtra pelo calendário de visualização se existir
            if ($this->calendarioVisualizacao) {
                $query->where('calendario_id', $this->calendarioVisualizacao->id);
            }
            
            $this->turmas = $query->whereHas('matriculas', function ($q) {
                    $q->where('status', 'ativo');
                })
                ->orderBy('nome')
                ->get();
        }
    }

    /**
     * Recarrega os dados quando o calendário muda (se houver um seletor)
     */
    public function updatedCalendarioVisualizacao()
    {
        $this->loadTurmas();
    }

    public function render()
    {
        return view('livewire.frequencias.frequencias-card-turmas');
    }
}