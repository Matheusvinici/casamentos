@extends('layouts.casamento')

@section('title', 'Confirmação de Presença - Nosso Casamento')

@push('styles')
<style>
    .rsvp-page {
        padding: 4rem 0;
        background: linear-gradient(180deg, var(--cream) 0%, var(--white) 100%);
        min-height: calc(100vh - 140px);
    }
    .rsvp-card {
        background: var(--white);
        border-radius: 12px;
        padding: 3rem;
        box-shadow: 0 10px 40px rgba(201,168,124,0.1);
        border: 1px solid rgba(201,168,124,0.15);
    }
    .rsvp-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.2rem;
        color: var(--text-dark);
        margin-bottom: 1rem;
        text-align: center;
    }
    .rsvp-desc {
        text-align: center;
        color: var(--text-light);
        margin-bottom: 2rem;
    }
    .input-elegant {
        border: 1px solid rgba(201,168,124,0.4);
        border-radius: 6px;
        padding: 0.8rem 1rem;
        width: 100%;
        margin-bottom: 1rem;
        font-size: 0.95rem;
    }
    .input-elegant:focus {
        outline: none;
        border-color: var(--rose-dark);
        box-shadow: 0 0 0 3px rgba(224,152,145,0.2);
    }
    .btn-submit {
        background: var(--rose);
        color: var(--white);
        border: none;
        padding: 0.8rem 2rem;
        font-size: 0.9rem;
        font-weight: 600;
        letter-spacing: 2px;
        text-transform: uppercase;
        border-radius: 30px;
        transition: all 0.3s ease;
        width: 100%;
    }
    .btn-submit:hover {
        background: var(--rose-dark);
        transform: translateY(-2px);
    }
    .btn-desistir {
        background: transparent;
        color: #dc3545;
        border: 1px solid #dc3545;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        text-decoration: none;
        transition: all 0.3s;
    }
    .btn-desistir:hover {
        background: #dc3545;
        color: white;
    }
    .list-group-item-custom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        border-radius: 8px !important;
        margin-bottom: 0.5rem;
        border: 1px solid rgba(0,0,0,0.05);
        background: #fafafa;
    }
    .status-badge {
        font-size: 0.75rem;
        padding: 0.3rem 0.6rem;
        border-radius: 12px;
    }
    .bg-soft-success { background: #d1e7dd; color: #0f5132; }
    .bg-soft-danger { background: #f8d7da; color: #842029; }
</style>
@endpush

@section('content')
<div class="rsvp-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="rsvp-card">
                    <h1 class="rsvp-title">Confirmação de Presença</h1>
                    <p class="rsvp-desc">Ficaremos muito felizes em celebrar este dia com você. Por favor, gerencie suas confirmações abaixo.</p>

                    @if($podeAdicionar)
                        <form action="{{ route('confirmacao.store') }}" method="POST" class="mb-5 p-4" style="background: rgba(201,168,124,0.05); border-radius: 8px;">
                            @csrf
                            <h5 style="font-family: 'Playfair Display', serif; margin-bottom: 1rem;">Adicionar Convidado</h5>
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="text" name="nome_completo" class="input-elegant" placeholder="Nome Completo do Convidado" required>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn-submit">Confirmar</button>
                                </div>
                            </div>
                            <small class="text-muted"><i class="fas fa-info-circle"></i> O prazo limite para novas confirmações é 05/05/2026.</small>
                        </form>
                    @else
                        <div class="alert alert-warning text-center">
                            <strong>O prazo para novas confirmações encerrou em 05 de maio de 2026.</strong><br>
                            Em caso de dúvidas, entre em contato com os noivos.
                        </div>
                    @endif

                    <h4 style="font-family: 'Playfair Display', serif; margin-bottom: 1.5rem; text-align: center;">Minhas Confirmações</h4>
                    
                    @if($confirmacoes->count() > 0)
                        <div class="list-group">
                            @foreach($confirmacoes as $c)
                                <div class="list-group-item-custom">
                                    <div>
                                        <h6 class="mb-1" style="font-weight: 600;">{{ $c->nome_completo }}</h6>
                                        <small class="text-muted">Confirmado em {{ $c->created_at->format('d/m/Y') }}</small>
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        @if($c->status == 'confirmado')
                                            <span class="status-badge bg-soft-success"><i class="fas fa-check-circle me-1"></i> Confirmado</span>
                                            
                                            <form action="{{ route('confirmacao.desistir', $c->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja cancelar a presença de {{ $c->nome_completo }}?');">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn-desistir" title="Cancelar Presença">
                                                    <i class="fas fa-times"></i> Desistir
                                                </button>
                                            </form>
                                        @else
                                            <span class="status-badge bg-soft-danger"><i class="fas fa-times-circle me-1"></i> Cancelado</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center p-4">
                            <i class="fas fa-user-times" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
                            <p class="text-muted">Nenhum convidado confirmado no seu grupo ainda.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
