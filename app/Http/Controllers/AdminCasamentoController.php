<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConfirmacaoPresenca;
use App\Models\PresenteComprado;
use App\Models\User;
use App\Http\Controllers\PresenteController;

class AdminCasamentoController extends Controller
{
    /**
     * Dashboard administrativo para visualização de presenças e presentes
     */
    public function dashboard()
    {
        // Total de presenças
        $totalConfirmados = ConfirmacaoPresenca::where('status', 'confirmado')->count();
        $totalDesistentes = ConfirmacaoPresenca::where('status', 'desistiu')->count();

        // Lista de confirmados ordenada pelo usuário que confirmou
        $presencas = ConfirmacaoPresenca::with('user')->orderBy('created_at', 'desc')->get();

        // Histórico de Presentes
        $compras = PresenteComprado::with('user')->orderBy('created_at', 'desc')->get();
        $presentesDetalhes = PresenteController::getPresentes();
        
        $presentesRecebidos = [];
        $totalArrecadado = 0;
        
        foreach ($compras as $compra) {
            if (isset($presentesDetalhes[$compra->presente_id])) {
                $detalhe = $presentesDetalhes[$compra->presente_id];
                $presentesRecebidos[] = [
                    'id' => $compra->id,
                    'usuario' => $compra->user ? $compra->user->name : 'Usuário ' . $compra->user_id,
                    'presente_id' => $detalhe['id'],
                    'nome_presente' => $detalhe['nome'],
                    'preco' => $detalhe['preco'],
                    'data_compra' => $compra->created_at,
                    'metodo' => $compra->metodo_pagamento
                ];
                $totalArrecadado += $detalhe['preco'];
            }
        }

        return view('admin.dashboard', compact(
            'totalConfirmados', 
            'totalDesistentes', 
            'presencas', 
            'presentesRecebidos', 
            'totalArrecadado'
        ));
    }

    /**
     * Remove o bloqueio (compra) de um presente
     */
    public function desbloquear($id)
    {
        $compra = PresenteComprado::find($id);
        
        if ($compra) {
            $compra->delete();
            return redirect()->back()->with('success', 'Presente desbloqueado com sucesso!');
        }

        return redirect()->back()->with('error', 'Registro de presente não encontrado.');
    }
}
