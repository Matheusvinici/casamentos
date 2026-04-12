@extends('layouts.app')

@section('title', 'Minhas Turmas')

@section('content')
<div class="mx-4 my-4">
    <div class="card shadow-sm rounded-3">
        <div class="card-header card-header-neutral text-gray d-flex align-items-center" style="border-left: 0.2rem solid #ff717a !important;">
            <div class="page-title-wrapper d-flex justify-content-between align-items-center w-100">
                <div class="page-title-heading">
                    <h5 class="m-0 fw-semibold">Minhas Turmas</h5>
                    <p class="text-muted opacity-75 mb-0">Gerencie as turmas com matrículas ativas</p>
                </div>
                <div>
                    <a href="{{ route('professor.dashboard') }}" class="btn btn-secondary btn-sm btn-action">
                        <i class="bi bi-arrow-left me-2"></i>Voltar
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card-body p-4">
            {{-- Alertas de sucesso ou erro --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                @forelse($turmas as $turma)
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 shadow-sm rounded-3">
                            <div class="card-header card-header-neutral text-gray">
                                <h6 class="m-0 fw-semibold">{{ $turma->nome }}</h6>
                            </div>
                            <div class="card-body text-center">
                                <p class="text-muted small mb-2">Curso: {{ $turma->curso->abreviacao ?? 'N/A' }}</p>
                                <p class="text-muted small mb-2">Turno: {{ $turma->turno->abreviacao ?? 'N/A' }}</p>
                                <p class="text-muted small mb-3">Matrículas Ativas: {{ $turma->matriculas()->where('status', 'ativo')->count() }}</p>
                                
                                <div class="row g-2">
                                    <div class="col-4">
                                        <a href="{{ route('Criar-Frequencia-Professor', ['turma_id' => $turma->id]) }}" 
                                           class="btn btn-outline-primary btn-sm btn-action w-100">
                                           Frequência
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="{{ route('Criar-Nota-Professor', ['turma_id' => $turma->id]) }}" 
                                           class="btn btn-outline-success btn-sm btn-action w-100">
                                           Notas
                                        </a>
                                    </div>
                                   <div class="col-4">
                                        <a href="{{ route('Criar-Conteudo-Professor', ['turma_id' => $turma->id]) }}" 
                                        class="btn btn-outline-success btn-sm btn-action w-100">
                                            Reg. de Aulas
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info rounded-3">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            Nenhuma turma com matrículas ativas encontrada.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('page_css')
<style>
    .card {
        transition: all 0.3s ease;
        border-radius: 0.75rem;
        border: 1px solid #e5e7eb !important;
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
    .alert {
        border-radius: 0.5rem;
        padding: 1rem;
        font-size: 0.9rem;
    }
    .alert-success {
        background-color: #d4edda !important;
        border-left: 4px solid #28a745 !important;
        color: #155724;
    }
    .alert-danger {
        background-color: #f8d7da !important;
        border-left: 4px solid #dc3545 !important;
        color: #721c24;
    }
    .alert-info {
        border-radius: 0.5rem;
        border-left: 4px solid #d97706 !important;
        background: #fef3c7 !important;
        padding: 1rem;
        font-size: 0.9rem;
    }
    .card-body h3 {
        font-size: 2rem;
        font-weight: bold;
    }
    @media (max-width: 768px) {
        .content-wrapper .card .card-header.card-header-neutral {
            min-height: auto !important;
            padding: 1.5rem !important;
        }
        .row > .col-md-6, .row > .col-md-4 {
            width: 100%;
        }
        .btn-action {
            width: 100%;
            text-align: center;
        }
    }
</style>
@endpush
@endsection