<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $permissions = Permission::orderBy('name')->paginate(10);
        return view('permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
            'prefix' => 'nullable|string',
        ], [
            'name.required' => 'O campo nome é obrigatório',
            'name.unique' => 'Já existe uma permissão com esse nome',
        ]);

        Permission::create([
            'name' => $request->name,
            'guard_name' => 'web',
            'prefix' => $request->prefix,
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permissão criada com sucesso');
    }

    public function show(Permission $permission)
    {
        return view('permissions.show', compact('permission'));
    }

    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id,
            'prefix' => 'nullable|string',
        ], [
            'name.required' => 'O campo nome é obrigatório',
            'name.unique' => 'Já existe uma permissão com esse nome',
        ]);

        $permission->update([
            'name' => $request->name,
            'prefix' => $request->prefix,
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permissão atualizada com sucesso');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('permissions.index')->with('success', 'Permissão excluída com sucesso');
    }
}