{{-- resources/views/livewire/conteudos/conteudos-manager.blade.php --}}
<div class="container-fluid py-4">
    <!-- Cabeçalho com modo condicional -->
    @if($mode == 'list')
        <!-- HEADER LISTAGEM -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-1 text-primary">
                                    <i class="fas fa-book-open me-2"></i>Conteúdos Ministrados
                                </h4>
                                <p class="text-muted mb-0">
                                    Gerencie os conteúdos das aulas da Escola de Idiomas de Juazeiro-BA
                                </p>
                            </div>
                            <!-- <div>
                                <button wire:click="create" class="btn btn-primary">
                                    <i class="fas fa-plus-circle me-2"></i>Novo Conteúdo
                                </button>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FILTROS -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" 
                                   wire:model.live="search" 
                                   class="form-control" 
                                   placeholder="Buscar por turma ou professor...">
                            @if($search)
                                <button wire:click="$set('search', '')" class="btn btn-outline-secondary" type="button">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-calendar-alt text-muted"></i>
                            </span>
                            <input type="date" 
                                   wire:model.live="filterData" 
                                   class="form-control" 
                                   placeholder="Filtrar por data">
                            @if($filterData)
                                <button wire:click="$set('filterData', '')" class="btn btn-outline-secondary" type="button">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABELA DE CONTEÚDOS -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center" style="width: 5%">#</th>
                                <th style="width: 15%">Turma</th>
                                <th style="width: 15%">Data</th>
                                <th style="width: 10%">Turno</th>
                                <th style="width: 15%">Professor</th>
                                <th class="text-center" style="width: 8%">Total Aulas</th>
                                <th style="width: 22%">Conteúdo</th>
                                <th class="text-center" style="width: 10%">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($conteudos as $index => $aula)
                                <tr>
                                    <td class="text-center">{{ $conteudos->firstItem() + $index }}</td>
                                    <td>
                                        <span class="fw-bold">{{ $aula->turma->nome }}</span>
                                    </td>
                                    <td>
                                        <div>{{ \Carbon\Carbon::parse($aula->dia)->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($aula->dia)->locale('pt_BR')->isoFormat('dddd') }}</small>
                                    </td>
                                    <td>{{ $aula->turno ? $aula->turno->nome : 'N/A' }}</td>
                                    <td>{{ $aula->turma->professor ? $aula->turma->professor->nome : 'N/A' }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-info">
                                            <i class="fas fa-clock me-1"></i>{{ $aula->total_aulas }} aula(s)
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 280px;" title="{{ $aula->conteudoMinistrado->conteudo ?? '' }}">
                                            <i class="fas fa-file-alt text-muted me-2"></i>
                                            {{ Str::limit($aula->conteudoMinistrado->conteudo ?? 'N/A', 80) }}
                                        </div>
                                        @if($aula->conteudoMinistrado->observacao)
                                            <small class="text-muted">
                                                <i class="fas fa-comment me-1"></i>{{ Str::limit($aula->conteudoMinistrado->observacao, 50) }}
                                            </small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button wire:click="view({{ $aula->id }})" 
                                                    class="btn btn-sm btn-outline-primary" 
                                                    title="Visualizar">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button wire:click="edit({{ $aula->id }})" 
                                                    class="btn btn-sm btn-outline-warning" 
                                                    title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button wire:click="delete({{ $aula->id }})" 
                                                    wire:confirm="Tem certeza que deseja excluir este conteúdo?" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="Excluir">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                                        <h5>Nenhum conteúdo encontrado</h5>
                                        <p class="text-muted">Clique em "Novo Conteúdo" para começar a registrar.</p>
                                        <button wire:click="create" class="btn btn-primary mt-3">
                                            <i class="fas fa-plus-circle me-2"></i>Registrar Primeiro Conteúdo
                                        </button>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($conteudos->hasPages())
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            Mostrando {{ $conteudos->firstItem() ?? 0 }} até {{ $conteudos->lastItem() ?? 0 }} de {{ $conteudos->total() }} registros
                        </small>
                        {{ $conteudos->links() }}
                    </div>
                </div>
            @endif
        </div>

    @else
        <!-- FORMULÁRIO (CREATE/EDIT/SHOW) - SEM SELECT DE TURMA -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                @if($mode == 'form' && $viewing_aula_id && $existing_conteudo && !$isEditing)
                                    <h4 class="mb-1 text-primary">
                                        <i class="fas fa-eye me-2"></i>Visualizar Conteúdo Ministrado
                                    </h4>
                                @elseif($mode == 'form' && $existing_conteudo)
                                    <h4 class="mb-1 text-warning">
                                        <i class="fas fa-edit me-2"></i>Editar Conteúdo Ministrado
                                    </h4>
                                @else
                                    <h4 class="mb-1 text-success">
                                        <i class="fas fa-plus-circle me-2"></i>Registrar Conteúdo Ministrado
                                    </h4>
                                @endif
                                <p class="text-muted mb-0">
                                    @if($turma)
                                        Turma: <strong>{{ $turma->nome }}</strong>
                                        @if($turma->professor)
                                            | Professor: <strong>{{ $turma->professor->nome }}</strong>
                                        @endif
                                    @endif
                                </p>
                            </div>
                            <div>
                                <button wire:click="backToList" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Voltar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertas -->
        @if($error_message)
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ $error_message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form wire:submit.prevent="save">
            <!-- Informações da Turma e Data -->
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="card-title text-primary mb-3">
                                <i class="fas fa-chalkboard-user me-2"></i>Informações da Turma
                            </h6>
                            @if($turma)
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label class="text-muted small">Turma</label>
                                        <p class="fw-bold mb-0">{{ $turma->nome }}</p>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="text-muted small">Professor</label>
                                        <p class="mb-0">{{ $turma->professor->nome ?? 'Não atribuído' }}</p>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="text-muted small">Curso</label>
                                        <p class="mb-0">{{ $turma->curso->nome ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="text-muted small">Nível</label>
                                        <p class="mb-0">{{ $turma->nivel->nome ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="text-muted small">Turno</label>
                                        <p class="mb-0">{{ $turma->turno->nome ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="text-muted small">Unidade</label>
                                        <p class="mb-0">{{ $turma->unidade->nome ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            @else
                                <p class="text-muted">Nenhuma turma selecionada</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="card-title text-primary mb-3">
                                <i class="fas fa-calendar-alt me-2"></i>Data da Aula
                            </h6>
                            <div class="mb-3">
                                <!-- <label for="data" class="form-label">Data</label> -->
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="fas fa-calendar-day"></i>
                                    </span>
                                    <input type="date" 
                                           class="form-control @error('data') is-invalid @enderror" 
                                           id="data" 
                                           wire:model.live="data" 
                                           @if($viewing_aula_id) disabled @endif
                                           required>
                                    @if(!$viewing_aula_id)
                                        <button type="button" class="btn btn-secondary" wire:click="loadData">
                                            <i class="fas fa-sync-alt me-2"></i>Carregar
                                        </button>
                                    @endif
                                </div>
                                @if($data)
                                    <small class="text-muted d-block mt-2">
                                        <i class="fas fa-calendar-week me-1"></i>
                                        {{ \Carbon\Carbon::parse($data)->locale('pt_BR')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                                    </small>
                                @endif
                                @error('data') 
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            @if($total_aulas_dia > 0)
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-chalkboard me-2"></i>
                                    <strong>Total de aulas no dia:</strong> {{ $total_aulas_dia }} aula(s)
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conteúdo Ministrado -->
            @if($is_data_loaded || $viewing_aula_id)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="card-title text-primary mb-3">
                            <i class="fas fa-book me-2"></i>Conteúdo Ministrado
                        </h6>
                        
                        <div class="mb-4">
                            <!-- <label for="conteudo" class="form-label fw-bold">
                                Conteúdo <span class="text-danger">*</span>
                            </label> -->
                            <textarea 
                                class="form-control @error('conteudo') is-invalid @enderror" 
                                id="conteudo" 
                                wire:model="conteudo" 
                                rows="8"
                                placeholder="Descreva detalhadamente o conteúdo ministrado nesta aula..."
                                @if($viewing_aula_id) disabled @endif
                            ></textarea>
                            @error('conteudo') 
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Descreva os tópicos abordados, atividades realizadas, exercícios, etc.
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="observacao" class="form-label fw-bold">
                                Observações
                            </label>
                            <textarea 
                                class="form-control" 
                                id="observacao" 
                                wire:model="observacao" 
                                rows="3"
                                placeholder="Observações adicionais sobre a aula..."
                                @if($viewing_aula_id) disabled @endif
                            ></textarea>
                            @error('observacao') 
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="d-flex justify-content-between">
                    <button type="button" wire:click="backToList" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Voltar
                    </button>
                    @if(!$viewing_aula_id)
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            {{ $existing_conteudo ? 'Atualizar Conteúdo' : 'Salvar Conteúdo' }}
                        </button>
                    @endif
                </div>

            @elseif(!$is_data_loaded && !$error_message && !$viewing_aula_id)
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                        <h5>Carregar dados da aula</h5>
                        <p class="text-muted">
                            Selecione uma data e clique em <strong>"Carregar"</strong> para começar.
                        </p>
                    </div>
                </div>
            @endif
        </form>
    @endif
</div>

@push('page_css')
<style>
    .table-responsive {
        overflow-x: auto;
    }
    
    .table th {
        font-weight: 600;
        border-top: none;
    }
    
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }
    
    textarea {
        resize: vertical;
    }
    
    .card {
        transition: all 0.2s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    .badge {
        font-weight: 500;
    }
</style>
@endpush

@push('page_scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        // Auto-ajuste do textarea
        const textareas = document.querySelectorAll('textarea');
        textareas.forEach(textarea => {
            textarea.style.height = 'auto';
            textarea.style.height = textarea.scrollHeight + 'px';
        });
    });
</script>
@endpush