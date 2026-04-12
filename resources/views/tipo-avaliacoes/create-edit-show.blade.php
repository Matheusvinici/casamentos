{{-- resources/views/tipo-avaliacoes/create-edit-show.blade.php --}}
@extends('layouts.app')

@section('title', $mode == 'create' ? 'Novo Tipo de Avaliação' : ($mode == 'edit' ? 'Editar Tipo de Avaliação' : 'Detalhes do Tipo de Avaliação'))

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary">
                        @if($mode == 'create')
                            <i class="fas fa-plus-circle me-2"></i>Novo Tipo de Avaliação
                        @elseif($mode == 'edit')
                            <i class="fas fa-edit me-2"></i>Editar Tipo de Avaliação
                        @else
                            <i class="fas fa-clipboard-list me-2"></i>Detalhes do Tipo de Avaliação
                        @endif
                    </h5>
                </div>

                <div class="card-body">
                    @if($mode == 'show')
                        {{-- Modo Visualização --}}
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="text-muted small">Período Letivo</label>
                                <p class="fw-bold">{{ $tipoAvaliacao->calendario->nomeCompleto }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="text-muted small">Curso</label>
                                <p class="fw-bold">{{ $tipoAvaliacao->curso ? $tipoAvaliacao->curso->nome : '-' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="text-muted small">Status</label>
                                <p>
                                    @if($tipoAvaliacao->ativo)
                                        <span class="badge bg-success">Ativo</span>
                                    @else
                                        <span class="badge bg-secondary">Inativo</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Nome da Avaliação</label>
                                <p class="fw-bold">{{ $tipoAvaliacao->nome }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Abreviação</label>
                                <p>{{ $tipoAvaliacao->abreviacao ?? '-' }}</p>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="text-muted small">Descrição</label>
                                <p class="p-3 bg-light rounded">{{ $tipoAvaliacao->descricao ?? 'Sem descrição' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="text-muted small">Peso</label>
                                <p class="h5">{{ $tipoAvaliacao->peso_formatado }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="text-muted small">Valor Máximo</label>
                                <p class="h5">{{ $tipoAvaliacao->valor_formatado }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="text-muted small">Ordem</label>
                                <p><span class="badge bg-secondary fs-6">{{ $tipoAvaliacao->ordem }}ª</span></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Cadastrado em</label>
                                <p>{{ $tipoAvaliacao->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Última atualização</label>
                                <p>{{ $tipoAvaliacao->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                            <a href="{{ route('Listar-Tipo-Avaliacoes', ['calendario_id' => $tipoAvaliacao->calendario_id]) }}" 
                               class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Voltar
                            </a>
                            <div>
                                <a href="{{ route('Editar-Tipo-Avaliacao', $tipoAvaliacao->id) }}" 
                                   class="btn btn-warning me-2">
                                    <i class="fas fa-edit me-2"></i>Editar
                                </a>
                                <button onclick="confirmDelete({{ $tipoAvaliacao->id }})" 
                                        class="btn btn-danger">
                                    <i class="fas fa-trash me-2"></i>Excluir
                                </button>
                            </div>
                        </div>

                    @else
                        {{-- Modo Create/Edit --}}
                        <form method="POST" 
                              action="{{ $mode == 'create' ? route('Gravar-Tipo-Avaliacao') : route('Atualizar-Tipo-Avaliacao', $tipoAvaliacao->id ?? '') }}">
                            @csrf
                            @if($mode == 'edit')
                                @method('PUT')
                            @endif

                            <div class="row g-3">
                                <!-- Calendário -->
                                <div class="col-md-6">
                                    <label for="calendario_id" class="form-label">Período Letivo <span class="text-danger">*</span></label>
                                    <select name="calendario_id" 
                                            id="calendario_id" 
                                            class="form-select @error('calendario_id') is-invalid @enderror" 
                                            {{ $mode == 'edit' ? '' : 'required' }}>
                                        <option value="">Selecione o período</option>
                                        @foreach($calendarios as $calendario)
                                            <option value="{{ $calendario->id }}" 
                                                {{ old('calendario_id', $tipoAvaliacao->calendario_id ?? $calendarioId) == $calendario->id ? 'selected' : '' }}>
                                                {{ $calendario->nomeCompleto }} 
                                                ({{ \Carbon\Carbon::parse($calendario->inicio)->format('d/m/Y') }} - 
                                                 {{ \Carbon\Carbon::parse($calendario->fim)->format('d/m/Y') }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('calendario_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Curso -->
                                <div class="col-md-6">
                                    <label for="curso_id" class="form-label">Curso <span class="text-danger">*</span></label>
                                    <select name="curso_id" 
                                            id="curso_id" 
                                            class="form-select @error('curso_id') is-invalid @enderror" 
                                            required>
                                        <option value="">Selecione o curso</option>
                                        @foreach($cursos as $curso)
                                            <option value="{{ $curso->id }}" 
                                                {{ old('curso_id', $tipoAvaliacao->curso_id ?? '') == $curso->id ? 'selected' : '' }}>
                                                {{ $curso->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('curso_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Nome -->
                                <div class="col-md-8">
                                    <label for="nome" class="form-label">Nome da Avaliação <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('nome') is-invalid @enderror" 
                                           id="nome" 
                                           name="nome" 
                                           value="{{ old('nome', $tipoAvaliacao->nome ?? '') }}" 
                                           placeholder="Ex: Prova 1, Trabalho em Grupo, Apresentação Final"
                                           required>
                                    @error('nome')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Abreviação -->
                                <div class="col-md-4">
                                    <label for="abreviacao" class="form-label">Abreviação</label>
                                    <input type="text" 
                                           class="form-control @error('abreviacao') is-invalid @enderror" 
                                           id="abreviacao" 
                                           name="abreviacao" 
                                           value="{{ old('abreviacao', $tipoAvaliacao->abreviacao ?? '') }}" 
                                           placeholder="Ex: P1, TB, AF"
                                           maxlength="20">
                                    @error('abreviacao')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Descrição -->
                                <div class="col-12">
                                    <label for="descricao" class="form-label">Descrição</label>
                                    <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                              id="descricao" 
                                              name="descricao" 
                                              rows="3" 
                                              placeholder="Descreva os detalhes desta avaliação...">{{ old('descricao', $tipoAvaliacao->descricao ?? '') }}</textarea>
                                    @error('descricao')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Linha: Peso, Valor Máximo, Ordem -->
                                <div class="col-md-4">
                                    <label for="peso" class="form-label">Peso na Média <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" 
                                               step="0.1" 
                                               min="0.1" 
                                               max="999.99" 
                                               class="form-control @error('peso') is-invalid @enderror" 
                                               id="peso" 
                                               name="peso" 
                                               value="{{ old('peso', $tipoAvaliacao->peso ?? 1.0) }}" 
                                               required>
                                        <span class="input-group-text">x</span>
                                    </div>
                                    @error('peso')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Peso multiplicador na média final</small>
                                </div>

                                <div class="col-md-4">
                                    <label for="valor_maximo" class="form-label">Valor Máximo <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" 
                                               step="0.1" 
                                               min="0.1" 
                                               max="999.99" 
                                               class="form-control @error('valor_maximo') is-invalid @enderror" 
                                               id="valor_maximo" 
                                               name="valor_maximo" 
                                               value="{{ old('valor_maximo', $tipoAvaliacao->valor_maximo ?? 10.0) }}" 
                                               required>
                                        <span class="input-group-text">pts</span>
                                    </div>
                                    @error('valor_maximo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Pontuação máxima da avaliação</small>
                                </div>

                                <div class="col-md-4">
                                    <label for="ordem" class="form-label">Ordem <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           min="1" 
                                           class="form-control @error('ordem') is-invalid @enderror" 
                                           id="ordem" 
                                           name="ordem" 
                                           value="{{ old('ordem', $tipoAvaliacao->ordem ?? $proximaOrdem ?? 1) }}" 
                                           required>
                                    @error('ordem')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Ordem de ocorrência no semestre</small>
                                </div>

                                <!-- Status Ativo -->
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="ativo" 
                                               id="ativo" 
                                               value="1" 
                                               {{ old('ativo', $tipoAvaliacao->ativo ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="ativo">
                                            Avaliação Ativa
                                        </label>
                                        <small class="text-muted d-block">Desmarque para desativar temporariamente</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Botões -->
                            <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                                <a href="{{ route('Listar-Tipo-Avaliacoes', ['calendario_id' => old('calendario_id', $tipoAvaliacao->calendario_id ?? $calendarioId)]) }}" 
                                   class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Voltar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>
                                    {{ $mode == 'create' ? 'Salvar' : 'Atualizar' }} Avaliação
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

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