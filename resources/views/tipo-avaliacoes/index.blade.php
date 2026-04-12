{{-- resources/views/tipo-avaliacoes/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Tipos de Avaliação')

@section('content')
<div class="container-fluid py-4">
    <!-- Cabeçalho -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1 text-primary">
                                <i class="fas fa-tasks me-2"></i>Tipos de Avaliação
                            </h4>
                            <p class="text-muted mb-0">
                                Gerencie os tipos de avaliação, pesos e valores para cada período letivo
                            </p>
                        </div>
                        <a href="{{ route('Criar-Tipo-Avaliacao', ['calendario_id' => $calendarioAtual?->id]) }}" 
                           class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Novo Tipo
                        </a>
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
                    <form method="GET" action="{{ route('Listar-Tipo-Avaliacoes') }}" class="row g-3">
                        <div class="col-md-8">
                            <label for="calendario_id" class="form-label">Selecione o Período Letivo</label>
                            <select name="calendario_id" id="calendario_id" class="form-select" onchange="this.form.submit()">
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
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter me-2"></i>Filtrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Resumo das Avaliações -->
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
                                    <i class="fas fa-weight-hanging text-success"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Total de Pesos</small>
                                    <span class="h5 mb-0">{{ number_format($totalPesos, 1, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Busca -->
    <div class="row mb-4">
        <div class="col-md-6">
            <form method="GET" action="{{ route('Search-Tipo-Avaliacao') }}">
                <div class="input-group">
                    <input type="text" 
                           name="q" 
                           class="form-control" 
                           placeholder="Buscar por nome, abreviação ou descrição..."
                           value="{{ request('q') }}">
                    <input type="hidden" name="calendario_id" value="{{ $calendarioAtual?->id }}">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search me-2"></i>Buscar
                    </button>
                    @if(request()->filled('q'))
                        <a href="{{ route('Listar-Tipo-Avaliacoes', ['calendario_id' => $calendarioAtual?->id]) }}" 
                           class="btn btn-outline-secondary">
                            Limpar
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Tipos de Avaliação -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    @if($calendarioAtual)
                        @if($tipoAvaliacoes->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th width="60">Ordem</th>
                                            <th>Curso</th>
                                            <th>Tipo Avaliação</th>
                                            <th>Abrev.</th>
                                            <th>Descrição</th>
                                            <th>Peso</th>
                                            <th>Valor Máx.</th>
                                            <th>Status</th>
                                            <th width="150">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tipoAvaliacoes as $avaliacao)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-secondary">{{ $avaliacao->ordem }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark border">{{ $avaliacao->curso ? $avaliacao->curso->nome : 'Geral' }}</span>
                                                </td>
                                                <td>
                                                    <strong>{{ $avaliacao->nome }}</strong>
                                                </td>
                                                <td>{{ $avaliacao->abreviacao ?? '-' }}</td>
                                                <td>
                                                    <span data-bs-toggle="tooltip" title="{{ $avaliacao->descricao }}">
                                                        {{ Str::limit($avaliacao->descricao, 30) ?: '-' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ $avaliacao->peso_formatado }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success">{{ $avaliacao->valor_formatado }}</span>
                                                </td>
                                                <td>
                                                    @if($avaliacao->ativo)
                                                        <span class="badge bg-success">Ativo</span>
                                                    @else
                                                        <span class="badge bg-secondary">Inativo</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('Ver-Tipo-Avaliacao', $avaliacao->id) }}" 
                                                           class="btn btn-sm btn-outline-primary" 
                                                           title="Visualizar">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('Editar-Tipo-Avaliacao', $avaliacao->id) }}" 
                                                           class="btn btn-sm btn-outline-warning" 
                                                           title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-danger" 
                                                                onclick="confirmDelete({{ $avaliacao->id }})"
                                                                title="Excluir">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>

                                                    <!-- Formulário de exclusão oculto -->
                                                    <form id="delete-form-{{ $avaliacao->id }}" 
                                                          action="{{ route('Deletar-Tipo-Avaliacao', $avaliacao->id) }}" 
                                                          method="POST" 
                                                          style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Paginação -->
                            <div class="d-flex justify-content-between align-items-center p-3 border-top">
                                <small class="text-muted">
                                    Mostrando {{ $tipoAvaliacoes->firstItem() ?? 0 }} a {{ $tipoAvaliacoes->lastItem() ?? 0 }} 
                                    de {{ $tipoAvaliacoes->total() }} tipos de avaliação
                                </small>
                                {{ $tipoAvaliacoes->appends(['calendario_id' => $calendarioAtual->id, 'q' => request('q')])->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                <h5>Nenhum tipo de avaliação cadastrado</h5>
                                <p class="text-muted">
                                    Clique no botão "Novo Tipo" para começar a cadastrar os tipos de avaliação 
                                    deste período letivo.
                                </p>
                                <a href="{{ route('Criar-Tipo-Avaliacao', ['calendario_id' => $calendarioAtual->id]) }}" 
                                   class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Novo Tipo
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                            <h5>Selecione um período letivo</h5>
                            <p class="text-muted">
                                Escolha um calendário no filtro acima para visualizar os tipos de avaliação.
                            </p>
                        </div>
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

    // Inicializar tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush