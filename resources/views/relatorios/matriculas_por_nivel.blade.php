@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-5 text-center fw-bold text-primary">Matrículas por Nível</h1>
    
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('Relatorios-Matriculas-Por-Nivel') }}" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label for="nivel_id" class="form-label fw-semibold">Nível</label>
                    <select name="nivel_id" id="nivel_id" class="form-select">
                        <option value="">Todos os Níveis</option>
                        @foreach($niveis as $nivel)
                            <option value="{{ $nivel->id }}" {{ $nivelId == $nivel->id ? 'selected' : '' }}>
                                {{ $nivel->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <label for="status" class="form-label fw-semibold">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="ativo" {{ $status == 'ativo' ? 'selected' : '' }}>Ativo</option>
                        <option value="inativo" {{ $status == 'inativo' ? 'selected' : '' }}>Inativo</option>
                        <option value="desistente" {{ $status == 'desistente' ? 'selected' : '' }}>Desistente</option>
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
        <a href="{{ route('Relatorios-Matriculas-Por-Nivel', array_merge(request()->all(), ['download' => true])) }}" 
           class="btn btn-success">
            <i class="bi bi-file-earmark-pdf-fill me-2"></i>Download PDF
        </a>
    </div>
    
    @if($matriculas->isEmpty())
        <div class="alert alert-info d-flex align-items-center">
            <i class="bi bi-info-circle-fill me-2"></i>
            Nenhuma matrícula encontrada com os filtros selecionados.
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="card-body table-responsive">
                <table class="table table-hover">
                    <thead class="table-info">
                        <tr>
                            <th>Aluno</th>
                            <th>Turma</th>
                            <th>Nível</th>
                            <th>Data Matrícula</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($matriculas as $matricula)
                            <tr>
                                <td>{{ $matricula->aluno->nome }}</td>
                                <td>{{ $matricula->turma->nome }}</td>
                                <td>{{ $matricula->turma->nivel->nome }}</td>
                                <td>{{ \Carbon\Carbon::parse($matricula->data_matricula)->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $matricula->status == 'ativo' ? 'success' : ($matricula->status == 'inativo' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($matricula->status) }}
                                    </span>
                                </td>
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