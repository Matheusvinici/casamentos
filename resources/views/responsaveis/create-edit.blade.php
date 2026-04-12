@extends('layouts.app')

@section('title', isset($responsavel) ? 'Editar Responsável' : 'Criar Novo Responsável')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>
                        <h5 class="m-0 text-dark">{{ isset($responsavel) ? 'Editar Responsável' : 'Criar Novo Responsável' }}</h5>
                        <p class="text-muted">Gerencie os dados do responsável da Escola de Idiomas de Juazeiro-BA</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid py-3 py-md-4">
        <div class="card shadow-xl rounded-1 mx-2 mb-3">
            <div class="card-header text-gray py-3 card-border">
                <h4 class="h5 mb-0 fw-semibold">{{ isset($responsavel) ? 'Editar Responsável' : 'Novo Responsável' }}</h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ isset($responsavel) ? route('Atualizar-Responsavel', $responsavel->id) : route('Gravar-Responsavel') }}" method="POST">
                    @csrf
                    @if(isset($responsavel))
                        @method('PUT')
                    @endif
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nome" class="form-label fw-semibold">Nome</label>
                                <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome" 
                                       value="{{ old('nome', $responsavel->nome ?? '') }}" 
                                       placeholder="Digite o nome completo do responsável" required>
                                @error('nome')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="telefone" class="form-label fw-semibold">Telefone</label>
                                <input type="text" class="form-control @error('telefone') is-invalid @enderror" id="telefone" name="telefone" 
                                       value="{{ old('telefone', $responsavel->telefone ?? '') }}" 
                                       placeholder="(88) 99999-9999" required>
                                @error('telefone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cpf" class="form-label fw-semibold">CPF</label>
                                <input type="text" class="form-control @error('cpf') is-invalid @enderror" id="cpf" name="cpf" 
                                       value="{{ old('cpf', $responsavel->cpf ?? '') }}" 
                                       placeholder="123.456.789-00" required>
                                @error('cpf')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" 
                                       value="{{ old('email', $responsavel->email ?? '') }}" 
                                       placeholder="exemplo@dominio.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="endereco" class="form-label fw-semibold">Endereço</label>
                        <input type="text" class="form-control @error('endereco') is-invalid @enderror" id="endereco" name="endereco" 
                               value="{{ old('endereco', $responsavel->endereco ?? '') }}" 
                               placeholder="Rua, número, bairro, cidade - BA" required>
                        @error('endereco')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary">Salvar</button>
                        <a href="{{ route('Listar-Responsaveis') }}" class="btn btn-outline-secondary">Cancelar</a>
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
    .form-label {
        margin-bottom: 0.5rem;
        color: #495057;
    }
    .form-control::placeholder {
        color: #6c757d;
        opacity: 0.8;
    }
    @media (max-width: 768px) {
        .form-label {
            font-size: 0.9rem;
        }
        .btn {
            width: 100%;
            margin-bottom: 0.5rem;
        }
        .d-flex {
            flex-direction: column;
        }
    }
</style>
@endpush