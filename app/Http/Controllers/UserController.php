<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $users = User::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
            })
            ->with('roles')
            ->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'exists:roles,name'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'name' => $validated['nome'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('Listar-Usuarios')->with('success', 'Usuário criado com sucesso.');
    }

    public function show(User $user): View
    {
        $user->load('roles');
        return view('users.show', compact('user'));
    }

 public function edit($id): View
{
    $user = User::findOrFail($id);
    $roles = Role::pluck('name', 'name')->all();
    $user->load('roles');
    return view('users.edit', compact('user', 'roles'));
}

  public function update(Request $request, $id): RedirectResponse
{
    $user = User::findOrFail($id);

    $validated = $request->validate([
        'nome' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        'role' => ['required', 'exists:roles,name'],
        'password' => ['nullable', 'confirmed', 'min:8'],
    ]);

    $user->update([
        'name' => $validated['nome'],
        'email' => $validated['email'],
        'password' => $validated['password'] ? bcrypt($validated['password']) : $user->password,
    ]);

    $user->syncRoles($validated['role']);

    return redirect()->route('Listar-Usuarios')->with('success', 'Usuário atualizado com sucesso.');
}

    public function destroy(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return redirect()->route('Listar-Usuarios')->with('error', 'Você não pode excluir seu próprio usuário.');
        }
        $user->delete();

        return redirect()->route('Listar-Usuarios')->with('warning', 'Usuário excluído com sucesso.');
    }
}