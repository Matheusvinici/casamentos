@extends('layouts.guest')

@section('title', 'Criar Conta - Nosso Casamento')

@section('content')
    <h2 class="auth-title">Criar sua conta</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Nome -->
        <div class="form-group">
            <label class="form-label-custom" for="name">Nome Completo</label>
            <input type="text"
                   id="name"
                   name="name"
                   class="form-control-custom @error('name') is-invalid @enderror"
                   placeholder="Seu nome completo"
                   value="{{ old('name') }}"
                   required
                   autofocus>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email -->
        <div class="form-group">
            <label class="form-label-custom" for="email">E-mail</label>
            <input type="email"
                   id="email"
                   name="email"
                   class="form-control-custom @error('email') is-invalid @enderror"
                   placeholder="seu@email.com"
                   value="{{ old('email') }}"
                   required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Telefone -->
        <div class="form-group">
            <label class="form-label-custom" for="phone1">Telefone com DDD</label>
            <input type="text"
                   id="phone1"
                   name="phone1"
                   class="form-control-custom @error('phone1') is-invalid @enderror"
                   placeholder="(87) 99999-9999"
                   value="{{ old('phone1') }}"
                   required>
            @error('phone1')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Senha -->
        <div class="form-group">
            <label class="form-label-custom" for="password">Senha</label>
            <input type="password"
                   id="password"
                   name="password"
                   class="form-control-custom @error('password') is-invalid @enderror"
                   placeholder="Mínimo 8 caracteres"
                   required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirmar Senha -->
        <div class="form-group">
            <label class="form-label-custom" for="password_confirmation">Confirmar Senha</label>
            <input type="password"
                   id="password_confirmation"
                   name="password_confirmation"
                   class="form-control-custom"
                   placeholder="Repita a senha"
                   required>
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-auth">
            <i class="fas fa-user-plus me-2"></i> Criar Conta
        </button>
    </form>

    <div class="auth-links">
        <p>Já tem uma conta? <a href="{{ route('login') }}"><strong>Fazer login</strong></a></p>
        <a href="/" style="font-size: 0.8rem; color: var(--text-light);">
            <i class="fas fa-arrow-left me-1"></i> Voltar ao site
        </a>
    </div>
@endsection