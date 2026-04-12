@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Detalhes do Usuário</h4>
                    <span class="badge bg-primary">{{ $user->roles->first()->name ?? 'Nenhum' }}</span>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Nome:</div>
                        <div class="col-md-8">{{ $user->name }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Email:</div>
                        <div class="col-md-8">{{ $user->email }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Função:</div>
                        <div class="col-md-8">{{ $user->roles->first()->name ?? 'Nenhum' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Cadastrado em:</div>
                        <div class="col-md-8">{{ $user->created_at->format('d/m/Y H:i:s') }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Última atualização:</div>
                        <div class="col-md-8">{{ $user->updated_at->format('d/m/Y H:i:s') }}</div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('Editar-Usuario', $user->id) }}" class="btn btn-primary me-2">
                            <i class="fas fa-edit me-1"></i> Editar
                        </a>
                        <a href="{{ route('Listar-Usuarios') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Voltar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection