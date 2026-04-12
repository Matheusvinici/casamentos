@extends('layouts.app')

@section('title', 'Lista de Categorias')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>
                        <h5 class="m-0 text-dark">Lista de Categorias</h5>
                        <p class="text-muted">Gerencie as categorias da Escola de Idiomas de Juazeiro-BA</p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('Criar-Categoria') }}" type="button" class="btn btn-outline-primary btn-sm">
                        Nova Categoria
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-3 py-md-4">
        <!-- Formulário de Busca -->
        <div class="row mb-3">
            <div class="col-md-6">
                <form method="GET" action="{{ route('Listar-Categorias') }}">
                    <div class="input-group">
                        <input type="text" name="search_categoria" class="form-control @error('search_categoria') is-invalid @enderror" 
                               placeholder="Buscar por nome da categoria..." 
                               value="{{ request()->input('search_categoria', '') }}">
                        <button type="submit" class="btn btn-outline-primary">Buscar</button>
                        @if(request()->filled('search_categoria'))
                            <a href="{{ route('Listar-Categorias') }}" class="btn btn-outline-secondary" title="Limpar pesquisa">
                                Limpar
                            </a>
                        @endif
                        @error('search_categoria')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </form>
            </div>
        </div>

        <!-- Card Principal -->
        <div class="card shadow-xl rounded-1 mx-2 mb-3">
            <div class="card-header text-gray py-3 card-border">
                <div class="d-flex justify-content-between">
                    <h4 class="h5 mb-0 fw-semibold">Lista de Categorias</h4>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Abreviação</th>
                                <th>Curso</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categorias as $categoria)
                                <tr>
                                    <td data-label="ID">{{ $categoria->id }}</td>
                                    <td data-label="Nome">{{ $categoria->nome }}</td>
                                    <td data-label="Abreviação">{{ $categoria->abreviacao }}</td>
                                    <td data-label="Curso">{{ $categoria->curso->nome ?? 'Sem curso' }}</td>
                                    <td data-label="Ações">
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('Ver-Categoria', $categoria->id) }}" class="btn btn-sm btn-outline-primary" title="Visualizar">
                                                Visualizar
                                            </a>
                                            <a href="{{ route('Editar-Categoria', $categoria->id) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                                Editar
                                            </a>
                                            <form action="{{ route('Deletar-Categoria', $categoria->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir"
                                                        onclick="return confirm('Tem certeza que deseja excluir esta categoria?')">
                                                    Excluir
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        Nenhuma categoria encontrada.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-light py-3 rounded-bottom-3">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <small class="text-muted">Total de categorias: {{ $categorias->total() }}</small>
                    {{ $categorias->appends(['search_categoria' => request()->input('search_categoria')])->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_css')
<style>
    .card {
        transition: all 0.3s ease;
    }
    .card-border {
        border-left: 0.2rem solid #ff717a !important;
        height: 55px;
    }
    .is-invalid {
        border-color: #dc3545 !important;
    }
    .invalid-feedback {
        display: none;
        color: #dc3545;
        font-size: 0.875rem;
    }
    .is-invalid ~ .invalid-feedback {
        display: block;
    }
    @media (max-width: 768px) {
        .table-responsive {
            border: 0;
        }
        .table thead {
            display: none;
        }
        .table tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
        }
        .table td {
            display: block;
            text-align: right;
            padding-left: 50%;
            position: relative;
            border-bottom: 1px solid #dee2e6;
        }
        .table td::before {
            content: attr(data-label);
            position: absolute;
            left: 1rem;
            width: calc(50% - 1rem);
            padding-right: 1rem;
            text-align: left;
            font-weight: bold;
        }
        .table td[data-label="Ações"] .d-flex {
            justify-content: flex-end;
        }
        .pagination {
            justify-content: center;
        }
    }
</style>
@endpush