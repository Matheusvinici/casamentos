@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-5 text-center fw-bold text-primary">Turmas dos Professores</h1>
    
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('Relatorios-Turmas-Professores') }}" class="row g-3 align-items-end">
                <div class="col-md-10">
                    <label for="professor_id" class="form-label fw-semibold">Professor</label>
                    <select name="professor_id" id="professor_id" class="form-select">
                        <option value="">Todos os Professores</option>
                        @foreach($professores as $professor)
                            <option value="{{ $professor->id }}" {{ $professorId == $professor->id ? 'selected' : '' }}>
                                {{ $professor->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel-fill me-2"></i>Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="mb-4">
        <a href="{{ route('Relatorios-Turmas-Professores', array_merge(request()->all(), ['download' => true])) }}" 
           class="btn btn-success">
            <i class="bi bi-file-earmark-pdf-fill me-2"></i>Download PDF
        </a>
    </div>
    
    @if($turmas->isEmpty())
        <div class="alert alert-info d-flex align-items-center">
            <i class="bi bi-info-circle-fill me-2"></i>
            Nenhuma turma encontrada com os filtros selecionados.
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="card-body table-responsive">
                <table class="table table-hover">
                    <thead class="table-info">
                        <tr>
                            <th>Professor</th>
                            <th>Turma</th>
                            <th>Curso</th>
                            <th>Nível</th>
                            <th>Turno</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($turmas as $turma)
                            <tr>
                                <td>{{ $turma->professor ? $turma->professor->nome : 'Sem professor' }}</td>
                                <td>{{ $turma->nome }}</td>
                                <td>{{ $turma->curso->nome }}</td>
                                <td>{{ $turma->nivel->nome }}</td>
                                <td>{{ $turma->turno->nome }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<style>
    .table-hover tbody tr:hover {
        background-color: #f1f5f9;
        transition: background-color 0.2s ease;
    }
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(24, 21, 199, 0.62) !important;
    }
</style>

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
@endsection
@endsection