{{-- resources/views/livewire/notas/notas-index.blade.php --}}
<div class="container-fluid py-4">
    <!-- Cabeçalho -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1 text-primary">
                                <i class="fas fa-star me-2"></i>Notas dos Alunos
                            </h4>
                            <p class="text-muted mb-0">
                                Visualize as notas dos alunos por período letivo
                            </p>
                        </div>
                        <div>
                            <a href="{{ Auth::guard('professor')->check() ? route('Notas-Turmas-Professor') : route('Notas-Turmas') }}" 
                               class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-2"></i>Voltar às Turmas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtro por Calendário -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('Listar-Notas') }}" class="row g-3">
                        <div class="col-md-8">
                            <label for="calendario_id" class="form-label">Selecione o Período Letivo</label>
                            <select name="calendario_id" id="calendario_id" class="form-select" wire:model.live="calendarioAtual">
                                <option value="">-- Selecione um calendário --</option>
                                @foreach($calendarios as $calendario)
                                    <option value="{{ $calendario->id }}" 
                                        {{ $calendarioAtual && $calendarioAtual->id == $calendario->id ? 'selected' : '' }}>
                                        {{ $calendario->nomeCompleto }} 
                                        ({{ \Carbon\Carbon::parse($calendario->inicio)->format('d/m/Y') }} - 
                                         {{ \Carbon\Carbon::parse($calendario->fim)->format('d/m/Y') }})
                                        @if($calendario->ativo) <span class="badge bg-success">Ativo</span> @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Resumo das Notas -->
        @if($calendarioAtual)
        <div class="col-md-6">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <h6 class="text-primary mb-3">Resumo do Período</h6>
                    <div class="row">
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                                    <i class="fas fa-star text-primary"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Total de Pontos</small>
                                    <span class="h5 mb-0">{{ number_format($totalPontos, 1, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                                    <i class="fas fa-users text-success"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Total de Alunos</small>
                                    <span class="h5 mb-0">{{ $totalAlunos }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Busca e Filtros -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group">
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       class="form-control" 
                       placeholder="Buscar por aluno...">
                <button class="btn btn-outline-primary" type="button">
                    <i class="fas fa-search"></i>
                </button>
                @if($search)
                    <a href="{{ route('Listar-Notas') }}" class="btn btn-outline-secondary">
                        Limpar
                    </a>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <select wire:model.live="filterTurma" class="form-select">
                <option value="">Todas as Turmas</option>
                @foreach($turmas as $turma)
                    <option value="{{ $turma->id }}">{{ $turma->nome }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Lista de Notas por Aluno -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    @if($calendarioAtual)
                        @if($alunos->count() > 0 && $tiposAvaliacao->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th width="50">#</th>
                                            <th>Aluno</th>
                                            <th>Turma</th>
                                            @foreach($tiposAvaliacao as $tipo)
                                                <th class="text-center">
                                                    {{ $tipo->abreviacao ?? $tipo->nome }}
                                                    <br>
                                                    <small class="text-muted">({{ $tipo->valor_maximo }})</small>
                                                </th>
                                            @endforeach
                                            <th class="text-center">Total</th>
                                            <th class="text-center">Média</th>
                                            <th class="text-center">Status</th>
                                            <th width="100">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($alunos as $index => $aluno)
                                            @php
                                                $turmaAluno = $aluno->turmas()
                                                    ->when(Auth::guard('professor')->check(), function($q) {
                                                        $q->where('professor_id', Auth::guard('professor')->id());
                                                    })
                                                    ->when($filterTurma, function($q) {
                                                        $q->where('turmas.id', $filterTurma);
                                                    })
                                                    ->first();
                                                
                                                $totalAluno = $totaisPorAluno[$aluno->id] ?? 0;
                                                $percentual = $totalPontos > 0 ? ($totalAluno / $totalPontos) * 100 : 0;
                                                
                                                if ($percentual >= 70) {
                                                    $statusClass = 'success';
                                                    $statusText = 'Aprovado';
                                                } elseif ($percentual >= 50) {
                                                    $statusClass = 'warning';
                                                    $statusText = 'Recuperação';
                                                } else {
                                                    $statusClass = 'danger';
                                                    $statusText = 'Reprovado';
                                                }
                                            @endphp
                                            <tr>
                                                <td>{{ $alunos->firstItem() + $index }}</td>
                                                <td>
                                                    <strong>{{ $aluno->nome }}</strong>
                                                </td>
                                                <td>{{ $turmaAluno->nome ?? 'N/A' }}</td>
                                                
                                                @foreach($tiposAvaliacao as $tipo)
                                                    @php
                                                        $nota = $notasPorAluno[$aluno->id][$tipo->id] ?? null;
                                                    @endphp
                                                    <td class="text-center">
                                                        @if($nota)
                                                            <span class="badge bg-{{ $nota->status_color }} p-2">
                                                                {{ number_format($nota->valor, 1, ',', '.') }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                @endforeach
                                                
                                                <td class="text-center">
                                                    <strong>{{ number_format($totalAluno, 1, ',', '.') }}</strong>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-{{ $statusClass }} p-2">
                                                        {{ number_format($percentual, 1) }}%
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        @if($turmaAluno)
                                                            <a href="{{ route('Criar-Nota-Professor', ['turma_id' => $turmaAluno->id]) }}" 
                                                               class="btn btn-sm btn-outline-success" 
                                                               title="Lançar Notas">
                                                                <i class="fas fa-plus"></i>
                                                            </a>
                                                        @endif
                                                        <a href="{{ route('Ver-Nota-Professor', [$turmaAluno->id ?? 0, $aluno->id, $tiposAvaliacao->first()?->id ?? 0]) }}" 
                                                           class="btn btn-sm btn-outline-primary" 
                                                           title="Visualizar">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Paginação -->
                            <div class="d-flex justify-content-between align-items-center p-3 border-top">
                                <small class="text-muted">
                                    Mostrando {{ $alunos->firstItem() ?? 0 }} a {{ $alunos->lastItem() ?? 0 }} 
                                    de {{ $alunos->total() }} alunos
                                </small>
                                {{ $alunos->links() }}
                            </div>
                        @elseif($tiposAvaliacao->count() == 0)
                            <div class="text-center py-5">
                                <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                <h5>Nenhum tipo de avaliação cadastrado</h5>
                                <p class="text-muted">
                                    Para visualizar notas, é necessário cadastrar os tipos de avaliação 
                                    deste período letivo.
                                </p>
                                <a href="{{ route('Criar-Tipo-Avaliacao', ['calendario_id' => $calendarioAtual->id]) }}" 
                                   class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Novo Tipo
                                </a>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5>Nenhum aluno encontrado</h5>
                                <p class="text-muted">
                                    Não há alunos com matrículas ativas neste período letivo.
                                </p>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                            <h5>Selecione um período letivo</h5>
                            <p class="text-muted">
                                Escolha um calendário no filtro acima para visualizar as notas.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('page_scripts')
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Tem certeza?',
                text: "Esta ação não poderá ser revertida!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        // Inicializar tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
    @endpush
</div>