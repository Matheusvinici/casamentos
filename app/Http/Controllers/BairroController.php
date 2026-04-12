<?php

namespace App\Http\Controllers;

use App\Models\Bairro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BairroController extends Controller
{
    /**
     * Lista os bairros com suporte a busca e paginação.
     */
    public function index(Request $request)
    {
        $search = $request->input('search_bairro');
        $bairros = Bairro::when($search, function ($query, $search) {
            return $query->where('nome', 'like', "%{$search}%");
        })->paginate(10)->appends(['search_bairro' => $search]);

        return view('bairros.index', compact('bairros'));
    }

    /**
     * Exibe o formulário de criação de bairro.
     */
    public function create()
    {
        return view('bairros.create-edit-show');
    }

    /**
     * Salva um novo bairro.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255|unique:bairros,nome',
        ], [
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.unique' => 'O nome do bairro já está em uso.',
        ]);

        Bairro::create($request->only(['nome']));

        return redirect()->route('Listar-Bairros')->with('success', 'Bairro cadastrado com sucesso!');
    }

    /**
     * Exibe os detalhes de um bairro.
     */
    public function show(Bairro $bairro)
    {
        return view('bairros.create-edit-show', compact('bairro'));
    }

    /**
     * Exibe o formulário de edição de um bairro.
     */
    public function edit(Bairro $bairro)
    {
        return view('bairros.create-edit-show', compact('bairro'));
    }

    /**
     * Atualiza um bairro existente.
     */
    public function update(Request $request, Bairro $bairro)
    {
        $request->validate([
            'nome' => 'required|string|max:255|unique:bairros,nome,' . $bairro->id,
        ], [
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.unique' => 'O nome do bairro já está em uso.',
        ]);

       

        // Atualizar apenas o campo 'nome'
        $updated = $bairro->update($request->only(['nome']));

      

        return redirect()->route('Listar-Bairros')->with('success', 'Bairro atualizado com sucesso!');
    }

    /**
     * Remove um bairro.
     */
    public function destroy(Bairro $bairro)
    {
        $bairro->delete();
        return redirect()->route('Listar-Bairros')->with('success', 'Bairro excluído com sucesso!');
    }
}