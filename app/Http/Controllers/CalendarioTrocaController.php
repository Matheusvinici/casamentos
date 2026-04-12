<?php

namespace App\Http\Controllers;

use App\Models\Calendario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalendarioTrocaController extends Controller
{
    /**
     * Troca o calendário ativo.
     */
    public function trocar(Request $request)
    {
        $request->validate([
            'calendario_id' => 'required|exists:calendarios,id'
        ]);

        try {
            DB::beginTransaction();

            $novoCalendario = Calendario::findOrFail($request->calendario_id);
            
            // Verifica se já está ativo
            if ($novoCalendario->ativo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este calendário já está ativo.'
                ]);
            }

            // Desativa todos os calendários
            Calendario::where('ativo', true)->update(['ativo' => false]);
            
            // Ativa o novo calendário
            $novoCalendario->ativo = true;
            $novoCalendario->save();

            DB::commit();

            // Armazena o calendário ativo na sessão (opcional)
            session(['calendario_ativo_id' => $novoCalendario->id]);

            return response()->json([
                'success' => true,
                'message' => 'Calendário alterado com sucesso!',
                'calendario' => $novoCalendario
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao alterar calendário: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtém o calendário ativo atual.
     */
    public function getAtivo()
    {
        $calendarioAtivo = Calendario::where('ativo', true)->first();
        
        return response()->json([
            'success' => true,
            'calendario' => $calendarioAtivo
        ]);
    }
}