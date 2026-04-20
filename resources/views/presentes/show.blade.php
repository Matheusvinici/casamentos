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
@php
    $linksCartao = [
        1 => 'https://link.infinitepay.io/jocemary/VC1D-1edJXEOAR-320,00',
        2 => 'https://link.infinitepay.io/jocemary/VC1D-7gkdkUOSxN-400,00',
        3 => 'https://link.infinitepay.io/jocemary/VC1D-Ne6lqLmh-521,00',
        4 => 'https://link.infinitepay.io/jocemary/VC1D-qyILjrDrt-350,00',
        5 => 'https://link.infinitepay.io/jocemary/VC1D-1HSRef5cAh-900,00',
        6 => 'https://link.infinitepay.io/jocemary/VC1D-7gkgUzyspr-421,00',
        7 => 'https://link.infinitepay.io/jocemary/VC1D-7gkgvjef1P-350,00',
        8 => 'https://link.infinitepay.io/jocemary/VC1D-7gkhNQaQl3-513,00',
        9 => 'https://link.infinitepay.io/jocemary/VC1D-djDz0MTLd-380,00',
        10 => 'https://link.infinitepay.io/jocemary/VC1D-7gkiHvumiJ-1200,00',
        11 => 'https://link.infinitepay.io/jocemary/VC1D-2YuuEdlzax-320,00',
        12 => 'https://link.infinitepay.io/jocemary/VC1D-Jrc31z6Jb-400,00',
        13 => 'https://link.infinitepay.io/jocemary/VC1D-1Gp2lAj1v-521,00',
        14 => 'https://link.infinitepay.io/jocemary/VC1D-Jrc5bL3Sj-450,00',
        15 => 'https://link.infinitepay.io/jocemary/VC1D-2Yuur0EvlB-900,00',
        16 => 'https://link.infinitepay.io/jocemary/VC1D-1vBgjvEuo9-150,00',
        17 => 'https://link.infinitepay.io/jocemary/VC1D-3qNNwmRQgH-150,00',
        18 => 'https://link.infinitepay.io/jocemary/VC1D-3qNNwmRQgH-150,00',
        19 => 'https://link.infinitepay.io/jocemary/VC1D-7gkmLD3Ixr-175,00',
        20 => 'https://link.infinitepay.io/jocemary/VC1D-1XLl12xpJd-175,00',
        21 => 'https://link.infinitepay.io/jocemary/VC1D-1XLl12xpJd-175,00',
        22 => 'https://link.infinitepay.io/jocemary/VC1D-VmaMzPbsR-220,00',
        23 => 'https://link.infinitepay.io/jocemary/VC1D-Bp3cOtZf-220,00',
        24 => 'https://link.infinitepay.io/jocemary/VC1D-7gkoLf4gC1-220,00',
        25 => 'https://link.infinitepay.io/jocemary/VC1D-7gkolaCgwn-225,00',
        26 => 'https://link.infinitepay.io/jocemary/VC1D-7gkpQGl4ZD-225,00',
        27 => 'https://link.infinitepay.io/jocemary/VC1D-3qNPxvHoQX-225,00',
        28 => 'https://link.infinitepay.io/jocemary/VC1D-7gkqgTSCef-300,00',
        29 => 'https://link.infinitepay.io/jocemary/VC1D-1vBiGvtvvt-300,00',
        30 => 'https://link.infinitepay.io/jocemary/VC1D-3qNQj7ELAd-300,00',
        31 => 'https://link.infinitepay.io/jocemary/VC1D-7gkryYl36d-350,00',
        32 => 'https://link.infinitepay.io/jocemary/VC1D-3qNR9mTGoF-350,00',
        33 => 'https://link.infinitepay.io/jocemary/VC1D-FtINPLGNl-350,00',
        34 => 'https://link.infinitepay.io/jocemary/VC1D-7gm9vUH0Kh-180,00',
        35 => 'https://link.infinitepay.io/jocemary/VC1D-3qO5D1w4gh-250,00',
        36 => 'https://link.infinitepay.io/jocemary/VC1D-lg15pxGcR-150,00',
        37 => 'https://link.infinitepay.io/jocemary/VC1D-7gmBR82LFh-200,00',
        38 => 'https://link.infinitepay.io/jocemary/VC1D-Nq0b35aNZ-160,00',
        39 => 'https://link.infinitepay.io/jocemary/VC1D-3qO6CJObhF-240,00',
        40 => 'https://link.infinitepay.io/jocemary/VC1D-7gmD9T9I1V-210,00',
        41 => 'https://link.infinitepay.io/jocemary/VC1D-3qO6nInbA3-250,00',
        42 => 'https://link.infinitepay.io/jocemary/VC1D-1XM2mylRK9-150,00',
        43 => 'https://link.infinitepay.io/jocemary/VC1D-1vC3bn8ylL-180,00',
        44 => 'https://link.infinitepay.io/jocemary/VC1D-7gmErpCvdr-250,00',
        45 => 'https://link.infinitepay.io/jocemary/VC1D-JrfzRKJhd-220,00',
        46 => 'https://link.infinitepay.io/jocemary/VC1D-5IRjcMlOl-230,00',
        47 => 'https://link.infinitepay.io/jocemary/VC1D-3qO8Jvrg2D-190,00',
        48 => 'https://link.infinitepay.io/jocemary/VC1D-xb286ykin-170,00',
    ];

    $chavesPix = [
        1 => '00020101021126890014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20227Jardim botanico de Curitiba5204000053039865406320.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***63045AA0',
        2 => '00020101021126900014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20228Tour Gastronomico Curitibano5204000053039865406400.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***63048AC6',
        3 => '00020101021126910014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20229Passeio de Trem  Serra do Mar5204000053039865406521.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***6304DB3C',
        4 => '00020101021126820014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20220Museu Oscar Niemeyer5204000053039865406350.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***63041C02',
        5 => '00020101021126920014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20230SPA Day para Casal em Curitiba5204000053039865406900.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***63049F04',
        6 => '00020101021126840014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20222Teleferico  Cristo Luz5204000053039865406421.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***6304C0FE',
        7 => '00020101021126850014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20223Passeio de Barco Pirata5204000053039865406350.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***6304CB05',
        8 => '00020101021126800014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20218Day Use Beach Club5204000053039865406513.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***6304C1A2',
        9 => '00020101021126800014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20218Day Use Beach Club5204000053039865406380.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***6304855E',
        10 => '00020101021126790014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20217Jantar Premium BC52040000530398654071200.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***63045F49',
        11 => '00020101021126890014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20227Trilha da Lagoinha do Leste5204000053039865406320.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***6304F9F4',
        12 => '00020101021126910014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20229passeio de escuna pelas ilhas5204000053039865406400.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***6304A064',
        13 => '00020101021126900014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20228Jantar Romantico a Beira Mar5204000053039865406521.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***63047949',
        14 => '00020101021126890014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20227Voo de Parapente em Floripa5204000053039865406450.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***6304724D',
        15 => '00020101021126920014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20230suite romantica  cafe da manha5204000053039865406900.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***6304B909',
        16 => '00020101021126830014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20221Cafe colonial rustico5204000053039865406150.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***6304C610',
        17 => '00020101021126910014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20229Stand UP Paddle ao por do sol5204000053039865406150.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***6304BC70',
        18 => '00020101021126910014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20229Snorkel nas Aguas Cristalinas5204000053039865406150.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***63044DB4',
        19 => '00020101021126850014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20223Bosque Alemao e Parques5204000053039865406175.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***63041595',
        20 => '00020101021126890014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20227Passeio no Oceanic Aquarium5204000053039865406175.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***63042091',
        21 => '00020101021126850014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20223Aventura de Quadriciclo5204000053039865406175.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***6304EF2C',
        22 => '00020101021126880014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20226Almoco em Santa Felicidade5204000053039865406220.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***6304C85D',
        23 => '00020101021126860014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20224Jantar Tematico Surpresa5204000053039865406220.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***630464A9',
        24 => '00020101021126860014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20224Passeio Ilha do Campeche5204000053039865406220.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***630427C7',
        25 => '00020101021126820014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20220Degustacao de Vinhos5204000053039865406225.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***630481BF',
        27 => '00020101021126840014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20222Aulas Iniciais de Surf5204000053039865406225.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***63040847',
        28 => '00020101021126850014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20223Noite no Hard Rock Cafe5204000053039865406300.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***6304E9FE',
        29 => '00020101021126890014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20227Passeio de Lancha Privativa5204000053039865406300.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***63043B6B',
        30 => '00020101021126800014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20218Day Use Beach Club5204000053039865406300.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***630470A5',
        31 => '00020101021126920014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20230Ensaio Fotografico Pos Wedding5204000053039865406350.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***630449AB',
        32 => '00020101021126870014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20225Parque Beto Carrero World5204000053039865406350.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***6304726E',
        33 => '00020101021126950014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20233Jantar Romantico de Frutos do Mar5204000053039865406350.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***630459D1',
        34 => '00020101021126970014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20235Jantar Italiano em Santa Felicidade5204000053039865406180.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***6304F39A',
        35 => '00020101021126830014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20221Fondue Casal Curitiba5204000053039865406250.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***6304FEF9',
        36 => '00020101021126860014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20224Almoco no Largo da Ordem5204000053039865406150.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***630458D7',
        37 => '00020101021126830014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20221Jantar Arabe Especial5204000053039865406200.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***630480F6',
        38 => '00020101021126870014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20225Pizza Premium em Curitiba5204000053039865406160.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***63047027',
        39 => '00020101021126880014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20226Jantar com vista Mar em BC5204000053039865406240.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***63040F9A',
        40 => '00020101021126830014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20221Almoco Pescados em BC5204000053039865406210.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***6304C27F',
        41 => '00020101021126870014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20225Rodizio de Carnes Premium5204000053039865406250.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***6304BF1E',
        42 => '00020101021126880014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20226Hamburguer Artesanal Casal5204000053039865406150.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***630488AE',
        43 => '00020101021126820014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20220Noite mexicana em BC5204000053039865406180.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***63040F7F',
        44 => '00020101021126910014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20229Sequencia de Camarao na Lagoa5204000053039865406250.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***630405D9',
        45 => '00020101021126890014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20227Jantar Acoriano Tradicional5204000053039865406220.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***63041468',
        46 => '00020101021126840014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20222Ostras e Frutos do Mar5204000053039865406230.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***63048C2D',
        47 => '00020101021126880014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20226Almoco Beiramar Praia mole5204000053039865406190.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***630415F0',
        48 => '00020101021126860014br.gov.bcb.pix0136288c593b-7789-4e3b-9488-17a34c0c2aa20224Jantar de Massas na Ilha5204000053039865406170.005802BR5922MATHEUS V V DE ANDRADE6009PETROLINA62070503***630470F3',
    ];

    $linkCartaoAtual = $linksCartao[$presente['id']] ?? '';
    $chavePixAtual = $chavesPix[$presente['id']] ?? 'SUA_CHAVE_PIX_AQUI';
@endphp

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
                            <span class="pix-key-value" id="pixKey">{{ $chavePixAtual }}</span>
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
                            <a href="{{ $linkCartaoAtual != '' ? $linkCartaoAtual : '#' }}" target="_blank" class="btn-cartao-link" @if($linkCartaoAtual == '') onclick="alert('Link de pagamento em breve!'); return false;" @endif>
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
