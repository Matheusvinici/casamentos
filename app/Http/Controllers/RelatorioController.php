<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aluno;
use App\Models\Matricula;
use App\Models\Turma;
use App\Models\Curso;
use App\Models\Nivel;
use App\Models\Professor;
use App\Models\Bairro;
use App\Models\Unidade;
use App\Models\Calendario;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class RelatorioController extends Controller
{
    public function index()
    {
        // Obtém o calendário para visualização
        $calendarioVisualizacaoId = session('calendario_visualizacao_id');
        $calendarioVisualizacao = Calendario::find($calendarioVisualizacaoId);
        
        return view('relatorios.index', compact('calendarioVisualizacao'));
    }

    public function search(Request $request)
    {
        return view('relatorios.index');
    }

    public function matriculasPorTurma(Request $request)
{
    $request->validate([
        'turma_id' => 'nullable|exists:turmas,id',
        'status' => 'nullable|in:ativo,inativo,desistente',
    ], [
        'turma_id.exists' => 'A turma selecionada é inválida.',
        'status.in' => 'O status selecionado é inválido.',
    ]);

    $turmaId = $request->input('turma_id');
    $status = $request->input('status', 'ativo');
    
    // Obtém o calendário para visualização
    $calendarioVisualizacaoId = session('calendario_visualizacao_id');

    $query = Matricula::with(['aluno', 'turma', 'turma.curso', 'calendario'])
        ->when($calendarioVisualizacaoId, function ($query, $calendarioId) {
            return $query->where('calendario_id', $calendarioId);
        });

    if ($turmaId) {
        $query->where('turma_id', $turmaId);
    }

    $query->where('status', $status);
    
    // ORDENAÇÃO ALFANUMÉRICA: por nome do aluno (ordem alfabética)
    $query->join('alunos', 'matriculas.aluno_id', '=', 'alunos.id')
          ->orderBy('alunos.nome', 'asc')
          ->select('matriculas.*'); // Evita conflito de colunas

    $matriculas = $query->get();
    
    $turmas = Turma::with('curso')
        ->when($calendarioVisualizacaoId, function ($query, $calendarioId) {
            return $query->where('calendario_id', $calendarioId);
        })
        ->orderBy('nome', 'asc') // Ordena turmas alfabeticamente
        ->get();
    
    // Obtém o objeto do calendário sendo visualizado
    $calendarioVisualizacao = Calendario::find($calendarioVisualizacaoId);

    if ($request->has('download')) {
        // Configurações do PDF para página única
        $pdf = Pdf::loadView('relatorios.pdf.matriculas_por_turma', 
            compact('matriculas', 'turmas', 'turmaId', 'status', 'calendarioVisualizacao'));
        
        // Configura para página única e tamanho A4 paisagem se necessário
        $pdf->setPaper('a4', 'landscape') // Use 'portrait' para retrato
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true
            ]);
        
        return $pdf->download('matriculas_por_turma_' . date('Y-m-d') . '.pdf');
    }

    return view('relatorios.matriculas_por_turma', 
        compact('matriculas', 'turmas', 'turmaId', 'status', 'calendarioVisualizacao'));
}
 public function comprovanteMatricula($matriculaId)
{
    $matricula = Matricula::with([
        'aluno', 
        'turma', 
        'turma.curso', 
        'turma.unidade',
        'turma.turno',
        'turma.professor',
        'calendario'
    ])->findOrFail($matriculaId);
    
    $calendarioVisualizacaoId = session('calendario_visualizacao_id');
    if ($calendarioVisualizacaoId && $matricula->calendario_id != $calendarioVisualizacaoId) {
        return redirect()->back()->with('error', 'Esta matrícula não pertence ao calendário selecionado.');
    }
    
    // Dados adicionais para a declaração
    $usuario = auth()->user();
    $cargo = 'Secretário(a) Municipal de Educação';
    
    $pdf = Pdf::loadView('relatorios.pdf.comprovante_matricula', compact('matricula', 'usuario', 'cargo'));
    
    // Configurações para o PDF
    $pdf->setPaper('a4', 'portrait');
    $pdf->setOptions([
        'defaultFont' => 'Arial',
        'isHtml5ParserEnabled' => true,
        'isRemoteEnabled' => true,
        'chroot' => public_path(),
    ]);
    
    $nomeArquivo = 'declaracao_matricula_' . 
                  str_replace(' ', '_', $matricula->aluno->nome) . '_' . 
                  date('Y-m-d') . '.pdf';
    
    return $pdf->download($nomeArquivo);
}

    public function matriculasPorCurso(Request $request)
    {
        $request->validate([
            'curso_id' => 'nullable|exists:cursos,id',
            'status' => 'nullable|in:ativo,inativo,desistente',
        ], [
            'curso_id.exists' => 'O curso selecionado é inválido.',
            'status.in' => 'O status selecionado é inválido.',
        ]);

        $cursoId = $request->input('curso_id');
        $status = $request->input('status', 'ativo');
        
        // Obtém o calendário para visualização
        $calendarioVisualizacaoId = session('calendario_visualizacao_id');

        $query = Matricula::with(['aluno', 'turma.curso', 'calendario'])
            ->when($calendarioVisualizacaoId, function ($query, $calendarioId) {
                return $query->where('calendario_id', $calendarioId);
            });

        if ($cursoId) {
            $query->whereHas('turma', function ($q) use ($cursoId) {
                $q->where('curso_id', $cursoId);
            });
        }

        $query->where('status', $status);

        $matriculas = $query->get();
        $cursos = Curso::all();
        
        // Obtém o objeto do calendário sendo visualizado
        $calendarioVisualizacao = Calendario::find($calendarioVisualizacaoId);

        if ($request->has('download')) {
            $pdf = Pdf::loadView('relatorios.pdf.matriculas_por_curso', 
                compact('matriculas', 'cursos', 'cursoId', 'status', 'calendarioVisualizacao'));
            return $pdf->download('matriculas_por_curso.pdf');
        }

        return view('relatorios.matriculas_por_curso', 
            compact('matriculas', 'cursos', 'cursoId', 'status', 'calendarioVisualizacao'));
    }

    public function matriculasPorNivel(Request $request)
    {
        $request->validate([
            'nivel_id' => 'nullable|exists:nivels,id',
            'status' => 'nullable|in:ativo,inativo,desistente',
        ], [
            'nivel_id.exists' => 'O nível selecionado é inválido.',
            'status.in' => 'O status selecionado é inválido.',
        ]);

        $nivelId = $request->input('nivel_id');
        $status = $request->input('status', 'ativo');
        
        // Obtém o calendário para visualização
        $calendarioVisualizacaoId = session('calendario_visualizacao_id');

        $query = Matricula::with(['aluno', 'turma.nivel', 'calendario'])
            ->when($calendarioVisualizacaoId, function ($query, $calendarioId) {
                return $query->where('calendario_id', $calendarioId);
            });

        if ($nivelId) {
            $query->whereHas('turma', function ($q) use ($nivelId) {
                $q->where('nivel_id', $nivelId);
            });
        }

        $query->where('status', $status);

        $matriculas = $query->get();
        $niveis = Nivel::all();
        
        // Obtém o objeto do calendário sendo visualizado
        $calendarioVisualizacao = Calendario::find($calendarioVisualizacaoId);

        if ($request->has('download')) {
            $pdf = Pdf::loadView('relatorios.pdf.matriculas_por_nivel', 
                compact('matriculas', 'niveis', 'nivelId', 'status', 'calendarioVisualizacao'));
            return $pdf->download('matriculas_por_nivel.pdf');
        }

        return view('relatorios.matriculas_por_nivel', 
            compact('matriculas', 'niveis', 'nivelId', 'status', 'calendarioVisualizacao'));
    }

    public function todasTurmas(Request $request)
    {
        $request->validate([
            'unidade_id' => 'nullable|exists:unidades,id',
            'curso_id' => 'nullable|exists:cursos,id',
        ], [
            'unidade_id.exists' => 'A unidade selecionada é inválida.',
            'curso_id.exists' => 'O curso selecionado é inválido.',
        ]);

        $unidadeId = $request->input('unidade_id');
        $cursoId = $request->input('curso_id');
        
        // Obtém o calendário para visualização
        $calendarioVisualizacaoId = session('calendario_visualizacao_id');

        $query = Turma::with(['unidade', 'curso', 'nivel', 'turno', 'professor', 'calendario'])
            ->when($calendarioVisualizacaoId, function ($query, $calendarioId) {
                return $query->where('calendario_id', $calendarioId);
            });

        if ($unidadeId) {
            $query->where('unidade_id', $unidadeId);
        }

        if ($cursoId) {
            $query->where('curso_id', $cursoId);
        }

        $turmas = $query->get();
        $unidades = Unidade::when($calendarioVisualizacaoId, function ($query, $calendarioId) {
            return $query->where('calendario_id', $calendarioId);
        })->get();
        $cursos = Curso::all();
        
        // Obtém o objeto do calendário sendo visualizado
        $calendarioVisualizacao = Calendario::find($calendarioVisualizacaoId);

        if ($request->has('download')) {
            $pdf = Pdf::loadView('relatorios.pdf.todas_turmas', 
                compact('turmas', 'unidades', 'cursos', 'unidadeId', 'cursoId', 'calendarioVisualizacao'));
            return $pdf->download('todas_turmas.pdf');
        }

        return view('relatorios.todas_turmas', 
            compact('turmas', 'unidades', 'cursos', 'unidadeId', 'cursoId', 'calendarioVisualizacao'));
    }

    public function localidadesAlunos(Request $request)
    {
        $request->validate([
            'bairro_id' => 'nullable|exists:bairros,id',
        ], [
            'bairro_id.exists' => 'O bairro selecionado é inválido.',
        ]);

        $bairroId = $request->input('bairro_id');
        
        // Obtém o calendário para visualização
        $calendarioVisualizacaoId = session('calendario_visualizacao_id');

        $query = Aluno::with(['bairro', 'cidade'])
            ->when($calendarioVisualizacaoId, function ($query, $calendarioId) {
                // Filtra alunos que têm matrícula no calendário selecionado
                return $query->whereHas('matriculas', function ($q) use ($calendarioId) {
                    $q->where('calendario_id', $calendarioId);
                });
            });

        if ($bairroId) {
            $query->where('bairro_id', $bairroId);
        }

        $alunos = $query->get();
        $bairros = Bairro::all();

        $alunosPorBairro = $alunos->groupBy('bairro.nome')->map->count();
        
        // Obtém o objeto do calendário sendo visualizado
        $calendarioVisualizacao = Calendario::find($calendarioVisualizacaoId);

        if ($request->has('download')) {
            $pdf = Pdf::loadView('relatorios.pdf.localidades_alunos', 
                compact('alunos', 'bairros', 'bairroId', 'alunosPorBairro', 'calendarioVisualizacao'));
            return $pdf->download('localidades_alunos.pdf');
        }

        return view('relatorios.localidades_alunos', 
            compact('alunos', 'bairros', 'bairroId', 'alunosPorBairro', 'calendarioVisualizacao'));
    }

    public function faixaEtariaCurso(Request $request)
    {
        $request->validate([
            'curso_id' => 'nullable|exists:cursos,id',
        ], [
            'curso_id.exists' => 'O curso selecionado é inválido.',
        ]);

        $cursoId = $request->input('curso_id');
        
        // Obtém o calendário para visualização
        $calendarioVisualizacaoId = session('calendario_visualizacao_id');

        $query = Matricula::with(['aluno', 'turma.curso', 'calendario'])
            ->where('status', 'ativo')
            ->when($calendarioVisualizacaoId, function ($query, $calendarioId) {
                return $query->where('calendario_id', $calendarioId);
            });

        if ($cursoId) {
            $query->whereHas('turma', function ($q) use ($cursoId) {
                $q->where('curso_id', $cursoId);
            });
        }

        $matriculas = $query->get();
        $cursos = Curso::all();

        $faixasEtarias = [
            '0-5' => 0,
            '6-10' => 0,
            '11-15' => 0,
            '16-20' => 0,
            '21+' => 0,
        ];

        foreach ($matriculas as $matricula) {
            $idade = Carbon::parse($matricula->aluno->data_nascimento)->age;

            if ($idade <= 5) {
                $faixasEtarias['0-5']++;
            } elseif ($idade <= 10) {
                $faixasEtarias['6-10']++;
            } elseif ($idade <= 15) {
                $faixasEtarias['11-15']++;
            } elseif ($idade <= 20) {
                $faixasEtarias['16-20']++;
            } else {
                $faixasEtarias['21+']++;
            }
        }
        
        // Obtém o objeto do calendário sendo visualizado
        $calendarioVisualizacao = Calendario::find($calendarioVisualizacaoId);

        if ($request->has('download')) {
            $pdf = Pdf::loadView('relatorios.pdf.faixa_etaria_curso', 
                compact('matriculas', 'cursos', 'cursoId', 'faixasEtarias', 'calendarioVisualizacao'));
            return $pdf->download('faixa_etaria_curso.pdf');
        }

        return view('relatorios.faixa_etaria_curso', 
            compact('matriculas', 'cursos', 'cursoId', 'faixasEtarias', 'calendarioVisualizacao'));
    }

    public function turmasProfessores(Request $request)
    {
        $request->validate([
            'professor_id' => 'nullable|exists:professores,id',
        ], [
            'professor_id.exists' => 'O professor selecionado é inválido.',
        ]);

        $professorId = $request->input('professor_id');
        
        // Obtém o calendário para visualização
        $calendarioVisualizacaoId = session('calendario_visualizacao_id');

        $query = Turma::with(['professor', 'curso', 'nivel', 'turno', 'calendario'])
            ->when($calendarioVisualizacaoId, function ($query, $calendarioId) {
                return $query->where('calendario_id', $calendarioId);
            });

        if ($professorId) {
            $query->where('professor_id', $professorId);
        }

        $turmas = $query->get();
        $professores = Professor::all();
        
        // Obtém o objeto do calendário sendo visualizado
        $calendarioVisualizacao = Calendario::find($calendarioVisualizacaoId);

        if ($request->has('download')) {
            $pdf = Pdf::loadView('relatorios.pdf.turmas_professores', 
                compact('turmas', 'professores', 'professorId', 'calendarioVisualizacao'));
            return $pdf->download('turmas_professores.pdf');
        }

        return view('relatorios.turmas_professores', 
            compact('turmas', 'professores', 'professorId', 'calendarioVisualizacao'));
    }

    public function professoresAtivos(Request $request)
    {
        // Obtém o calendário para visualização
        $calendarioVisualizacaoId = session('calendario_visualizacao_id');
        
        // Filtra professores que têm turmas no calendário selecionado
        $professores = Professor::when($calendarioVisualizacaoId, function ($query, $calendarioId) {
            return $query->whereHas('turmas', function ($q) use ($calendarioId) {
                $q->where('calendario_id', $calendarioId);
            });
        })->get();
        
        // Obtém o objeto do calendário sendo visualizado
        $calendarioVisualizacao = Calendario::find($calendarioVisualizacaoId);

        if ($request->has('download')) {
            $pdf = Pdf::loadView('relatorios.pdf.professores_ativos', 
                compact('professores', 'calendarioVisualizacao'));
            return $pdf->download('professores_ativos.pdf');
        }

        return view('relatorios.professores_ativos', 
            compact('professores', 'calendarioVisualizacao'));
    }

    public function quantidadeMatriculasAtivas(Request $request)
    {
        $request->validate([
            'curso_id' => 'nullable|exists:cursos,id',
            'nivel_id' => 'nullable|exists:nivels,id',
        ], [
            'curso_id.exists' => 'O curso selecionado é inválido.',
            'nivel_id.exists' => 'O nível selecionado é inválido.',
        ]);

        $cursoId = $request->input('curso_id');
        $nivelId = $request->input('nivel_id');
        
        // Obtém o calendário para visualização
        $calendarioVisualizacaoId = session('calendario_visualizacao_id');

        $query = Matricula::where('status', 'ativo')
            ->with(['turma.curso', 'turma.nivel', 'calendario'])
            ->when($calendarioVisualizacaoId, function ($query, $calendarioId) {
                return $query->where('calendario_id', $calendarioId);
            });

        if ($cursoId) {
            $query->whereHas('turma', function ($q) use ($cursoId) {
                $q->where('curso_id', $cursoId);
            });
        }

        if ($nivelId) {
            $query->whereHas('turma', function ($q) use ($nivelId) {
                $q->where('nivel_id', $nivelId);
            });
        }

        $matriculas = $query->get();
        $cursos = Curso::all();
        $niveis = Nivel::all();

        $totalMatriculas = $matriculas->count();
        $matriculasPorCurso = $matriculas->groupBy('turma.curso.nome')->map->count();
        $matriculasPorNivel = $matriculas->groupBy('turma.nivel.nome')->map->count();
        
        // Obtém o objeto do calendário sendo visualizado
        $calendarioVisualizacao = Calendario::find($calendarioVisualizacaoId);

        if ($request->has('download')) {
            $pdf = Pdf::loadView('relatorios.pdf.quantidade_matriculas_ativas', 
                compact('matriculas', 'cursos', 'niveis', 'cursoId', 'nivelId', 
                       'totalMatriculas', 'matriculasPorCurso', 'matriculasPorNivel', 'calendarioVisualizacao'));
            return $pdf->download('quantidade_matriculas_ativas.pdf');
        }

        return view('relatorios.quantidade_matriculas_ativas', 
            compact('matriculas', 'cursos', 'niveis', 'cursoId', 'nivelId', 
                   'totalMatriculas', 'matriculasPorCurso', 'matriculasPorNivel', 'calendarioVisualizacao'));
    }
    
    /**
     * Relatório específico de um calendário
     */
    public function resumoCalendario($calendarioId = null)
    {
        $calendarioId = $calendarioId ?? session('calendario_visualizacao_id');
        $calendario = Calendario::findOrFail($calendarioId);
        
        // Total de turmas no calendário
        $totalTurmas = Turma::where('calendario_id', $calendarioId)->count();
        
        // Total de matrículas ativas no calendário
        $totalMatriculas = Matricula::where('calendario_id', $calendarioId)
            ->where('status', 'ativo')
            ->count();
        
        // Matrículas por status
        $matriculasPorStatus = Matricula::where('calendario_id', $calendarioId)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
        
        // Turmas por curso
        $turmasPorCurso = Turma::where('calendario_id', $calendarioId)
            ->with('curso')
            ->get()
            ->groupBy('curso.nome')
            ->map->count();
        
        // Matrículas por turma
        $matriculasPorTurma = Matricula::where('calendario_id', $calendarioId)
            ->where('status', 'ativo')
            ->with('turma')
            ->get()
            ->groupBy('turma.nome')
            ->map->count();
        
        return view('relatorios.resumo_calendario', compact(
            'calendario',
            'totalTurmas',
            'totalMatriculas',
            'matriculasPorStatus',
            'turmasPorCurso',
            'matriculasPorTurma'
        ));
    }
}