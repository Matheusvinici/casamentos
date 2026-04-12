<?php

namespace App\Http\Controllers;

use App\Models\Professor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfessorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search_professor');
        $professores = Professor::when($search, function ($query, $search) {
            return $query->where('nome', 'like', "%{$search}%")
                        ->orWhere('cpf', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
        })->paginate(50);

        return view('professores.index', compact('professores'));
    }

    public function search(Request $request)
    {
        $search = $request->input('search_professor');
        $professores = Professor::when($search, function ($query, $search) {
            return $query->where('nome', 'like', "%{$search}%")
                        ->orWhere('cpf', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
        })->paginate(50);

        return view('professores.index', compact('professores'));
    }

    public function create()
    {
        return view('professores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'telefone' => 'required|string|max:20',
            'cpf' => 'required|string|max:14|unique:professores,cpf',
            'email' => 'required|email|max:255|unique:professores,email',
            'password' => 'required|string|min:8|confirmed', // Adiciona validação de senha
        ], [
            'nome.required' => 'O nome do professor é obrigatório.',
            'telefone.required' => 'O telefone é obrigatório.',
            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.unique' => 'O CPF informado já está em uso.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'O e-mail deve ser válido.',
            'email.unique' => 'O e-mail informado já está em uso.',
            'password.required' => 'A senha é obrigatória.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'A confirmação da senha não coincide.',
        ]);

        Professor::create([
            'nome' => $request->nome,
            'telefone' => $request->telefone,
            'cpf' => $request->cpf,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hashear a senha
        ]);

        return redirect()->route('Listar-Professores')->with('success', 'Professor cadastrado com sucesso!');
    }

    public function show($id)
    {
        $professor = Professor::findOrFail($id);
        return view('professores.show', compact('professor'));
    }

    public function edit($id)
    {
        $professor = Professor::findOrFail($id);
        return view('professores.edit', compact('professor'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'telefone' => 'required|string|max:20',
            'cpf' => 'required|string|max:14|unique:professores,cpf,' . $id,
            'email' => 'required|email|max:255|unique:professores,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed', // Senha opcional na edição
        ], [
            'nome.required' => 'O nome do professor é obrigatório.',
            'telefone.required' => 'O telefone é obrigatório.',
            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.unique' => 'O CPF informado já está em uso.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'O e-mail deve ser válido.',
            'email.unique' => 'O e-mail informado já está em uso.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'A confirmação da senha não coincide.',
        ]);

        $professor = Professor::findOrFail($id);
        $data = $request->only(['nome', 'telefone', 'cpf', 'email']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password); // Atualiza senha se fornecida
        }
        $professor->update($data);

        return redirect()->route('Listar-Professores')->with('success', 'Professor atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $professor = Professor::findOrFail($id);
        $professor->delete();
        return redirect()->route('Listar-Professores')->with('success', 'Professor removido com sucesso!');
    }

    public function minhasTurmas(Request $request)
    {
        $professor = Auth::guard('professor')->user();
        $turmas = $professor->turmas()->paginate(10);
        return view('professores.turmas', compact('turmas', 'professor'));
    }
}
