@extends('layouts.guest')

@section('title', 'Entrar - Nosso Casamento')

@section('content')
    @if(session()->has('url.intended') && str_contains(session('url.intended'), 'confirmacao'))
        <div style="background: linear-gradient(135deg, rgba(201,168,124,0.15), rgba(212,168,83,0.1)); border: 1px solid rgba(201,168,124,0.3); border-radius: 10px; padding: 1rem 1.2rem; margin-bottom: 1.5rem; text-align: center;">
            <i class="fas fa-calendar-check" style="color: var(--rose-dark); font-size: 1.3rem; margin-bottom: 0.3rem; display: block;"></i>
            <strong style="color: var(--text-dark); font-size: 0.9rem;">Confirme sua Presença</strong>
            <p style="color: var(--text-medium); font-size: 0.8rem; margin: 0.3rem 0 0;">
                Faça login para confirmar presença no casamento de Mary & Matheus.
            </p>
        </div>
    @endif

    <h2 class="auth-title">Bem-vindo(a) de volta</h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="form-group">
            <label class="form-label-custom" for="email">E-mail</label>
            <input type="email"
                   id="email"
                   name="email"
                   class="form-control-custom @error('email') is-invalid @enderror"
                   placeholder="seu@email.com"
                   value="{{ old('email') }}"
                   required
                   autofocus>
            @error('email')
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
                   placeholder="••••••••"
                   required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remember -->
        <div class="remember-check">
            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label for="remember">Lembrar de mim</label>
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-auth">
            <i class="fas fa-sign-in-alt me-2"></i> Entrar
        </button>
    </form>

    <div class="auth-links">
        <p>Não tem conta? <a href="{{ route('register') }}"><strong>Criar conta</strong></a></p>
        <a href="/" style="font-size: 0.8rem; color: var(--text-light);">
            <i class="fas fa-arrow-left me-1"></i> Voltar ao site
        </a>
    </div>
@endsection

@push('scripts')
<script>
    // Check if there's an intended gift stored
    document.addEventListener('DOMContentLoaded', function() {
        const intendedGift = localStorage.getItem('intended_gift');
        if (intendedGift) {
            // Add hidden input for intended gift
            const form = document.querySelector('form');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'intended_gift';
            input.value = intendedGift;
            form.appendChild(input);
            localStorage.removeItem('intended_gift');
        }
    });
</script>
@endpush