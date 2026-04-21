<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthProfessor;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\PaisController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AlunoController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\NivelController;
use App\Http\Controllers\TurmaController;
use App\Http\Controllers\TurnoController;
use App\Http\Controllers\BairroController;
use App\Http\Controllers\CidadeController;
use App\Http\Controllers\EstadoController;
use App\Http\Controllers\LetivoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UnidadeController;
use App\Http\Controllers\DistritoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\MatriculaController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\CalendarioController;
use App\Http\Controllers\FrequenciaController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\ResponsavelController;
use App\Http\Controllers\TipoAvaliacaoController;
use App\Http\Controllers\DashboardProfessorController;
use App\Http\Controllers\UnidadeVisualizacaoController;
use App\Http\Controllers\CalendarioVisualizacaoController;
use App\Http\Controllers\CalendarioTrocaController;
use App\Http\Controllers\ConteudoMinistradoController;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PresenteController;
use App\Http\Controllers\ConfirmacaoController;
use App\Http\Controllers\AdminCasamentoController;

Route::get('/', function () {
    $comprados = \App\Models\PresenteComprado::pluck('presente_id')->toArray();
    $presentes = \App\Http\Controllers\PresenteController::getPresentes();
    $minhasConfirmacoes = collect();
    if (\Illuminate\Support\Facades\Auth::check()) {
        $minhasConfirmacoes = \App\Models\ConfirmacaoPresenca::where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->where('status', 'confirmado')
            ->orderBy('created_at', 'asc')
            ->get();
    }
    return view('welcome', compact('comprados', 'presentes', 'minhasConfirmacoes'));
})->name('welcome');

Route::middleware(['auth'])->group(function () {
    Route::get('/presente/{id}', [PresenteController::class, 'show'])->name('presente.show');
    Route::post('/presente/{id}/bloquear', [PresenteController::class, 'bloquear'])->name('presente.bloquear');
    Route::post('/presente/{id}/comprovante', [PresenteController::class, 'uploadComprovante'])->name('presente.comprovante');
    Route::get('/meus-presentes', [PresenteController::class, 'meusPresentes'])->name('presentes.meus');

    // Confirmação de Presença
    Route::get('/confirmacao', [ConfirmacaoController::class, 'index'])->name('confirmacao.index');
    Route::post('/confirmacao', [ConfirmacaoController::class, 'store'])->name('confirmacao.store');
    Route::put('/confirmacao/{id}/desistir', [ConfirmacaoController::class, 'desistir'])->name('confirmacao.desistir');
});

// Admin Route
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/admin/casamento/dashboard', [AdminCasamentoController::class, 'dashboard'])->name('admin.casamento.dashboard');
    Route::delete('/admin/casamento/presente/{id}/desbloquear', [AdminCasamentoController::class, 'desbloquear'])->name('admin.casamento.presente.desbloquear');
    Route::post('/admin/casamento/confirmacao', [AdminCasamentoController::class, 'adicionarConfirmacao'])->name('admin.casamento.confirmacao.store');
    Route::put('/admin/casamento/confirmacao/{id}', [AdminCasamentoController::class, 'editarConfirmacao'])->name('admin.casamento.confirmacao.update');
    Route::get('/admin/casamento/relatorio-confirmacoes', [AdminCasamentoController::class, 'gerarRelatorioConfirmacoesPdf'])->name('admin.casamento.relatorio.confirmacoes');
    Route::get('/admin/casamento/relatorio-presentes', [AdminCasamentoController::class, 'gerarRelatorioPresentesPdf'])->name('admin.casamento.relatorio.presentes');
});

