<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConfirmacaoPresenca;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ConfirmacaoController extends Controller
{
    /**
     * Verifica se a funcionalidade de adicionar novos convidados ainda está ativa
     */
    private function canAddConfirmation()
    {
        // Prazo final: 5 de maio de 2026
        $deadline = Carbon::create(2026, 5, 5, 23, 59, 59);
        return Carbon::now()->lessThanOrEqualTo($deadline);
    }

    /**
     * Exibe a tela de confirmação de presença (painel do hóspede)
     */
    public function index()
    {
        $user = Auth::user();
        $confirmacoes = ConfirmacaoPresenca::where('user_id', $user->id)
                                           ->orderBy('created_at', 'asc')
                                           ->get();
        
        $podeAdicionar = $this->canAddConfirmation();
        
        return view('confirmacao.index', compact('confirmacoes', 'podeAdicionar'));
    }

    /**
     * Adiciona um nova confirmação ao grupo do usuário
     */
    public function store(Request $request)
    {
        if (!$this->canAddConfirmation()) {
            return redirect()->route('confirmacao.index')->with('error', 'O prazo para novas confirmações encerrou em 05 de maio de 2026.');
        }

        $request->validate([
            'nome_completo' => 'required|string|max:255',
        ]);

        ConfirmacaoPresenca::create([
            'user_id' => Auth::id(),
            'nome_completo' => $request->nome_completo,
            'status' => 'confirmado'
        ]);

        return redirect()->route('confirmacao.index')->with('success', "Presença de {$request->nome_completo} confirmada com sucesso!");
    }

    /**
     * Altera o status para 'desistiu'
     */
    public function desistir($id)
    {
        $confirmacao = ConfirmacaoPresenca::where('id', $id)
                                          ->where('user_id', Auth::id())
                                          ->firstOrFail();
                                          
        $confirmacao->update(['status' => 'desistiu']);

        return redirect()->route('confirmacao.index')->with('success', "A presença de {$confirmacao->nome_completo} foi cancelada.");
    }
}
