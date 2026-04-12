@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-5 text-center fw-bold text-primary">Faixa Etária por Curso</h1>
    
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('Relatorios-Faixa-Etaria-Curso') }}" class="row g-3 align-items-end">
                <div class="col-md-10">
                    <label for="curso_id" class="form-label fw-semibold">Curso</label>
                    <select name="curso_id" id="curso_id" class="form-select">
                        <option value="">Todos os Cursos</option>
                        @foreach($cursos as $curso)
                            <option value="{{ $curso->id }}" {{ $cursoId == $curso->id ? 'selected' : '' }}>
                                {{ $curso->nome }}
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
        <a href="{{ route('Relatorios-Faixa-Etaria-Curso', array_merge(request()->all(), ['download' => true])) }}" 
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
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3">Resumo por Faixa Etária</h5>
                <table class="table table-hover">
                    <thead class="table-info">
                        <tr>
                            <th>Faixa Etária</th>
                            <th>Quantidade de Alunos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($faixasEtarias as $faixa => $quantidade)
                            <tr>
                                <td>{{ $faixa }} anos</td>
                                <td>{{ $quantidade }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card shadow-sm border-0">
            <div class="card-body table-responsive">
                <h5 class="card-title mb-3">Detalhes dos Alunos</h5>
                <table class="table table-hover">
                    <thead class="table-info">
                        <tr>
                            <th>Aluno</th>
                            <th>Idade</th>
                            <th>Curso</th>
                            <th>Turma</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($matriculas as $matricula)
                            <tr>
                                <td>{{ $matricula->aluno->nome }}</td>
                                <td>{{ \Carbon\Carbon::parse($matricula->aluno->data_nascimento)->age }} anos</td>
                                <td>{{ $matricula->turma->curso->nome }}</td>
                                <td>{{ $matricula->turma->nome }}</td>
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