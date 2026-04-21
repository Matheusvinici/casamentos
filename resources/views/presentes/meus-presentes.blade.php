@extends('layouts.casamento')

@section('title', 'Meus Presentes - Nosso Casamento')

@push('styles')
<style>
    .history-page {
        min-height: calc(100vh - 140px);
        padding: 4rem 0;
        background: linear-gradient(180deg, var(--cream) 0%, var(--champagne) 100%);
    }

    .history-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.2rem;
        color: var(--text-dark);
        margin-bottom: 2rem;
        text-align: center;
    }

    .history-subtitle {
        text-align: center;
        color: var(--text-light);
        margin-bottom: 3rem;
        font-size: 1rem;
    }

    .gift-history-card {
        background: var(--white);
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid rgba(201,168,124,0.1);
        box-shadow: 0 10px 30px rgba(201,168,124,0.08);
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .gift-history-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(201,168,124,0.12);
        border-color: rgba(201,168,124,0.3);
    }

    .gift-history-image {
        width: 150px;
        height: 150px;
        object-fit: cover;
    }

    .gift-history-body {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .gift-history-info {
        flex: 1;
    }

    .gift-history-city {
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--rose);
        margin-bottom: 0.3rem;
    }

    .gift-history-name {
        font-family: 'Playfair Display', serif;
        font-size: 1.3rem;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }

    .gift-history-date {
        font-size: 0.85rem;
        color: var(--text-light);
        margin-bottom: 0.2rem;
    }

    .gift-history-method {
        font-size: 0.85rem;
        color: var(--text-light);
    }

    .gift-history-price-box {
        text-align: right; margin-left: 2rem;
    }

    .gift-history-price {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        color: var(--rose-dark);
        font-weight: 600;
        margin-bottom: 0.3rem;
    }

    .badge-status {
        background: var(--rose-dark);
        color: white;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        display: inline-block;
    }

    .gift-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
        flex-wrap: wrap;
    }

    .btn-pay {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.5rem 1.2rem;
        border-radius: 25px;
        font-size: 0.78rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-pay-card {
        background: linear-gradient(135deg, var(--rose), var(--rose-dark));
        color: var(--white);
    }

    .btn-pay-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(201,168,124,0.35);
        color: var(--white);
    }

    .btn-pay-pix {
        background: var(--white);
        color: var(--text-dark);
        border: 1.5px solid rgba(201,168,124,0.4);
    }

    .btn-pay-pix:hover {
        background: var(--cream);
        border-color: var(--rose);
        transform: translateY(-2px);
        color: var(--text-dark);
    }

    .empty-state {
        text-align: center;
        padding: 3rem 0;
    }
    
    .empty-state i {
        font-size: 3rem;
        color: var(--rose-light);
        margin-bottom: 1rem;
    }
    
    .empty-state h3 {
        font-family: 'Playfair Display', serif;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }
    
    .btn-elegant {
        background: transparent;
        border: 1.5px solid var(--rose);
        color: var(--rose-dark);
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 2px;
        text-transform: uppercase;
        padding: 0.8rem 2.5rem;
        border-radius: 30px;
        text-decoration: none;
        transition: all 0.4s ease;
        display: inline-block;
        margin-top: 1rem;
    }

    .btn-elegant:hover {
        background: var(--rose);
        color: var(--white);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(201,168,124,0.3);
    }

    @media (max-width: 768px) {
        .gift-history-card {
            flex-direction: column;
        }
        .gift-history-image {
            width: 100%;
            height: 200px;
        }
        .gift-history-body {
            flex-direction: column;
            align-items: flex-start;
            gap: 1.5rem;
        }
        .gift-history-price-box {
            margin-left: 0;
            text-align: left;
        }
        .gift-actions {
            width: 100%;
        }
        .btn-pay {
            flex: 1;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="history-page">
    <div class="container">
        <h1 class="history-title">Meus Presentes</h1>
        
        @if(count($meusPresentes) > 0)
            <p class="history-subtitle">Muito obrigado por fazer parte da nossa história! 💛</p>
            
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    @foreach($meusPresentes as $presente)
                        <div class="gift-history-card">
                            <img src="{{ $presente['imagem'] }}" class="gift-history-image" alt="{{ $presente['nome'] }}" onerror="this.src='https://images.unsplash.com/photo-1549468057-5ce754b4f175?w=600'">
                            <div class="gift-history-body">
                                <div class="gift-history-info">
                                    <div class="gift-history-city">{{ $presente['cidade'] }}</div>
                                    <h3 class="gift-history-name">{{ $presente['nome'] }}</h3>
                                    <div class="gift-history-date">
                                        <i class="far fa-calendar-alt me-1"></i> Comprado em: {{ \Carbon\Carbon::parse($presente['data_compra'])->format('d/m/Y H:i') }}
                                    </div>
                                    <div class="gift-history-method">
                                        <i class="fas {{ $presente['metodo'] == 'pix' ? 'fa-qrcode' : 'fa-credit-card' }} me-1"></i>
                                        Método: {{ strtoupper($presente['metodo']) }}
                                    </div>
                                    <div class="gift-actions">
                                        <a href="{{ route('presente.show', $presente['presente_id']) }}" class="btn-pay btn-pay-card">
                                            <i class="fas fa-credit-card"></i> Pagar com Cartão
                                        </a>
                                        <a href="{{ route('presente.show', $presente['presente_id']) }}" class="btn-pay btn-pay-pix">
                                            <i class="fas fa-qrcode"></i> Pagar com PIX
                                        </a>
                                    </div>
                                </div>
                                <div class="gift-history-price-box">
                                    <div class="gift-history-price">R$ {{ number_format($presente['preco'], 2, ',', '.') }}</div>
                                    <span class="badge-status"><i class="fas fa-check me-1"></i> {{ $presente['status'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-gift"></i>
                <h3>Você ainda não escolheu nenhum presente</h3>
                <p style="color: var(--text-light);">Confira nossa lista de presentes e escolha uma experiência especial para nós!</p>
                <a href="/#presentes" class="btn-elegant">Ver Lista de Presentes</a>
            </div>
        @endif
        
    </div>
</div>
@endsection
