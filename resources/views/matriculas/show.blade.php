@extends('layouts.app')

@section('title', 'Visualizar Matrícula')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>
                        <h5 class="m-0 text-dark">Visualizar Matrícula</h5>
                        <p class="text-muted">Gerencie as matrículas da Escola de Idiomas de Juazeiro-BA</p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('Listar-Matriculas') }}" class="btn btn-outline-secondary btn-sm">Voltar</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-3 py-md-4">
        <div class="card shadow-xl rounded-1 mx-2 mb-3">
            <div class="card-header text-gray py-3 card-border">
                <h4 class="h5 mb-0 fw-semibold">Detalhes da Matrícula</h4>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Aluno</label>
                        <p class="form-control-plaintext">{{ $matricula->aluno->nome ?? 'Sem aluno' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Turma</label>
                        <p class="form-control-plaintext">
                            {{ $matricula->turma->nome ?? 'Sem turma' }}
                            ({{ $matricula->turma->curso->abreviacao ?? 'N/A' }} -
                            {{ $matricula->turma->turno->abreviacao ?? 'N/A' }} -
                            Semestre: {{ $matricula->turma->calendario->ano ?? 'N/A' }})
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Data da Matrícula</label>
                        <p class="form-control-plaintext">{{ \Carbon\Carbon::parse($matricula->data_matricula)->format('d/m/Y') }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <p class="form-control-plaintext">{{ ucfirst($matricula->status) }}</p>
                    </div>
                </div>

                <div class="mt-4">
                    <h5 class="h6 fw-semibold">Histórico de Matrículas do Aluno</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Turma</th>
                                <th>Semestre</th>
                                <th>Data da Matrícula</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($historicoMatriculas as $historico)
                                <tr>
                                    <td>{{ $historico->turma->nome ?? 'N/A' }} ({{ $historico->turma->curso->abreviacao ?? 'N/A' }})</td>
                                    <td>{{ $historico->turma->calendario->ano ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($historico->data_matricula)->format('d/m/Y') }}</td>
                                    <td>{{ ucfirst($historico->status) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <a href="{{ route('Editar-Matricula', $matricula->id) }}" class="btn btn-outline-warning">Editar</a>
                    <a href="{{ route('Listar-Matriculas') }}" class="btn btn-outline-secondary">Voltar</a>
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
    .form-control-plaintext {
        padding: 0.375rem 0;
        margin-bottom: 0;
        line-height: 1.5;
        color: #212529;
    }
    .table {
        font-size: 0.875rem;
    }
</style>
@endpush