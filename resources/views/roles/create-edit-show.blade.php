@extends('layouts.app')

@section('title', $create ? 'Adicionar Papel' : ($edit ? 'Editar Papel' : 'Visualizar Papel'))

@section('content-title')
    {{ $create ? __('Adicionar Papel') : ($edit ? __('Editar Papel') : __('Visualizar Papel')) }}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('Listar-Papeis') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
            @if(!$create)
                <a href="{{ route('Editar-Papel', $role) }}" class="btn btn-primary float-right">
                    <i class="fas fa-edit"></i> Editar
                </a>
            @endif
        </div>
        <div class="card-body">
            <!-- Display Validation Errors -->
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ $edit ? route('Atualizar-Papel', $role) : ($create ? route('Gravar-Papel') : '#') }}">
                @csrf
                @if($edit)
                    @method('PUT')
                @endif

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">{{ __('Nome') }}</label>
                        <input id="name" {{ $show ? 'disabled' : '' }} class="form-control" type="text" name="name" value="{{ $show || $edit ? ($role->name ?? old('name')) : old('name') }}" required autocomplete="name">
                        @error('name')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __('Permissões') }}</label>
                    <div class="row">
                        @foreach($groupedPermissions as $prefix => $permissions)
                            <div class="col-md-4">
                                <h5 class="font-weight-bold">{{ ucfirst($prefix) }}</h5>
                                @foreach($permissions as $value)
                                    <div class="form-check">
                                        <input type="checkbox"
                                               {{ ($edit || $show) && isset($role) && $role->permissions->contains($value) ? 'checked' : '' }}
                                               {{ $show ? 'disabled' : '' }}
                                               name="permission[{{ $value->id }}]"
                                               value="{{ $value->id }}"
                                               class="form-check-input"
                                               id="perm-{{ $value->id }}">
                                        <label class="form-check-label" for="perm-{{ $value->id }}">{{ $value->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                    @error('permission.*')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                @if($edit || $create)
                    <button type="submit" class="btn btn-primary">
                        {{ __('Salvar') }}
                    </button>
                @endif
            </form>
        </div>
    </div>
@endsection