@extends('layouts.app')

@section('title', __('Gerenciamento de Usuários'))

@section('content-title', __('Gerenciamento de Usuários'))

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mt-4">Gerenciamento de Usuários</h1>
            <a href="{{ route('Criar-Usuario') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Novo Usuário
            </a>
        </div>

        <div class="card mb-4 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center bg-white">
                <div class="w-50">
                    <form method="GET" action="{{ route('Listar-Usuarios') }}">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control"
                                   placeholder="Pesquisar por nome ou email..." value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <span class="badge bg-info text-dark">
                    {{ auth()->user()->roles->first()->name ?? 'Nenhum Papel' }}
                </span>
            </div>

            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger mb-4">
                        {{ session('error') }}
                    </div>
                @endif
                @if (session('warning'))
                    <div class="alert alert-warning mb-4">
                        {{ session('warning') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Papel</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge
                                            @if($user->hasRole('Administrador')) bg-danger
                                            @elseif($user->hasRole('Secretaria')) bg-primary
                                            @elseif($user->hasRole('Professor')) bg-success
                                            @else bg-secondary
                                            @endif">
                                            {{ $user->roles->first()->name ?? 'Nenhum Papel' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('Ver-Usuario', $user->id) }}"
                                               class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('Editar-Usuario', $user->id) }}"
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('Deletar-Usuario', $user->id) }}" method="POST"
                                                  class="d-inline" onsubmit="return confirm('Tem certeza?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Nenhum usuário encontrado</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $users->appends(['search' => request('search')])->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection