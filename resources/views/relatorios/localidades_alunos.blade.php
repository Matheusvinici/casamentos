@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-5 text-center fw-bold text-primary">Localidades dos Alunos</h1>
    
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('Relatorios-Localidades-Alunos') }}" class="row g-3 align-items-end">
                <div class="col-md-10">
                    <label for="bairro_id" class="form-label fw-semibold">Bairro</label>
                    <select name="bairro_id" id="bairro_id" class="form-select">
                        <option value="">Todos os Bairros</option>
                        @foreach($bairros as $bairro)
                            <option value="{{ $bairro->id }}" {{ $bairroId == $bairro->id ? 'selected' : '' }}>
                                {{ $bairro->nome }}
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
        <a href="{{ route('Relatorios-Localidades-Alunos', array_merge(request()->all(), ['download' => true])) }}" 
           class="btn btn-success">
            <i class="bi bi-file-earmark-pdf-fill me-2"></i>Download PDF
        </a>
    </div>
    
    @if($alunos->isEmpty())
        <div class="alert alert-info d-flex align-items-center">
            <i class="bi bi-info-circle-fill me-2"></i>
            Nenhum aluno encontrado com os filtros selecionados.
        </div>
    @else
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3">Resumo por Bairro</h5>
                <table class="table table-hover">
                    <thead class="table-info">
                        <tr>
                            <th>Bairro</th>
                            <th>Quantidade de Alunos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alunosPorBairro as $bairro => $quantidade)
                            <tr>
                                <td>{{ $bairro }}</td>
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
                            <th>Bairro</th>
                            <th>Cidade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alunos as $aluno)
                            <tr>
                                <td>{{ $aluno->nome }}</td>
                                <td>{{ $aluno->bairro->nome }}</td>
                                <td>{{ $aluno->cidade->nome }}</td>
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