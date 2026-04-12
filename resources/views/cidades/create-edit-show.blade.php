@extends('layouts.app')

@section('title', $edit ? 'Editar Cidade' : ($show ? 'Visualizar Cidade' : 'Nova Cidade'))

@section('content')
<div class="card">
    <div class="card-header">
        <div class="page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>
                        <h5 class="m-0 text-dark">{{ $edit ? 'Editar Cidade' : ($show ? 'Visualizar Cidade' : 'Nova Cidade') }}</h5>
                        <p class="text-muted">Gerencie as cidades</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-3 py-md-4">
        <div class="card shadow-xl rounded-1 mx-2 mb-3">
            <div class="card-header text-gray py-3 card-border">
                <h4 class="h5 mb-0 fw-semibold">{{ $edit ? 'Editar Cidade' : ($show ? 'Visualizar Cidade' : 'Cadastrar Cidade') }}</h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ $edit ? route('cidades.update', $cidade->id) : route('cidades.store') }}" method="POST">
                    @csrf
                    @if($edit) @method('PUT') @endif

                    <div class="form-group mb-2">
                        <label for="estado_id">Estado</label>
                        <select class="form-control @error('estado_id') is-invalid @enderror" id="estado_id" name="estado_id" {{ $show ? 'disabled' : '' }}>
                            <option value="">Selecione um estado</option>
                            @foreach($estados as $estado)
                                <option value="{{ $estado->id }}" {{ (isset($cidade) && $cidade->estado_id == $estado->id) ? 'selected' : '' }}>
                                    {{ $estado->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('estado_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-2">
                        <label for="nome">Nome da Cidade</label>
                        <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome" 
                               value="{{ old('nome', isset($cidade) ? $cidade->nome : '') }}" 
                               placeholder="Digite o nome da cidade" {{ $show ? 'disabled' : '' }}>
                        @error('nome')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-2">
                        <label for="codigo_ibge">Código IBGE</label>
                        <input type="text" class="form-control @error('codigo_ibge') is-invalid @enderror" id="codigo_ibge" name="codigo_ibge" 
                               value="{{ old('codigo_ibge', isset($cidade) ? $cidade->codigo_ibge : '') }}" 
                               placeholder="Digite o código IBGE" {{ $show ? 'disabled' : '' }}>
                        @error('codigo_ibge')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if(!$show)
                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-primary">
                                {{ $edit ? 'Atualizar' : 'Cadastrar' }}
                            </button>
                            <a href="{{ route('cidades.index') }}" class="btn btn-outline-secondary">
                                Voltar
                            </a>
                        </div>
                    @else
                        <div class="d-flex gap-2 mt-3">
                            <a href="{{ route('cidades.index') }}" class="btn btn-outline-secondary">
                                Voltar
                            </a>
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