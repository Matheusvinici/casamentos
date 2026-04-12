
<div class="container-fluid py-4">
    <!-- Cabeçalho -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1 text-primary">
                                <i class="fas fa-calendar-check me-2"></i>Lista de Frequências
                            </h4>
                            <p class="text-muted mb-0">
                                Gerencie as frequências da Escola de Idiomas de Juazeiro-BA
                            </p>
                        </div>
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
                        @if($search || $filterData)
                            <a href="{{ route(Auth::guard('web')->check() ? 'Listar-Frequencias' : 'Listar-Frequencias-Professor') }}" 
                               class="btn btn-outline-secondary"
                               title="Limpar pesquisa">
                                <i class="fas fa-times"></i>
                            </a>
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

    <!-- Tabela de Frequências -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        
                            <th class="text-center" style="width: 5%">#</th>
                            <th style="width: 18%">Turma</th>
                            <th style="width: 18%">Data</th>
                            <th style="width: 12%">Turno</th>
                            <th style="width: 18%">Professor</th>
                            <th class="text-center" style="width: 10%">Total Aulas</th>
                            <th class="text-center" style="width: 10%">Total Alunos</th>
                            <th class="text-center" style="width: 9%">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($frequencias as $index => $frequencia)
                            <tr>
                                <td class="text-center text-muted">
                                    {{ $frequencias->firstItem() + $index }}
                                </td>
                                <td data-label="Turma">
                                    <div class="fw-bold">{{ $frequencia->turma->nome }}</div>
                                    <small class="text-muted">
                                        {{ $frequencia->turma->curso->nome ?? '' }} 
                                        {{ $frequencia->turma->nivel->nome ?? '' }}
                                    </small>
                                </td>
                                <td data-label="Data">
                                    <div>{{ \Carbon\Carbon::parse($frequencia->dia)->format('d/m/Y') }}</div>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-week me-1"></i>
                                        {{ \Carbon\Carbon::parse($frequencia->dia)->locale('pt_BR')->isoFormat('dddd') }}
                                    </small>
                                </td>
                                <td data-label="Turno">
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $frequencia->turno ? $frequencia->turno->nome : 'N/A' }}
                                    </span>
                                </td>
                                <td data-label="Professor">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-chalkboard-user text-muted me-2"></i>
                                        {{ $frequencia->professor ? $frequencia->professor->nome : 'N/A' }}
                                    </div>
                                </td>
                                <td class="text-center" data-label="Total Aulas">
                                    <span class="badge bg-info">
                                        <i class="fas fa-chalkboard me-1"></i>
                                        {{ $frequencia->total_aulas }} aula{{ $frequencia->total_aulas != 1 ? 's' : '' }}
                                    </span>
                                </td>
                                <td class="text-center" data-label="Total Alunos">
                                    <span class="badge bg-primary">
                                        <i class="fas fa-users me-1"></i>
                                        {{ $frequencia->total_alunos }} aluno{{ $frequencia->total_alunos != 1 ? 's' : '' }}
                                    </span>
                                </td>
                                <td data-label="Ações">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route(Auth::guard('web')->check() ? 'Ver-Frequencia' : 'Ver-Frequencia-Professor', ['aulas_id' => $frequencia->aula_id, 'turma_id' => $frequencia->turma->id]) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Visualizar">
                                            <i class="fas fa-eye me-1"></i> Ver
                                        </a>
                                        <!-- <a href="{{ route(Auth::guard('web')->check() ? 'Editar-Frequencia' : 'Editar-Frequencia-Professor', ['aulas_id' => $frequencia->aula_id, 'turma_id' => $frequencia->turma->id]) }}" 
                                           class="btn btn-sm btn-outline-warning" 
                                           title="Editar">
                                            <i class="fas fa-edit me-1"></i> Editar
                                        </a>
                                        <button wire:click="delete({{ $frequencia->aula_id }})" 
                                                wire:confirm="Tem certeza que deseja excluir esta frequência?" 
                                                class="btn btn-sm btn-outline-danger" 
                                                title="Excluir">
                                            <i class="fas fa-trash me-1"></i> Excluir -->
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Nenhuma frequência encontrada</h5>
                                    <p class="text-muted mb-0">Não há registros de frequência para exibir.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($frequencias->hasPages())
                <div class="card-footer bg-white border-0">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                        <small class="text-muted mb-2 mb-md-0">
                            <i class="fas fa-chart-line me-1"></i>
                            Mostrando {{ $frequencias->firstItem() ?? 0 }} até {{ $frequencias->lastItem() ?? 0 }} de {{ $frequencias->total() }} registros
                        </small>
                        <div>
                            {{ $frequencias->links() }}
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
    
    /* Badge custom colors */
    .badge.bg-info {
        background-color: #17a2b8 !important;
    }
    
    .badge.bg-primary {
        background-color: #007bff !important;
    }
    
    .badge.bg-secondary {
        background-color: #6c757d !important;
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
        
        .btn-group .btn {
            padding: 0.375rem 0.75rem;
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
            if (typeof bootstrap !== 'undefined') {
                new bootstrap.Tooltip(element);
            }
        });
    });
</script>
@endpush