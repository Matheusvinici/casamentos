<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Bairro;
use App\Models\Escola;
use App\Models\Cidade;
use App\Models\Pais;

class SearchSelect extends Component
{
    public $search = '';
    public $selectedId = null;
    public $type; // 'bairro', 'escola', 'cidade' ou 'pais'
    public $results = [];
    public $placeholder = 'Digite para buscar...';

    public function mount($type, $selectedId = null)
    {
        $this->type = $type;
        $this->selectedId = $selectedId;

        if ($this->type == 'pais') {
            $this->placeholder = 'Digite o país...';
        }

        if ($this->selectedId) {
            $this->setSelectedName();
        }
    }

    public function updatedSearch()
    {
        if (strlen($this->search) < 2) {
            $this->results = [];
            return;
        }

        $query = null;

        switch ($this->type) {
            case 'bairro':
                $query = Bairro::query();
                break;
            case 'escola':
                $query = Escola::query();
                break;
            case 'cidade':
                $query = Cidade::query();
                break;
            case 'pais':
                $query = Pais::query();
                break;
        }

        if ($query) {
            $this->results = $query->where('nome', 'like', '%' . $this->search . '%')
                ->take(10)
                ->get()
                ->toArray();
        }
    }

   public function selectItem($id)
{
    $this->selectedId = $id;
    $this->setSelectedName();
    $this->results = [];
    
    // Emitir evento para o componente pai
    $this->dispatch('updateSelected', $this->type, $id);
}

    private function setSelectedName()
    {
        $item = null;

        switch ($this->type) {
            case 'bairro':
                $item = Bairro::find($this->selectedId);
                break;
            case 'escola':
                $item = Escola::find($this->selectedId);
                break;
            case 'cidade':
                $item = Cidade::find($this->selectedId);
                break;
            case 'pais':
                $item = Pais::find($this->selectedId);
                break;
        }

        $this->search = $item ? $item->nome : '';
    }

    public function render()
    {
        return view('livewire.search-select');
    }
}