<?php

namespace App\Http\Controllers;

use App\Models\TipoAvaliacao;
use App\Models\Calendario;
use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipoAvaliacaoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $calendarioId = $request->get('calendario_id', session('calendario_visualizacao_id'));
        
        if (!$calendarioId) {
            $calendario = Calendario::where('ativo', true)
                ->orderBy('ano', 'desc')
                ->orderBy('semestre', 'desc')
                ->first();
            $calendarioId = $calendario?->id;
        }

        $calendarios = Calendario::ordenado()->get();
        $calendarioAtual = Calendario::find($calendarioId);

        $tipoAvaliacoes = TipoAvaliacao::with(['calendario', 'curso'])
            ->when($calendarioId, function ($query, $calendarioId) {
                return $query->where('calendario_id', $calendarioId);
            })
            ->ordenado()
            ->paginate(15);

        $totalPontos = TipoAvaliacao::totalPontosPorCalendario($calendarioId);
        $totalPesos = TipoAvaliacao::totalPesosPorCalendario($calendarioId);

        return view('tipo-avaliacoes.index', compact(
            'tipoAvaliacoes', 
            'calendarios', 
            'calendarioAtual',
            'totalPontos',
            'totalPesos'
        ));
    }

    /**
     * Search for listings.
     */
    public function search(Request $request)
    {
        $search = $request->get('q');
        $calendarioId = $request->get('calendario_id');

        $tipoAvaliacoes = TipoAvaliacao::with(['calendario', 'curso'])
            ->when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('nome', 'like', "%{$search}%")
                      ->orWhere('abreviacao', 'like', "%{$search}%")
                      ->orWhere('descricao', 'like', "%{$search}%");
                });
            })
            ->when($calendarioId, function ($query, $calendarioId) {
                return $query->where('calendario_id', $calendarioId);
            })
            ->ordenado()
            ->paginate(15);

        if ($request->ajax()) {
            return response()->json($tipoAvaliacoes);
        }

        return view('tipo-avaliacoes.index', compact('tipoAvaliacoes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $calendarioId = $request->get('calendario_id', session('calendario_visualizacao_id'));
        $calendarios = Calendario::ordenado()->get();
        $cursos = Curso::orderBy('nome')->get();
        $proximaOrdem = TipoAvaliacao::getProximaOrdem($calendarioId);
        
        return view('tipo-avaliacoes.create-edit-show', [
            'tipoAvaliacao' => null,
            'calendarios' => $calendarios,
            'cursos' => $cursos,
            'calendarioId' => $calendarioId,
            'proximaOrdem' => $proximaOrdem,
            'mode' => 'create'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'calendario_id' => 'required|exists:calendarios,id',
            'curso_id' => 'required|exists:cursos,id',
            'nome' => 'required|string|max:255',
            'abreviacao' => 'nullable|string|max:20',
            'descricao' => 'nullable|string',
            'peso' => 'required|numeric|min:0.1|max:999.99',
            'valor_maximo' => 'required|numeric|min:0.1|max:999.99',
            'ordem' => 'required|integer|min:1',
            'ativo' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            // Ajusta a ordem das avaliações existentes
            TipoAvaliacao::where('calendario_id', $request->calendario_id)
                ->where('ordem', '>=', $request->ordem)
                ->increment('ordem');

            TipoAvaliacao::create($validated);

            DB::commit();

            return redirect()
                ->route('Listar-Tipo-Avaliacoes', ['calendario_id' => $request->calendario_id])
                ->with('success', 'Tipo de avaliação cadastrado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $tipoAvaliacao = TipoAvaliacao::with(['calendario', 'curso'])->findOrFail($id);
        
        return view('tipo-avaliacoes.create-edit-show', [
            'tipoAvaliacao' => $tipoAvaliacao,
            'calendarios' => Calendario::ordenado()->get(),
            'cursos' => Curso::orderBy('nome')->get(),
            'mode' => 'show'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $tipoAvaliacao = TipoAvaliacao::findOrFail($id);
        $calendarios = Calendario::ordenado()->get();
        $cursos = Curso::orderBy('nome')->get();
        
        return view('tipo-avaliacoes.create-edit-show', [
            'tipoAvaliacao' => $tipoAvaliacao,
            'calendarios' => $calendarios,
            'cursos' => $cursos,
            'mode' => 'edit'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $tipoAvaliacao = TipoAvaliacao::findOrFail($id);

        $validated = $request->validate([
            'calendario_id' => 'required|exists:calendarios,id',
            'curso_id' => 'required|exists:cursos,id',
            'nome' => 'required|string|max:255',
            'abreviacao' => 'nullable|string|max:20',
            'descricao' => 'nullable|string',
            'peso' => 'required|numeric|min:0.1|max:999.99',
            'valor_maximo' => 'required|numeric|min:0.1|max:999.99',
            'ordem' => 'required|integer|min:1',
            'ativo' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            // Se mudou a ordem, reorganiza
            if ($tipoAvaliacao->ordem != $request->ordem) {
                // Libera a posição atual
                TipoAvaliacao::where('calendario_id', $tipoAvaliacao->calendario_id)
                    ->where('ordem', '>', $tipoAvaliacao->ordem)
                    ->decrement('ordem');

                // Ajusta para nova posição
                TipoAvaliacao::where('calendario_id', $request->calendario_id)
                    ->where('ordem', '>=', $request->ordem)
                    ->where('id', '!=', $id)
                    ->increment('ordem');
            }

            $tipoAvaliacao->update($validated);

            DB::commit();

            return redirect()
                ->route('Listar-Tipo-Avaliacoes', ['calendario_id' => $tipoAvaliacao->calendario_id])
                ->with('success', 'Tipo de avaliação atualizado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Erro ao atualizar: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $tipoAvaliacao = TipoAvaliacao::findOrFail($id);
            $calendarioId = $tipoAvaliacao->calendario_id;
            $ordemRemovida = $tipoAvaliacao->ordem;

            $tipoAvaliacao->delete();

            // Reorganiza as ordens
            TipoAvaliacao::where('calendario_id', $calendarioId)
                ->where('ordem', '>', $ordemRemovida)
                ->decrement('ordem');

            DB::commit();

            return redirect()
                ->route('Listar-Tipo-Avaliacoes', ['calendario_id' => $calendarioId])
                ->with('success', 'Tipo de avaliação excluído com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao excluir: ' . $e->getMessage());
        }
    }
}