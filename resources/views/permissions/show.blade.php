@extends('layouts.app')

@section('content')
    <div class="bg-light rounded">
        <div class="card m-4">
            <div class="card-header">
                <h5>Detalhes da Permissão</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Nome</label>
                    <p>{{ $permission->name }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label">Prefixo</label>
                    <p>{{ $permission->prefix ?? 'Nenhum' }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label">Guard</label>
                    <p>{{ $permission->guard_name }}</p>
                </div>
                <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-info">Editar</a>
                <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Voltar</a>
            </div>
        </div>
    </div>
@endsection