@extends('layouts.app')

@section('title', 'Lista de Letivos')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>
                        <h5 class="m-0 text-dark">Listar dias letivos</h5>
                        <p class="text-muted">Gerencie os dias letivos da Escola de Idiomas de Juazeiro-BA</p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('Criar-Letivo') }}" class="btn btn-outline-primary btn-sm">Novo dia letivo</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-3 py-md-4">
        @livewire('letivos.letivos-index')
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
