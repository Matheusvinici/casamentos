<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use App\Models\Turma;
use App\Models\Aluno;
use App\Models\TipoAvaliacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotaController extends Controller
{
    public function __construct()
    {
        // Não usar middleware 'auth' aqui porque professor usa guard diferente
        // As rotas já têm os middlewares específicos
    }

    public function index()
    {
        return view('notas.index');
    }

    public function turmas()
    {
        return view('notas.cardturmas');
    }

    public function create(Request $request)
    {
        $turma_id = $request->query('turma_id');
        $tipo_avaliacao_id = $request->query('tipo_avaliacao_id');

        if (!$turma_id) {
            abort(400, 'O parâmetro turma_id é obrigatório.');
        }

        // Verifica se é professor
        if (Auth::guard('professor')->check()) {
            $turma = Turma::where('id', $turma_id)
                ->where('professor_id', Auth::guard('professor')->id())
                ->firstOrFail();
        } 
        // Verifica se é admin
        elseif (Auth::check()) {
            $turma = Turma::findOrFail($turma_id);
        } 
        else {
            abort(403, 'Não autenticado');
        }

        return view('notas.create-edit-show', [
            'mode' => 'create',
            'turma_id' => $turma_id,
            'turma' => $turma,
            'tipo_avaliacao_id' => $tipo_avaliacao_id,
        ]);
    }

    public function edit($turma_id, $aluno_id, $tipo_avaliacao_id)
    {
        // Verifica se é professor
        if (Auth::guard('professor')->check()) {
            $turma = Turma::where('id', $turma_id)
                ->where('professor_id', Auth::guard('professor')->id())
                ->firstOrFail();
        } 
        // Verifica se é admin
        elseif (Auth::check()) {
            $turma = Turma::findOrFail($turma_id);
        } 
        else {
            abort(403, 'Não autenticado');
        }

        return view('notas.create-edit-show', [
            'mode' => 'edit',
            'turma_id' => $turma_id,
            'turma' => $turma,
            'aluno_id' => $aluno_id,
            'tipo_avaliacao_id' => $tipo_avaliacao_id,
        ]);
    }

    public function show($turma_id, $aluno_id, $tipo_avaliacao_id)
    {
        // Verifica se é professor
        if (Auth::guard('professor')->check()) {
            $turma = Turma::where('id', $turma_id)
                ->where('professor_id', Auth::guard('professor')->id())
                ->firstOrFail();
        } 
        // Verifica se é admin
        elseif (Auth::check()) {
            $turma = Turma::findOrFail($turma_id);
        } 
        else {
            abort(403, 'Não autenticado');
        }

        return view('notas.create-edit-show', [
            'mode' => 'show',
            'turma_id' => $turma_id,
            'turma' => $turma,
            'aluno_id' => $aluno_id,
            'tipo_avaliacao_id' => $tipo_avaliacao_id,
        ]);
    }

    public function destroy($id)
    {
        $nota = Nota::findOrFail($id);
        
        // Verifica permissão
        if (Auth::guard('professor')->check()) {
            $turma = Turma::find($nota->turma_id);
            if (!$turma || $turma->professor_id != Auth::guard('professor')->id()) {
                abort(403, 'Acesso não autorizado.');
            }
        } elseif (!Auth::check()) {
            abort(403, 'Não autenticado');
        }
        
        $turma_id = $nota->turma_id;
        $nota->delete();
        
        // Redireciona baseado no guard
        if (Auth::guard('professor')->check()) {
            return redirect()->route('Listar-Notas-Professor', ['turma_id' => $turma_id])
                ->with('success', 'Nota deletada com sucesso.');
        } else {
            return redirect()->route('Listar-Notas', ['turma_id' => $turma_id])
                ->with('success', 'Nota deletada com sucesso.');
        }
    }
}