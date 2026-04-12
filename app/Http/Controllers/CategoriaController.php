<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Curso;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Lista as categorias com suporte a busca e paginação.
     */
    public function index(Request $request)
    {
        $search = $request->input('search_categoria');
        $categorias = Categoria::with('curso')
            ->when($search, function ($query, $search) {
                return $query->where('nome', 'like', "%{$search}%");
            })->paginate(10)->appends(['search_categoria' => $search]);

        return view('categorias.index', compact('categorias'));
    }

    /**
     * Exibe o formulário de criação de categoria.
     */
    public function create()
    {
        $cursos = Curso::all();
        return view('categorias.create-edit-show', compact('cursos'));
    }

    /**
     * Salva uma nova categoria.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'abreviacao' => 'required|string|max:10',
            'curso_id' => 'required|exists:cursos,id',
        ], [
            'nome.required' => 'O nome da categoria é obrigatório.',
            'abreviacao.required' => 'A abreviação da categoria é obrigatória.',
            'curso_id.required' => 'O curso é obrigatório.',
            'curso_id.exists' => 'O curso selecionado é inválido.',
        ]);

        Categoria::create($request->all());

        return redirect()->route('Listar-Categorias')->with('success', 'Categoria cadastrada com sucesso!');
    }

    /**
     * Exibe os detalhes de uma categoria.
     */
    public function show($id)
    {
        $categoria = Categoria::findOrFail($id);
        $cursos = Curso::all();
        return view('categorias.create-edit-show', compact('categoria', 'cursos'));
    }

    /**
     * Exibe o formulário de edição de uma categoria.
     */
    public function edit($id)
    {
        $categoria = Categoria::findOrFail($id);
        $cursos = Curso::all();
        return view('categorias.create-edit-show', compact('categoria', 'cursos'));
    }

    /**
     * Atualiza uma categoria existente.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'abreviacao' => 'required|string|max:10',
            'curso_id' => 'required|exists:cursos,id',
        ], [
            'nome.required' => 'O nome da categoria é obrigatório.',
            'abreviacao.required' => 'A abreviação da categoria é obrigatória.',
            'curso_id.required' => 'O curso é obrigatório.',
            'curso_id.exists' => 'O curso selecionado é inválido.',
        ]);

        $categoria = Categoria::findOrFail($id);
        $categoria->update($request->all());

        return redirect()->route('Listar-Categorias')->with('success', 'Categoria atualizada com sucesso!');
    }

    /**
     * Remove uma categoria.
     */
    public function destroy($id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->delete();
        return redirect()->route('Listar-Categorias')->with('success', 'Categoria removida com sucesso!');
    }
}