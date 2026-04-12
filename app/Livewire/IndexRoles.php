<?php

namespace App\Livewire;

use Spatie\Permission\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;

class IndexRoles extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $data = Role::when($this->search, function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%');
        })->paginate(10);

        return view('livewire.index-roles', compact('data'));
    }
}