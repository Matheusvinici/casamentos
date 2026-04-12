@extends('layouts.app')

@section('title', 'Visualizar Turma')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>
                        <h5 class="m-0 text-dark">Visualizar Turma</h5>
                        <p class="text-muted">Gerencie as turmas da Escola de Idiomas de Juazeiro-BA</p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('turmas.index') }}" class="btn btn-outline-secondary btn-sm">Voltar</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-3 py-md-4">
        <div class="card shadow-xl rounded-1 mx-2 mb-3">
            <div class="card-header text-gray py-3 card-border">
                <h4 class="h5 mb-0 fw-semibold">Detalhes da Turma</h4>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nome da Turma</label>
                        <p class="form-control-plaintext">{{ $turma->nome }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Capacidade</label>
                        <p class="form-control-plaintext">{{ $turma->capacidade }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Vagas</label>
                        <p class="form-control-plaintext">{{ $turma->vaga }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Unidade</label>
                        <p class="form-control-plaintext">{{ $turma->unidade->nome ?? 'Sem unidade' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Curso</label>
                        <p class="form-control-plaintext">{{ $turma->curso->abreviacao ?? 'Sem curso' }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nível</label>
                        <p class="form-control-plaintext">{{ $turma->nivel->abreviacao ?? 'Sem nível' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Turno</label>
                        <p class="form-control-plaintext">{{ $turma->turno->abreviacao ?? 'Sem turno' }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Professor</label>
                        <p class="form-control-plaintext">{{ $turma->professor->nome ?? 'Sem professor' }}</p>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('turmas.edit', $turma->id) }}" class="btn btn-outline-warning">Editar</a>
                    <a href="{{ route('turmas.index') }}" class="btn btn-outline-secondary">Voltar</a>
                </div>
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
    .form-control-plaintext {
        padding: 0.375rem 0;
        margin-bottom: 0;
        line-height: 1.5;
        color: #212529;
    }
</style>
@endpush