Auth::routes([
    'verify' => false,
    'reset' => false,
    'confirm' => false,
    'register' => true,
    'login' => true,
]);

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // Usuários
    Route::prefix('usuarios')->middleware('permission:Listar-Usuarios')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('Listar-Usuarios');
        Route::get('/search', [UserController::class, 'search'])->name('Search-Usuario')->middleware('permission:Search-Usuario');
        Route::get('/create', [UserController::class, 'create'])->name('Criar-Usuario')->middleware('permission:Criar-Usuario');
        Route::post('/store', [UserController::class, 'store'])->name('Gravar-Usuario')->middleware('permission:Gravar-Usuario');
        Route::get('/show/{id}', [UserController::class, 'show'])->name('Ver-Usuario')->middleware('permission:Ver-Usuario');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('Atualizar-Usuario')->middleware('permission:Atualizar-Usuario');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('Editar-Usuario')->middleware('permission:Editar-Usuario');
        Route::delete('/destroy/{id}', [UserController::class, 'destroy'])->name('Deletar-Usuario')->middleware('permission:Deletar-Usuario');
    });

    // Papéis
    Route::prefix('papeis')->middleware('permission:Listar-Papeis')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('Listar-Papeis');
        Route::get('/search', [RoleController::class, 'search'])->name('Search-Papel')->middleware('permission:Search-Papel');
        Route::get('/create', [RoleController::class, 'create'])->name('Criar-Papel')->middleware('permission:Criar-Papel');
        Route::post('/store', [RoleController::class, 'store'])->name('Gravar-Papel')->middleware('permission:Gravar-Papel');
        Route::get('/show/{id}', [RoleController::class, 'show'])->name('Ver-Papel')->middleware('permission:Ver-Papel');
        Route::put('/update/{id}', [RoleController::class, 'update'])->name('Atualizar-Papel')->middleware('permission:Atualizar-Papel');
        Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('Editar-Papel')->middleware('permission:Editar-Papel');
        Route::delete('/destroy/{id}', [RoleController::class, 'destroy'])->name('Deletar-Papel')->middleware('permission:Deletar-Papel');
        Route::get('/clone/{id}', [RoleController::class, 'clone'])->name('Clonar-Papel')->middleware('permission:Clonar-Papel');
        Route::get('/listar-papeis-usuarios', [RoleController::class, 'listar_papeis_usuarios'])->name('Listar-Papeis-Usuarios')->middleware('permission:Listar-Papeis-Usuarios');
        Route::post('/revogar-papel', [RoleController::class, 'revogarPapel'])->name('Revogar-Papel')->middleware('permission:Revogar-Papel');
        Route::get('/copy-permissions', [RoleController::class, 'showCopyPermissionsForm'])->name('Mostrar-Copiar-Permissoes')->middleware('permission:Mostrar-Copiar-Permissoes');
        Route::post('/copy-permissions', [RoleController::class, 'copyPermissions'])->name('Copiar-Permissoes')->middleware('permission:Copiar-Permissoes');
    });

    // Permissões
    Route::prefix('permissoes')->middleware('permission:permissions.index')->group(function () {
        Route::get('/', [PermissionsController::class, 'index'])->name('permissions.index');
        Route::get('/create', [PermissionsController::class, 'create'])->name('permissions.create')->middleware('permission:permissions.create');
        Route::post('/store', [PermissionsController::class, 'store'])->name('permissions.store')->middleware('permission:permissions.store');
        Route::get('/show/{permission}', [PermissionsController::class, 'show'])->name('permissions.show')->middleware('permission:permissions.show');
        Route::get('/edit/{permission}', [PermissionsController::class, 'edit'])->name('permissions.edit')->middleware('permission:permissions.edit');
        Route::put('/update/{permission}', [PermissionsController::class, 'update'])->name('permissions.update')->middleware('permission:permissions.update');
        Route::delete('/destroy/{permission}', [PermissionsController::class, 'destroy'])->name('permissions.destroy')->middleware('permission:permissions.destroy');
    });

    // Alunos
    Route::prefix('alunos')->middleware('permission:Listar-Alunos')->group(function () {
        Route::get('/', [AlunoController::class, 'index'])->name('Listar-Alunos');
        Route::get('/biometria', \App\Livewire\AlunoFotoCaptura::class)->name('Alunos-Biometria');
        Route::get('/search', [AlunoController::class, 'search'])->name('Search-Aluno')->middleware('permission:Search-Aluno');
        Route::get('/create', [AlunoController::class, 'create'])->name('Criar-Aluno')->middleware('permission:Criar-Aluno');
        Route::post('/store', [AlunoController::class, 'store'])->name('Gravar-Aluno')->middleware('permission:Gravar-Aluno');
        Route::get('/show/{id}', [AlunoController::class, 'show'])->name('Ver-Aluno')->middleware('permission:Ver-Aluno');
        Route::put('/update/{id}', [AlunoController::class, 'update'])->name('Atualizar-Aluno')->middleware('permission:Atualizar-Aluno');
        Route::get('/edit/{id}', [AlunoController::class, 'edit'])->name('Editar-Aluno')->middleware('permission:Editar-Aluno');
        Route::delete('/destroy/{id}', [AlunoController::class, 'destroy'])->name('Deletar-Aluno')->middleware('permission:Deletar-Aluno');
    });

    // Responsáveis
    Route::prefix('responsaveis')->middleware('permission:Listar-Responsaveis')->group(function () {
        Route::get('/', [ResponsavelController::class, 'index'])->name('Listar-Responsaveis');
        Route::get('/search', [ResponsavelController::class, 'search'])->name('Search-Responsavel')->middleware('permission:Search-Responsavel');
        Route::get('/create', [ResponsavelController::class, 'create'])->name('Criar-Responsavel')->middleware('permission:Criar-Responsavel');
        Route::post('/store', [ResponsavelController::class, 'store'])->name('Gravar-Responsavel')->middleware('permission:Gravar-Responsavel');
        Route::get('/show/{id}', [ResponsavelController::class, 'show'])->name('Ver-Responsavel')->middleware('permission:Ver-Responsavel');
        Route::put('/update/{id}', [ResponsavelController::class, 'update'])->name('Atualizar-Responsavel')->middleware('permission:Atualizar-Responsavel');
        Route::get('/edit/{id}', [ResponsavelController::class, 'edit'])->name('Editar-Responsavel')->middleware('permission:Editar-Responsavel');
        Route::delete('/destroy/{id}', [ResponsavelController::class, 'destroy'])->name('Deletar-Responsavel')->middleware('permission:Deletar-Responsavel');
    });

    // Localidades
    Route::prefix('bairros')->middleware('permission:Listar-Bairros')->group(function () {
        Route::get('/', [BairroController::class, 'index'])->name('Listar-Bairros');
        Route::get('/search', [BairroController::class, 'search'])->name('Search-Bairro')->middleware('permission:Search-Bairro');
        Route::get('/create', [BairroController::class, 'create'])->name('Criar-Bairro')->middleware('permission:Criar-Bairro');
        Route::post('/store', [BairroController::class, 'store'])->name('Gravar-Bairro')->middleware('permission:Gravar-Bairro');
        Route::get('/show/{id}', [BairroController::class, 'show'])->name('Ver-Bairro')->middleware('permission:Ver-Bairro');
        Route::put('/update/{id}', [BairroController::class, 'update'])->name('Atualizar-Bairro')->middleware('permission:Atualizar-Bairro');
        Route::get('/edit/{id}', [BairroController::class, 'edit'])->name('Editar-Bairro')->middleware('permission:Editar-Bairro');
        Route::delete('/destroy/{id}', [BairroController::class, 'destroy'])->name('Deletar-Bairro')->middleware('permission:Deletar-Bairro');
    });

    Route::prefix('cidades')->middleware('permission:Listar-Cidades')->group(function () {
        Route::get('/', [CidadeController::class, 'index'])->name('Listar-Cidades');
        Route::get('/search', [CidadeController::class, 'search'])->name('Search-Cidade')->middleware('permission:Search-Cidade');
        Route::get('/create', [CidadeController::class, 'create'])->name('Criar-Cidade')->middleware('permission:Criar-Cidade');
        Route::post('/store', [CidadeController::class, 'store'])->name('Gravar-Cidade')->middleware('permission:Gravar-Cidade');
        Route::get('/show/{id}', [CidadeController::class, 'show'])->name('Ver-Cidade')->middleware('permission:Ver-Cidade');
        Route::put('/update/{id}', [CidadeController::class, 'update'])->name('Atualizar-Cidade')->middleware('permission:Atualizar-Cidade');
        Route::get('/edit/{id}', [CidadeController::class, 'edit'])->name('Editar-Cidade')->middleware('permission:Editar-Cidade');
        Route::delete('/destroy/{id}', [CidadeController::class, 'destroy'])->name('Deletar-Cidade')->middleware('permission:Deletar-Cidade');
    });

    Route::prefix('distritos')->middleware('permission:Listar-Distritos')->group(function () {
        Route::get('/', [DistritoController::class, 'index'])->name('Listar-Distritos');
        Route::get('/search', [DistritoController::class, 'search'])->name('Search-Distrito')->middleware('permission:Search-Distrito');
        Route::get('/create', [DistritoController::class, 'create'])->name('Criar-Distrito')->middleware('permission:Criar-Distrito');
        Route::post('/store', [DistritoController::class, 'store'])->name('Gravar-Distrito')->middleware('permission:Gravar-Distrito');
        Route::get('/show/{id}', [DistritoController::class, 'show'])->name('Ver-Distrito')->middleware('permission:Ver-Distrito');
        Route::put('/update/{id}', [DistritoController::class, 'update'])->name('Atualizar-Distrito')->middleware('permission:Atualizar-Distrito');
        Route::get('/edit/{id}', [DistritoController::class, 'edit'])->name('Editar-Distrito')->middleware('permission:Editar-Distrito');
        Route::delete('/destroy/{id}', [DistritoController::class, 'destroy'])->name('Deletar-Distrito')->middleware('permission:Deletar-Distrito');
    });

    Route::prefix('estados')->middleware('permission:Listar-Estados')->group(function () {
        Route::get('/', [EstadoController::class, 'index'])->name('Listar-Estados');
        Route::get('/search', [EstadoController::class, 'search'])->name('Search-Estado')->middleware('permission:Search-Estado');
        Route::get('/create', [EstadoController::class, 'create'])->name('Criar-Estado')->middleware('permission:Criar-Estado');
        Route::post('/store', [EstadoController::class, 'store'])->name('Gravar-Estado')->middleware('permission:Gravar-Estado');
        Route::get('/show/{id}', [EstadoController::class, 'show'])->name('Ver-Estado')->middleware('permission:Ver-Estado');
        Route::put('/update/{id}', [EstadoController::class, 'update'])->name('Atualizar-Estado')->middleware('permission:Atualizar-Estado');
        Route::get('/edit/{id}', [EstadoController::class, 'edit'])->name('Editar-Estado')->middleware('permission:Editar-Estado');
        Route::delete('/destroy/{id}', [EstadoController::class, 'destroy'])->name('Deletar-Estado')->middleware('permission:Deletar-Estado');
    });

    Route::prefix('paises')->middleware('permission:Listar-Paises')->group(function () {
        Route::get('/', [PaisController::class, 'index'])->name('Listar-Paises');
        Route::get('/search', [PaisController::class, 'search'])->name('Search-Pais')->middleware('permission:Search-Pais');
        Route::get('/create', [PaisController::class, 'create'])->name('Criar-Pais')->middleware('permission:Criar-Pais');
        Route::post('/store', [PaisController::class, 'store'])->name('Gravar-Pais')->middleware('permission:Gravar-Pais');
        Route::get('/show/{id}', [PaisController::class, 'show'])->name('Ver-Pais')->middleware('permission:Ver-Pais');
        Route::put('/update/{id}', [PaisController::class, 'update'])->name('Atualizar-Pais')->middleware('permission:Atualizar-Pais');
        Route::get('/edit/{id}', [PaisController::class, 'edit'])->name('Editar-Pais')->middleware('permission:Editar-Pais');
        Route::delete('/destroy/{id}', [PaisController::class, 'destroy'])->name('Deletar-Pais')->middleware('permission:Deletar-Pais');
    });

    Route::prefix('calendarios')->middleware('permission:Listar-Calendarios')->group(function () {
        Route::get('/', [CalendarioController::class, 'index'])->name('Listar-Calendarios');
        Route::get('/search', [CalendarioController::class, 'search'])->name('Search-Calendario')->middleware('permission:Search-Calendario');
        Route::get('/create', [CalendarioController::class, 'create'])->name('Criar-Calendario')->middleware('permission:Criar-Calendario');
        Route::post('/store', [CalendarioController::class, 'store'])->name('Gravar-Calendario')->middleware('permission:Gravar-Calendario');
        Route::get('/show/{id}', [CalendarioController::class, 'show'])->name('Ver-Calendario')->middleware('permission:Ver-Calendario');
        Route::get('/edit/{id}', [CalendarioController::class, 'edit'])->name('Editar-Calendario')->middleware('permission:Editar-Calendario');
        Route::put('/update/{id}', [CalendarioController::class, 'update'])->name('Atualizar-Calendario')->middleware('permission:Atualizar-Calendario');
        Route::delete('/destroy/{id}', [CalendarioController::class, 'destroy'])->name('Deletar-Calendario')->middleware('permission:Deletar-Calendario');
        Route::patch('/toggle-active/{id}', [CalendarioController::class, 'toggleActive'])->name('Toggle-Calendario-Active')->middleware('permission:Editar-Calendario');
    });

    Route::prefix('unidades')->group(function () {
        Route::post('/store', [UnidadeController::class, 'store'])->name('Gravar-Unidade')->middleware('permission:Gravar-Unidade');
        Route::put('/update/{id}', [UnidadeController::class, 'update'])->name('Atualizar-Unidade')->middleware('permission:Atualizar-Unidade');
        Route::delete('/destroy/{id}', [UnidadeController::class, 'destroy'])->name('Deletar-Unidade')->middleware('permission:Deletar-Unidade');
    });

    Route::prefix('categorias')->middleware('permission:Listar-Categorias')->group(function () {
        Route::get('/', [CategoriaController::class, 'index'])->name('Listar-Categorias');
        Route::get('/search', [CategoriaController::class, 'search'])->name('Search-Categoria')->middleware('permission:Search-Categoria');
        Route::get('/create', [CategoriaController::class, 'create'])->name('Criar-Categoria')->middleware('permission:Criar-Categoria');
        Route::post('/store', [CategoriaController::class, 'store'])->name('Gravar-Categoria')->middleware('permission:Gravar-Categoria');
        Route::get('/show/{id}', [CategoriaController::class, 'show'])->name('Ver-Categoria')->middleware('permission:Ver-Categoria');
        Route::put('/update/{id}', [CategoriaController::class, 'update'])->name('Atualizar-Categoria')->middleware('permission:Atualizar-Categoria');
        Route::get('/edit/{id}', [CategoriaController::class, 'edit'])->name('Editar-Categoria')->middleware('permission:Editar-Categoria');
        Route::delete('/destroy/{id}', [CategoriaController::class, 'destroy'])->name('Deletar-Categoria')->middleware('permission:Deletar-Categoria');
    });

    Route::prefix('cursos')->middleware('permission:Listar-Cursos')->group(function () {
        Route::get('/', [CursoController::class, 'index'])->name('Listar-Cursos');
        Route::get('/search', [CursoController::class, 'search'])->name('Search-Curso')->middleware('permission:Search-Curso');
        Route::get('/create', [CursoController::class, 'create'])->name('Criar-Curso')->middleware('permission:Criar-Curso');
        Route::post('/store', [CursoController::class, 'store'])->name('Gravar-Curso')->middleware('permission:Gravar-Curso');
        Route::get('/show/{id}', [CursoController::class, 'show'])->name('Ver-Curso')->middleware('permission:Ver-Curso');
        Route::put('/update/{id}', [CursoController::class, 'update'])->name('Atualizar-Curso')->middleware('permission:Atualizar-Curso');
        Route::get('/edit/{id}', [CursoController::class, 'edit'])->name('Editar-Curso')->middleware('permission:Editar-Curso');
        Route::delete('/destroy/{id}', [CursoController::class, 'destroy'])->name('Deletar-Curso')->middleware('permission:Deletar-Curso');
    });

    Route::prefix('niveis')->middleware('permission:Listar-Niveis')->group(function () {
        Route::get('/', [NivelController::class, 'index'])->name('Listar-Niveis');
        Route::get('/search', [NivelController::class, 'search'])->name('Search-Nivel')->middleware('permission:Search-Nivel');
        Route::get('/create', [NivelController::class, 'create'])->name('Criar-Nivel')->middleware('permission:Criar-Nivel');
        Route::post('/store', [NivelController::class, 'store'])->name('Gravar-Nivel')->middleware('permission:Gravar-Nivel');
        Route::get('/show/{id}', [NivelController::class, 'show'])->name('Ver-Nivel')->middleware('permission:Ver-Nivel');
        Route::put('/update/{id}', [NivelController::class, 'update'])->name('Atualizar-Nivel')->middleware('permission:Atualizar-Nivel');
        Route::get('/edit/{id}', [NivelController::class, 'edit'])->name('Editar-Nivel')->middleware('permission:Editar-Nivel');
        Route::delete('/destroy/{id}', [NivelController::class, 'destroy'])->name('Deletar-Nivel')->middleware('permission:Deletar-Nivel');
    });

    Route::prefix('tipo-avaliacoes')->middleware('permission:Listar-Tipo-Avaliacoes')->group(function () {
        Route::get('/', [TipoAvaliacaoController::class, 'index'])->name('Listar-Tipo-Avaliacoes');
        Route::get('/search', [TipoAvaliacaoController::class, 'search'])->name('Search-Tipo-Avaliacao')->middleware('permission:Search-Tipo-Avaliacao');
        Route::get('/create', [TipoAvaliacaoController::class, 'create'])->name('Criar-Tipo-Avaliacao')->middleware('permission:Criar-Tipo-Avaliacao');
        Route::post('/store', [TipoAvaliacaoController::class, 'store'])->name('Gravar-Tipo-Avaliacao')->middleware('permission:Gravar-Tipo-Avaliacao');
        Route::get('/show/{id}', [TipoAvaliacaoController::class, 'show'])->name('Ver-Tipo-Avaliacao')->middleware('permission:Ver-Tipo-Avaliacao');
        Route::put('/update/{id}', [TipoAvaliacaoController::class, 'update'])->name('Atualizar-Tipo-Avaliacao')->middleware('permission:Atualizar-Tipo-Avaliacao');
        Route::get('/edit/{id}', [TipoAvaliacaoController::class, 'edit'])->name('Editar-Tipo-Avaliacao')->middleware('permission:Editar-Tipo-Avaliacao');
        Route::delete('/destroy/{id}', [TipoAvaliacaoController::class, 'destroy'])->name('Deletar-Tipo-Avaliacao')->middleware('permission:Deletar-Tipo-Avaliacao');
    });

    Route::prefix('turnos')->middleware('permission:Listar-Turnos')->group(function () {
        Route::get('/', [TurnoController::class, 'index'])->name('Listar-Turnos');
        Route::get('/search', [TurnoController::class, 'search'])->name('Search-Turno')->middleware('permission:Search-Turno');
        Route::get('/create', [TurnoController::class, 'create'])->name('Criar-Turno')->middleware('permission:Criar-Turno');
        Route::post('/store', [TurnoController::class, 'store'])->name('Gravar-Turno')->middleware('permission:Gravar-Turno');
        Route::get('/show/{id}', [TurnoController::class, 'show'])->name('Ver-Turno')->middleware('permission:Ver-Turno');
        Route::put('/update/{id}', [TurnoController::class, 'update'])->name('Atualizar-Turno')->middleware('permission:Atualizar-Turno');
        Route::get('/edit/{id}', [TurnoController::class, 'edit'])->name('Editar-Turno')->middleware('permission:Editar-Turno');
        Route::delete('/destroy/{id}', [TurnoController::class, 'destroy'])->name('Deletar-Turno')->middleware('permission:Deletar-Turno');
    });

    Route::prefix('turmas')->middleware('permission:Listar-Turmas')->group(function () {
        Route::get('/', [TurmaController::class, 'index'])->name('Listar-Turmas');
        Route::get('/search', [TurmaController::class, 'search'])->name('Search-Turma')->middleware('permission:Search-Turma');
        Route::get('/create', [TurmaController::class, 'create'])->name('Criar-Turma')->middleware('permission:Criar-Turma');
        Route::post('/store', [TurmaController::class, 'store'])->name('Gravar-Turma')->middleware('permission:Gravar-Turma');
        Route::get('/show/{id}', [TurmaController::class, 'show'])->name('Ver-Turma')->middleware('permission:Ver-Turma');
        Route::put('/update/{id}', [TurmaController::class, 'update'])->name('Atualizar-Turma')->middleware('permission:Atualizar-Turma');
        Route::get('/edit/{id}', [TurmaController::class, 'edit'])->name('Editar-Turma')->middleware('permission:Editar-Turma');
        Route::delete('/destroy/{id}', [TurmaController::class, 'destroy'])->name('Deletar-Turma')->middleware('permission:Deletar-Turma');
    });

        Route::middleware(['auth'])->group(function () {
        Route::post('/calendario/trocar', [CalendarioTrocaController::class, 'trocar'])
            ->name('calendario.trocar');
        
        Route::get('/calendario/ativo', [CalendarioTrocaController::class, 'getAtivo'])
            ->name('calendario.ativo');

            
    });

    Route::middleware(['auth'])->group(function () {
    Route::post('/calendario/visualizar', [CalendarioVisualizacaoController::class, 'visualizar'])
        ->name('calendario.visualizar');
    
            Route::get('/calendario/visualizacao-atual', [CalendarioVisualizacaoController::class, 'getVisualizacaoAtual'])
                ->name('calendario.visualizacao.atual');
        });

        // Rotas para visualização

            
        Route::post('/unidade/visualizar', [UnidadeVisualizacaoController::class, 'visualizar'])
            ->name('unidade.visualizar');
            Route::post('/calendarios/{calendarioId}/unidades/{unidadeId}/ativar', 
    [CalendarioController::class, 'ativarUnidade'])
    ->name('calendario.ativar-unidade');

    Route::prefix('matriculas')->middleware('permission:Listar-Matriculas')->group(function () {
        Route::get('/', [MatriculaController::class, 'index'])->name('Listar-Matriculas');
        Route::get('/search', [MatriculaController::class, 'search'])->name('Search-Matricula')->middleware('permission:Search-Matricula');
        Route::get('/create', [MatriculaController::class, 'create'])->name('Criar-Matricula')->middleware('permission:Criar-Matricula');
        Route::post('/store', [MatriculaController::class, 'store'])->name('Gravar-Matricula')->middleware('permission:Gravar-Matricula');
        Route::get('/show/{id}', [MatriculaController::class, 'show'])->name('Ver-Matricula')->middleware('permission:Ver-Matricula');
        Route::put('/update/{id}', [MatriculaController::class, 'update'])->name('Atualizar-Matricula')->middleware('permission:Atualizar-Matricula');
        Route::get('/edit/{id}', [MatriculaController::class, 'edit'])->name('Editar-Matricula')->middleware('permission:Editar-Matricula');
        Route::delete('/destroy/{id}', [MatriculaController::class, 'destroy'])->name('Deletar-Matricula')->middleware('permission:Deletar-Matricula');
    });

    Route::prefix('notas')->middleware('permission:Listar-Notas')->group(function () {
        Route::get('/', [NotaController::class, 'index'])->name('Listar-Notas');
        Route::get('/search', [NotaController::class, 'search'])->name('Search-Nota')->middleware('permission:Search-Nota');
        Route::get('/create', [NotaController::class, 'create'])->name('Criar-Nota')->middleware('permission:Criar-Nota');
        Route::post('/store', [NotaController::class, 'store'])->name('Gravar-Nota')->middleware('permission:Gravar-Nota');
        Route::get('/show/{id}', [NotaController::class, 'show'])->name('Ver-Nota')->middleware('permission:Ver-Nota');
        Route::put('/update/{id}', [NotaController::class, 'update'])->name('Atualizar-Nota')->middleware('permission:Atualizar-Nota');
        Route::get('/edit/{id}', [NotaController::class, 'edit'])->name('Editar-Nota')->middleware('permission:Editar-Nota');
        Route::delete('/destroy/{id}', [NotaController::class, 'destroy'])->name('Deletar-Nota')->middleware('permission:Deletar-Nota');
    });

    // Frequências administradas por usuários com permissão administrativa
    Route::prefix('frequencias')->middleware('permission:Listar-Frequencias')->group(function () {
        Route::get('/', [FrequenciaController::class, 'index'])->name('Listar-Frequencias');
        Route::get('/search', [FrequenciaController::class, 'search'])->name('Search-Frequencia')->middleware('permission:Search-Frequencia');
        Route::get('/create', [FrequenciaController::class, 'create'])->name('Criar-Frequencia')->middleware('permission:Criar-Frequencia');
        Route::post('/store', [FrequenciaController::class, 'store'])->name('Gravar-Frequencia')->middleware('permission:Gravar-Frequencia');
        Route::get('/show/{aulas_id}/{turma_id}', [FrequenciaController::class, 'show'])->name('Ver-Frequencia')->middleware('permission:Ver-Frequencia');
        Route::put('/update/{frequencia}', [FrequenciaController::class, 'update'])->name('Atualizar-Frequencia')->middleware('permission:Atualizar-Frequencia');
        Route::get('/edit/{aulas_id}/{turma_id}', [FrequenciaController::class, 'edit'])->name('Editar-Frequencia')->middleware('permission:Editar-Frequencia');
        Route::delete('/destroy/{frequencia}', [FrequenciaController::class, 'destroy'])->name('Deletar-Frequencia')->middleware('permission:Deletar-Frequencia');
    });
    Route::prefix('conteudos')->middleware('permission:Listar-Conteudos')->group(function () {
        Route::get('/conteudos', [ConteudoMinistradoController::class, 'index'])->name('Listar-Conteudos');
        Route::get('/conteudos/create', [ConteudoMinistradoController::class, 'create'])->name('Criar-Conteudo');
        Route::get('/conteudos/{aulas_id}/{turma_id}/edit', [ConteudoMinistradoController::class, 'edit'])->name('Editar-Conteudo');
        Route::get('/conteudos/{aulas_id}/{turma_id}', [ConteudoMinistradoController::class, 'show'])->name('Ver-Conteudo');
    });


    Route::prefix('professores')->middleware('permission:Listar-Professores')->group(function () {
        Route::get('/', [ProfessorController::class, 'index'])->name('Listar-Professores');
        Route::get('/search', [ProfessorController::class, 'search'])->name('Search-Professor')->middleware('permission:Search-Professor');
        Route::get('/create', [ProfessorController::class, 'create'])->name('Criar-Professor')->middleware('permission:Criar-Professor');
        Route::post('/store', [ProfessorController::class, 'store'])->name('Gravar-Professor')->middleware('permission:Gravar-Professor');
        Route::get('/show/{id}', [ProfessorController::class, 'show'])->name('Ver-Professor')->middleware('permission:Ver-Professor');
        Route::put('/update/{id}', [ProfessorController::class, 'update'])->name('Atualizar-Professor')->middleware('permission:Atualizar-Professor');
        Route::get('/edit/{id}', [ProfessorController::class, 'edit'])->name('Editar-Professor')->middleware('permission:Editar-Professor');
        Route::delete('/destroy/{id}', [ProfessorController::class, 'destroy'])->name('Deletar-Professor')->middleware('permission:Deletar-Professor');
    });

    Route::prefix('relatorios')->middleware('permission:Listar-Relatorios')->group(function () {
        Route::get('/', [RelatorioController::class, 'index'])->name('Listar-Relatorios');
        Route::get('/matriculas-por-turma', [RelatorioController::class, 'matriculasPorTurma'])->name('Relatorios-Matriculas-Por-Turma')->middleware('permission:Relatorios-Matriculas-Por-Turma');
        Route::get('/matriculas-por-curso', [RelatorioController::class, 'matriculasPorCurso'])->name('Relatorios-Matriculas-Por-Curso')->middleware('permission:Relatorios-Matriculas-Por-Curso');
        Route::get('/matriculas-por-nivel', [RelatorioController::class, 'matriculasPorNivel'])->name('Relatorios-Matriculas-Por-Nivel')->middleware('permission:Relatorios-Matriculas-Por-Nivel');
        Route::get('/todas-turmas', [RelatorioController::class, 'todasTurmas'])->name('Relatorios-Todas-Turmas')->middleware('permission:Relatorios-Todas-Turmas');
        Route::get('/turmas-professores', [RelatorioController::class, 'turmasProfessores'])->name('Relatorios-Turmas-Professores')->middleware('permission:Relatorios-Turmas-Professores');
        Route::get('/localidades-alunos', [RelatorioController::class, 'localidadesAlunos'])->name('Relatorios-Localidades-Alunos')->middleware('permission:Relatorios-Localidades-Alunos');
        Route::get('/faixa-etaria-curso', [RelatorioController::class, 'faixaEtariaCurso'])->name('Relatorios-Faixa-Etaria-Curso')->middleware('permission:Relatorios-Faixa-Etaria-Curso');
        Route::get('/professores-ativos', [RelatorioController::class, 'professoresAtivos'])->name('Relatorios-Professores-Ativos')->middleware('permission:Relatorios-Professores-Ativos');
        Route::get('/quantidade-matriculas-ativas', [RelatorioController::class, 'quantidadeMatriculasAtivas'])->name('Relatorios-Quantidade-Matriculas-Ativas')->middleware('permission:Relatorios-Quantidade-Matriculas-Ativas');
        
        // NOVA ROTA: Comprovante de matrícula individual
        Route::get('/comprovante-matricula/{matriculaId}', [RelatorioController::class, 'comprovanteMatricula'])
            ->name('Relatorios-Comprovante-Matricula')
            ->middleware('permission:Relatorios-Matriculas-Por-Turma');
    });

        Route::prefix('letivos')->middleware('permission:Listar-Letivos')->group(function () {
        Route::get('/', [LetivoController::class, 'index'])->name('Listar-Letivos');
        Route::get('/create', [LetivoController::class, 'create'])->name('Criar-Letivo')->middleware('permission:Criar-Letivo');
        Route::post('/store', [LetivoController::class, 'store'])->name('Gravar-Letivo')->middleware('permission:Gravar-Letivo');
        Route::get('/show/{id}', [LetivoController::class, 'show'])->name('Ver-Letivo')->middleware('permission:Ver-Letivo');
        Route::put('/update/{id}', [LetivoController::class, 'update'])->name('Atualizar-Letivo')->middleware('permission:Atualizar-Letivo');
        Route::get('/edit/{id}', [LetivoController::class, 'edit'])->name('Editar-Letivo')->middleware('permission:Editar-Letivo');
        Route::delete('/destroy/{id}', [LetivoController::class, 'destroy'])->name('Deletar-Letivo')->middleware('permission:Deletar-Letivo');
    });

    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show')->middleware('permission:profile.show');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update')->middleware('permission:profile.update');

    Route::view('about', 'about')->name('about')->middleware('permission:about');
});

