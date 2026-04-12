<div class="container-fluid py-4">
    <!-- Cabeçalho -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1 text-warning">
                                <i class="fas fa-edit me-2"></i>Editar Frequência
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

    <!-- Informações da Turma -->
    @if($turma)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title text-primary mb-3">
                            <i class="fas fa-chalkboard-user me-2"></i>Informações da Turma
                        </h6>
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <label class="text-muted small">Turma</label>
                                <p class="fw-bold mb-0">{{ $turma->nome }}</p>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="text-muted small">Professor</label>
                                <p class="mb-0">{{ $turma->professor->nome ?? 'Não atribuído' }}</p>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="text-muted small">Curso</label>
                                <p class="mb-0">{{ $turma->curso->nome ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="text-muted small">Nível</label>
                                <p class="mb-0">{{ $turma->nivel->nome ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="text-muted small">Turno</label>
                                <p class="mb-0">{{ $turma->turno->nome ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="text-muted small">Unidade</label>
                                <p class="mb-0">{{ $turma->unidade->nome ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <form wire:submit.prevent="save">
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

        <!-- Data e Calendário -->
        <div class="row mb-4">
            <div class="col-md-5 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <label class="form-label fw-bold text-primary">
                            <i class="fas fa-calendar-alt me-2"></i>Data da Aula
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-calendar-day"></i>
                            </span>
                            <input type="date" 
                                   class="form-control bg-light" 
                                   wire:model="data" 
                                   disabled>
                            <button type="button" class="btn btn-secondary" wire:click="loadData">
                                <i class="fas fa-sync-alt me-2"></i>Carregar
                            </button>
                        </div>
                        @if($data)
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-calendar-week me-1"></i>
                                {{ \Carbon\Carbon::parse($data)->locale('pt_BR')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                            </small>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-7 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <label class="form-label fw-bold text-primary">
                            <i class="fas fa-calendar me-2"></i>Calendário
                        </label>
                        <select class="form-select bg-light" wire:model="calendario_id" {{ $calendario_id ? 'disabled' : '' }} required>
                            <option value="">Selecione um calendário</option>
                            @foreach($calendarios as $calendario)
                                <option value="{{ $calendario->id }}" {{ $calendario->id == $calendario_id ? 'selected' : '' }}>
                                    {{ $calendario->ano }} {{ $calendario->ativo ? '(Ativo)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('calendario_id') 
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabela de Alunos -->
        @if($alunos->isNotEmpty() && $is_data_loaded && $total_aulas_dia > 0)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="text-center">
                                    <th style="width: 5%">#</th>
                                    <th style="width: 20%">Aluno</th>
                                    <th style="width: 10%">Total Aulas</th>
                                    <th style="width: 10%">Aulas Presentes</th>
                                    <th style="width: 15%">Aulas Ausentes</th>
                                    <th style="width: 20%">Justificativa</th>
                                    <th style="width: 20%">Observação</th>
                                 </tr>
                            </thead>
                            <tbody>
                                @foreach($alunos as $index => $aluno)
                                    @php
                                        $aulas_ausentes_valor = $aulas_ausentes[$aluno->id] ?? 0;
                                        $aulas_presentes = $total_aulas_dia - $aulas_ausentes_valor;
                                    @endphp
                                    <tr>
                                        <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                        <td data-label="Aluno">
                                            <div class="fw-bold">{{ $aluno->nome }}</div>
                                            <small class="text-muted">Matrícula: {{ $aluno->matricula_id ?? 'N/A' }}</small>
                                        </td>
                                        <td class="text-center" data-label="Total Aulas">
                                            <span class="badge bg-info px-3 py-2">
                                                <i class="fas fa-chalkboard me-1"></i>
                                                {{ $total_aulas_dia }} aula(s)
                                            </span>
                                        </td>
                                        <td class="text-center" data-label="Aulas Presentes">
                                            <span class="badge bg-success px-3 py-2">
                                                <i class="fas fa-check-circle me-1"></i>
                                                {{ $aulas_presentes }} aula(s)
                                            </span>
                                        </td>
                                        <td data-label="Aulas Ausentes">
                                            <select
                                                class="form-select form-select-sm {{ $aulas_ausentes_valor >= 1 ? 'text-danger border-danger' : '' }}"
                                                wire:model.live="aulas_ausentes.{{ $aluno->id }}"
                                                style="min-width: 120px;">
                                                @if($total_aulas_dia > 0)
                                                    <option value="0">0 (Presente)</option>
                                                    @for($i = 1; $i <= $total_aulas_dia; $i++)
                                                        <option value="{{ $i }}" {{ $aulas_ausentes_valor == $i ? 'selected' : '' }}>
                                                            {{ $i }} aula{{ $i != 1 ? 's' : '' }}
                                                        </option>
                                                    @endfor
                                                @endif
                                            </select>
                                            @error('aulas_ausentes.'.$aluno->id) 
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td data-label="Justificativa">
                                            <select 
                                                class="form-select form-select-sm" 
                                                wire:model.live="justificativas.{{ $aluno->id }}" 
                                                {{ $aulas_ausentes_valor > 0 ? '' : 'disabled' }}>
                                                <option value="">Selecione...</option>
                                                <option value="Justificativa por escrito">📝 Justificativa por escrito</option>
                                                <option value="Justificativa verbal">🗣️ Justificativa verbal</option>
                                                <option value="Não apresentou justificativa">❌ Não apresentou justificativa</option>
                                            </select>
                                            @if($justificativas[$aluno->id] ?? false)
                                                <small class="text-success d-block mt-1">
                                                    <i class="fas fa-check-circle me-1"></i>Justificativa salva
                                                </small>
                                            @endif
                                        </td>
                                        <td data-label="Observação">
                                            <input type="text" 
                                                   class="form-control form-control-sm" 
                                                   wire:model.live="observacoes.{{ $aluno->id }}" 
                                                   placeholder="Observação..."
                                                   {{ $aulas_ausentes_valor > 0 ? '' : 'disabled' }}>
                                            @if($observacoes[$aluno->id] ?? false)
                                                <small class="text-info d-block mt-1">
                                                    <i class="fas fa-comment me-1"></i>Observação salva
                                                </small>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="7" class="text-center py-3">
                                        <div class="d-flex justify-content-center gap-4">
                                            <div>
                                                <i class="fas fa-users text-primary me-1"></i>
                                                <strong>Total Alunos:</strong> {{ $alunos->count() }}
                                            </div>
                                            <div>
                                                <i class="fas fa-clock text-warning me-1"></i>
                                                <strong>Total Faltas:</strong> 
                                                {{ collect($aulas_ausentes)->sum() }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Botões de Ação -->
            <div class="d-flex justify-content-between">
                <a href="{{ route(Auth::guard('web')->check() ? 'Listar-Frequencias' : 'Listar-Frequencias-Professor') }}" 
                   class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-warning" {{ $total_aulas_dia == 0 ? 'disabled' : '' }}>
                    <i class="fas fa-save me-2"></i>Atualizar Frequências
                </button>
            </div>

        @elseif($is_data_loaded && $total_aulas_dia == 0)
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h5>Nenhuma aula agendada</h5>
                    <p class="text-muted">Não há aulas programadas para esta turma no dia selecionado.</p>
                </div>
            </div>
        @elseif($is_data_loaded && $alunos->isEmpty())
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                    <h5>Nenhum aluno encontrado</h5>
                    <p class="text-muted">Não há alunos com matrícula ativa nesta turma.</p>
                </div>
            </div>
        @elseif(!$is_data_loaded && !$error_message)
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                    <h5>Carregar dados da aula</h5>
                    <p class="text-muted">Clique em <strong>"Carregar"</strong> para editar as frequências.</p>
                </div>
            </div>
        @endif
    </form>
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
    
    .form-select-sm, .form-control-sm {
        font-size: 0.875rem;
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
    
    .card {
        transition: all 0.2s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    .table tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
    
    select.text-danger, select.text-danger:focus {
        border-color: #dc3545;
        color: #dc3545;
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
        
        .form-select-sm, .form-control-sm {
            width: auto;
            min-width: 150px;
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