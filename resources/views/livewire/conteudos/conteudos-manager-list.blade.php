{{-- resources/views/livewire/conteudos/conteudos-manager-list.blade.php --}}
<div class="container-fluid py-4">
    <!-- Cabeçalho -->
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

    <!-- Alertas -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filtros -->
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
                            <button wire:click="$set('search', '')" 
                                    class="btn btn-outline-secondary" 
                                    type="button"
                                    title="Limpar pesquisa">
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
                            <button wire:click="$set('filterData', '')" 
                                    class="btn btn-outline-secondary" 
                                    type="button"
                                    title="Limpar filtro">
                                <i class="fas fa-times"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Conteúdos -->
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
                            <th class="text-center" style="width: 8%">Aulas</th>
                            <th style="width: 27%">Conteúdo</th>
                            <th class="text-center" style="width: 10%">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($conteudos as $index => $aula)
                            <tr>
                                <td class="text-center text-muted">
                                    {{ $conteudos->firstItem() + $index }}
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $aula->turma->nome }}</div>
                                    <small class="text-muted">
                                        {{ $aula->turma->curso->nome ?? '' }} 
                                        {{ $aula->turma->nivel->nome ?? '' }}
                                    </small>
                                </td>
                                <td>
                                    <div>{{ \Carbon\Carbon::parse($aula->dia)->format('d/m/Y') }}</div>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-week me-1"></i>
                                        {{ \Carbon\Carbon::parse($aula->dia)->locale('pt_BR')->isoFormat('dddd') }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $aula->turno ? $aula->turno->nome : 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-chalkboard-user text-muted me-2"></i>
                                        {{ $aula->turma->professor ? $aula->turma->professor->nome : 'N/A' }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info">
                                        <i class="fas fa-chalkboard me-1"></i>
                                        {{ $aula->total_aulas }} aula(s)
                                    </span>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 320px;" 
                                         title="{{ $aula->conteudoMinistrado->conteudo ?? '' }}">
                                        <i class="fas fa-file-alt text-muted me-2"></i>
                                        {{ Str::limit($aula->conteudoMinistrado->conteudo ?? 'N/A', 80) }}
                                    </div>
                                    @if($aula->conteudoMinistrado && $aula->conteudoMinistrado->observacao)
                                        <small class="text-muted d-block mt-1">
                                            <i class="fas fa-comment me-1"></i>
                                            {{ Str::limit($aula->conteudoMinistrado->observacao, 50) }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button wire:click="view({{ $aula->id }})" 
                                                class="btn btn-sm btn-outline-primary" 
                                                title="Visualizar">
                                            <i class="fas fa-eye me-1"></i> Ver
                                        </button>
                                        <!-- <button wire:click="edit({{ $aula->id }})" 
                                                class="btn btn-sm btn-outline-warning" 
                                                title="Editar">
                                            <i class="fas fa-edit me-1"></i> Editar
                                        </button> -->
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Nenhum conteúdo encontrado</h5>
                                    <p class="text-muted mb-0">Clique em "Novo Conteúdo" para começar a registrar.</p>
                                    <button wire:click="create" class="btn btn-primary mt-3">
                                        <i class="fas fa-plus-circle me-2"></i>Registrar Primeiro Conteúdo
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($conteudos->hasPages())
                <div class="card-footer bg-white border-0">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                        <small class="text-muted mb-2 mb-md-0">
                            <i class="fas fa-chart-line me-1"></i>
                            Mostrando {{ $conteudos->firstItem() ?? 0 }} até {{ $conteudos->lastItem() ?? 0 }} de {{ $conteudos->total() }} registros
                        </small>
                        <div>
                            {{ $conteudos->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('page_css')
<style>
    .table-responsive {
        overflow-x: auto;
    }
    
    .table th {
        font-weight: 600;
        border-top: none;
        background-color: #f8f9fa;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    .btn-group .btn {
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
    }
    
    .btn-group .btn i {
        font-size: 0.75rem;
    }
    
    .badge {
        font-weight: 500;
        padding: 0.35rem 0.65rem;
    }
    
    .card {
        transition: all 0.2s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    .table tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
        cursor: pointer;
    }
    
    @media (max-width: 768px) {
        .table thead {
            display: none;
        }
        
        .table tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            background: white;
        }
        
        .table td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #dee2e6;
        }
        
        .table td::before {
            content: attr(data-label);
            font-weight: 600;
            margin-right: 1rem;
            color: #6c757d;
        }
        
        .table td:last-child {
            border-bottom: none;
        }
        
        .btn-group {
            width: 100%;
            justify-content: flex-end;
        }
        
        .text-truncate {
            max-width: 100% !important;
        }
    }
</style>
@endpush

@push('page_scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        // Adicionar tooltips
        const tooltips = document.querySelectorAll('[title]');
        tooltips.forEach(element => {
            element.setAttribute('data-bs-toggle', 'tooltip');
            element.setAttribute('data-bs-placement', 'top');
        });
    });
</script>
@endpush