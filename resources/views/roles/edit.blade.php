@extends('layouts.app')

@section('title', 'Editar Papel')

@section('content-title')
    {{ __('Editar Papel') }}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('Listar-Papeis') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
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

            <form method="POST" action="{{ route('Atualizar-Papel', $role->id) }}">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">{{ __('Nome') }}</label>
                        <input id="name" class="form-control" type="text" name="name" value="{{ old('name', $role->name) }}" required autocomplete="name">
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
                                <h5 class="font-weight-bold">{{ ucfirst(str_replace('-', ' ', $prefix)) }}</h5>
                                @foreach($permissions as $value)
                                    <div class="form-check">
                                        <input type="checkbox"
                                               name="permission[]"
                                               value="{{ $value->id }}"
                                               class="form-check-input"
                                               id="perm-{{ $value->id }}"
                                               {{ isset($rolePermissions[$value->id]) ? 'checked' : '' }}>
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

                <button type="submit" class="btn btn-primary">
                    {{ __('Salvar') }}
                </button>
            </form>
        </div>
    </div>
@endsection