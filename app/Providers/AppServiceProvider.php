<?php

namespace App\Providers;

use App\Models\Calendario;
use App\Models\Unidade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator; 
use Illuminate\Support\Facades\URL; 
use Illuminate\Support\Facades\Validator; 

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Paginator::useBootstrap();
    //   Validator::extend('cpf', 'App\Utils\CpfValidation@validate');
      if(config('app.env') == 'production'){
        URL::forceScheme('https');
      }

        View::composer('*', function ($view) {
            try {
                
                $calendarios = Calendario::orderBy('ano', 'DESC')
                    ->orderBy('semestre', 'DESC')
                    ->get();
                
               
                $calendarioAtivo = Calendario::where('ativo', true)->first();
                
                $calendarioVisualizacaoId = session('calendario_visualizacao_id');
                $calendarioVisualizacao = $calendarioVisualizacaoId 
                    ? Calendario::find($calendarioVisualizacaoId)
                    : $calendarioAtivo;
                
                $todasUnidades = Unidade::with('calendario')
                    ->join('calendarios', 'unidades.calendario_id', '=', 'calendarios.id')
                    ->select('unidades.*')
                    ->orderBy('calendarios.ano', 'DESC')
                    ->orderBy('calendarios.semestre', 'DESC')
                    ->orderBy('unidades.nome')
                    ->get();
                
                $unidadeAtiva = Unidade::where('ativo', true)
                    ->with('calendario')
                    ->first();
                
                $unidadeVisualizacaoId = session('unidade_visualizacao_id');
                $unidadeVisualizacao = $unidadeVisualizacaoId 
                    ? Unidade::with('calendario')->find($unidadeVisualizacaoId)
                    : $unidadeAtiva;
                
                $view->with([
                    'calendarios' => $calendarios, 
                    'calendarioAtivo' => $calendarioAtivo,
                    'calendarioVisualizacao' => $calendarioVisualizacao,
                    'todasUnidades' => $todasUnidades,
                    'unidadeAtiva' => $unidadeAtiva,
                    'unidadeVisualizacao' => $unidadeVisualizacao,
                ]);
                
            } catch (\Exception $e) {
                $view->with([
                    'calendarios' => collect(),
                    'todasUnidades' => collect(),
                ]);
            }
        });
    }
}