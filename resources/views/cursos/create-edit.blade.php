@extends('layouts.app')

@section('title', \Route::currentRouteName() === 'Ver-Curso' ? 'Visualizar Curso' : (\Route::currentRouteName() === 'Editar-Curso' ? 'Editar Curso' : 'Novo Curso'))

@section('content')
<div class="card">
    <div class="card-header">
        <div class="page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>
                        <h5 class="m-0 text-dark">
                            {{ \Route::currentRouteName() === 'Ver-Curso' ? 'Visualizar Curso' : (\Route::currentRouteName() === 'Editar-Curso' ? 'Editar Curso' : 'Novo Curso') }}
                        </h5>
                        <p class="text-muted">Gerencie os cursos da Escola de Idiomas de Juazeiro-BA</p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('Listar-Cursos') }}" class="btn btn-outline-secondary btn-sm">Voltar</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-3 py-md-4">
        <div class="card shadow-xl rounded-1 mx-2 mb-3">
            <div class="card-header text-gray py-3 card-border">
                <h4 class="h5 mb-0 fw-semibold">
                    {{ \Route::currentRouteName() === 'Ver-Curso' ? 'Detalhes do Curso' : (\Route::currentRouteName() === 'Editar-Curso' ? 'Editar Curso' : 'Novo Curso') }}
                </h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ \Route::currentRouteName() === 'Editar-Curso' ? route('Atualizar-Curso', $curso->id) : route('Gravar-Curso') }}" method="POST">
                    @csrf
                    @if(\Route::currentRouteName() === 'Editar-Curso')
                        @method('PUT')
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nome" class="form-label">Nome do Curso</label>
                            <input type="text" name="nome" id="nome" class="form-control @error('nome') is-invalid @enderror" 
                                   placeholder="Digite o nome do curso" 
                                   value="{{ old('nome', isset($curso) ? $curso->nome : '') }}" 
                                   {{ \Route::currentRouteName() === 'Ver-Curso' ? 'readonly' : '' }}>
                            @error('nome')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="abreviacao" class="form-label">Abreviação</label>
                            <input type="text" name="abreviacao" id="abreviacao" class="form-control @error('abreviacao') is-invalid @enderror" 
                                   placeholder="Digite a abreviação do curso" 
                                   value="{{ old('abreviacao', isset($curso) ? $curso->abreviacao : '') }}" 
                                   maxlength="10" 
                                   {{ \Route::currentRouteName() === 'Ver-Curso' ? 'readonly' : '' }}>
                            @error('abreviacao')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    @if(\Route::currentRouteName() !== 'Ver-Curso')
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-outline-primary">
                                {{ \Route::currentRouteName() === 'Editar-Curso' ? 'Atualizar' : 'Criar' }}
                            </button>
                            <a href="{{ route('Listar-Cursos') }}" class="btn btn-outline-secondary">Cancelar</a>
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