<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RoleController extends Controller
{
    public function index(Request $request): View
    {
        return view('roles.index');
    }

    public function create(): View
    {
        $create = true;
        $edit = false;
        $show = false;

        $permission = Permission::whereNotNull('prefix')
            ->where('prefix', '!=', 'Sanctum')
            ->get();
        $groupedPermissions = $permission->groupBy('prefix');
        return view('roles.create-edit-show', compact('permission', 'create', 'edit', 'show', 'groupedPermissions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permission.*' => 'exists:permissions,id',
        ], [
            'name.required' => 'O campo nome é obrigatório.',
            'name.unique' => 'Já existe um papel com esse nome.',
            'permission.*.exists' => 'Permissão inválida.',
        ]);

        $permissionsID = array_map(
            function($value) { return (int)$value; },
            $request->input('permission', [])
        );

        $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);
        $role->syncPermissions($permissionsID);

        return redirect()->route('Listar-Papeis')
                        ->with('success', 'Papel criado com sucesso');
    }

    public function show($id): View
    {
        $create = false;
        $edit = false;
        $show = true;

        $role = Role::findOrFail($id);
        $permission = Permission::whereNotNull('prefix')
            ->where('prefix', '!=', 'Sanctum')
            ->get();

        $groupedPermissions = $permission->groupBy('prefix');

        return view('roles.create-edit-show', compact('role', 'groupedPermissions', 'create', 'edit', 'show'));
    }

      public function edit($id): View
    {
        $role = Role::findOrFail($id);
        $permission = Permission::whereNotNull('prefix')
            ->where('prefix', '!=', 'Sanctum')
            ->get();
        $rolePermissions = DB::table("role_has_permissions")
            ->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        $groupedPermissions = $permission->groupBy('prefix');

        return view('roles.edit', compact('role', 'permission', 'rolePermissions', 'groupedPermissions'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $role = Role::findOrFail($id);

        $validationRules = [
            'permission.*' => 'nullable|exists:permissions,id',
        ];

        if ($request->name !== $role->name) {
            $validationRules['name'] = 'required|string|unique:roles,name,' . $role->id . ',id,guard_name,web';
        } else {
            $validationRules['name'] = 'required|string';
        }

        $request->validate($validationRules, [
            'name.required' => 'O campo nome é obrigatório.',
            'name.unique' => 'Já existe um papel com esse nome para o guard web.',
            'permission.*.exists' => 'Permissão inválida.',
        ]);

        // Atualizar diretamente no banco
        Role::where('id', $role->id)->update(['name' => $request->name]);

        $permissionsID = array_filter(array_map(
            function($value) { return (int)$value; },
            $request->input('permission', [])
        ));

        $role->syncPermissions($permissionsID);

        return redirect()->route('Listar-Papeis')
                        ->with('success', 'Papel atualizado com sucesso');
    }
    public function destroy($id): RedirectResponse
    {
        DB::table("roles")->where('id', $id)->delete();
        return redirect()->route('Listar-Papeis')
                        ->with('success', 'Papel deletado com sucesso');
    }
}