Route::middleware([AuthProfessor::class])->group(function () {
    Route::get('/professor/dashboard', [DashboardProfessorController::class, 'index'])->name('professor.dashboard');

    Route::get('/professor/turmas', [FrequenciaController::class, 'turmas'])->name('Mostrar-Turmas-Professor');
    Route::get('/professor/notas/turmas', [NotaController::class, 'turmas'])->name('Notas-Turmas-Professor');
    
    // Frequências do Professor
    Route::prefix('professor/frequencias')->group(function () {
        Route::get('/', [FrequenciaController::class, 'index'])->name('Listar-Frequencias-Professor');
        Route::get('/create', [FrequenciaController::class, 'create'])->name('Criar-Frequencia-Professor');
        Route::post('/store', [FrequenciaController::class, 'store'])->name('Gravar-Frequencia-Professor');
        Route::get('/show/{aulas_id}/{turma_id}', [FrequenciaController::class, 'show'])->name('Ver-Frequencia-Professor');
        Route::get('/edit/{aulas_id}/{turma_id}', [FrequenciaController::class, 'edit'])->name('Editar-Frequencia-Professor');
        Route::delete('/destroy/{frequencia}', [FrequenciaController::class, 'destroy'])->name('Deletar-Frequencia-Professor');
    });
    
    Route::prefix('professor/notas')->group(function () {
        Route::get('/', [NotaController::class, 'index'])->name('Listar-Notas-Professor');
        Route::get('/create', [NotaController::class, 'create'])->name('Criar-Nota-Professor');
        Route::post('/store', [NotaController::class, 'store'])->name('Gravar-Nota-Professor');
        Route::get('/show/{turma_id}/{aluno_id}/{tipo_avaliacao_id}', [NotaController::class, 'show'])->name('Ver-Nota-Professor');
        Route::get('/edit/{turma_id}/{aluno_id}/{tipo_avaliacao_id}', [NotaController::class, 'edit'])->name('Editar-Nota-Professor');
        Route::put('/update/{id}', [NotaController::class, 'update'])->name('Atualizar-Nota-Professor');
        Route::delete('/destroy/{id}', [NotaController::class, 'destroy'])->name('Deletar-Nota-Professor');
    });

     Route::prefix('professor/conteudos')->group(function () {
         Route::get('/', [ConteudoMinistradoController::class, 'index'])->name('Listar-Conteudos-Professor');
        Route::get('/create', [ConteudoMinistradoController::class, 'create'])->name('Criar-Conteudo-Professor');
        Route::get('/{turma_id}/{aulas_id}', [ConteudoMinistradoController::class, 'show'])->name('Ver-Conteudo-Professor');
        Route::get('/{turma_id}/{aulas_id}/edit', [ConteudoMinistradoController::class, 'edit'])->name('Editar-Conteudo-Professor');
        Route::put('/update/{id}', [ConteudoMinistradoController::class, 'update'])->name('Atualizar-Conteudo-Professor');
        Route::delete('/destroy/{id}', [ConteudoMinistradoController::class, 'destroy'])->name('Deletar-Conteudo-Professor');
    });
});