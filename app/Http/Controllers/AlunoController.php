<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Escola;
use App\Models\Bairro;
use App\Models\Cidade;
use App\Models\Pais;
use Illuminate\Http\Request;

class AlunoController extends Controller
{
    public function index(Request $request)
    {
        $query = Aluno::query();

        if ($request->has('search_aluno')) {
            $searchTerm = $request->input('search_aluno');
            $query->where('nome', 'like', "%{$searchTerm}%");
        }

        $alunos = $query->with('escola')->paginate(100);

        return view('alunos.index', compact('alunos'));
    }

    public function create()
    {
        return view('alunos.create');
    }

    public function show($id)
    {
        $aluno = Aluno::with(['deficiencias', 'escola', 'bairro', 'cidade', 'pais'])->findOrFail($id);
        return view('alunos.show', compact('aluno'));
    }

    public function edit($id)
    {
        $aluno = Aluno::findOrFail($id);
        return view('alunos.edit', compact('aluno'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'telefone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'endereco' => 'nullable|string|max:255',
            'bairro_id' => 'nullable|exists:bairros,id',
            'cidade_id' => 'nullable|exists:cidades,id',
            'pais_id' => 'nullable|exists:paises,id',
            'distrito' => 'nullable|string|max:255',
            'turno_idioma' => 'nullable|string|max:255',
            'ano_escolar' => 'nullable|string|max:50',              // NOVO CAMPO - opcional
            'aluno_cpf' => 'nullable|string|max:14',                // NOVO CAMPO - opcional
            'contato_emergencia' => 'nullable|string|max:20',
            'data_nascimento' => 'required|date',
            'tipo' => 'required|in:aluno_rede,servidor,outros',
            'escola_id' => 'nullable|exists:escolas,id',
            'turno_escola' => 'nullable|string|max:255',
            'origem' => 'nullable|in:municipal,estadual',
            'origem_servidor' => 'nullable|string|max:255',
            'responsavel_nome' => 'nullable|string|max:255',
            'responsavel_telefone' => 'nullable|string|max:20',
            'responsavel_cpf' => 'nullable|string|max:14',
            'responsavel_email' => 'nullable|email|max:255',
            'responsavel_endereco' => 'nullable|string|max:255',
        ]);

        Aluno::create($validated);

        return redirect()->route('Listar-Alunos')->with('success', 'Aluno criado com sucesso!');
    }

    public function update(Request $request, Aluno $aluno)
    {
        $rules = [
            'nome' => 'required|string|max:255',
            'telefone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:alunos,email,' . $aluno->id,
            'endereco' => 'nullable|string|max:255',
            'bairro_id' => 'nullable|exists:bairros,id',
            'cidade_id' => 'nullable|exists:cidades,id',
            'pais_id' => 'nullable|exists:paises,id',
            'distrito' => 'nullable|string|max:255',
            'turno_idioma' => 'nullable|string|max:255',
            'ano_escolar' => 'nullable|string|max:50',              // NOVO CAMPO - opcional
            'aluno_cpf' => 'nullable|string|max:14|unique:alunos,aluno_cpf,' . $aluno->id,  // NOVO CAMPO - opcional com unique
            'contato_emergencia' => 'nullable|string|max:20',
            'data_nascimento' => 'required|date',
            'tipo' => 'required|in:aluno_rede,servidor,outros',
            'escola_id' => 'nullable|exists:escolas,id',
            'turno_escola' => 'nullable|string|max:255',
            'origem' => 'nullable|in:municipal,estadual',
            'origem_servidor' => 'nullable|string|max:255',
            'responsavel_nome' => 'nullable|string|max:255',
            'responsavel_telefone' => 'nullable|string|max:20',
            'responsavel_cpf' => 'nullable|string|max:14|unique:alunos,responsavel_cpf,' . $aluno->id,
            'responsavel_email' => 'nullable|email|max:255|unique:alunos,responsavel_email,' . $aluno->id,
            'responsavel_endereco' => 'nullable|string|max:255',
        ];

        // Ajustar regras para tipo aluno_rede
        if ($request->input('tipo') === 'aluno_rede') {
            $rules['escola_id'] = 'required|exists:escolas,id';
            $rules['turno_escola'] = 'required|string|max:255';
            $rules['origem'] = 'required|in:municipal,estadual';
            // Mantém os campos de responsável como obrigatórios? Se não, pode deixar como nullable
        } else {
            $rules['origem_servidor'] = 'required|string|max:255';
            $rules['escola_id'] = 'nullable';
            $rules['turno_escola'] = 'nullable';
            $rules['origem'] = 'nullable';
            $rules['responsavel_nome'] = 'nullable';
            $rules['responsavel_telefone'] = 'nullable';
            $rules['responsavel_cpf'] = 'nullable';
            $rules['responsavel_email'] = 'nullable';
            $rules['responsavel_endereco'] = 'nullable';
        }

        $validated = $request->validate($rules);

        // Limpar campos desnecessários com base no tipo
        if ($request->input('tipo') !== 'aluno_rede') {
            $validated['escola_id'] = null;
            $validated['turno_escola'] = null;
            $validated['origem'] = null;
            $validated['responsavel_nome'] = null;
            $validated['responsavel_telefone'] = null;
            $validated['responsavel_cpf'] = null;
            $validated['responsavel_email'] = null;
            $validated['responsavel_endereco'] = null;
        } else {
            $validated['origem_servidor'] = null;
        }

        // Os campos ano_escolar e aluno_cpf são sempre mantidos (podem ser null ou ter valor)
        // Não precisam de limpeza especial pois são comuns a todos os tipos

        $aluno->update($validated);

        return redirect()->route('Listar-Alunos')->with('success', 'Aluno atualizado com sucesso!');
    }
    public function destroy(Aluno $aluno)
    {
        $aluno->delete();
        return redirect()->route('Listar-Alunos')->with('success', 'Aluno removido com sucesso!');
    }
}