@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ isset($bimestreLetivo) ? 'Editar Bimestre Letivo' : 'Criar Bimestre Letivo' }}</h1>
    
    <form action="{{ isset($bimestreLetivo) ? route('bimestre-letivos.update', $bimestreLetivo->id) : route('bimestre-letivos.store') }}" method="POST">
        @csrf
        @if(isset($bimestreLetivo))
            @method('PUT')
        @endif
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="ano">Ano</label>
                    <input type="number" class="form-control" id="ano" name="ano" 
                           value="{{ old('ano', $bimestreLetivo->ano ?? '') }}" min="2000" max="2100" required>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nome">Nome do Bimestre</label>
                    <input type="text" class="form-control" id="nome" name="nome" 
                           value="{{ old('nome', $bimestreLetivo->nome ?? '') }}" required>
                </div>
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('bimestre-letivos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection