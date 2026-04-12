@extends('layouts.casamento')

@section('title', $presente['nome'] . ' - Presente de Casamento')

@push('styles')
<style>
    .payment-page {
        min-height: calc(100vh - 140px);
        padding: 3rem 0;
        background: linear-gradient(180deg, var(--cream) 0%, var(--champagne) 100%);
    }

    .back-link {
        color: var(--text-light);
        text-decoration: none;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 2rem;
    }

    .back-link:hover {
        color: var(--rose-dark);
        transform: translateX(-3px);
    }

    .gift-detail-card {
        background: var(--white);
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid rgba(201,168,124,0.1);
        box-shadow: 0 10px 40px rgba(201,168,124,0.08);
    }

    .gift-detail-image {
        width: 100%;
        height: 300px;
        object-fit: cover;
    }

    .gift-detail-body {
        padding: 2rem;
    }

    .gift-detail-city {
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--rose);
        margin-bottom: 0.5rem;
    }

    .gift-detail-name {
        font-family: 'Playfair Display', serif;
        font-size: 1.6rem;
        color: var(--text-dark);
        margin-bottom: 0.8rem;
    }

    .gift-detail-desc {
        color: var(--text-light);
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .gift-detail-price {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        color: var(--rose-dark);
        font-weight: 600;
    }

    /* Payment Options */
    .payment-section {
        background: var(--white);
        border-radius: 20px;
        padding: 2rem;
        border: 1px solid rgba(201,168,124,0.1);
        box-shadow: 0 10px 40px rgba(201,168,124,0.08);
    }

    .payment-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.3rem;
        color: var(--text-dark);
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .payment-option {
        border: 2px solid rgba(201,168,124,0.2);
        border-radius: 16px;
        padding: 1.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 1rem;
        position: relative;
    }

    .payment-option:hover {
        border-color: var(--rose);
        box-shadow: 0 5px 20px rgba(201,168,124,0.12);
    }

    .payment-option.selected {
        border-color: var(--rose);
        background: rgba(201,168,124,0.04);
        box-shadow: 0 5px 20px rgba(201,168,124,0.15);
    }

    .payment-option-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--rose-light), var(--rose));
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--white);
        font-size: 1.2rem;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .payment-option-icon.cartao {
        background: linear-gradient(135deg, #667eea, #764ba2);
    }

    .payment-option-title {
        font-weight: 600;
        color: var(--text-dark);
        font-size: 1rem;
        margin-bottom: 0.2rem;
    }

    .payment-option-desc {
        font-size: 0.8rem;
        color: var(--text-light);
    }

    .payment-radio {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        border: 2px solid rgba(201,168,124,0.3);
        transition: all 0.3s ease;
    }

    .payment-option.selected .payment-radio {
        border-color: var(--rose);
        background: var(--rose);
        box-shadow: inset 0 0 0 4px var(--white);
    }

    /* PIX Section */
    .pix-details {
        display: none;
        animation: fadeInUp 0.5s ease forwards;
    }

    .pix-details.show {
        display: block;
    }

    .pix-key-box {
        background: var(--champagne);
        border: 1px solid rgba(201,168,124,0.2);
        border-radius: 12px;
        padding: 1.2rem;
        margin: 1.5rem 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    .pix-key-value {
        font-family: monospace;
        font-size: 0.9rem;
        color: var(--text-dark);
        word-break: break-all;
        flex: 1;
    }

    .btn-copy {
        background: var(--rose);
        color: var(--white);
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .btn-copy:hover {
        background: var(--rose-dark);
        transform: scale(1.02);
    }

    .btn-copy.copied {
        background: #22c55e;
    }

    /* Cartão Section */
    .cartao-details {
        display: none;
        animation: fadeInUp 0.5s ease forwards;
    }

    .cartao-details.show {
        display: block;
    }

    .btn-cartao-link {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: var(--white);
        border: none;
        padding: 0.8rem 2rem;
        border-radius: 25px;
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        width: 100%;
        text-align: center;
    }

    .btn-cartao-link:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102,126,234,0.35);
        color: var(--white);
    }

    /* Upload Section */
    .upload-section {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid rgba(201,168,124,0.15);
    }

    .upload-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.1rem;
        color: var(--text-dark);
        margin-bottom: 1rem;
    }

    .upload-area {
        border: 2px dashed rgba(201,168,124,0.3);
        border-radius: 16px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .upload-area:hover {
        border-color: var(--rose);
        background: rgba(201,168,124,0.03);
    }

    .upload-area.has-file {
        border-color: #22c55e;
        background: rgba(34,197,94,0.03);
    }

    .upload-area input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .upload-icon {
        font-size: 2rem;
        color: var(--rose-light);
        margin-bottom: 0.8rem;
    }

    .upload-text {
        font-size: 0.85rem;
        color: var(--text-light);
    }

    .upload-text strong {
        color: var(--rose-dark);
    }

    .file-name {
        font-size: 0.8rem;
        color: #22c55e;
        font-weight: 600;
        margin-top: 0.5rem;
    }

    .btn-confirm {
        background: linear-gradient(135deg, var(--rose), var(--rose-dark));
        color: var(--white);
        border: none;
        padding: 0.85rem 2.5rem;
        border-radius: 30px;
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        margin-top: 1.5rem;
    }

    .btn-confirm:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(201,168,124,0.35);
    }

    .btn-confirm:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .info-note {
        background: rgba(201,168,124,0.08);
        border-radius: 12px;
        padding: 1rem;
        font-size: 0.8rem;
        color: var(--text-medium);
        margin-top: 1rem;
        display: flex;
        align-items: flex-start;
        gap: 0.8rem;
    }

    .info-note i {
        color: var(--rose);
        font-size: 1rem;
        margin-top: 0.1rem;
    }

    /* Success Message */
    .success-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(250,248,245,0.95);
        z-index: 9999;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .success-overlay.show {
        display: flex;
        animation: fadeInUp 0.5s ease;
    }

    .success-content i {
        font-size: 4rem;
        color: #22c55e;
        margin-bottom: 1.5rem;
    }

    .success-content h2 {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }

    .success-content p {
        color: var(--text-light);
        font-size: 0.95rem;
        margin-bottom: 2rem;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 768px) {
        .gift-detail-image {
            height: 200px;
        }
        .payment-section {
            margin-top: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="payment-page">
    <div class="container">
        <a href="/#presentes" class="back-link">
            <i class="fas fa-arrow-left"></i> Voltar aos presentes
        </a>

        <div class="row g-4">
            <!-- Gift Details -->
            <div class="col-lg-5">
                <div class="gift-detail-card">
                    <img src="{{ $presente['imagem'] }}" class="gift-detail-image" alt="{{ $presente['nome'] }}">
                    <div class="gift-detail-body">
                        <div class="gift-detail-city">{{ $presente['cidade'] }}</div>
                        <h1 class="gift-detail-name">{{ $presente['nome'] }}</h1>
                        <p class="gift-detail-desc">{{ $presente['descricao'] }}</p>
                        <div class="gift-detail-price">R$ {{ number_format($presente['preco'], 2, ',', '.') }}</div>
                    </div>
                </div>
            </div>

            <!-- Payment Section -->
            <div class="col-lg-7">
                <div class="payment-section">
                    <h2 class="payment-title">Escolha a forma de pagamento</h2>

                    <!-- PIX Option -->
                    <div class="payment-option" id="optionPix" onclick="selectPayment('pix')">
                        <div class="d-flex align-items-center">
                            <div class="payment-option-icon">
                                <i class="fas fa-qrcode"></i>
                            </div>
                            <div>
                                <div class="payment-option-title">PIX</div>
                                <div class="payment-option-desc">Copie a chave e faça a transferência</div>
                            </div>
                        </div>
                        <div class="payment-radio"></div>
                    </div>

                    <!-- Cartão Option -->
                    <div class="payment-option" id="optionCartao" onclick="selectPayment('cartao')">
                        <div class="d-flex align-items-center">
                            <div class="payment-option-icon cartao">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <div>
                                <div class="payment-option-title">Cartão de Crédito</div>
                                <div class="payment-option-desc">Será redirecionado para o link de pagamento</div>
                            </div>
                        </div>
                        <div class="payment-radio"></div>
                    </div>

                    <!-- PIX Details -->
                    <div class="pix-details" id="pixDetails">
                        <h3 style="font-family: 'Playfair Display', serif; font-size: 1.1rem; color: var(--text-dark); margin-top: 1.5rem;">
                            Chave PIX (Copia e Cola)
                        </h3>
                        <div class="pix-key-box">
                            <span class="pix-key-value" id="pixKey">SUA_CHAVE_PIX_AQUI</span>
                            <button class="btn-copy" id="btnCopy" onclick="copyPixKey()">
                                <i class="fas fa-copy me-1"></i> Copiar
                            </button>
                        </div>
                        <div class="info-note">
                            <i class="fas fa-info-circle"></i>
                            <span>Após realizar o PIX, anexe o comprovante abaixo para confirmar seu presente.</span>
                        </div>
                    </div>

                    <!-- Cartão Details -->
                    <div class="cartao-details" id="cartaoDetails">
                        <div style="margin-top: 1.5rem;">
                            <p style="font-size: 0.85rem; color: var(--text-medium); margin-bottom: 1rem; text-align: center;">
                                Você será redirecionado para uma página segura de pagamento.
                                <br>Após o pagamento, volte aqui e anexe o comprovante.
                            </p>
                            <a href="https://SEU_LINK_DE_PAGAMENTO_AQUI" target="_blank" class="btn-cartao-link">
                                <i class="fas fa-external-link-alt me-2"></i> Ir para Pagamento
                            </a>
                        </div>
                        <div class="info-note" style="margin-top: 1.5rem;">
                            <i class="fas fa-info-circle"></i>
                            <span>Após concluir o pagamento por cartão, volte aqui e anexe o comprovante para confirmar.</span>
                        </div>
                    </div>

                    <!-- Upload Comprovante -->
                    <div class="upload-section">
                        <h3 class="upload-title">
                            <i class="fas fa-cloud-upload-alt me-2" style="color: var(--rose);"></i>
                            Anexar Comprovante
                        </h3>

                        <form id="comprovanteForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="metodo_pagamento" id="metodoPagamento" value="">

                            <div class="upload-area" id="uploadArea">
                                <input type="file" name="comprovante" id="comprovanteFile" accept=".jpg,.jpeg,.png,.pdf,.webp">
                                <div class="upload-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <div class="upload-text">
                                    <strong>Clique para selecionar</strong> ou arraste o arquivo
                                    <br><small>JPG, PNG, PDF ou WebP (máx. 10MB)</small>
                                </div>
                                <div class="file-name" id="fileName" style="display: none;"></div>
                            </div>

                            <button type="submit" class="btn-confirm" id="btnConfirm" disabled>
                                <i class="fas fa-check me-2"></i> Confirmar Pagamento
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Overlay -->
<div class="success-overlay" id="successOverlay">
    <div class="success-content">
        <i class="fas fa-heart"></i>
        <h2>Muito Obrigado!</h2>
        <p>Seu presente <strong>{{ $presente['nome'] }}</strong> foi confirmado.<br>Sua contribuição significa o mundo para nós! 💛</p>
        <a href="/" class="btn-elegant" style="text-decoration: none;">Voltar ao Início</a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let selectedPayment = null;

    function selectPayment(method) {
        selectedPayment = method;
        document.getElementById('metodoPagamento').value = method;

        // Update visual
        document.getElementById('optionPix').classList.toggle('selected', method === 'pix');
        document.getElementById('optionCartao').classList.toggle('selected', method === 'cartao');

        // Show/hide details
        document.getElementById('pixDetails').classList.toggle('show', method === 'pix');
        document.getElementById('cartaoDetails').classList.toggle('show', method === 'cartao');

        // Check if can enable submit
        checkSubmitButton();
    }

    function copyPixKey() {
        const pixKey = document.getElementById('pixKey').textContent;
        const btn = document.getElementById('btnCopy');

        navigator.clipboard.writeText(pixKey).then(() => {
            btn.innerHTML = '<i class="fas fa-check me-1"></i> Copiado!';
            btn.classList.add('copied');

            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-copy me-1"></i> Copiar';
                btn.classList.remove('copied');
            }, 2000);
        }).catch(() => {
            // Fallback
            const textarea = document.createElement('textarea');
            textarea.value = pixKey;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);

            btn.innerHTML = '<i class="fas fa-check me-1"></i> Copiado!';
            btn.classList.add('copied');
            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-copy me-1"></i> Copiar';
                btn.classList.remove('copied');
            }, 2000);
        });
    }

    // File upload
    document.getElementById('comprovanteFile').addEventListener('change', function() {
        const fileName = this.files[0] ? this.files[0].name : '';
        const fileNameEl = document.getElementById('fileName');
        const uploadArea = document.getElementById('uploadArea');

        if (fileName) {
            fileNameEl.textContent = '📎 ' + fileName;
            fileNameEl.style.display = 'block';
            uploadArea.classList.add('has-file');
        } else {
            fileNameEl.style.display = 'none';
            uploadArea.classList.remove('has-file');
        }

        checkSubmitButton();
    });

    function checkSubmitButton() {
        const hasFile = document.getElementById('comprovanteFile').files.length > 0;
        const hasPayment = selectedPayment !== null;
        document.getElementById('btnConfirm').disabled = !(hasFile && hasPayment);
    }

    // Form submit
    document.getElementById('comprovanteForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const btn = document.getElementById('btnConfirm');

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Enviando...';

        fetch('/presente/{{ $presente["id"] }}/comprovante', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('successOverlay').classList.add('show');
            } else {
                Swal.fire({
                    title: 'Erro',
                    text: data.message || 'Erro ao enviar comprovante.',
                    icon: 'error',
                    confirmButtonColor: '#c9a87c',
                    background: '#faf8f5',
                    color: '#3d3427',
                });
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check me-2"></i> Confirmar Pagamento';
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            Swal.fire({
                title: 'Erro',
                text: 'Erro ao enviar comprovante. Tente novamente.',
                icon: 'error',
                confirmButtonColor: '#c9a87c',
                background: '#faf8f5',
                color: '#3d3427',
            });
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check me-2"></i> Confirmar Pagamento';
        });
    });
</script>
@endpush
