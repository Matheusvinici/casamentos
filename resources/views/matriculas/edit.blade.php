@extends('layouts.app')

@section('title', 'Editar Matrícula')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>
                        <h5 class="m-0 text-dark">Editar Matrícula</h5>
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
                <h4 class="h5 mb-0 fw-semibold">Editar Matrícula</h4>
            </div>
            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($turmas->isEmpty())
                    <div class="alert alert-warning">
                        Nenhuma turma disponível no semestre ativo. Crie turmas no calendário ativo antes de prosseguir.
                    </div>
                @endif

                <form method="POST" action="{{ route('Atualizar-Matricula', $matricula->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="aluno_id" class="form-label">Aluno</label>
                            <select id="aluno_id" name="aluno_id" class="form-control @error('aluno_id') is-invalid @enderror">
                                <option value="">Selecione um aluno</option>
                                @foreach($alunos as $aluno)
                                    <option value="{{ $aluno->id }}" {{ $matricula->aluno_id == $aluno->id ? 'selected' : '' }}>
                                        {{ $aluno->nome }}
                                    </option>
                                @endforeach
                            </select>
                            @error('aluno_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="turma_id" class="form-label">Turma</label>
                            <select id="turma_id" name="turma_id" class="form-control @error('turma_id') is-invalid @enderror">
                                <option value="">Selecione uma turma</option>
                                @foreach($turmas as $turma)
                                    <option value="{{ $turma->id }}" {{ $matricula->turma_id == $turma->id ? 'selected' : '' }}>
                                        {{ $turma->nome }} ({{ $turma->letra }} - {{ $turma->nivel->abreviacao ?? 'N/A' }} - {{ $turma->curso->abreviacao ?? 'N/A' }} - {{ $turma->turno->abreviacao }} - Vagas: {{ $turma->vaga }})
                                    </option>
                                @endforeach
                            </select>
                            @error('turma_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="data_matricula" class="form-label">Data da Matrícula</label>
                            <input type="date" id="data_matricula" name="data_matricula" class="form-control @error('data_matricula') is-invalid @enderror"
                                   value="{{ old('data_matricula', $matricula->data_matricula->format('Y-m-d')) }}">
                            @error('data_matricula')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-control @error('status') is-invalid @enderror">
                                <option value="">Selecione o status</option>
                                <option value="ativo" {{ $matricula->status == 'ativo' ? 'selected' : '' }}>Ativo</option>
                                <option value="inativo" {{ $matricula->status == 'inativo' ? 'selected' : '' }}>Inativo</option>
                                <option value="desistente" {{ $matricula->status == 'desistente' ? 'selected' : '' }}>Desistente</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary" {{ $turmas->isEmpty() ? 'disabled' : '' }}>Salvar</button>
                        <a href="{{ route('Listar-Matriculas') }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
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
    .alert-danger, .alert-warning {
        font-size: 0.875rem;
    }
</style>
@endpush