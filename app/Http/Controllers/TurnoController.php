<?php

namespace App\Http\Controllers;

use App\Models\Turno;
use Illuminate\Http\Request;

class TurnoController extends Controller
{
    /**
     * Lista os turnos com suporte a busca e paginação.
     */
    public function index(Request $request)
    {
        $search = $request->input('search_turno');
        $turnos = Turno::when($search, function ($query, $search) {
            return $query->where('nome', 'like', "%{$search}%");
        })->paginate(10)->appends(['search_turno' => $search]);

        return view('turnos.index', compact('turnos'));
    }

    /**
     * Exibe o formulário de criação de turno.
     */
    public function create()
    {
        return view('turnos.create-edit');
    }

    /**
     * Salva um novo turno.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'abreviacao' => 'required|string|max:10',
        ], [
            'nome.required' => 'O nome do turno é obrigatório.',
            'abreviacao.required' => 'A abreviação do turno é obrigatória.',
        ]);

        Turno::create($request->all());

        return redirect()->route('Listar-Turnos')->with('success', 'Turno cadastrado com sucesso!');
    }

    /**
     * Exibe os detalhes de um turno.
     */
    public function show($id)
    {
        $turno = Turno::findOrFail($id);
        return view('turnos.create-edit', compact('turno'));
    }

    /**
     * Exibe o formulário de edição de um turno.
     */
    public function edit($id)
    {
        $turno = Turno::findOrFail($id);
        return view('turnos.create-edit', compact('turno'));
    }

    /**
     * Atualiza um turno existente.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'abreviacao' => 'required|string|max:10',
        ], [
            'nome.required' => 'O nome do turno é obrigatório.',
            'abreviacao.required' => 'A abreviação do turno é obrigatória.',
        ]);

        $turno = Turno::findOrFail($id);
        $turno->update($request->all());

        return redirect()->route('Listar-Turnos')->with('success', 'Turno atualizado com sucesso!');
    }

    /**
     * Remove um turno.
     */
    public function destroy($id)
    {
        $turno = Turno::findOrFail($id);
        $turno->delete();
        return redirect()->route('Listar-Turnos')->with('success', 'Turno removido com sucesso!');
    }
}