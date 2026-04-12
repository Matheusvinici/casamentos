<div class="position-relative">
    <div class="form-group">
        <label class="form-label">Buscar Aluno</label>
        <div class="input-group">
            <input type="text" 
                   wire:model.live="search" 
                   class="form-control @error('aluno_id') is-invalid @enderror"
                   placeholder="Digite o nome, CPF, email ou telefone do aluno..."
                   autocomplete="off">
            @if($selectedAlunoId)
                <button type="button" class="btn btn-outline-secondary" wire:click="clearSelection">
                    <i class="fas fa-times"></i> Limpar
                </button>
            @endif
        </div>
        
        @if($showDropdown && $alunos->count() > 0)
            <div class="dropdown-menu show position-absolute w-100" style="top: 100%; left: 0; z-index: 1000; max-height: 300px; overflow-y: auto;">
                @foreach($alunos as $aluno)
                    <button type="button" 
                            class="dropdown-item" 
                            wire:click="selectAluno({{ $aluno->id }}, '{{ addslashes($aluno->nome) }}')">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <strong>{{ $aluno->nome }}</strong>
                                <div class="small text-muted">
                                    @if($aluno->aluno_cpf)
                                        CPF: {{ $aluno->aluno_cpf }} | 
                                    @endif
                                    @if($aluno->email)
                                        Email: {{ $aluno->email }}
                                    @endif
                                </div>
                                <div class="small text-muted">
                                    @if($aluno->telefone)
                                        Telefone: {{ $aluno->telefone }}
                                    @endif
                                    @if($aluno->responsavel_nome)
                                        | Responsável: {{ $aluno->responsavel_nome }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </button>
                @endforeach
            </div>
        @endif
        
        @if($showDropdown && $alunos->count() == 0 && strlen($search) > 0)
            <div class="dropdown-menu show position-absolute w-100" style="top: 100%; left: 0; z-index: 1000;">
                <div class="dropdown-item text-muted">
                    <i class="fas fa-search"></i> Nenhum aluno encontrado com "{{ $search }}"
                    <br>
                    <small>Tente buscar por nome, CPF, email ou telefone</small>
                </div>
            </div>
        @endif
    </div>
    
    <input type="hidden" name="aluno_id" value="{{ $selectedAlunoId }}">
    
    @error('aluno_id')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>