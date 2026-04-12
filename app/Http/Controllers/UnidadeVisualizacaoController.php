<?php

namespace App\Http\Controllers;

use App\Models\Unidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class UnidadeVisualizacaoController extends Controller
{
    public function visualizar(Request $request)
    {
        try {
            Log::info('=== INICIANDO ALTERAÇÃO DE VISUALIZAÇÃO ===');
            Log::info('Session ID: ' . Session::getId());
            Log::info('Request data:', $request->all());
            
            $request->validate([
                'unidade_id' => 'required|exists:unidades,id'
            ]);
            
            $unidade = Unidade::with('calendario')->findOrFail($request->unidade_id);
            
            Log::info('Unidade encontrada:', [
                'id' => $unidade->id,
                'nome' => $unidade->nome,
                'ativo' => $unidade->ativo,
                'calendario' => $unidade->calendario ? $unidade->calendario->nomeCompleto : 'N/A'
            ]);
            
            // Armazena na sessão
            Session::put('unidade_visualizacao_id', $unidade->id);
            Session::put('unidade_visualizacao_nome', $unidade->nome);
            
            // Força salvar a sessão
            Session::save();
            
            Log::info('Sessão salva:', [
                'unidade_visualizacao_id' => Session::get('unidade_visualizacao_id'),
                'session_data' => Session::all()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Visualização alterada para ' . $unidade->nome,
                'unidade' => [
                    'id' => $unidade->id,
                    'nome' => $unidade->nome,
                    'sigla' => $unidade->sigla,
                    'calendario' => $unidade->calendario ? $unidade->calendario->nomeCompleto : 'N/A',
                    'ativo' => $unidade->ativo
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('ERRO CRÍTICO ao alterar visualização:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
                'session_id' => Session::getId()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro: ' . $e->getMessage()
            ], 500);
        }
    }
}