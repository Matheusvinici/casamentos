<?php

namespace App\Http\Controllers;

use App\Models\Matricula;
use App\Models\Aluno;
use App\Models\Turma;
use App\Models\Calendario;
use App\Models\Unidade;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MatriculaController extends Controller
{
     public function index(Request $request)
{
    $search = $request->input('search_matricula');
    
    // Obtém o calendário para visualização (da sessão ou ativo)
    $calendarioVisualizacaoId = session('calendario_visualizacao_id');
    
    // SE NÃO TEM NA SESSÃO, USA O ATIVO
    if (!$calendarioVisualizacaoId) {
        $calendarioAtivo = Calendario::where('ativo', true)->first();
        
        if ($calendarioAtivo) {
            $calendarioVisualizacaoId = $calendarioAtivo->id;
            session(['calendario_visualizacao_id' => $calendarioVisualizacaoId]);
            session(['calendario_visualizacao_nome' => $calendarioAtivo->nomeCompleto]);
        }
    }
    
    // Query base com eager loading
    $query = Matricula::with([
        'aluno',
        'turma' => function($query) {
            $query->with(['curso', 'nivel', 'turno', 'unidade']);
        },
        'calendario',
        'unidade'
    ]);
    
    // Aplica filtro de calendário se existir
    if ($calendarioVisualizacaoId) {
        $query->where('calendario_id', $calendarioVisualizacaoId);
    }
    
    // Aplica busca se existir - CORRIGIDO: usa colunas existentes
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->whereHas('aluno', function($q2) use ($search) {
                $q2->where('nome', 'like', "%{$search}%")
                   ->orWhere('email', 'like', "%{$search}%")
                   ->orWhere('telefone', 'like', "%{$search}%")
                   ->orWhere('responsavel_nome', 'like', "%{$search}%")
                   ->orWhere('responsavel_cpf', 'like', "%{$search}%")
                   ->orWhere('responsavel_email', 'like', "%{$search}%");
            })->orWhereHas('turma', function($q2) use ($search) {
                $q2->where('nome', 'like', "%{$search}%")
                   ->orWhere('letra', 'like', "%{$search}%")
                   ->orWhereHas('curso', function($q3) use ($search) {
                       $q3->where('nome', 'like', "%{$search}%")
                          ->orWhere('abreviacao', 'like', "%{$search}%");
                   })
                   ->orWhereHas('nivel', function($q3) use ($search) {
                       $q3->where('nome', 'like', "%{$search}%")
                          ->orWhere('abreviacao', 'like', "%{$search}%");
                   })
                   ->orWhereHas('turno', function($q3) use ($search) {
                       $q3->where('nome', 'like', "%{$search}%")
                          ->orWhere('abreviacao', 'like', "%{$search}%");
                   });
            })->orWhereHas('unidade', function($q2) use ($search) {
                $q2->where('nome', 'like', "%{$search}%");
            });
        });
    }
    
    // Ordenação e paginação
    $matriculas = $query->orderBy('created_at', 'DESC')
                       ->orderBy('id', 'DESC')
                       ->paginate(100)
                       ->appends(['search_matricula' => $search]);
    
    // Obtém o objeto do calendário sendo visualizado
    $calendarioVisualizacao = Calendario::find($calendarioVisualizacaoId);
    
    return view('matriculas.index', compact('matriculas', 'calendarioVisualizacao'));
}
    /**
     * Exibe o formulário de criação de matrícula
     * SEMPRE usa o calendário ativo para criação
     */
    public function create()
    {
        $calendarioAtivo = Calendario::where('ativo', true)->first();
        
        if (!$calendarioAtivo) {
            return redirect()->route('Listar-Matriculas')
                ->with('error', 'Nenhum calendário ativo encontrado. Ative um calendário antes de matricular alunos.');
        }
        
        // Alunos que NÃO tem matrícula ativa no calendário ATIVO
        $alunos = Aluno::whereNotIn('id', function($query) use ($calendarioAtivo) {
            $query->select('aluno_id')
                  ->from('matriculas')
                  ->where('calendario_id', $calendarioAtivo->id)
                  ->where('status', 'ativo');
        })->get();
        
        $turmas = Turma::where('calendario_id', $calendarioAtivo->id)
            ->where('vaga', '>', 0)
            ->with(['curso', 'nivel', 'turno'])
            ->orderBy('nome')
            ->get();
            
        return view('matriculas.create', compact('alunos', 'turmas', 'calendarioAtivo'));
    }

        public function store(Request $request)
    {
        $request->validate([
            'aluno_id' => 'required|exists:alunos,id',
            'turma_id' => 'required|exists:turmas,id',
            'data_matricula' => 'required|date',
            'status' => 'required|in:ativo,inativo,desistente',
        ]);

        \DB::beginTransaction();
        
        try {
            $turma = Turma::findOrFail($request->turma_id);
            
            $calendarioAtivo = Calendario::where('ativo', true)->first();

            if (!$calendarioAtivo) {
                \DB::rollBack();
                return redirect()->back()->withErrors(['calendario' => 'Nenhum calendário ativo encontrado.'])->withInput();
            }

            if ($turma->calendario_id != $calendarioAtivo->id) {
                \DB::rollBack();
                return redirect()->back()->withErrors(['turma_id' => 'Esta turma não pertence ao calendário ativo.'])->withInput();
            }

            $unidade_id = $turma->unidade_id ?? Aluno::find($request->aluno_id)->unidade_id ?? Unidade::first()->id;

            if (!$unidade_id) {
                \DB::rollBack();
                return redirect()->back()->withErrors(['geral' => 'Não foi possível determinar a unidade.'])->withInput();
            }

            if ($request->status == 'ativo') {
                $existingMatricula = Matricula::where('aluno_id', $request->aluno_id)
                    ->where('calendario_id', $calendarioAtivo->id)
                    ->where('status', 'ativo')
                    ->first();

                if ($existingMatricula) {
                    \DB::rollBack();
                    return redirect()->back()->withErrors([
                        'aluno_id' => 'O aluno já possui uma matrícula ativa no calendário atual.'
                    ])->withInput();
                }
            }

            // VERIFICA SE ATINGIU O LIMITE DE VAGAS (CAMPO vaga É FIXO)
            $activeMatriculas = Matricula::where('turma_id', $turma->id)
                ->where('calendario_id', $calendarioAtivo->id)
                ->where('status', 'ativo')
                ->count();

            if ($request->status == 'ativo' && $activeMatriculas >= $turma->vaga) {
                \DB::rollBack();
                return redirect()->back()->withErrors(['turma_id' => 'Limite de matrículas atingido para esta turma.'])->withInput();
            }

            // CRIA A MATRÍCULA
            $matricula = Matricula::create([
                'aluno_id' => $request->aluno_id,
                'turma_id' => $request->turma_id,
                'calendario_id' => $calendarioAtivo->id,
                'unidade_id' => $unidade_id,
                'data_matricula' => $request->data_matricula,
                'status' => $request->status
            ]);

            \DB::commit();

            return redirect()->route('Listar-Matriculas')->with('success', 'Matrícula realizada com sucesso!');
            
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()
                ->withErrors(['geral' => 'Erro ao processar matrícula.'])
                ->withInput();
        }
    }
    /**
     * Exibe os detalhes de uma matrícula.
     */
    public function show($id)
    {
        $matricula = Matricula::findOrFail($id);
        $matricula->load(['aluno', 'turma', 'turma.calendario']);
        
        $calendarioAtivo = Calendario::where('ativo', true)->first();
        $historicoMatriculas = Matricula::where('aluno_id', $matricula->aluno_id)
            ->with(['turma', 'turma.calendario'])
            ->orderBy('created_at', 'DESC')
            ->get();
            
        return view('matriculas.show', compact('matricula', 'historicoMatriculas', 'calendarioAtivo'));
    }

    /**
     * Exibe o formulário de edição de matrícula.
     */
    public function edit($id)
    {
        $matricula = Matricula::findOrFail($id);
        
        $calendarioAtivo = Calendario::where('ativo', true)->first();
        
        if (!$calendarioAtivo) {
            return redirect()->route('Listar-Matriculas')
                ->with('error', 'Nenhum calendário ativo encontrado.');
        }
        
        // Alunos disponíveis para matrícula (não tem matrícula ativa no calendário atual, exceto a atual)
        $alunos = Aluno::where(function($query) use ($calendarioAtivo, $matricula) {
            $query->whereNotIn('id', function($q) use ($calendarioAtivo) {
                $q->select('aluno_id')
                  ->from('matriculas')
                  ->where('status', 'ativo')
                  ->whereIn('turma_id', function($subq) use ($calendarioAtivo) {
                      $subq->select('id')
                           ->from('turmas')
                           ->where('calendario_id', $calendarioAtivo->id);
                  });
            })->orWhere('id', $matricula->aluno_id);
        })->get();
        
        $turmas = Turma::where('calendario_id', $calendarioAtivo->id)
            ->when($matricula->turma_id, function($query, $turmaId) {
                // Inclui a turma atual mesmo se não tiver vaga
                $query->orWhere('id', $turmaId);
            })
            ->with(['curso', 'nivel', 'turno'])
            ->orderBy('nome')
            ->get();
            
        return view('matriculas.edit', compact('matricula', 'alunos', 'turmas', 'calendarioAtivo'));
    }

     public function update(Request $request, $id)
    {
        $request->validate([
            'aluno_id' => 'required|exists:alunos,id',
            'turma_id' => 'required|exists:turmas,id',
            'data_matricula' => 'required|date',
            'status' => 'required|in:ativo,inativo,desistente',
        ]);

        \DB::beginTransaction();
        
        try {
            $matricula = Matricula::findOrFail($id);
            $novaTurma = Turma::findOrFail($request->turma_id);
            $oldTurma = Turma::find($matricula->turma_id);
            
            $calendarioAtivo = Calendario::where('ativo', true)->first();

            if (!$calendarioAtivo) {
                \DB::rollBack();
                return redirect()->back()->withErrors(['calendario' => 'Nenhum calendário ativo encontrado.'])->withInput();
            }

            if ($novaTurma->calendario_id != $calendarioAtivo->id) {
                \DB::rollBack();
                return redirect()->back()->withErrors(['turma_id' => 'Esta turma não pertence ao calendário ativo.'])->withInput();
            }

            $unidade_id = $novaTurma->unidade_id ?? Aluno::find($request->aluno_id)->unidade_id ?? $matricula->unidade_id;

            if (!$unidade_id) {
                \DB::rollBack();
                return redirect()->back()->withErrors(['geral' => 'Não foi possível determinar a unidade.'])->withInput();
            }

            // VERIFICA SE O ALUNO JÁ TEM OUTRA MATRÍCULA ATIVA (exceto a atual)
            if ($request->status == 'ativo') {
                $existingMatricula = Matricula::where('aluno_id', $request->aluno_id)
                    ->where('calendario_id', $calendarioAtivo->id)
                    ->where('status', 'ativo')
                    ->where('id', '!=', $id)
                    ->first();

                if ($existingMatricula) {
                    \DB::rollBack();
                    return redirect()->back()->withErrors([
                        'aluno_id' => 'Este aluno já possui uma matrícula ativa no calendário atual.'
                    ])->withInput();
                }
            }

            // VERIFICA LIMITE DE VAGAS NA NOVA TURMA (SE FOR ATIVO)
            if ($request->status == 'ativo') {
                $activeMatriculasNovaTurma = Matricula::where('turma_id', $novaTurma->id)
                    ->where('status', 'ativo')
                    ->where('id', '!=', $id)
                    ->count();

                if ($activeMatriculasNovaTurma >= $novaTurma->vaga) {
                    \DB::rollBack();
                    return redirect()->back()->withErrors(['turma_id' => 'Limite de matrículas atingido para esta turma.']);
                }
            }

            // ATUALIZA A MATRÍCULA
            $matricula->update([
                'aluno_id' => $request->aluno_id,
                'turma_id' => $request->turma_id,
                'unidade_id' => $unidade_id,
                'data_matricula' => $request->data_matricula,
                'status' => $request->status
            ]);

            \DB::commit();

            return redirect()->route('Listar-Matriculas')->with('success', 'Matrícula atualizada com sucesso!');
            
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()
                ->withErrors(['geral' => 'Erro ao atualizar matrícula.'])
                ->withInput();
        }
    }

    /**
     * Remove uma matrícula.
     */
    public function destroy($id)
    {
        $matricula = Matricula::findOrFail($id);
        $turma = $matricula->turma;
        
        if ($matricula->status == 'ativo' && $turma) {
            $turma->vaga += 1;
            $turma->save();
        }

        $matricula->delete();
        return redirect()->route('Listar-Matriculas')->with('success', 'Matrícula removida com sucesso!');
    }
    
    /**
     * Lista todas as matrículas de um aluno específico
     */
    public function matriculasPorAluno($alunoId)
    {
        $aluno = Aluno::findOrFail($alunoId);
        $calendarioAtivo = Calendario::where('ativo', true)->first();
        
        $matriculas = Matricula::where('aluno_id', $alunoId)
            ->with(['turma', 'turma.calendario'])
            ->orderBy('created_at', 'DESC')
            ->get();
            
        return view('matriculas.por-aluno', compact('aluno', 'matriculas', 'calendarioAtivo'));
    }
}