<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConfirmacaoPresenca;
use App\Models\PresenteComprado;
use App\Models\User;
use App\Http\Controllers\PresenteController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
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
    /**
     * Exclui uma confirmação de presença permanentemente
     */
    public function destroyConfirmacao($id)
    {
        $confirmacao = ConfirmacaoPresenca::findOrFail($id);
        $confirmacao->delete();

        return redirect()->back()->with('success', 'Confirmação excluída com sucesso!');
    }

    /**
     * Gera o PDF individual de um convite
     */
    public function gerarConviteIndividualPdf($id, $senha)
    {
        $confirmacao = ConfirmacaoPresenca::with('user')->findOrFail($id);

        if ($confirmacao->senha_acesso !== $senha) {
            abort(403, 'Acesso negado.');
        }

        $pdf = Pdf::loadView('admin.relatorios.convite-individual', compact('confirmacao'))
            ->setPaper('a5', 'portrait');
            
        return $pdf->stream('Ingresso_Casamento_' . \Illuminate\Support\Str::slug($confirmacao->nome_completo) . '.pdf');
    }
    /**
     * Dispara as notificações de convite para todos os confirmados via Node.js Bot
     */
    public function dispararConvitesMassa()
    {
        set_time_limit(0); // Garante que o script não morra por timeout em envios longos
        
        $presencas = ConfirmacaoPresenca::with('user')
            ->where('status', 'confirmado')
            ->get();

        $enviados = 0;
        $erros = 0;

        foreach ($presencas as $p) {
            $telefone = $p->user ? preg_replace('/[^0-9]/', '', $p->user->phone1 ?? $p->user->phone2) : '';
            
            if (!$telefone) {
                $erros++;
                continue;
            }

            if (substr($telefone, 0, 2) != '55') {
                $telefone = '55' . $telefone;
            }

            $linkPdf = route('convite.individual.pdf', ['id' => $p->id, 'senha' => $p->senha_acesso ?? '0000']);
            $mensagem = "Olá! Aqui está o ingresso individual e intransferível para o nosso casamento.\n\n" .
                        "*Convidado(a):* {$p->nome_completo}\n" .
                        "*Sua Senha de Acesso:* {$p->senha_acesso}\n\n" .
                        "Apresente a senha acima ou baixe e mostre o PDF na entrada do evento para liberar seu acesso:\n" .
                        "{$linkPdf}\n\n" .
                        "Estamos muito felizes em ter você com a gente!";

            try {
                $response = Http::timeout(30)->post('http://localhost:3001/send-message', [
                    'number' => $telefone,
                    'message' => $mensagem,
                    'pdfUrl' => $linkPdf,
                    'pdfName' => 'Ingresso_' . \Illuminate\Support\Str::slug($p->nome_completo) . '.pdf'
                ]);

                if ($response->successful()) {
                    $enviados++;
                } else {
                    $erros++;
                }

                // Pequeno delay para não sobrecarregar o robô e evitar erros de protocolo
                sleep(2);
            } catch (\Exception $e) {
                $erros++;
            }
        }

        return redirect()->back()->with('success', "Disparos concluídos! Sucessos: {$enviados}, Falhas/Sem fone: {$erros}");
    }

    /**
     * Dispara notificação individual via robô sem abrir nova guia
     */
    public function dispararIndividualBot($id)
    {
        $p = ConfirmacaoPresenca::with('user')->findOrFail($id);
        
        $telefone = $p->user ? preg_replace('/[^0-9]/', '', $p->user->phone1 ?? $p->user->phone2) : '';
        
        if (!$telefone) {
            return redirect()->back()->with('error', "Telefone não encontrado para este convidado.");
        }

        if (substr($telefone, 0, 2) != '55') {
            $telefone = '55' . $telefone;
        }

        $linkPdf = route('convite.individual.pdf', ['id' => $p->id, 'senha' => $p->senha_acesso ?? '0000']);
        $mensagem = "Olá! Aqui está o ingresso individual e intransferível para o nosso casamento.\n\n" .
                    "*Convidado(a):* {$p->nome_completo}\n" .
                    "*Sua Senha de Acesso:* {$p->senha_acesso}\n\n" .
                    "Apresente a senha acima ou baixe e mostre o PDF na entrada do evento para liberar seu acesso:\n" .
                    "{$linkPdf}\n\n" .
                    "Estamos muito felizes em ter você com a gente!";

        try {
            $response = Http::post('http://localhost:3001/send-message', [
                'number' => $telefone,
                'message' => $mensagem,
                'pdfUrl' => $linkPdf,
                'pdfName' => 'Ingresso_' . \Illuminate\Support\Str::slug($p->nome_completo) . '.pdf'
            ]);

            if ($response->successful()) {
                return redirect()->back()->with('success', "Convite enviado com sucesso para {$p->nome_completo}!");
            }
            
            return redirect()->back()->with('error', "Erro ao enviar via robô. Verifique se ele está ativo.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "Erro de conexão com o robô.");
        }
    }
}
