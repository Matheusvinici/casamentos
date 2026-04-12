@extends('layouts.app')

@section('title', 'Detalhes do Professor')

@section('content')
 
<div class="card">
    <div class="card-header">
        <div class="page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>
                        <h5 class="m-0 text-dark">Detalhes do Professor</h5>
                        <p class="text-muted">Informações do professor</p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('Ver-Professor', $professor->id) }}" type="button" class="btn btn-outline-warning btn-sm">
                        Editar
                    </a>
                    <a href="{{ route('Listar-Professores') }}" type="button" class="btn btn-outline-secondary btn-sm">
                        Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid py-3 py-md-4">
        <div class="card shadow-xl rounded-1 mx-2 mb-3">
            <div class="card-header text-gray py-3 card-border">
                <h4 class="h5 mb-0 fw-semibold">Informações</h4>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="fw-bold">ID</label>
                            <p class="mb-0">{{ $professor->id }}</p>
                        </div>
                        <div class="form-group mb-3">
                            <label class="fw-bold">Nome</label>
                            <p class="mb-0">{{ $professor->nome }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="fw-bold">CPF</label>
                            <p class="mb-0">{{ $professor->cpf }}</p>
                        </div>
                        <div class="form-group mb-3">
                            <label class="fw-bold">Email</label>
                            <p class="mb-0">{{ $professor->email }}</p>
                        </div>
                        <div class="form-group mb-3">
                            <label class="fw-bold">Telefone</label>
                            <p class="mb-0">{{ $professor->telefone }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('page_css')
<style>
    .card {
        transition: all 0.3s ease;
    }
    .card-border {
        border-left: 0.2rem solid #ff717a !important;
        height: 55px;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    @media (max-width: 768px) {
        .form-group {
            margin-bottom: 1rem;
        }
    }
</style>
@endpush
@endsection