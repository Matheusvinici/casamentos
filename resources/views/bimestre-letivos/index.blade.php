@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Bimestres Letivos</h1>
    <a href="{{ route('bimestre-letivos.create') }}" class="btn btn-primary mb-3">Novo Bimestre</a>
    
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ano</th>
                    <th>Nome</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bimestres as $bimestre)
                <tr>
                    <td>{{ $bimestre->id }}</td>
                    <td>{{ $bimestre->ano }}</td>
                    <td>{{ $bimestre->nome }}</td>
                    <td>
                        <a href="{{ route('bimestre-letivos.show', $bimestre->id) }}" class="btn btn-info btn-sm">Ver</a>
                        <a href="{{ route('bimestre-letivos.edit', $bimestre->id) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('bimestre-letivos.destroy', $bimestre->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza?')">Excluir</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $bimestres->links() }}
    </div>
</div>
@endsection