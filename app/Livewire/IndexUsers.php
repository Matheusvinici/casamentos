<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class IndexUsers extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $data = User::when($this->search, function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%');
        })->whereNotIn('id', [1])->paginate(10);

        return view('livewire.index-users', compact('data'));
    }
}