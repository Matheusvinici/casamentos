<?php

namespace App\Http\Controllers;

use App\Models\Calendario;
use Illuminate\Http\Request;

class LetivoController extends Controller
{
    /**
     * Lista os letivos (renderiza o componente Livewire).
     */
    public function index()
    {
        // Obtém o calendário para visualização
        $calendarioVisualizacaoId = session('calendario_visualizacao_id');
        $calendarioVisualizacao = Calendario::find($calendarioVisualizacaoId);
        
        return view('letivos.index', compact('calendarioVisualizacao'));
    }

    /**
     * Exibe o formulário de criação de letivo.
     */
    public function create()
    {
        // Obtém o calendário ativo para criação
        $calendarioAtivo = Calendario::where('ativo', true)->first();
        
        if (!$calendarioAtivo) {
            return redirect()->route('letivos.index')
                ->with('error', 'Nenhum calendário ativo encontrado. Ative um calendário antes de criar dias letivos.');
        }
        
        return view('letivos.create', compact('calendarioAtivo'));
    }

    /**
     * Chama o componente Livewire para salvar um novo letivo.
     */
    public function store(Request $request)
    {
        return view('letivos.create');
    }

    /**
     * Exibe os detalhes de um letivo.
     */
    public function show($id)
    {
        return view('letivos.show', compact('id'));
    }

    /**
     * Exibe o formulário de edição de letivo.
     */
    public function edit($id)
    {
        return view('letivos.edit', compact('id'));
    }

    /**
     * Chama o componente Livewire para atualizar um letivo existente.
     */
    public function update(Request $request, $id)
    {
        return view('letivos.edit', compact('id'));
    }

    /**
     * Chama o componente Livewire para excluir um letivo.
     */
    public function destroy($id)
    {
        return view('letivos.index');
    }
}