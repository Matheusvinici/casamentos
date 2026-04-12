{{-- resources/views/livewire/letivos/letivos-edit.blade.php --}}
<div>
    {{-- Erros de validação --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-start">
                <i class="fas fa-exclamation-circle me-2 mt-1"></i>
                <div class="flex-grow-1">
                    <strong class="d-block mb-1">Erros encontrados:</strong>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li class="small">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    {{-- Alerta se sem turmas --}}
    @if($turmas->isEmpty())
        <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <div>
                    <strong>Atenção!</strong> Nenhuma turma disponível no calendário selecionado.
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form wire:submit.prevent="save">
        {{-- Seção 1: Turma + Info --}}
        <div class="row mb-4">
            <div class="col-md-12 mb-3">
                <label for="turma_id" class="form-label fw-medium">Turma <span class="text-danger">*</span></label>
                <select wire:model.live="turma_id" 
                        id="turma_id" 
                        class="form-select @error('turma_id') is-invalid @enderror"
                        {{ $turmas->isEmpty() ? 'disabled' : '' }}>
                    <option value="">Selecione uma turma</option>
                    @foreach($turmas as $turma)
                        <option value="{{ $turma->id }}">
                            {{ $turma->nome }} ({{ $turma->unidade->nome ?? 'N/A' }} - {{ $turma->letra }} - {{ $turma->curso->abreviacao ?? 'N/A' }} - {{ $turma->turno->abreviacao }})
                        </option>
                    @endforeach
                </select>
                @error('turma_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            
            {{-- Info da turma selecionada --}}
            @if($semestre_selecionado)
                <div class="col-md-12 mt-2">
                    <div class="alert alert-light border">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-chalkboard-teacher text-info me-2"></i>
                            <div>
                                <small class="text-muted d-block">Turma Selecionada:</small>
                                <span class="fw-medium">{{ $semestre_selecionado }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Seções de Aulas --}}
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0 text-dark fw-semibold">
                    <i class="fas fa-clock me-2 text-primary"></i>
                    Horários das Aulas
                </h6>
                <span class="badge bg-light text-dark border">
                    {{ count($sections) }} aula(s)
                </span>
            </div>
            
            @foreach($sections as $index => $section)
                <div class="card border-light mb-3 shadow-sm">
                    <div class="card-header bg-light py-2 px-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 text-dark fw-medium">
                                <i class="fas fa-chalkboard-teacher me-2 text-primary fs-6"></i>
                                Aula {{ $index + 1 }} {{ $index === 0 ? '(Editando)' : '' }}
                            </h6>
                            @if(count($sections) > 1)
                                <button type="button" 
                                        wire:click="removeSection({{ $index }})" 
                                        class="btn btn-sm btn-outline-danger py-1 px-2"
                                        title="Remover esta aula">
                                    <i class="fas fa-times me-1"></i> Remover
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="card-body p-3">
                        <div class="row g-2">
                            <div class="col-md-3">
                                <label class="form-label fw-medium small">Dia da Semana <span class="text-danger">*</span></label>
                                <select wire:model.live="sections.{{ $index }}.dia" 
                                        class="form-select form-select-sm @error('sections.' . $index . '.dia') is-invalid @enderror"
                                        {{ $turmas->isEmpty() || !$turma_id ? 'disabled' : '' }}>
                                    <option value="">Selecione um dia</option>
                                    <option value="segunda-feira">Segunda-feira</option>
                                    <option value="terça-feira">Terça-feira</option>
                                    <option value="quarta-feira">Quarta-feira</option>
                                    <option value="quinta-feira">Quinta-feira</option>
                                    <option value="sexta-feira">Sexta-feira</option>
                                    <option value="sábado">Sábado</option>
                                    <option value="domingo">Domingo</option>
                                </select>
                                @error('sections.' . $index . '.dia')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label fw-medium small">Horário de Início <span class="text-danger">*</span></label>
                                <input type="time" 
                                       wire:model.live="sections.{{ $index }}.horario_inicio" 
                                       class="form-control form-control-sm @error('sections.' . $index . '.horario_inicio') is-invalid @enderror"
                                       {{ $turmas->isEmpty() || !$turma_id ? 'disabled' : '' }}>
                                @error('sections.' . $index . '.horario_inicio')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label fw-medium small">Horário de Saída <span class="text-danger">*</span></label>
                                <input type="time" 
                                       wire:model.live="sections.{{ $index }}.horario_saida" 
                                       class="form-control form-control-sm @error('sections.' . $index . '.horario_saida') is-invalid @enderror"
                                       {{ $turmas->isEmpty() || !$turma_id ? 'disabled' : '' }}>
                                @error('sections.' . $index . '.horario_saida')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label fw-medium small">Duração da Aula</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" 
                                           wire:model="sections.{{ $index }}.quantidade_horas" 
                                           class="form-control form-control-sm bg-light" 
                                           readonly
                                           placeholder="Calculado automaticamente">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-hourglass-half text-muted"></i>
                                    </span>
                                </div>
                                @if($section['quantidade_horas'])
                                    <small class="text-success mt-1 d-block">
                                        <i class="fas fa-check-circle me-1"></i>
                                        {{ $section['quantidade_horas'] }}
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Botão para adicionar mais seções --}}
        <div class="mb-4">
            <button type="button" 
                    wire:click="addSection" 
                    class="btn btn-outline-primary btn-sm px-3"
                    {{ $turmas->isEmpty() || !$turma_id ? 'disabled' : '' }}>
                <i class="fas fa-plus me-1"></i> Adicionar Outra Aula
            </button>
            @if($turmas->isEmpty())
                <small class="text-muted ms-2">Selecione um calendário com turmas primeiro</small>
            @elseif(!$turma_id)
                <small class="text-muted ms-2">Selecione uma turma primeiro</small>
            @endif
        </div>

        {{-- Botões: Submit e cancelar --}}
        <div class="d-flex gap-2 mt-4 pt-3 border-top">
            <button type="submit" 
                    class="btn btn-primary btn-sm px-4"
                    {{ $turmas->isEmpty() || !$turma_id ? 'disabled' : '' }}>
                <i class="fas fa-save me-1"></i> Salvar Todas as Aulas
            </button>
            <a href="{{ route('Listar-Letivos') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-times me-1"></i> Cancelar
            </a>
        </div>
    </form>

    {{-- CSS --}}
    <style>
        .form-label {
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 0.4rem;
        }
        
        .form-control, .form-select {
            border-radius: 6px;
            border: 1px solid #dee2e6;
            transition: all 0.2s ease;
        }
        
        .form-control-sm, .form-select-sm {
            font-size: 0.85rem;
            padding: 0.35rem 0.75rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #4dabf7;
            box-shadow: 0 0 0 3px rgba(77, 171, 247, 0.1);
            outline: none;
        }
        
        .form-control:disabled, .form-select:disabled {
            background-color: #f8f9fa;
            cursor: not-allowed;
        }
        
        .invalid-feedback {
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }
        
        .btn {
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .btn-sm {
            padding: 0.35rem 0.75rem;
            font-size: 0.85rem;
        }
        
        .btn-primary {
            background-color: #4dabf7;
            border-color: #4dabf7;
        }
        
        .btn-primary:hover {
            background-color: #339af0;
            border-color: #339af0;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(50, 92, 134, 0.1);
        }
        
        .btn-primary:disabled {
            background-color: #a5d8ff;
            border-color: #a5d8ff;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        .btn-outline-primary {
            color: #4dabf7;
            border-color: #4dabf7;
        }
        
        .btn-outline-primary:hover {
            background-color: #4dabf7;
            border-color: #4dabf7;
            transform: translateY(-1px);
        }
        
        .btn-outline-danger {
            color: #fa5252;
            border-color: #fa5252;
        }
        
        .btn-outline-danger:hover {
            background-color: #fa5252;
            border-color: #fa5252;
            transform: translateY(-1px);
        }
        
        .btn-outline-secondary {
            color: #868e96;
            border-color: #dee2e6;
        }
        
        .btn-outline-secondary:hover {
            background-color: #f8f9fa;
            border-color: #adb5bd;
            color: #495057;
        }
        
        .badge {
            padding: 0.35rem 0.65rem;
            font-weight: 500;
            font-size: 0.75rem;
        }
        
        .alert {
            border-radius: 8px;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .alert-light {
            background-color: #f8f9fa;
            border-color: #e9ecef;
        }
        
        .border-light {
            border-color: #f1f3f5 !important;
        }
        
        .shadow-sm {
            box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
        }
        
        .card {
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e9ecef;
        }
        
        .card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-bottom: 1px solid #e9ecef;
        }
        
        /* Responsividade */
        @media (max-width: 768px) {
            .row.g-2 > [class*="col-"] {
                margin-bottom: 1rem;
            }
            
            .d-flex.gap-2 {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }
        }
    </style>
</div>