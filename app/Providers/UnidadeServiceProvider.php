<?php

namespace App\Providers;

use App\Models\Unidade;
use App\Models\Calendario;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class UnidadeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('*', function ($view) {
            try {
                // Obtém TODAS as unidades, ordenadas por calendário
                $todasUnidades = Unidade::with('calendario')
                    ->join('calendarios', 'unidades.calendario_id', '=', 'calendarios.id')
                    ->select('unidades.*')
                    ->orderBy('calendarios.ano', 'DESC')
                    ->orderBy('calendarios.semestre', 'DESC')
                    ->orderBy('unidades.nome')
                    ->get();
                
                // Obtém a unidade ativa (onde ativo = 1)
                $unidadeAtiva = Unidade::where('ativo', true)
                    ->with('calendario')
                    ->first();
                
                // Obtém a unidade para visualização (da sessão ou ativa)
                $unidadeVisualizacaoId = session('unidade_visualizacao_id');
                $unidadeVisualizacao = null;
                
                if ($unidadeVisualizacaoId) {
                    $unidadeVisualizacao = Unidade::with('calendario')->find($unidadeVisualizacaoId);
                }
                
                // Se não encontrou na sessão, usa a ativa
                if (!$unidadeVisualizacao && $unidadeAtiva) {
                    $unidadeVisualizacao = $unidadeAtiva;
                    session(['unidade_visualizacao_id' => $unidadeVisualizacao->id]);
                }
                
                // Obtém o calendário ativo (onde ativo = 1) - CORRIGIDO
                $calendarioAtivo = Calendario::where('ativo', true)->first();
                
                // Se não houver calendário ativo, use o da unidade ativa
                if (!$calendarioAtivo && $unidadeAtiva) {
                    $calendarioAtivo = $unidadeAtiva->calendario;
                }
                
                // Compartilha com a view
                $view->with('todasUnidades', $todasUnidades);
                $view->with('unidadeAtiva', $unidadeAtiva);
                $view->with('unidadeVisualizacao', $unidadeVisualizacao);
                $view->with('calendarioAtivo', $calendarioAtivo);
                
            } catch (\Exception $e) {
                \Log::error('Erro no UnidadeServiceProvider: ' . $e->getMessage());
                $view->with('todasUnidades', collect());
                $view->with('unidadeAtiva', null);
                $view->with('unidadeVisualizacao', null);
                $view->with('calendarioAtivo', null);
            }
        });
    }
}