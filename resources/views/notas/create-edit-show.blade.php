@extends('layouts.app')

@section('title', $mode == 'create' ? 'Lançar Notas' : ($mode == 'edit' ? 'Editar Nota' : 'Visualizar Nota'))

@section('content')
<div class="mx-4 my-4">
    {{-- Cabeçalho com informações da turma --}}
    @if(isset($turma) && $turma)
        <div class="card shadow-sm rounded-3 mb-4">
            <div class="card-header card-header-neutral text-gray" style="border-left: 0.2rem solid #ff717a !important;">
                <h5 class="m-0 fw-semibold">Turma: {{ $turma->nome }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <small class="text-muted d-block">Unidade</small>
                        <strong>{{ $turma->unidade->nome ?? 'N/A' }}</strong>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">Curso</small>
                        <strong>{{ $turma->curso->nome ?? 'N/A' }}</strong>
                    </div>
                    <div class="col-md-2">
                        <small class="text-muted d-block">Nível</small>
                        <strong>{{ $turma->nivel->nome ?? 'N/A' }}</strong>
                    </div>
                    <div class="col-md-2">
                        <small class="text-muted d-block">Turno</small>
                        <strong>{{ $turma->turno->nome ?? 'N/A' }}</strong>
                    </div>
                    <div class="col-md-2">
                        <small class="text-muted d-block">Professor</small>
                        <strong>{{ $turma->professor->nome ?? 'N/A' }}</strong>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Chamada do Livewire UNIFICADO --}}
    <livewire:notas.notas-create-edit-show 
        :turma_id="$turma_id" 
        :tipo_avaliacao_id="$tipo_avaliacao_id ?? null" 
        :aluno_id="$aluno_id ?? null" 
        :mode="$mode" 
    />
</div>
@endsection

@push('page_css')
<style>
    .card {
        transition: all 0.3s ease;
        border-radius: 0.75rem;
        border: 1px solid #e5e7eb !important;
    }
    .card-header.card-header-neutral {
        background: #f8f9fa !important;
        border-left: 0.2rem solid #ff717a !important;
        border-right: none !important;
        border-top: none !important;
        border-bottom: none !important;
        min-height: 55px;
        padding: 1rem !important;
    }
    .badge-success {
        background-color: #28a745;
        color: white;
        padding: 0.25em 0.5em;
        border-radius: 0.25rem;
        font-weight: bold;
    }
    .badge-warning {
        background-color: #ffc107;
        color: #212529;
        padding: 0.25em 0.5em;
        border-radius: 0.25rem;
        font-weight: bold;
    }
    .badge-danger {
        background-color: #dc3545;
        color: white;
        padding: 0.25em 0.5em;
        border-radius: 0.25rem;
        font-weight: bold;
    }
    .badge-info {
        background-color: #17a2b8;
        color: white;
        padding: 0.25em 0.5em;
        border-radius: 0.25rem;
        font-weight: bold;
    }
    @media (max-width: 768px) {
        .card-header.card-header-neutral {
            min-height: auto !important;
            padding: 1.5rem !important;
        }
    }
</style>
@endpush