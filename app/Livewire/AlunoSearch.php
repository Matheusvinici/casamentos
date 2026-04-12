<?php

namespace App\Livewire;

use App\Models\Aluno;
use Livewire\Component;
use Illuminate\Support\Facades\Schema;

class AlunoSearch extends Component
{
    public $search = '';
    public $selectedAluno = null;
    public $selectedAlunoId = null;
    public $showDropdown = false;
    
    public function mount($selectedAlunoId = null, $selectedAluno = null)
    {
        if ($selectedAlunoId && $selectedAluno) {
            $this->selectedAlunoId = $selectedAlunoId;
            $this->selectedAluno = $selectedAluno;
            $this->search = $selectedAluno;
        }
    }
    
    protected $listeners = ['clearSelection' => 'clearSelection'];
    
    public function updatedSearch()
    {
        $this->showDropdown = strlen($this->search) > 0;
        $this->selectedAluno = null;
        $this->selectedAlunoId = null;
        
        $this->dispatch('aluno-selecionado', alunoId: null);
    }
    
    public function selectAluno($alunoId, $alunoNome)
    {
        $this->selectedAlunoId = $alunoId;
        $this->selectedAluno = $alunoNome;
        $this->search = $alunoNome;
        $this->showDropdown = false;
        
        $this->dispatch('aluno-selecionado', alunoId: $alunoId);
    }
    
    public function clearSelection()
    {
        $this->search = '';
        $this->selectedAluno = null;
        $this->selectedAlunoId = null;
        $this->showDropdown = false;
        $this->dispatch('aluno-selecionado', alunoId: null);
    }
    
    public function getAlunosProperty()
    {
        if (empty($this->search)) {
            return collect();
        }
        
        // Busca pelos campos existentes na tabela alunos
        return Aluno::where('nome', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orWhere('telefone', 'like', '%' . $this->search . '%')
            ->orWhere('aluno_cpf', 'like', '%' . $this->search . '%') // CPF do aluno
            ->orWhere('responsavel_nome', 'like', '%' . $this->search . '%')
            ->orWhere('responsavel_cpf', 'like', '%' . $this->search . '%')
            ->orWhere('responsavel_email', 'like', '%' . $this->search . '%')
            ->limit(10)
            ->get();
    }
    
    public function render()
    {
        return view('livewire.aluno-search', [
            'alunos' => $this->alunos
        ]);
    }
}