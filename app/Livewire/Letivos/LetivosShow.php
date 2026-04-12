<?php

namespace App\Livewire\Letivos;

use App\Models\Letivo;
use Livewire\Component;

class LetivosShow extends Component
{
    public $letivoId;
    public $letivo;

    public function mount($id)
    {
        $this->letivoId = $id;
        $this->letivo = Letivo::with(['turma.unidade', 'turma.curso', 'turma.nivel', 'turma.turno'])->findOrFail($id);  // Adicionado 'unidade'
    }

    public function render()
    {
        return view('livewire.letivos.letivos-show');
    }
}
