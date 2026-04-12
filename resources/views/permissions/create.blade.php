@extends('layouts.app')

@section('content')
    <div class="bg-light rounded">
        <div class="card m-4">
            <div class="card-header">
                <h5>Adicionar Permissão</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('permissions.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome</label>
                        <input value="{{ old('name') }}" type="text" class="form-control" name="name" placeholder="Nome da permissão" required>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="prefix" class="form-label">Prefixo (opcional)</label>
                        <input value="{{ old('prefix') }}" type="text" class="form-control" name="prefix" placeholder="Prefixo da permissão">
                        @error('prefix')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Voltar</a>
                </form>
            </div>
        </div>
    </div>
@endsection