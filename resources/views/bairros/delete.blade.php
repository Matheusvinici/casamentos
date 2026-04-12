@extends('layouts.app')

@section('title', 'Excluir Bairro')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>
                        <h5 class="m-0 text-dark">Excluir Bairro</h5>
                        <p class="text-muted">Gerencie os bairros da Escola de Idiomas de Juazeiro-BA</p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('bairros.index') }}" class="btn btn-outline-secondary btn-sm">Voltar</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-3 py-md-4">
        <div class="card shadow-xl rounded-1 mx-2 mb-3">
            <div class="card-header text-gray py-3 card-border">
                <h4 class="h5 mb-0 fw-semibold">Confirmar Exclusão</h4>
            </div>
            <div class="card-body p-4">
                <p>Tem certeza que deseja excluir o bairro <strong>{{ $bairro->nome }}</strong>? Esta ação não pode ser desfeita.</p>
                <form action="{{ route('bairros.destroy', $bairro->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-outline-danger">Excluir</button>
                        <a href="{{ route('bairros.index') }}" class="btn btn-outline-secondary">Cancelar</a>
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
</style>
@endpush