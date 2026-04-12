<?php

namespace App\Http\Controllers;

use App\Models\Responsavel;
use Illuminate\Http\Request;

class ResponsavelController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search_responsavel');
        $responsaveis = Responsavel::when($search, function ($query, $search) {
            return $query->where('nome', 'like', "%{$search}%")
                        ->orWhere('cpf', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
        })->paginate(10);
        return view('responsaveis.index', compact('responsaveis'));
    }

    public function search(Request $request)
    {
        $search = $request->input('search_responsavel');
        $responsaveis = Responsavel::when($search, function ($query, $search) {
            return $query->where('nome', 'like', "%{$search}%")
                        ->orWhere('cpf', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
        })->paginate(10);
        return view('responsaveis.index', compact('responsaveis'));
    }

    public function create()
    {
        return view('responsaveis.create-edit');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'telefone' => 'required|string|max:20',
            'cpf' => 'required|string|max:14|unique:responsavels,cpf',
            'email' => 'required|email|max:255|unique:responsavels,email',
            'endereco' => 'required|string|max:255',
        ], [
            'nome.required' => 'O nome do responsável é obrigatório.',
            'telefone.required' => 'O telefone é obrigatório.',
            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.unique' => 'O CPF informado já está em uso.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'O e-mail deve ser válido.',
            'email.unique' => 'O e-mail informado já está em uso.',
            'endereco.required' => 'O endereço é obrigatório.',
        ]);

        Responsavel::create($request->all());

        return redirect()->route('Listar-Responsaveis')->with('success', 'Responsável criado com sucesso!');
    }

    public function show(Responsavel $responsavel)
    {
        return view('responsaveis.create-edit', compact('responsavel'));
    }

    public function edit(Responsavel $responsavel)
    {
        return view('responsaveis.create-edit', compact('responsavel'));
    }

    public function update(Request $request, Responsavel $responsavel)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'telefone' => 'required|string|max:20',
            'cpf' => 'required|string|max:14|unique:responsavels,cpf,' . $responsavel->id,
            'email' => 'required|email|max:255|unique:responsavels,email,' . $responsavel->id,
            'endereco' => 'required|string|max:255',
        ], [
            'nome.required' => 'O nome do responsável é obrigatório.',
            'telefone.required' => 'O telefone é obrigatório.',
            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.unique' => 'O CPF informado já está em uso.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'O e-mail deve ser válido.',
            'email.unique' => 'O e-mail informado já está em uso.',
            'endereco.required' => 'O endereço é obrigatório.',
        ]);

        $responsavel->update($request->all());

        return redirect()->route('Listar-Responsaveis')->with('success', 'Responsável atualizado com sucesso!');
    }

    public function destroy(Responsavel $responsavel)
    {
        $responsavel->delete();
        return redirect()->route('Listar-Responsaveis')->with('success', 'Responsável removido com sucesso!');
    }
}