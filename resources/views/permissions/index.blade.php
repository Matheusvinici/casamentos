@extends('layouts.app')

@section('content')
    <div class="bg-light rounded">
        <div class="card m-4">
            <div class="card-header">
                <h5>Permissões</h5>
                <a href="{{ route('permissions.create') }}" class="btn btn-primary btn-sm">Adicionar</a>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col" width="1%"></th>
                            <th scope="col" width="15%">Nome</th>
                            <th scope="col">Prefixo</th>
                            <th scope="col">Guard</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permissions as $permission)
                            <tr>
                                <td>
                                    <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-info btn-sm">Editar</a>
                                </td>
                                <td>{{ $permission->name }}</td>
                                <td>{{ $permission->prefix ?? 'Nenhum' }}</td>
                                <td>{{ $permission->guard_name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex mt-3">
                    {{ $permissions->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection