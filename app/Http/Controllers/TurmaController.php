<?php

namespace App\Http\Controllers;

use App\Models\Turma;
use App\Models\Unidade;
use App\Models\Curso;
use App\Models\Categoria;
use App\Models\Nivel;
use App\Models\Turno;
use App\Models\Professor;
use App\Models\Calendario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TurmaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search_turma');
        
        // Obtém o calendário para visualização (da sessão)
        $calendarioVisualizacaoId = session('calendario_visualizacao_id');
        
        // SE NÃO TEM NA SESSÃO, USA O ATIVO
        if (!$calendarioVisualizacaoId) {
            $calendarioAtivo = Calendario::where('ativo', true)->first();
            
            if ($calendarioAtivo) {
                $calendarioVisualizacaoId = $calendarioAtivo->id;
                session(['calendario_visualizacao_id' => $calendarioVisualizacaoId]);
                session(['calendario_visualizacao_nome' => $calendarioAtivo->nomeCompleto]);
                session()->save();
            }
        }
        
       
        
        // Obtém as turmas com filtro por calendário
        $turmas = Turma::with(['unidade', 'curso', 'categoria', 'nivel', 'turno', 'professor', 'calendario'])
            ->when($calendarioVisualizacaoId, function ($query, $calendarioId) {
                return $query->where('calendario_id', $calendarioId);
            })
            ->when($search, function ($query, $search) {
                return $query->where('nome', 'like', "%{$search}%")
                    ->orWhereHas('curso', function ($q) use ($search) {
                        $q->where('nome', 'like', "%{$search}%");
                    })
                    ->orWhereHas('professor', function ($q) use ($search) {
                        $q->where('nome', 'like', "%{$search}%");
                    });
            })
            ->orderBy('nome')
            ->paginate(10)
            ->appends(['search_turma' => $search]);

        // Obtém o objeto do calendário sendo visualizado
        $calendarioVisualizacao = Calendario::find($calendarioVisualizacaoId);
        
        return view('turmas.index', compact('turmas', 'calendarioVisualizacao'));
    }

    public function search(Request $request)
    {
        $search = $request->input('search_turma');
        $calendarioVisualizacaoId = session('calendario_visualizacao_id');
        
        $turmas = Turma::with(['unidade', 'curso', 'categoria', 'nivel', 'turno', 'professor', 'calendario'])
            ->when($calendarioVisualizacaoId, function ($query, $calendarioId) {
                return $query->where('calendario_id', $calendarioId);
            })
            ->when($search, function ($query, $search) {
                return $query->where('nome', 'like', "%{$search}%");
            })
            ->paginate(10);

        $calendarioVisualizacao = Calendario::find($calendarioVisualizacaoId);
        
        return view('turmas.index', compact('turmas', 'calendarioVisualizacao'));
    }

    public function create()
    {
        // Obtém o calendário ativo para criação
        $calendarioAtivo = Calendario::where('ativo', true)->first();
        
        if (!$calendarioAtivo) {
            return redirect()->route('Listar-Turmas')
                ->with('error', 'Nenhum calendário ativo encontrado. Ative um calendário antes de criar turmas.');
        }
        
        // Filtra unidades pelo calendário ativo
        $unidades = Unidade::where('calendario_id', $calendarioAtivo->id)->get();
        $cursos = Curso::all();
        $categorias = Categoria::all();
        $niveis = Nivel::all();
        $turnos = Turno::all();
        $professores = Professor::all();
        
        return view('turmas.create-edit-show', compact('unidades', 'cursos', 'categorias', 'niveis', 'turnos', 'professores', 'calendarioAtivo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'letra' => 'required|string|size:1',
            'capacidade' => 'required|integer|min:1',
            'vaga' => 'required|integer|min:0|lte:capacidade',
            'unidade_id' => 'required|exists:unidades,id',
            'curso_id' => 'required|exists:cursos,id',
            'categoria_id' => 'required|exists:categorias,id',
            'nivel_id' => 'required|exists:nivels,id',
            'turno_id' => 'required|exists:turnos,id',
            'professor_id' => 'required|exists:professores,id',
        ], [
            'nome.required' => 'O campo nome é obrigatório.',
            'letra.required' => 'O campo letra é obrigatório.',
            'letra.size' => 'A letra deve ser um único caractere.',
            'capacidade.required' => 'O campo capacidade é obrigatório.',
            'capacidade.min' => 'A capacidade deve ser pelo menos 1.',
            'vaga.required' => 'O campo vagas é obrigatório.',
            'vaga.min' => 'O número de vagas não pode ser negativo.',
            'vaga.lte' => 'O número de vagas não pode exceder a capacidade.',
            'unidade_id.required' => 'O campo unidade é obrigatório.',
            'curso_id.required' => 'O campo curso é obrigatório.',
            'categoria_id.required' => 'O campo categoria é obrigatório.',
            'nivel_id.required' => 'O campo nível é obrigatório.',
            'turno_id.required' => 'O campo turno é obrigatório.',
            'professor_id.required' => 'O campo professor é obrigatório.',
        ]);

        // Busca a unidade selecionada
        $unidade = Unidade::findOrFail($request->unidade_id);
        $calendarioAtivo = Calendario::where('ativo', true)->first();

        if (!$calendarioAtivo) {
            return redirect()->back()
                ->withErrors(['calendario' => 'Nenhum calendário ativo encontrado.'])
                ->withInput();
        }

        // Verifica se a unidade pertence ao calendário ativo
        if ($unidade->calendario_id != $calendarioAtivo->id) {
            return redirect()->back()
                ->withErrors(['unidade_id' => 'Esta unidade não pertence ao calendário ativo.'])
                ->withInput();
        }

        $data = $request->all();
        $data['calendario_id'] = $unidade->calendario_id;

        Turma::create($data);
        return redirect()->route('Listar-Turmas')->with('success', 'Turma cadastrada com sucesso!');
    }

    public function show($id)
    {
        $turma = Turma::findOrFail($id);
        
        // Obtém o calendário ativo
        $calendarioAtivo = Calendario::where('ativo', true)->first();
        
        // Mostra unidades do mesmo calendário da turma
        $unidades = Unidade::where('calendario_id', $turma->calendario_id)->get();
        $cursos = Curso::all();
        $categorias = Categoria::all();
        $niveis = Nivel::all();
        $turnos = Turno::all();
        $professores = Professor::all();
        
        return view('turmas.create-edit-show', compact('turma', 'unidades', 'cursos', 'categorias', 'niveis', 'turnos', 'professores', 'calendarioAtivo'));
    }

    public function edit($id)
    {
        $turma = Turma::findOrFail($id);
        
        // Obtém o calendário ativo
        $calendarioAtivo = Calendario::where('ativo', true)->first();
        
        // Verifica se a turma pertence ao calendário ativo
        if ($turma->calendario_id != $calendarioAtivo->id) {
            return redirect()->route('Listar-Turmas')
                ->with('error', 'Esta turma pertence a um calendário não ativo e não pode ser editada.');
        }
        
        // Mostra apenas unidades do mesmo calendário da turma (ativo)
        $unidades = Unidade::where('calendario_id', $calendarioAtivo->id)->get();
        $cursos = Curso::all();
        $categorias = Categoria::all();
        $niveis = Nivel::all();
        $turnos = Turno::all();
        $professores = Professor::all();
        
        return view('turmas.create-edit-show', compact('turma', 'unidades', 'cursos', 'categorias', 'niveis', 'turnos', 'professores', 'calendarioAtivo'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'letra' => 'required|string|size:1',
            'capacidade' => 'required|integer|min:1',
            'vaga' => 'required|integer|min:0|lte:capacidade',
            'unidade_id' => 'required|exists:unidades,id',
            'curso_id' => 'required|exists:cursos,id',
            'categoria_id' => 'required|exists:categorias,id',
            'nivel_id' => 'required|exists:nivels,id',
            'turno_id' => 'required|exists:turnos,id',
            'professor_id' => 'required|exists:professores,id',
        ], [
            'nome.required' => 'O campo nome é obrigatório.',
            'letra.required' => 'O campo letra é obrigatório.',
            'letra.size' => 'A letra deve ser um único caractere.',
            'capacidade.required' => 'O campo capacidade é obrigatório.',
            'capacidade.min' => 'A capacidade deve ser pelo menos 1.',
            'vaga.required' => 'O campo vagas é obrigatório.',
            'vaga.min' => 'O número de vagas não pode ser negativo.',
            'vaga.lte' => 'O número de vagas não pode exceder a capacidade.',
            'unidade_id.required' => 'O campo unidade é obrigatório.',
            'curso_id.required' => 'O campo curso é obrigatório.',
            'categoria_id.required' => 'O campo categoria é obrigatório.',
            'nivel_id.required' => 'O campo nível é obrigatório.',
            'turno_id.required' => 'O campo turno é obrigatório.',
            'professor_id.required' => 'O campo professor é obrigatório.',
        ]);

        $turma = Turma::findOrFail($id);
        $unidade = Unidade::findOrFail($request->unidade_id);
        $calendarioAtivo = Calendario::where('ativo', true)->first();

        if (!$calendarioAtivo) {
            return redirect()->back()
                ->withErrors(['calendario' => 'Nenhum calendário ativo encontrado.'])
                ->withInput();
        }

        // Verifica se a turma pertence ao calendário ativo
        if ($turma->calendario_id != $calendarioAtivo->id) {
            return redirect()->back()
                ->withErrors(['geral' => 'Esta turma não pertence ao calendário ativo e não pode ser editada.'])
                ->withInput();
        }

        // Verifica se a nova unidade pertence ao calendário ativo
        if ($unidade->calendario_id != $calendarioAtivo->id) {
            return redirect()->back()
                ->withErrors(['unidade_id' => 'Esta unidade não pertence ao calendário ativo.'])
                ->withInput();
        }

        $data = $request->all();
        $data['calendario_id'] = $unidade->calendario_id;

        $turma->update($data);
        return redirect()->route('Listar-Turmas')->with('success', 'Turma atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $turma = Turma::findOrFail($id);
        
        // Verifica se há matrículas vinculadas
        if ($turma->matriculas()->count() > 0) {
            return redirect()->route('Listar-Turmas')
                ->with('error', 'Não é possível excluir a turma porque existem matrículas vinculadas a ela.');
        }
        
        $turma->delete();
        return redirect()->route('Listar-Turmas')->with('success', 'Turma deletada com sucesso!');
    }
}