<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConfirmacaoPresenca;
use App\Models\PresenteComprado;
use App\Models\User;
use App\Http\Controllers\PresenteController;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

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

    /**
     * Adiciona uma nova confirmação de presença manualmente
     */
    public function adicionarConfirmacao(Request $request)
    {
        $request->validate([
            'nome_completo' => 'required|string|max:255',
            'status' => 'required|in:confirmado,desistiu',
        ]);

        ConfirmacaoPresenca::create([
            'user_id' => Auth::id(), // Vinculado ao admin que adicionou
            'nome_completo' => $request->nome_completo,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Confirmação adicionada com sucesso!');
    }

    /**
     * Edita uma confirmação de presença existente
     */
    public function editarConfirmacao(Request $request, $id)
    {
        $request->validate([
            'nome_completo' => 'required|string|max:255',
            'status' => 'required|in:confirmado,desistiu',
        ]);

        $confirmacao = ConfirmacaoPresenca::findOrFail($id);
        $confirmacao->update([
            'nome_completo' => $request->nome_completo,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Confirmação atualizada com sucesso!');
    }

    /**
     * Gera relatório PDF da lista de confirmações
     */
    public function gerarRelatorioConfirmacoesPdf()
    {
        $confirmacoes = ConfirmacaoPresenca::with('user')->orderBy('nome_completo', 'asc')->get();
        $totalConfirmados = ConfirmacaoPresenca::where('status', 'confirmado')->count();
        $totalDesistentes = ConfirmacaoPresenca::where('status', 'desistiu')->count();

        $pdf = Pdf::loadView('admin.relatorios.confirmacoes', compact('confirmacoes', 'totalConfirmados', 'totalDesistentes'));
        
        return $pdf->stream('relatorio_confirmacoes.pdf');
    }

    /**
     * Gera relatório PDF da lista de presentes
     */
    public function gerarRelatorioPresentesPdf()
    {
        $compras = PresenteComprado::with('user')->orderBy('created_at', 'desc')->get();
        $presentesDetalhes = PresenteController::getPresentes();
        
        $presentesRecebidos = [];
        $totalArrecadado = 0;
        
        foreach ($compras as $compra) {
            if (isset($presentesDetalhes[$compra->presente_id])) {
                $detalhe = $presentesDetalhes[$compra->presente_id];
                $presentesRecebidos[] = [
                    'id' => $compra->id,
                    'usuario' => $compra->user ? $compra->user->name : 'N/A',
                    'presente_id' => $detalhe['id'],
                    'nome_presente' => $detalhe['nome'],
                    'preco' => $detalhe['preco'],
                    'data_compra' => $compra->created_at,
                    'metodo' => $compra->metodo_pagamento
                ];
                $totalArrecadado += $detalhe['preco'];
            }
        }

        $pdf = Pdf::loadView('admin.relatorios.presentes', compact('presentesRecebidos', 'totalArrecadado'));
        
        return $pdf->stream('relatorio_presentes.pdf');
    }
}
