<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cidade;
use App\Models\Estado;

class CidadeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search_cidade');
        $cidades = Cidade::with('estado')
            ->when($search, function ($query, $search) {
                return $query->where('nome', 'LIKE', "%{$search}%")
                    ->orWhere('codigo_ibge', 'LIKE', "%{$search}%")
                    ->orWhereHas('estado', function ($q) use ($search) {
                        $q->where('nome', 'LIKE', "%{$search}%");
                    });
            })->paginate(10);

        return view('cidades.index', compact('cidades'));
    }

    public function search(Request $request)
    {
        $search = $request->input('search_cidade');
        $cidades = Cidade::with('estado')
            ->when($search, function ($query, $search) {
                return $query->where('nome', 'LIKE', "%{$search}%")
                    ->orWhere('codigo_ibge', 'LIKE', "%{$search}%")
                    ->orWhereHas('estado', function ($q) use ($search) {
                        $q->where('nome', 'LIKE', "%{$search}%");
                    });
            })->paginate(10);

        return view('cidades.index', compact('cidades'));
    }

    public function create()
    {
        $edit = false;
        $show = false;
        $estados = Estado::all();
        return view('cidades.create-edit-show', compact('edit', 'show', 'estados'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'estado_id' => 'required|exists:estados,id',
            'nome' => 'required|string|max:255',
            'codigo_ibge' => 'required|string|max:20|unique:cidades,codigo_ibge',
        ], [
            'estado_id.required' => 'O campo estado é obrigatório.',
            'estado_id.exists' => 'O estado selecionado é inválido.',
            'nome.required' => 'O campo nome é obrigatório.',
            'codigo_ibge.required' => 'O campo código IBGE é obrigatório.',
            'codigo_ibge.unique' => 'O código IBGE já está em uso.',
        ]);

        Cidade::create($request->all());

        return redirect()->route('Listar-Cidades')->with('success', 'Cidade cadastrada com sucesso!');
    }

    public function show(Cidade $cidade)
    {
        $show = true;
        $edit = false;
        $estados = Estado::all();
        return view('cidades.create-edit-show', compact('cidade', 'show', 'edit', 'estados'));
    }

    public function edit(Cidade $cidade)
    {
        $edit = true;
        $show = false;
        $estados = Estado::all();
        return view('cidades.create-edit-show', compact('cidade', 'edit', 'show', 'estados'));
    }

    public function update(Request $request, Cidade $cidade)
    {
        $request->validate([
            'estado_id' => 'required|exists:estados,id',
            'nome' => 'required|string|max:255',
            'codigo_ibge' => 'required|string|max:20|unique:cidades,codigo_ibge,' . $cidade->id,
        ], [
            'estado_id.required' => 'O campo estado é obrigatório.',
            'estado_id.exists' => 'O estado selecionado é inválido.',
            'nome.required' => 'O campo nome é obrigatório.',
            'codigo_ibge.required' => 'O campo código IBGE é obrigatório.',
            'codigo_ibge.unique' => 'O código IBGE já está em uso.',
        ]);

        $cidade->update($request->all());

        return redirect()->route('Listar-Cidades')->with('success', 'Cidade atualizada com sucesso!');
    }

    public function confirmDelete(Cidade $cidade)
    {
        return view('cidades.delete', compact('cidade'));
    }

    public function destroy(Cidade $cidade)
    {
        $cidade->delete();
        return redirect()->route('Listar-Cidades')->with('success', 'Cidade excluída com sucesso!');
    }
}