@extends('layouts.app')

@section('title', 'Editar Dia Letivo')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Cabeçalho Simples -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h4 mb-2 text-dark">
                        <i class="fas fa-calendar-edit text-warning me-2"></i>
                        Editar Dia Letivo
                    </h1>
                    <p class="text-muted mb-0">Atualize as informações do dia letivo selecionado</p>
                </div>
                <div>
                    <a href="{{ route('Listar-Letivos') }}" class="btn btn-outline-secondary btn-sm px-3">
                        <i class="fas fa-arrow-left me-1"></i> Voltar
                    </a>
                </div>
            </div>

            <!-- Card Principal -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-medium">
                            Formulário de Edição
                        </h5>
                        @if(isset($calendarioVisualizacao))
                            <span class="badge bg-light text-dark border small">
                                <i class="fas fa-calendar-alt me-1"></i>
                                {{ $calendarioVisualizacao->nomeCompleto }}
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="card-body p-4">
                    @livewire('letivos.letivos-edit', ['id' => $id])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_css')
<style>
    .card {
        border-radius: 10px;
        border: 1px solid #e9ecef;
        overflow: hidden;
    }
    
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
    }
    
    .btn {
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.85rem;
        transition: all 0.2s ease;
    }
    
    .btn-sm {
        padding: 0.35rem 0.75rem;
    }
    
    .btn-outline-secondary {
        color: #6c757d;
        border-color: #dee2e6;
    }
    
    .btn-outline-secondary:hover {
        background-color: #f8f9fa;
        border-color: #adb5bd;
        color: #495057;
        transform: translateY(-1px);
    }
    
    .badge {
        padding: 0.35rem 0.65rem;
        font-weight: 500;
        font-size: 0.75rem;
        border-radius: 20px;
    }
    
    h1, h4, h5 {
        color: #212529;
    }
    
    .text-muted {
        color: #6c757d !important;
    }
    
    /* Responsividade */
    @media (max-width: 768px) {
        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        
        .card-body {
            padding: 1.5rem !important;
        }
    }
</style>
@endpush