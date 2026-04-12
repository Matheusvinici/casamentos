{{-- resources/views/livewire/notas/notas-create-edit-show.blade.php --}}
<div class="container-fluid py-4">
    <!-- Cabeçalho -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1 text-primary">
                                @if($mode == 'create')
                                    <i class="fas fa-plus-circle me-2"></i>Lançar Notas
                                @elseif($mode == 'edit')
                                    <i class="fas fa-edit me-2"></i>Editar Nota
                                @else
                                    <i class="fas fa-eye me-2"></i>Visualizar Nota
                                @endif
                            </h4>
                            <p class="text-muted mb-0">
                                Turma: <strong>{{ $turma->nome }}</strong> | 
                                Período: <strong>{{ $turma->calendario->nomeCompleto ?? 'N/A' }}</strong>
                            </p>
                        </div>
                        <div>
                            <a href="{{ Auth::guard('professor')->check() ? route('Notas-Turmas-Professor') : route('Notas-Turmas') }}" 
                               class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-2"></i>Voltar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas -->
    @if($error_message)
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            {{ $error_message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Formulário principal -->
    <form wire:submit.prevent="save">
        <!-- Data de Lançamento -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <label for="data_lancamento" class="form-label">Data de Lançamento</label>
                        <input type="date" 
                               class="form-control @error('data_lancamento') is-invalid @enderror" 
                               id="data_lancamento" 
                               wire:model.live="data_lancamento" 
                               @if($mode == 'show') disabled @endif
                               required>
                        @error('data_lancamento') 
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Conteúdo principal baseado no modo -->
        @if($mode == 'create')
            <!-- CREATE: Tabela com todos os alunos e todas as avaliações -->
            @if($alunos->isNotEmpty() && $tiposAvaliacao->isNotEmpty())
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr class="text-center">
                                        <th>#</th>
                                        <th>Aluno</th>
                                        <th>Matrícula</th>
                                        @foreach($tiposAvaliacao as $tipo)
                                            <th>
                                                {{ $tipo->abreviacao ?? $tipo->nome }}
                                                <br>
                                                <small class="text-muted">({{ $tipo->valor_formatado }})</small>
                                            </th>
                                        @endforeach
                                        <th>Total</th>
                                        <!-- <th>Média</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($alunos as $index => $aluno)
                                        @php
                                            $matricula = \App\Models\Matricula::where('aluno_id', $aluno->id)
                                                ->where('turma_id', $turma_id)
                                                ->where('status', 'ativo')
                                                ->first();
                                            $somaNotas = 0;
                                            $somaPesos = 0;
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>
                                                <strong>{{ $aluno->nome }}</strong>
                                            </td>
                                            <td class="text-center">
                                                {{ $matricula?->id ?? 'N/A' }}
                                            </td>
                                            
                                            @foreach($tiposAvaliacao as $tipo)
                                                @php
                                                    $nota = $notas[$aluno->id][$tipo->id] ?? '';
                                                    if ($nota !== '' && $nota !== null) {
                                                        $somaNotas += $nota;
                                                        $somaPesos++;
                                                    }
                                                @endphp
                                                <td class="text-center">
                                                    <input type="number" 
                                                           step="0.1" 
                                                           min="0" 
                                                           max="{{ $tipo->valor_maximo }}"
                                                           class="form-control form-control-sm text-center" 
                                                           style="width: 80px; margin: 0 auto;"
                                                           wire:model.live="notas.{{ $aluno->id }}.{{ $tipo->id }}"
                                                           placeholder="0.0">
                                                </td>
                                            @endforeach
                                            
                                            <td class="text-center align-middle">
                                                <span class="badge bg-info p-2">
                                                    {{ number_format($somaNotas, 1, ',', '.') }}
                                                </span>
                                            </td>
                                          
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ Auth::guard('professor')->check() ? route('Notas-Turmas-Professor') : route('Notas-Turmas') }}" 
                       class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Salvar Notas
                    </button>
                </div>
            @else
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-exclamation-triangle fa-3x text-muted mb-3"></i>
                        <h5>Não é possível lançar notas</h5>
                        <p class="text-muted">
                            @if($alunos->isEmpty())
                                Nenhum aluno com matrícula ativa encontrado nesta turma.
                            @else
                                Nenhum tipo de avaliação cadastrado para este período letivo.
                            @endif
                        </p>
                        <a href="{{ Auth::guard('professor')->check() ? route('Notas-Turmas-Professor') : route('Notas-Turmas') }}" 
                           class="btn btn-primary mt-3">
                            <i class="fas fa-arrow-left me-2"></i>Voltar
                        </a>
                    </div>
                </div>
            @endif

        @elseif($mode == 'edit')
            <!-- EDIT: Formulário para editar uma nota específica -->
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Editar Nota</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="text-muted small">Aluno</label>
                                    <p class="fw-bold">{{ $aluno->nome }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Avaliação</label>
                                    <p class="fw-bold">{{ $tipoAvaliacao->nome }} ({{ $tipoAvaliacao->abreviacao ?? 'Sem abrev.' }})</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="valor" class="form-label">Nota <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" 
                                               step="0.1" 
                                               min="0" 
                                               max="{{ $tipoAvaliacao->valor_maximo }}"
                                               class="form-control @error('valor') is-invalid @enderror" 
                                               id="valor" 
                                               wire:model.live="valor"
                                               required>
                                        <span class="input-group-text">/ {{ $tipoAvaliacao->valor_maximo }}</span>
                                    </div>
                                    @error('valor') 
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status</label>
                                    <div>
                                        @if($valor !== '' && $valor !== null)
                                            @php
                                                $percentual = ($valor / $tipoAvaliacao->valor_maximo) * 100;
                                            @endphp
                                            <span class="badge {{ $percentual >= 70 ? 'bg-success' : ($percentual >= 50 ? 'bg-warning text-dark' : 'bg-danger') }} fs-6 p-2">
                                                {{ $percentual >= 70 ? 'Aprovado' : ($percentual >= 50 ? 'Recuperação' : 'Reprovado') }}
                                                ({{ number_format($percentual, 0) }}%)
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('Listar-Notas-Professor', ['turma_id' => $turma_id]) }}" 
                                   class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Atualizar Nota
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @elseif($mode == 'show')
            <!-- SHOW: Visualização detalhada da nota -->
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Detalhes da Nota</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="text-muted small">Aluno</label>
                                    <p class="fw-bold">{{ $aluno->nome }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Turma</label>
                                    <p>{{ $turma->nome }}</p>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label class="text-muted small">Avaliação</label>
                                    <p>{{ $tipoAvaliacao->nome }} ({{ $tipoAvaliacao->abreviacao ?? 'Sem abrev.' }})</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="text-muted small">Nota</label>
                                    <p>
                                        <span class="badge {{ $nota->status_color }} fs-5 p-2">
                                            {{ $nota->valor_formatado }} / {{ $tipoAvaliacao->valor_formatado }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <label class="text-muted small">Status</label>
                                    <p>
                                        <span class="badge bg-{{ $nota->status_color }} fs-6 p-2">
                                            {{ $nota->status }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label class="text-muted small">Data de Lançamento</label>
                                    <p>{{ $nota->data_lancamento_formatada }}</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="text-muted small">Lançado por</label>
                                    <p>{{ $nota->professor->nome ?? 'Sistema' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="text-muted small">Cadastrado em</label>
                                    <p>{{ $nota->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('Listar-Notas-Professor', ['turma_id' => $turma_id]) }}" 
                                   class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Voltar
                                </a>
                                <div>
                                    <a href="{{ route('Editar-Nota-Professor', [$turma_id, $aluno_id, $tipo_avaliacao_id]) }}" 
                                       class="btn btn-warning me-2">
                                        <i class="fas fa-edit me-2"></i>Editar
                                    </a>
                                    <button onclick="confirmDelete({{ $nota->id }})" class="btn btn-danger">
                                        <i class="fas fa-trash me-2"></i>Excluir
                                    </button>
                                </div>
                            </div>

                            <form id="delete-form-{{ $nota->id }}" 
                                  action="{{ route('Deletar-Nota-Professor', $nota->id) }}" 
                                  method="POST" 
                                  style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </form>
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
</script>
@endpush