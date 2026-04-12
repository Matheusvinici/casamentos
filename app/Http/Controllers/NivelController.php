<?php

namespace App\Http\Controllers;

use App\Models\Nivel;
use Illuminate\Http\Request;

class NivelController extends Controller
{
    /**
     * Lista os níveis com suporte a busca e paginação.
     */
    public function index(Request $request)
    {
        $search = $request->input('search_nivel');
        $niveis = Nivel::when($search, function ($query, $search) {
            return $query->where('nome', 'like', "%{$search}%");
        })->paginate(10)->appends(['search_nivel' => $search]);

        return view('niveis.index', compact('niveis'));
    }

    /**
     * Exibe o formulário de criação de nível.
     */
    public function create()
    {
        return view('niveis.create-edit');
    }

    /**
     * Salva um novo nível.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'abreviacao' => 'required|string|max:10',
        ], [
            'nome.required' => 'O nome do nível é obrigatório.',
            'abreviacao.required' => 'A abreviação do nível é obrigatória.',
        ]);

        Nivel::create($request->all());

        return redirect()->route('Listar-Niveis')->with('success', 'Nível cadastrado com sucesso!');
    }

    /**
     * Exibe os detalhes de um nível.
     */
    public function show($id)
    {
        $nivel = Nivel::findOrFail($id);
        return view('niveis.create-edit', compact('nivel'));
    }

    /**
     * Exibe o formulário de edição de um nível.
     */
    public function edit($id)
    {
        $nivel = Nivel::findOrFail($id);
        return view('niveis.create-edit', compact('nivel'));
    }

    /**
     * Atualiza um nível existente.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'abreviacao' => 'required|string|max:10',
        ], [
            'nome.required' => 'O nome do nível é obrigatório.',
            'abreviacao.required' => 'A abreviação do nível é obrigatória.',
        ]);

        $nivel = Nivel::findOrFail($id);
        $nivel->update($request->all());

        return redirect()->route('Listar-Niveis')->with('success', 'Nível atualizado com sucesso!');
    }

    /**
     * Remove um nível.
     */
    public function destroy($id)
    {
        $nivel = Nivel::findOrFail($id);
        $nivel->delete();
        return redirect()->route('Listar-Niveis')->with('success', 'Nível removido com sucesso!');
    }
}