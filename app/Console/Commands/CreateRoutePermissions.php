<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Route;

class CreateRoutePermissions extends Command
{
    protected $signature = 'permission:create-route-permissions';
    protected $description = 'Cria permissões automaticamente com base nas rotas nomeadas';

    public function handle()
    {
        $this->info('Gerando permissões a partir das rotas...');

        $routes = Route::getRoutes()->getRoutes();
        $created = 0;
        $skipped = 0;

        foreach ($routes as $route) {
            if ($name = $route->getName()) {
                if ($this->shouldCreatePermission($name)) {
                    $permission = Permission::firstOrCreate([
                        'name' => $name,
                        'guard_name' => 'web',
                    ], [
                        'prefix' => $this->getRoutePrefix($route),
                    ]);

                    if ($permission->wasRecentlyCreated) {
                        $this->info("Permissão criada: {$name}");
                        $created++;
                    } else {
                        $skipped++;
                    }
                }
            }
        }

        $this->info("Permissões geradas com sucesso! Total criado: {$created}, Total pulado: {$skipped}");
    }

    protected function shouldCreatePermission($name)
    {
        $excludedPatterns = [
            'password.',
            'verification.',
            'two-factor.',
            'sanctum.',
            'livewire.',
            'storage.',
            'login',
            'logout',
            'register',
            'email.',
            'forgot-password',
            'reset-password',
            'ignition.',
        ];

        foreach ($excludedPatterns as $pattern) {
            if (str_starts_with($name, $pattern)) {
                return false;
            }
        }

        $includedPrefixes = [
            'Listar-',
            'Search-',
            'Criar-',
            'Gravar-',
            'Ver-',
            'Editar-',
            'Atualizar-',
            'Deletar-',
            'permissions.',
            'profile.',
            'about',
            'home',
            'dashboard',
            'Relatorios-',
            'Clonar-',
            'Revogar-',
            'Mostrar-',
            'Copiar-',
            'Listar-Frequencias',
            'Criar-Frequencia',
            'Gravar-Frequencia',
            'Ver-Frequencia',
            'Editar-Frequencia',
            'Atualizar-Frequencia',
            'Deletar-Frequencia',
            'Alunos'
        ];

        foreach ($includedPrefixes as $prefix) {
            if (str_starts_with($name, $prefix)) {
                return true;
            }
        }

        return false;
    }

    protected function getRoutePrefix($route)
    {
        $prefix = $route->action['prefix'] ?? null;
        return $prefix ? str_replace('/', '-', trim($prefix, '/')) : null;
    }
}