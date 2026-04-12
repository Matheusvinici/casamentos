<?php

namespace App\Http\Controllers;

use App\Models\Aula;
use App\Models\Aluno;
use App\Models\Frequencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrequenciaController extends Controller
{
    public function index()
    {
        return view('frequencias.index');
    }

    public function create(Request $request)
    {
        // Obtém o turma_id da query string
        $turma_id = $request->query('turma_id');

        // Valida se o turma_id foi fornecido
        if (!$turma_id) {
            abort(400, 'O parâmetro turma_id é obrigatório.');
        }

        // Passa o turma_id para a view
        return view('frequencias.create', [
            'turma_id' => $turma_id,
            'frequencia' => null,
        ]);
    }

    public function store(Request $request)
    {
        // A lógica de armazenamento é tratada pelo componente Livewire FrequenciasCreate
        return redirect()->route('frequencias.index');
    }

    public function show($aulas_id, $aluno_id)
    {
        $aula = Aula::with('turma.unidade', 'turma.curso', 'turma.nivel', 'turma.turno', 'turma.professor')->findOrFail($aulas_id);
        if (Auth::guard('professor')->check() && $aula->professor_id != Auth::guard('professor')->id()) {
            abort(403, 'Acesso não autorizado.');
        }

        $frequencia = Frequencia::where('aulas_id', $aulas_id)
            ->where('aluno_id', $aluno_id)
            ->first();

        $aluno = Aluno::findOrFail($aluno_id);
        $turma = $aula->turma;

        return view('frequencias.show', [
            'frequencia' => $frequencia,
            'aula' => $aula,
            'aluno' => $aluno,
            'turma' => $turma,
        ]);
    }

    public function edit(Request $request, $aulas_id, $aluno_id)
    {
        // Busca a aula correspondente ao aulas_id
        $aula = Aula::findOrFail($aulas_id);

        // Verifica se o professor autenticado tem permissão para editar
        if (Auth::guard('professor')->check() && $aula->professor_id != Auth::guard('professor')->id()) {
            abort(403, 'Acesso não autorizado.');
        }

        return view('frequencias.edit', [
            'aulas_id' => $aulas_id,
            'aluno_id' => $aluno_id,
            'turma_id' => $aula->turma_id,
            'data' => $aula->dia->format('Y-m-d'),
        ]);
    }

    public function update(Request $request, Frequencia $frequencia)
    {
        // A lógica de atualização é tratada pelo componente Livewire FrequenciasEdit
        return redirect()->route('frequencias.index');
    }

    public function destroy(Frequencia $frequencia)
    {
        $frequencia->delete();
        return redirect()->route('frequencias.index')->with('success', 'Frequência deletada com sucesso.');
    }

    public function turmas()
    {
        return view('frequencias.cardturmas');
    }
}