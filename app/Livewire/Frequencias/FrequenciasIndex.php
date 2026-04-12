<?php

namespace App\Livewire\Frequencias;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Frequencia;
use App\Models\Aula;
use App\Models\Turma;
use Illuminate\Support\Facades\Auth;

class FrequenciasIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filterData = '';

    protected $queryString = ['search' => ['except' => ''], 'filterData' => ['except' => '']];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterData()
    {
        $this->resetPage();
    }

    public function render()
    {
        $frequenciasData = collect();

        if (Auth::guard('web')->check()) {
            // Para administradores: todas as turmas
            $turmas = Turma::with(['unidade', 'matriculas'])->get();
        } elseif (Auth::guard('professor')->check()) {
            // Para professores: apenas suas turmas
            $professor = Auth::guard('professor')->user();
            $turmas = $professor->turmas()->with(['unidade', 'matriculas'])->get();
        } else {
            $turmas = collect();
        }

        foreach ($turmas as $turma) {
            // Filtrar aulas pelo dia, se $filterData estiver definido
            $aulasQuery = Aula::where('turma_id', $turma->id)
                ->with(['turno', 'professor', 'letivo', 'calendario'])
                ->whereNull('deleted_at'); // Ignora aulas com soft delete
            if ($this->filterData) {
                $aulasQuery->where('dia', $this->filterData);
            }
            $aulas = $aulasQuery->get();

            foreach ($aulas as $aula) {
                // Calcular número total de alunos matriculados com status ativo
                $totalAlunos = $turma->matriculas()->where('status', 'ativo')->count();

                // Criar objeto para exibição
                $frequenciaData = new \stdClass();
                $frequenciaData->aula_id = $aula->id;
                $frequenciaData->turma = $turma;
                $frequenciaData->dia = $aula->dia;
                $frequenciaData->turno = $aula->turno;
                $frequenciaData->professor = $aula->professor;
                $frequenciaData->total_aulas = $aula->total_aulas;
                $frequenciaData->total_alunos = $totalAlunos;

                $frequenciasData->push($frequenciaData);
            }
        }

        // Aplicar filtro de busca após coletar todos os dados (case-insensitive)
        if ($this->search) {
            $search = strtolower(trim($this->search));
            $frequenciasData = $frequenciasData->filter(function ($item) use ($search) {
                return stripos(strtolower($item->turma->nome), $search) !== false ||
                       ($item->professor && stripos(strtolower($item->professor->nome), $search) !== false);
            });
        }

        // Paginação manual
        $perPage = 50;
        $currentPage = $this->getPage();
        $paginatedData = $frequenciasData->slice(($currentPage - 1) * $perPage, $perPage);
        $frequencias = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedData,
            $frequenciasData->count(),
            $perPage,
            $currentPage,
            ['path' => route(Auth::guard('web')->check() ? 'Listar-Frequencias' : 'Listar-Frequencias-Professor')]
        );

        return view('livewire.frequencias.frequencias-index', compact('frequencias'));
    }

    public function delete($aula_id)
    {
        // Buscar a aula
        $aula = Aula::findOrFail($aula_id);

        // Verificar autorização: administrador ou professor responsável pela aula
        if (Auth::guard('web')->check() || (Auth::guard('professor')->check() && $aula->professor_id == Auth::guard('professor')->user()->id)) {
            // Inativar todas as frequências da aula (soft delete)
            Frequencia::where('aulas_id', $aula->id)->whereNull('deleted_at')->update(['deleted_at' => now()]);
            // Inativar a aula (soft delete)
            $aula->delete();
            // Disparar evento para atualizar a interface
            $this->dispatch('frequencia-deleted');
            session()->flash('success', 'Frequências da turma no dia selecionado foram inativadas com sucesso!');
            $this->resetPage();
        } else {
            session()->flash('error', 'Acesso não autorizado.');
        }
    }
}