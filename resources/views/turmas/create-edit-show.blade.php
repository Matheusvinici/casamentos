@extends('layouts.app')

@section('title', isset($turma) ? ($turma->exists ? 'Editar Turma' : 'Visualizar Turma') : 'Nova Turma')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>
                        <h5 class="m-0 text-dark">
                            {{ isset($turma) ? ($turma->exists ? 'Editar Turma' : 'Visualizar Turma') : 'Nova Turma' }}
                        </h5>
                        <p class="text-muted">Gerencie as turmas da Escola de Idiomas de Juazeiro-BA</p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('Listar-Turmas') }}" class="btn btn-outline-secondary btn-sm">Voltar</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-3 py-md-4">
        <div class="card shadow-xl rounded-1 mx-2 mb-3">
            <div class="card-header text-gray py-3 card-border">
                <h4 class="h5 mb-0 fw-semibold">
                    {{ isset($turma) ? ($turma->exists ? 'Editar Turma' : 'Detalhes da Turma') : 'Nova Turma' }}
                </h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ isset($turma) && $turma->exists ? route('Atualizar-Turma', $turma->id) : route('Gravar-Turma') }}" method="POST">
                    @csrf
                    @if(isset($turma) && $turma->exists)
                        @method('PUT')
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nome" class="form-label">Nome da Turma</label>
                            <input type="text" name="nome" id="nome" class="form-control @error('nome') is-invalid @enderror" 
                                   placeholder="Digite o nome da turma" 
                                   value="{{ old('nome', isset($turma) ? $turma->nome : '') }}" 
                                   {{ isset($turma) && !$turma->exists ? 'readonly' : '' }}>
                            @error('nome')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="letra" class="form-label">Letra da Turma</label>
                            <select name="letra" id="letra" class="form-control @error('letra') is-invalid @enderror"
                                    {{ isset($turma) && !$turma->exists ? 'disabled' : '' }}>
                                <option value="">Selecione uma letra</option>
                                @foreach(range('A', 'Z') as $letter)
                                    <option value="{{ $letter }}" 
                                            {{ old('letra', isset($turma) ? $turma->letra : '') == $letter ? 'selected' : '' }}>
                                        {{ $letter }}
                                    </option>
                                @endforeach
                            </select>
                            @error('letra')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="capacidade" class="form-label">Capacidade</label>
                            <input type="number" name="capacidade" id="capacidade" class="form-control @error('capacidade') is-invalid @enderror" 
                                   placeholder="Digite a capacidade da turma" 
                                   value="{{ old('capacidade', isset($turma) ? $turma->capacidade : '') }}" 
                                   {{ isset($turma) && !$turma->exists ? 'readonly' : '' }}>
                            @error('capacidade')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="vaga" class="form-label">Vagas Disponíveis</label>
                            <input type="number" name="vaga" id="vaga" class="form-control @error('vaga') is-invalid @enderror" 
                                   placeholder="Digite o número de vagas" 
                                   value="{{ old('vaga', isset($turma) ? $turma->vaga : '') }}" 
                                   {{ isset($turma) && !$turma->exists ? 'readonly' : '' }}>
                            @error('vaga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="unidade_id" class="form-label">Semestre</label>
                            <select name="unidade_id" id="unidade_id" class="form-control @error('unidade_id') is-invalid @enderror"
                                    {{ isset($turma) && !$turma->exists ? 'disabled' : '' }}>
                                <option value="">Selecione uma unidade</option>
                                @foreach($unidades as $unidade)
                                    <option value="{{ $unidade->id }}" 
                                            {{ old('unidade_id', isset($turma) ? $turma->unidade_id : '') == $unidade->id ? 'selected' : '' }}>
                                        {{ $unidade->nome }}
                                    </option>
                                @endforeach
                            </select>
                            @error('unidade_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="curso_id" class="form-label">Curso</label>
                            <select name="curso_id" id="curso_id" class="form-control @error('curso_id') is-invalid @enderror"
                                    {{ isset($turma) && !$turma->exists ? 'disabled' : '' }}>
                                <option value="">Selecione um curso</option>
                                @foreach($cursos as $curso)
                                    <option value="{{ $curso->id }}" 
                                            {{ old('curso_id', isset($turma) ? $turma->curso_id : '') == $curso->id ? 'selected' : '' }}>
                                        {{ $curso->abreviacao }}
                                    </option>
                                @endforeach
                            </select>
                            @error('curso_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="categoria_id" class="form-label">Categoria</label>
                            <select name="categoria_id" id="categoria_id" class="form-control @error('categoria_id') is-invalid @enderror"
                                    {{ isset($turma) && !$turma->exists ? 'disabled' : '' }}>
                                <option value="">Selecione uma categoria</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" 
                                            {{ old('categoria_id', isset($turma) ? $turma->categoria_id : '') == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nome }}
                                    </option>
                                @endforeach
                            </select>
                            @error('categoria_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nivel_id" class="form-label">Nível</label>
                            <select name="nivel_id" id="nivel_id" class="form-control @error('nivel_id') is-invalid @enderror"
                                    {{ isset($turma) && !$turma->exists ? 'disabled' : '' }}>
                                <option value="">Selecione um nível</option>
                                @foreach($niveis as $nivel)
                                    <option value="{{ $nivel->id }}" 
                                            {{ old('nivel_id', isset($turma) ? $turma->nivel_id : '') == $nivel->id ? 'selected' : '' }}>
                                        {{ $nivel->abreviacao }}
                                    </option>
                                @endforeach
                            </select>
                            @error('nivel_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="turno_id" class="form-label">Turno</label>
                            <select name="turno_id" id="turno_id" class="form-control @error('turno_id') is-invalid @enderror"
                                    {{ isset($turma) && !$turma->exists ? 'disabled' : '' }}>
                                <option value="">Selecione um turno</option>
                                @foreach($turnos as $turno)
                                    <option value="{{ $turno->id }}" 
                                            {{ old('turno_id', isset($turma) ? $turma->turno_id : '') == $turno->id ? 'selected' : '' }}>
                                        {{ $turno->abreviacao }}
                                    </option>
                                @endforeach
                            </select>
                            @error('turno_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="professor_id" class="form-label">Professor</label>
                            <select name="professor_id" id="professor_id" class="form-control @error('professor_id') is-invalid @enderror"
                                    {{ isset($turma) && !$turma->exists ? 'disabled' : '' }}>
                                <option value="">Selecione um professor</option>
                                @foreach($professores as $professor)
                                    <option value="{{ $professor->id }}" 
                                            {{ old('professor_id', isset($turma) ? $turma->professor_id : '') == $professor->id ? 'selected' : '' }}>
                                        {{ $professor->nome }}
                                    </option>
                                @endforeach
                            </select>
                            @error('professor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    @if(!isset($turma) || $turma->exists)
                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-outline-primary">
                                {{ isset($turma) && $turma->exists ? 'Atualizar' : 'Criar' }}
                            </button>
                            <a href="{{ route('Listar-Turmas') }}" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    @endif
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
</style>
@endpush