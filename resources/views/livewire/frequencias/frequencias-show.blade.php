<div class="container-fluid py-4">
    <!-- Cabeçalho -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1 text-primary">
                                <i class="fas fa-calendar-check me-2"></i>Visualizar Frequência
                            </h4>
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
                            <a href="{{ route(Auth::guard('web')->check() ? 'Listar-Frequencias' : 'Listar-Frequencias-Professor') }}" 
                               class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Voltar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informações da Aula -->
    @if($aula)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-calendar-alt me-2"></i>Data da Aula
                                </h6>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar-day fa-2x text-muted me-3"></i>
                                    <div>
                                        <h5 class="mb-0">{{ \Carbon\Carbon::parse($aula->dia)->format('d/m/Y') }}</h5>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($aula->dia)->locale('pt_BR')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-chalkboard me-2"></i>Informações da Aula
                                </h6>
                                <div class="row">
                                    <div class="col-6">
                                        <label class="text-muted small">Total de Aulas</label>
                                        <p class="fw-bold mb-0">{{ $aula->total_aulas }} aula(s)</p>
                                    </div>
                                    <div class="col-6">
                                        <label class="text-muted small">Turno</label>
                                        <p class="mb-0">{{ $aula->turno->nome ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Legenda -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-primary mb-3">
                        <i class="fas fa-info-circle me-2"></i>Legenda
                    </h6>
                    <div class="d-flex flex-wrap gap-3">
                        <span class="badge bg-success px-3 py-2">
                            <i class="fas fa-check-circle me-1"></i>Presente
                        </span>
                        <span class="badge bg-warning px-3 py-2 text-dark">
                            <i class="fas fa-clock me-1"></i>Falta Parcial
                        </span>
                        <span class="badge bg-danger px-3 py-2">
                            <i class="fas fa-times-circle me-1"></i>Ausente
                        </span>
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

    <!-- Tabela de Frequências -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-center">
                            <th style="width: 5%">#</th>
                            <th style="width: 20%">Aluno</th>
                            <th style="width: 12%">Status</th>
                            <th class="text-center" style="width: 10%">Total Aulas</th>
                            <th class="text-center" style="width: 10%">Aulas Presentes</th>
                            <th class="text-center" style="width: 10%">Aulas Ausentes</th>
                            <th style="width: 18%">Justificativa</th>
                            <th style="width: 15%">Observação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($frequencias as $index => $frequencia)
                            @php
                                $aulas_presentes = $aula->total_aulas - $frequencia->aulas_ausentes;
                                $status = '';
                                $status_class = '';
                                
                                if ($frequencia->aulas_ausentes == 0) {
                                    $status = 'Presente';
                                    $status_class = 'success';
                                } elseif ($frequencia->aulas_ausentes == $aula->total_aulas) {
                                    $status = 'Ausente';
                                    $status_class = 'danger';
                                } else {
                                    $status = 'Falta Parcial';
                                    $status_class = 'warning text-dark';
                                }
                            @endphp
                            <tr>
                                <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                <td data-label="Aluno">
                                    <div class="fw-bold">{{ $frequencia->aluno->nome ?? 'N/A' }}</div>
                                    <small class="text-muted">Matrícula: {{ $frequencia->matricula_id ?? 'N/A' }}</small>
                                </td>
                                <td class="text-center" data-label="Status">
                                    <span class="badge bg-{{ $status_class }} px-3 py-2">
                                        <i class="fas 
                                            @if($status == 'Presente') fa-check-circle
                                            @elseif($status == 'Ausente') fa-times-circle
                                            @else fa-clock
                                            @endif me-1"></i>
                                        {{ $status }}
                                    </span>
                                </td>
                                <td class="text-center" data-label="Total Aulas">
                                    <span class="badge bg-info px-3 py-2">
                                        <i class="fas fa-chalkboard me-1"></i>
                                        {{ $aula->total_aulas }} aula(s)
                                    </span>
                                </td>
                                <td class="text-center" data-label="Aulas Presentes">
                                    <span class="badge bg-success px-3 py-2">
                                        <i class="fas fa-check-circle me-1"></i>
                                        {{ $aulas_presentes }} aula(s)
                                    </span>
                                </td>
                                <td class="text-center" data-label="Aulas Ausentes">
                                    <span class="badge bg-danger px-3 py-2">
                                        <i class="fas fa-times-circle me-1"></i>
                                        {{ $frequencia->aulas_ausentes }} aula(s)
                                    </span>
                                </td>
                                <td data-label="Justificativa">
                                    @if($frequencia->justificativa)
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-file-alt text-primary me-2"></i>
                                            <span>{{ $frequencia->justificativa }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted">
                                            <i class="fas fa-minus-circle me-1"></i>Sem justificativa
                                        </span>
                                    @endif
                                </td>
                                <td data-label="Observação">
                                    @if($frequencia->observacao)
                                        <div class="text-truncate" style="max-width: 200px;" title="{{ $frequencia->observacao }}">
                                            <i class="fas fa-comment text-info me-2"></i>
                                            {{ Str::limit($frequencia->observacao, 50) }}
                                        </div>
                                    @else
                                        <span class="text-muted">
                                            <i class="fas fa-minus-circle me-1"></i>Sem observação
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Nenhuma frequência registrada</h5>
                                    <p class="text-muted mb-0">Não há registros de frequência para esta aula.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($frequencias->isNotEmpty())
                        <tfoot class="bg-light">
                            <tr>
                                <td colspan="8" class="text-center py-3">
                                    <div class="d-flex justify-content-center gap-4">
                                        <div>
                                            <i class="fas fa-check-circle text-success me-1"></i>
                                            <strong>Presentes:</strong> 
                                            {{ $frequencias->filter(fn($f) => $f->aulas_ausentes == 0)->count() }}
                                        </div>
                                        <div>
                                            <i class="fas fa-clock text-warning me-1"></i>
                                            <strong>Falta Parcial:</strong> 
                                            {{ $frequencias->filter(fn($f) => $f->aulas_ausentes > 0 && $f->aulas_ausentes < $aula->total_aulas)->count() }}
                                        </div>
                                        <div>
                                            <i class="fas fa-times-circle text-danger me-1"></i>
                                            <strong>Ausentes:</strong> 
                                            {{ $frequencias->filter(fn($f) => $f->aulas_ausentes == $aula->total_aulas)->count() }}
                                        </div>
                                        <div>
                                            <i class="fas fa-chalkboard text-info me-1"></i>
                                            <strong>Total de Faltas:</strong> 
                                            {{ $frequencias->sum('aulas_ausentes') }}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <!-- Botões de Ação -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-between">
                <a href="{{ route(Auth::guard('web')->check() ? 'Listar-Frequencias' : 'Listar-Frequencias-Professor') }}" 
                   class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Voltar
                </a>
                @if($aula)
                    <a href="{{ route(Auth::guard('web')->check() ? 'Editar-Frequencia' : 'Editar-Frequencia-Professor', ['aulas_id' => $aula->id, 'turma_id' => $aula->turma_id]) }}" 
                       class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Editar Frequência
                    </a>
                @endif
            </div>
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
    
    .badge {
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
    }
    
    .badge.bg-info {
        background-color: #17a2b8 !important;
    }
    
    .badge.bg-success {
        background-color: #28a745 !important;
    }
    
    .badge.bg-danger {
        background-color: #dc3545 !important;
    }
    
    .badge.bg-warning {
        background-color: #ffc107 !important;
        color: #212529 !important;
    }
    
    .card {
        transition: all 0.2s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    .table tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
    
    .text-truncate {
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    tfoot {
        font-weight: 500;
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
            min-width: 120px;
        }
        
        .table td:last-child {
            border-bottom: none;
        }
        
        .badge {
            white-space: nowrap;
        }
        
        .text-truncate {
            max-width: 150px;
        }
        
        tfoot td {
            display: block;
            text-align: center;
        }
        
        tfoot .d-flex {
            flex-direction: column;
            gap: 0.5rem;
        }
    }
</style>
@endpush