<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // php artisan permission:create-route-permissions
        // Verificar e criar o usuário se não existir
        $user = User::firstOrCreate(
            ['email' => 'matheus2vandrade@gmail.com'],
            [
                'name' => 'Matheus Vinicius',
                'password' => bcrypt('@dminDev'),
                'phone1' => '123456789',
                'phone2' => '987654321',
            ]
        );

        // Criar o papel de administrador se não existir
        $role = Role::firstOrCreate(['name' => 'Admin']);

        // Obter todas as permissões e sincronizá-las com o papel
        $permissions = Permission::pluck('id', 'id')->all();
        $role->syncPermissions($permissions);

        // Atribuir o papel ao usuário
        $user->assignRole([$role->id]);
        $userAdmin = User::where('email', 'matheus2vandrade@gmail.com')->first();
        $userAdmin->assignRole('Admin');
    }
    }
