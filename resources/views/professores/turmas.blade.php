@extends('layouts.app')

@section('title', 'Minhas Turmas')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>
                        <h5 class="m-0 text-dark">Minhas Turmas</h5>
                        <p class="text-muted">Turmas associadas ao professor {{ $professor->nome }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-3 py-md-4">
        <div class="card shadow-xl rounded-1 mx-2 mb-3">
            <div class="card-header text-gray py-3 card-border">
                <h4 class="h5 mb-0 fw-semibold">Lista de Turmas</h4>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nome da Turma</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($turmas as $turma)
                                <tr>
                                    <td data-label="ID">{{ $turma->id }}</td>
                                    <td data-label="Nome da Turma">{{ $turma->nome }}</td>
                                    <td data-label="Ações">
                                        <a href="#" class="btn btn-sm btn-outline-primary">Ver Detalhes</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4">
                                        Nenhuma turma encontrada.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-light py-3 rounded-bottom-3">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                        <small class="text-muted">Total de turmas: {{ $turmas->total() }}</small>
                        {{ $turmas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('page_css')
<style>
    .card {
        transition: all 0.3s ease;
    }
    .card-border {
        border-left: 0.2rem solid #ff717a !important;
        height: 55px;
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
        .pagination {
            justify-content: center;
        }
    }
</style>
@endpush
@endsection
