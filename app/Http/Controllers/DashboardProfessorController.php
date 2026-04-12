<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Aula;
use App\Models\Turma;
use App\Models\Calendario;
use App\Models\Frequencia;

class DashboardProfessorController extends Controller
{
    public function index()
    {
        $professor = Auth::guard('professor')->user();
        
        // Busca o calendário ativo
        $calendarioAtivo = Calendario::where('ativo', true)->first();
        
        if (!$calendarioAtivo) {
            return view('dashboards.dashboard-professores', [
                'professor' => $professor,
                'turmasCount' => 0,
                'aulasCount' => 0,
                'calendarioAtivo' => null
            ])->with('error', 'Nenhum calendário ativo encontrado.');
        }
        
        // Busca os IDs das turmas do professor no calendário ativo
        $turmasIds = $professor->turmas()
            ->where('calendario_id', $calendarioAtivo->id)
            ->pluck('id');
        
        // Contagem de turmas com matrículas
        $turmasCount = $professor->turmas()
            ->where('calendario_id', $calendarioAtivo->id)
            ->whereHas('matriculas', function($query) {
                $query->where('status', 'ativo'); // Se quiser filtrar apenas matrículas ativas
            })
            ->count();
        
        // Contagem de aulas
        $aulasCount = Aula::whereIn('turma_id', $turmasIds)->count();

        return view('dashboards.dashboard-professores', [
            'professor' => $professor,
            'turmasCount' => $turmasCount,
            'aulasCount' => $aulasCount,
            'calendarioAtivo' => $calendarioAtivo
        ]);
    }
}