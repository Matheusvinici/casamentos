@extends('layouts.app')

@section('title', 'Dashboard do Professor')

@section('content')
<div class="mx-4 my-4">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header card-header-neutral text-gray d-flex align-items-center" style="border-left: 0.2rem solid #ff717a !important;">
            <div class="page-title-wrapper d-flex justify-content-between align-items-center w-100">
                <div class="page-title-heading">
                    @if(isset($professor->nome))
                        <h5 class="m-0 fw-semibold">Bem-vindo, {{ $professor->nome }}!</h5>
                    @else
                        <h5 class="m-0 fw-semibold">Bem-vindo!</h5>
                    @endif
                    <p class="text-muted opacity-75 mb-0">Seu painel para gerenciar turmas e frequências</p>
                </div>
                @if(isset($calendarioAtivo) && $calendarioAtivo)
                <div class="page-title-actions">
                    <span class="badge bg-info text-white">
                        <i class="bi bi-calendar-event me-1"></i>
                        {{ $calendarioAtivo->nome }} ({{ $calendarioAtivo->ano }})
                    </span>
                </div>
                @endif
            </div>
        </div>

        <div class="card-body p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4 rounded-3" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4 rounded-3" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="card h-100 border-0 shadow-sm rounded-3">
                        <div class="card-body text-center">
                            <h6 class="fw-semibold text-muted">Turmas com Matrículas</h6>
                            <h3 class="text-primary">{{ $turmasCount }}</h3>
                            <p class="text-muted small mb-3">
                                <i class="bi bi-calendar-check me-1"></i>
                                @if(isset($calendarioAtivo) && $calendarioAtivo)
                                    No calendário: {{ $calendarioAtivo->nome }}
                                @else
                                    Nenhum calendário ativo
                                @endif
                            </p>
                            <a href="{{ route('Mostrar-Turmas-Professor') }}" class="btn btn-primary btn-sm btn-action">
                                <i class="bi bi-people-fill me-2"></i>Ver Minhas Turmas
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card h-100 border-0 shadow-sm rounded-3">
                        <div class="card-body text-center">
                            <h6 class="fw-semibold text-muted">Total de Aulas</h6>
                            <h3 class="text-primary">{{ $aulasCount }}</h3>
                            <p class="text-muted small mb-3">
                                <i class="bi bi-calendar-check me-1"></i>
                                @if(isset($calendarioAtivo) && $calendarioAtivo)
                                    No calendário: {{ $calendarioAtivo->nome }}
                                @else
                                    Nenhum calendário ativo
                                @endif
                            </p>
                            <a href="{{ route('Listar-Frequencias-Professor') }}" class="btn btn-primary btn-sm btn-action">
                                <i class="bi bi-calendar-check-fill me-2"></i>Ver Frequências
                            </a>
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
        border-radius: 0.75rem;
        border: none !important;
    }
    .content-wrapper .card .card-header.card-header-neutral {
        background: #f8f9fa !important;
        border-left: 0.2rem solid #ff717a !important;
        border-right: none !important;
        border-top: none !important;
        border-bottom: none !important;
        min-height: 55px;
        padding: 1rem !important;
        transition: all 0.3s ease !important;
        overflow: visible !important;
        box-shadow: none !important;
    }
    .text-gray {
        color: #333 !important;
    }
    .btn-action {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .card-body h3 {
        font-size: 2rem;
        font-weight: bold;
    }
    .badge {
        font-size: 0.85rem;
        padding: 0.5rem 1rem;
        border-radius: 2rem;
    }
</style>
@endpush
@endsection