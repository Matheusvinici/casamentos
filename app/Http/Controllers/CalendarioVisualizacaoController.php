<?php

namespace App\Http\Controllers;

use App\Models\Calendario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CalendarioVisualizacaoController extends Controller
{
    /**
     * Altera o calendário que está sendo visualizado
     */
    public function visualizar(Request $request)
    {
        try {
            \Log::info('=== CALENDARIO VISUALIZAR ===');
            \Log::info('Request:', $request->all());
            \Log::info('Session ID:', [Session::getId()]);
            \Log::info('Session before:', Session::all());
            
            $request->validate([
                'calendario_id' => 'required|exists:calendarios,id'
            ]);
            
            $calendario = Calendario::findOrFail($request->calendario_id);
            
            \Log::info('Calendário encontrado:', [
                'id' => $calendario->id,
                'nome' => $calendario->nomeCompleto,
                'ativo' => $calendario->ativo
            ]);
            
            // Armazena na sessão
            Session::put('calendario_visualizacao_id', $calendario->id);
            Session::put('calendario_visualizacao_nome', $calendario->nomeCompleto);
            Session::save(); // IMPORTANTE: Salva a sessão
            
            \Log::info('Session after:', Session::all());
            \Log::info('Session saved:', [Session::getId()]);
            
            return response()->json([
                'success' => true,
                'message' => 'Visualização alterada para ' . $calendario->nomeCompleto,
                'calendario' => [
                    'id' => $calendario->id,
                    'nome' => $calendario->nomeCompleto,
                    'ativo' => $calendario->ativo
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro ao alterar visualização:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Erro: ' . $e->getMessage()
            ], 500);
        }
    }
}