<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    /**
     * Lista os cursos com suporte a busca e paginação.
     */
    public function index(Request $request)
    {
        $search = $request->input('search_curso');
        $cursos = Curso::when($search, function ($query, $search) {
            return $query->where('nome', 'like', "%{$search}%");
        })->paginate(10)->appends(['search_curso' => $search]);

        return view('cursos.index', compact('cursos'));
    }

    /**
     * Exibe o formulário de criação de curso.
     */
    public function create()
    {
        return view('cursos.create-edit');
    }

    /**
     * Salva um novo curso.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'abreviacao' => 'required|string|max:10',
        ], [
            'nome.required' => 'O nome do curso é obrigatório.',
            'abreviacao.required' => 'A abreviação do curso é obrigatória.',
        ]);

        Curso::create($request->all());

        return redirect()->route('Listar-Cursos')->with('success', 'Curso cadastrado com sucesso!');
    }

    /**
     * Exibe os detalhes de um curso.
     */
    public function show($id)
    {
        $curso = Curso::findOrFail($id);
        return view('cursos.create-edit', compact('curso'));
    }

    /**
     * Exibe o formulário de edição de um curso.
     */
    public function edit($id)
    {
        $curso = Curso::findOrFail($id);
        return view('cursos.create-edit', compact('curso'));
    }

    /**
     * Atualiza um curso existente.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'abreviacao' => 'required|string|max:10',
        ], [
            'nome.required' => 'O nome do curso é obrigatório.',
            'abreviacao.required' => 'A abreviação do curso é obrigatória.',
        ]);

        $curso = Curso::findOrFail($id);
        $curso->update($request->all());

        return redirect()->route('Listar-Cursos')->with('success', 'Curso atualizado com sucesso!');
    }

    /**
     * Remove um curso.
     */
    public function destroy($id)
    {
        $curso = Curso::findOrFail($id);
        $curso->delete();
        return redirect()->route('Listar-Cursos')->with('success', 'Curso removido com sucesso!');
    }
}