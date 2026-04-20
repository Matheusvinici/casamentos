@extends('layouts.casamento')

@section('title', 'Mary & Matheus - Nosso Casamento')

@push('styles')
<style>
    /* ===== HERO ===== */
    .hero {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        position: relative;
        background-image: linear-gradient(rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0.7)), url('{{ asset("images/foto1.jpeg") }}');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        overflow: hidden;
    }
        background: radial-gradient(ellipse at 30% 50%, rgba(212,168,83,0.06) 0%, transparent 50%),
                    radial-gradient(ellipse at 70% 50%, rgba(201,168,124,0.08) 0%, transparent 50%);
        animation: floatBg 20s ease-in-out infinite;
    }

    @keyframes floatBg {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(1deg); }
    }

    .hero-content {
        position: relative;
        z-index: 2;
        padding: 2rem;
    }

    .hero-label {
        font-family: 'Lato', sans-serif;
        font-size: 0.85rem;
        letter-spacing: 6px;
        text-transform: uppercase;
        color: var(--cream);
        margin-bottom: 1.5rem;
        opacity: 0;
        animation: fadeInUp 1s ease 0.2s forwards;
    }

    .hero-names {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2.5rem, 8vw, 5.5rem);
        font-weight: 400;
        color: #ffffff;
        text-shadow: 2px 2px 10px rgba(0,0,0,0.3);
        line-height: 1.1;
        margin-bottom: 1rem;
        opacity: 0;
        animation: fadeInUp 1s ease 0.5s forwards;
    }

    .hero-names .amp {
        font-style: italic;
        color: var(--rose-light);
        font-size: 0.7em;
        display: inline-block;
        margin: 0 0.3em;
    }

    .hero-date {
        font-family: 'Lato', sans-serif;
        font-size: 1.1rem;
        letter-spacing: 4px;
        color: var(--cream);
        margin-bottom: 3rem;
        opacity: 0;
        animation: fadeInUp 1s ease 0.8s forwards;
    }

    .hero-divider {
        display: none;
    }

    /* Countdown */
    .countdown {
        display: flex;
        justify-content: center;
        gap: 2.5rem;
        margin-bottom: 3.5rem;
        opacity: 0;
        animation: fadeInUp 1s ease 1s forwards;
    }

    .countdown-item {
        text-align: center;
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        padding: 1.5rem 1rem;
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.2);
        min-width: 90px;
    }

    .countdown-number {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        font-weight: 600;
        color: #ffffff;
        line-height: 1;
    }

    .countdown-label {
        font-size: 0.75rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--cream);
        margin-top: 0.5rem;
    }

    .hero-cta {
        opacity: 0;
        animation: fadeInUp 1s ease 1.2s forwards;
    }

    .btn-elegant {
        background: var(--rose);
        border: none;
        color: var(--white);
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 2px;
        text-transform: uppercase;
        padding: 1rem 3rem;
        border-radius: 30px;
        text-decoration: none;
        transition: all 0.4s ease;
        display: inline-block;
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }

    .btn-elegant:hover {
        background: var(--rose-dark);
        color: var(--white);
        transform: translateY(-2px);
        box-shadow: 0 12px 25px rgba(0,0,0,0.3);
    }

    .scroll-indicator {
        position: absolute;
        bottom: 2rem;
        left: 50%;
        transform: translateX(-50%);
        color: var(--rose);
        font-size: 1.2rem;
        animation: bounce 2s infinite;
        opacity: 0.6;
        z-index: 2;
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateX(-50%) translateY(0); }
        40% { transform: translateX(-50%) translateY(-10px); }
        60% { transform: translateX(-50%) translateY(-5px); }
    }

    /* ===== SECTION STYLES ===== */
    .section-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(1.8rem, 4vw, 2.5rem);
        color: var(--text-dark);
        text-align: center;
        margin-bottom: 0.5rem;
        font-weight: 400;
    }

    .section-subtitle {
        font-size: 0.8rem;
        letter-spacing: 4px;
        text-transform: uppercase;
        color: var(--text-light);
        text-align: center;
        margin-bottom: 3rem;
    }

    .section-divider {
        width: 40px;
        height: 1px;
        background: var(--rose);
        margin: 1rem auto 1.5rem;
    }

    /* ===== EVENT INFO ===== */
    .event-section {
        padding: 5rem 0;
        background: var(--white);
    }

    .event-card {
        text-align: center;
        padding: 2.5rem 2rem;
        border-radius: 16px;
        background: var(--cream);
        border: 1px solid rgba(201,168,124,0.12);
        transition: all 0.4s ease;
        height: 100%;
    }

    .event-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(201,168,124,0.12);
        border-color: rgba(201,168,124,0.3);
    }

    .event-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--rose-light), var(--rose));
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        color: var(--white);
        font-size: 1.3rem;
    }

    .event-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.3rem;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }

    .event-venue {
        font-size: 0.9rem;
        color: var(--rose-dark);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .event-time {
        font-size: 0.85rem;
        color: var(--text-medium);
        margin-bottom: 0.3rem;
    }

    .event-address {
        font-size: 0.8rem;
        color: var(--text-light);
        font-style: italic;
    }

    /* ===== GIFTS SECTION ===== */
    .gifts-section {
        padding: 5rem 0;
        background: linear-gradient(180deg, var(--cream) 0%, var(--champagne) 100%);
    }

    .city-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.4rem;
        color: var(--text-dark);
        text-align: center;
        margin: 3rem 0 1.5rem;
        position: relative;
    }

    .city-title::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 50%;
        transform: translateX(-50%);
        width: 30px;
        height: 1px;
        background: var(--rose);
    }

    .city-title:first-of-type {
        margin-top: 0;
    }

    .gift-card {
        background: var(--white);
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid rgba(201,168,124,0.1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .gift-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 50px rgba(201,168,124,0.15);
        border-color: rgba(201,168,124,0.3);
    }

    .gift-card.sold {
        opacity: 0.5;
        pointer-events: none;
    }

    .gift-card.sold .gift-overlay {
        display: flex;
    }

    .gift-overlay {
        display: none;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(61,52,39,0.6);
        align-items: center;
        justify-content: center;
        border-radius: 16px;
        z-index: 10;
    }

    .gift-overlay span {
        font-family: 'Playfair Display', serif;
        color: var(--white);
        font-size: 1.2rem;
        font-style: italic;
        background: rgba(0,0,0,0.4);
        padding: 0.5rem 1.5rem;
        border-radius: 20px;
    }

    .gift-image {
        height: 200px;
        object-fit: cover;
        width: 100%;
        transition: transform 0.6s ease;
    }

    .gift-card:hover .gift-image {
        transform: scale(1.05);
    }

    .gift-image-wrapper {
        overflow: hidden;
        position: relative;
    }

    .gift-city-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        background: rgba(255,255,255,0.92);
        backdrop-filter: blur(10px);
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: var(--rose-dark);
        z-index: 2;
    }

    .gift-body {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .gift-name {
        font-family: 'Playfair Display', serif;
        font-size: 1.1rem;
        color: var(--text-dark);
        margin-bottom: 0.4rem;
    }

    .gift-description {
        font-size: 0.82rem;
        color: var(--text-light);
        margin-bottom: 1rem;
        line-height: 1.5;
        flex: 1;
    }

    .gift-price {
        font-family: 'Playfair Display', serif;
        font-size: 1.4rem;
        color: var(--rose-dark);
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .btn-gift {
        background: linear-gradient(135deg, var(--rose), var(--rose-dark));
        border: none;
        color: var(--white);
        font-size: 0.78rem;
        font-weight: 600;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding: 0.7rem 1.5rem;
        border-radius: 25px;
        transition: all 0.3s ease;
        width: 100%;
        display: inline-block;
        text-align: center;
        text-decoration: none;
    }

    .btn-gift:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(201,168,124,0.35);
        color: var(--white);
    }

    /* ===== FILTER TABS ===== */
    .filter-tabs {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-bottom: 2.5rem;
        flex-wrap: wrap;
    }

    .filter-tab {
        background: transparent;
        border: 1.5px solid rgba(201,168,124,0.3);
        color: var(--text-medium);
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding: 0.5rem 1.5rem;
        border-radius: 25px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .filter-tab:hover,
    .filter-tab.active {
        background: var(--rose);
        border-color: var(--rose);
        color: var(--white);
    }

    /* ===== ANIMATIONS ===== */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .reveal.visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* ===== ROMANTIC GALLERY ===== */
    .romantic-gallery {
        position: relative;
        height: 650px;
        margin-top: 5rem;
        max-width: 1000px;
        margin-left: auto;
        margin-right: auto;
    }
    .gallery-img {
        position: absolute;
        border-radius: 12px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        border: 8px solid var(--white);
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        object-fit: cover;
        cursor: pointer;
    }
    .gallery-img:hover {
        z-index: 10 !important;
        transform: scale(1.04) translateY(-5px);
        box-shadow: 0 25px 50px rgba(0,0,0,0.25);
    }
    .img-1 { width: 45%; height: 380px; top: 0; left: 0; z-index: 2; }
    .img-2 { width: 42%; height: 320px; top: 60px; right: 0; z-index: 1; }
    .img-3 { width: 38%; height: 280px; bottom: 0; left: 12%; z-index: 3; }
    .img-4 { width: 48%; height: 340px; bottom: 40px; right: 8%; z-index: 4; }

    @media (max-width: 991px) {
        .romantic-gallery { height: 500px; }
        .img-1 { height: 300px; }
        .img-2 { height: 250px; top: 40px; }
        .img-3 { height: 220px; bottom: 20px; }
        .img-4 { height: 280px; bottom: 0; }
    }

    @media (max-width: 768px) {
        .countdown { gap: 1rem; margin-bottom: 2.5rem; }
        .countdown-item { padding: 1rem 0.5rem; min-width: 75px; }
        .countdown-number { font-size: 1.8rem; }
        .event-card { margin-bottom: 1rem; }
        
        /* Mobile Linear Gallery */
        .romantic-gallery {
            height: auto;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            margin-top: 3rem;
            padding: 0 1rem;
        }
        .gallery-img {
            position: relative;
            width: 100% !important;
            height: 350px !important;
            top: auto !important;
            left: auto !important;
            right: auto !important;
            bottom: auto !important;
            border-width: 5px;
        }
    }

    @media (max-width: 576px) {
        .hero { min-height: 90vh; }
        .hero-names { font-size: 2.8rem; }
        .countdown-item { padding: 0.8rem 0.3rem; min-width: 65px; border-radius: 8px; }
        .countdown-number { font-size: 1.5rem; }
        .countdown-label { font-size: 0.6rem; letter-spacing: 1px; }
        .filter-tabs { gap: 0.3rem; }
        .filter-tab { padding: 0.4rem 1rem; font-size: 0.7rem; }
    }

    /* ===== RSVP BANNER SECTION ===== */
    .rsvp-banner-section {
        position: relative;
        padding: 5rem 0;
        background: linear-gradient(180deg, #ede4d8 0%, #e8ddd0 50%, #ede4d8 100%);
        border-top: 1px solid rgba(201,168,124,0.2);
        border-bottom: 1px solid rgba(201,168,124,0.2);
        overflow: hidden;
        text-align: center;
    }

    .rsvp-floating-hearts {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        overflow: hidden;
    }

    .rsvp-heart {
        position: absolute;
        bottom: -30px;
        font-size: 1.2rem;
        color: rgba(201,168,124,0.2);
        animation: floatHeart 8s ease-in-out infinite;
    }

    @keyframes floatHeart {
        0% { transform: translateY(0) rotate(0deg); opacity: 0; }
        10% { opacity: 1; }
        90% { opacity: 1; }
        100% { transform: translateY(-600px) rotate(45deg); opacity: 0; }
    }

    .rsvp-banner-content {
        position: relative;
        z-index: 2;
        max-width: 650px;
        margin: 0 auto;
    }

    .rsvp-icon-wrapper {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--rose-light), var(--rose));
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2rem;
        color: var(--white);
        box-shadow: 0 10px 40px rgba(201,168,124,0.2);
        animation: pulseIcon 2.5s ease-in-out infinite;
    }

    @keyframes pulseIcon {
        0%, 100% { box-shadow: 0 10px 40px rgba(201,168,124,0.2); transform: scale(1); }
        50% { box-shadow: 0 10px 50px rgba(201,168,124,0.35); transform: scale(1.05); }
    }

    .rsvp-label {
        font-family: 'Lato', sans-serif;
        font-size: 0.8rem;
        letter-spacing: 4px;
        text-transform: uppercase;
        color: var(--rose-dark);
        margin-bottom: 0.5rem;
    }

    .rsvp-heading {
        font-family: 'Playfair Display', serif;
        font-size: clamp(1.8rem, 5vw, 2.8rem);
        color: var(--text-dark);
        margin-bottom: 0.5rem;
        font-weight: 400;
    }

    .rsvp-divider {
        width: 50px;
        height: 2px;
        background: linear-gradient(90deg, transparent, var(--rose), transparent);
        margin: 1rem auto 1.5rem;
    }

    .rsvp-text {
        color: var(--text-medium);
        font-size: 0.95rem;
        line-height: 1.8;
        margin-bottom: 2rem;
    }

    .rsvp-text strong {
        color: var(--rose-dark);
    }

    .btn-rsvp {
        display: inline-block;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, var(--rose), var(--rose-dark));
        color: var(--white);
        font-size: 1rem;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        padding: 1.1rem 3rem;
        border-radius: 50px;
        text-decoration: none;
        transition: all 0.4s ease;
        box-shadow: 0 8px 30px rgba(201,168,124,0.3);
        animation: pulseBtn 2s ease-in-out infinite;
    }

    @keyframes pulseBtn {
        0%, 100% { box-shadow: 0 8px 30px rgba(201,168,124,0.3); }
        50% { box-shadow: 0 8px 45px rgba(201,168,124,0.5); }
    }

    .btn-rsvp:hover {
        transform: translateY(-3px) scale(1.03);
        box-shadow: 0 12px 40px rgba(201,168,124,0.5);
        color: var(--white);
    }

    .btn-rsvp-shine {
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent);
        animation: shine 3s ease-in-out infinite;
    }

    @keyframes shine {
        0% { left: -100%; }
        50% { left: 100%; }
        100% { left: 100%; }
    }

    .rsvp-note {
        margin-top: 1.5rem;
        color: var(--text-light);
        font-size: 0.8rem;
        font-style: italic;
    }

    .rsvp-confirmed-list {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 0.6rem;
        margin-bottom: 2rem;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }

    .rsvp-confirmed-item {
        display: flex;
        align-items: center;
        background: var(--white);
        border: 1px solid rgba(201,168,124,0.25);
        padding: 0.5rem 1.2rem;
        border-radius: 25px;
        color: var(--text-dark);
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(201,168,124,0.1);
    }

    .rsvp-confirmed-item:hover {
        background: var(--cream);
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(201,168,124,0.2);
    }

    @media (max-width: 576px) {
        .rsvp-banner-section { padding: 3.5rem 1rem; }
        .btn-rsvp { padding: 0.9rem 2rem; font-size: 0.85rem; letter-spacing: 1px; }
        .rsvp-icon-wrapper { width: 65px; height: 65px; font-size: 1.6rem; }
    }
</style>
@endpush

@section('content')
    <!-- ===== HERO ===== -->
    <section class="hero" id="inicio">
        <div class="hero-content">
            <div class="hero-label">Estamos nos casando</div>
            <h1 class="hero-names">
                Mary <span class="amp">&</span> Matheus
            </h1>
            <div class="hero-divider"></div>
            <div class="hero-date">06 · Junho · 2026</div>

            <!-- Countdown -->
            <div class="countdown" id="countdown">
                <div class="countdown-item">
                    <div class="countdown-number" id="days">--</div>
                    <div class="countdown-label">Dias</div>
                </div>
                <div class="countdown-item">
                    <div class="countdown-number" id="hours">--</div>
                    <div class="countdown-label">Horas</div>
                </div>
                <div class="countdown-item">
                    <div class="countdown-number" id="minutes">--</div>
                    <div class="countdown-label">Min</div>
                </div>
                <div class="countdown-item">
                    <div class="countdown-number" id="seconds">--</div>
                    <div class="countdown-label">Seg</div>
                </div>
            </div>

            <div class="hero-cta">
                <a href="#rsvp" class="btn-elegant" style="margin-right: 0.8rem;">
                    <i class="fas fa-calendar-check me-1"></i> Confirmar Presença
                </a>
                <a href="#presentes" class="btn-elegant" style="background: transparent; border: 2px solid var(--rose-light);">Ver Presentes</a>
            </div>
        </div>
        <div class="scroll-indicator">
            <i class="fas fa-chevron-down"></i>
        </div>
    </section>

    <!-- ===== EVENT INFO ===== -->
    <section class="event-section" id="evento">
        <div class="container">
            <div class="reveal">
                <p class="section-subtitle">Celebre conosco</p>
                <h2 class="section-title">O Grande Dia</h2>
                <div class="section-divider"></div>
            </div>

            <div class="row g-4 mt-2">
                <div class="col-md-6 reveal">
                    <div class="event-card">
                        <div class="event-icon">
                            <i class="fas fa-church"></i>
                        </div>
                        <h3 class="event-title">Cerimônia</h3>
                        <p class="event-venue">Paróquia São José Operário</p>
                        <p class="event-time">
                            <i class="far fa-clock me-1"></i> 17h00
                        </p>
                        <p class="event-time">
                            <i class="far fa-calendar me-1"></i> 06 de Junho de 2026
                        </p>
                        <p class="event-address">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            Diocese de Petrolina - PE
                        </p>
                    </div>
                </div>

                <div class="col-md-6 reveal">
                    <div class="event-card">
                        <div class="event-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h3 class="event-title">Jantar</h3>
                        <p class="event-venue">Restaurante Bêra Dágua</p>
                        <p class="event-time">
                            <i class="far fa-clock me-1"></i> 19h00 às 23h00
                        </p>
                        <p class="event-time">
                            <i class="far fa-calendar me-1"></i> 06 de Junho de 2026
                        </p>
                        <p class="event-address">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            Após a cerimônia
                        </p>
                    </div>
                </div>
            </div>

            <!-- Galeria do Casal Mais Criativa -->
            <div class="romantic-gallery reveal">
                <img src="{{ asset('images/foto1.jpeg') }}" class="gallery-img img-1" alt="Momento Inesquecível 1">
                <img src="{{ asset('images/foto2.jpeg') }}" class="gallery-img img-2" alt="Nossa História 2">
                <img src="{{ asset('images/foto3.jpeg') }}" class="gallery-img img-3" alt="Sorrisos 3">
                <img src="{{ asset('images/foto4.jpeg') }}" class="gallery-img img-4" alt="Romance 4">
            </div>
            
        </div>
    </section>

    <!-- ===== RSVP SECTION ===== -->
    <section class="rsvp-banner-section" id="rsvp">
        <div class="rsvp-floating-hearts">
            <span class="rsvp-heart" style="left:5%; animation-delay:0s;">♥</span>
            <span class="rsvp-heart" style="left:15%; animation-delay:1.5s;">♥</span>
            <span class="rsvp-heart" style="left:30%; animation-delay:0.8s;">♥</span>
            <span class="rsvp-heart" style="left:50%; animation-delay:2.2s;">♥</span>
            <span class="rsvp-heart" style="left:70%; animation-delay:0.3s;">♥</span>
            <span class="rsvp-heart" style="left:85%; animation-delay:1.8s;">♥</span>
            <span class="rsvp-heart" style="left:95%; animation-delay:1s;">♥</span>
        </div>
        <div class="container">
            <div class="rsvp-banner-content reveal">
                @auth
                    @if($minhasConfirmacoes->count() > 0)
                        {{-- LOGADO COM CONFIRMAÇÕES --}}
                        <div class="rsvp-icon-wrapper">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <p class="rsvp-label">Presença Confirmada</p>
                        <h2 class="rsvp-heading">Nos vemos lá! 🎉</h2>
                        <div class="rsvp-divider"></div>
                        <p class="rsvp-text">
                            Olá, <strong>{{ Auth::user()->name }}</strong>! Que alegria saber que você estará conosco.<br>
                            {{ $minhasConfirmacoes->count() == 1 ? 'Você confirmou 1 pessoa' : 'Você confirmou ' . $minhasConfirmacoes->count() . ' pessoas' }}:
                        </p>

                        <div class="rsvp-confirmed-list">
                            @foreach($minhasConfirmacoes as $conf)
                                <div class="rsvp-confirmed-item">
                                    <i class="fas fa-check-circle" style="color: var(--rose-dark); margin-right: 0.5rem;"></i>
                                    <span>{{ $conf->nome_completo }}</span>
                                </div>
                            @endforeach
                        </div>

                        <a href="{{ route('confirmacao.index') }}" class="btn-rsvp" style="animation: none;">
                            <i class="fas fa-user-plus me-2"></i> Gerenciar Confirmações
                            <span class="btn-rsvp-shine"></span>
                        </a>
                        <p class="rsvp-note">
                            <i class="fas fa-info-circle me-1"></i>
                            Você pode adicionar ou remover convidados até 05 de Maio de 2026.
                        </p>
                    @else
                        {{-- LOGADO SEM CONFIRMAÇÕES --}}
                        <div class="rsvp-icon-wrapper">
                            <i class="fas fa-envelope-open-text"></i>
                        </div>
                        <p class="rsvp-label">Sua presença é o nosso maior presente</p>
                        <h2 class="rsvp-heading">Confirme sua Presença</h2>
                        <div class="rsvp-divider"></div>
                        <p class="rsvp-text">
                            Olá, <strong>{{ Auth::user()->name }}</strong>! Ainda não vimos sua confirmação.<br>
                            Ficaremos imensamente felizes em celebrar este momento com você.<br>
                            Confirme até <strong>05 de Maio de 2026</strong>.
                        </p>
                        <a href="{{ route('confirmacao.index') }}" class="btn-rsvp" id="btnRsvp">
                            <i class="fas fa-calendar-check me-2"></i> Confirmar Presença Agora
                            <span class="btn-rsvp-shine"></span>
                        </a>
                        <p class="rsvp-note">
                            <i class="fas fa-exclamation-triangle me-1" style="color: var(--gold);"></i>
                            Você ainda não confirmou presença. Clique acima para confirmar!
                        </p>
                    @endif
                @else
                    {{-- NÃO LOGADO --}}
                    <div class="rsvp-icon-wrapper">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                    <p class="rsvp-label">Sua presença é o nosso maior presente</p>
                    <h2 class="rsvp-heading">Confirme sua Presença</h2>
                    <div class="rsvp-divider"></div>
                    <p class="rsvp-text">
                        Ficaremos imensamente felizes em celebrar este momento tão especial com você.<br>
                        Por favor, confirme sua presença até <strong>05 de Maio de 2026</strong>.
                    </p>
                    <a href="{{ route('confirmacao.index') }}" class="btn-rsvp" id="btnRsvp">
                        <i class="fas fa-calendar-check me-2"></i> Confirmar Presença
                        <span class="btn-rsvp-shine"></span>
                    </a>
                    <p class="rsvp-note">
                        <i class="fas fa-info-circle me-1"></i>
                        Você precisará fazer login ou criar uma conta rápida para confirmar.
                    </p>
                    <p style="font-size: 1.0rem; color: rgba(0,0,0,0.1); margin-top: 1.5rem; letter-spacing: 3px;">Vai Corinthians!!!</p>
                @endauth
            </div>
        </div>
    </section>

    <!-- ===== GIFTS ===== -->
    <section class="gifts-section" id="presentes">
        <div class="container">
            <div class="reveal">
                <p class="section-subtitle">Presentes para a Lua de Mel</p>
                <h2 class="section-title">O nosso Grande Dia</h2>
                <div class="section-divider"></div>
                <p class="text-center mb-4" style="color: var(--text-light); font-size: 0.9rem; max-width: 600px; margin: 0 auto 2rem;">
                    Cada presente é uma experiência especial que viveremos juntos na nossa lua de mel.
                    Sua contribuição nos ajuda a criar memórias inesquecíveis!
                </p>
            </div>

            <!-- Filtros -->
            <div class="filter-tabs reveal">
                <button class="filter-tab active" onclick="filterGifts('todos', this)">Todos</button>
                <button class="filter-tab" onclick="filterGifts('curitiba', this)">Curitiba</button>
                <button class="filter-tab" onclick="filterGifts('balneario', this)">Balneário Camboriú</button>
                <button class="filter-tab" onclick="filterGifts('florianopolis', this)">Florianópolis</button>
            </div>

            <!-- Grid de Presentes -->
            <div class="row g-4" id="giftsGrid">
                @foreach($presentes as $presente)
                    @php
                        $citySlug = '';
                        if(str_contains($presente['cidade'], 'Curitiba')) $citySlug = 'curitiba';
                        elseif(str_contains($presente['cidade'], 'Camboriú')) $citySlug = 'balneario';
                        elseif(str_contains($presente['cidade'], 'Florianópolis')) $citySlug = 'florianopolis';
                        $isSold = in_array($presente['id'], $comprados);
                    @endphp
                    <div class="col-lg-4 col-md-6 gift-item" data-city="{{ $citySlug }}">
                        <div class="gift-card {{ $isSold ? 'sold' : '' }}" onclick="selectGift({{ $presente['id'] }}, '{{ addslashes($presente['nome']) }}', '{{ number_format($presente['preco'], 2, ',', '.') }}')">
                            <div class="gift-image-wrapper">
                                <span class="gift-city-badge">{{ $presente['cidade'] }}</span>
                                <img src="{{ $presente['imagem'] }}" class="gift-image" alt="{{ $presente['nome'] }}" onerror="this.src='https://images.unsplash.com/photo-1549468057-5ce754b4f175?w=600'">
                                <div class="gift-overlay">
                                    <span>Presenteado</span>
                                </div>
                            </div>
                            <div class="gift-body">
                                <h4 class="gift-name">{{ $presente['nome'] }}</h4>
                                <p class="gift-description">{{ $presente['descricao'] }}</p>
                                <div class="gift-price">R$ {{ number_format($presente['preco'], 2, ',', '.') }}</div>
                                <span class="btn-gift">{{ $isSold ? 'Já Escolhido' : 'Presentear' }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // ===== COUNTDOWN =====
    function updateCountdown() {
        const weddingDate = new Date('2026-06-06T17:00:00-03:00').getTime();
        const now = new Date().getTime();
        const diff = weddingDate - now;

        if (diff <= 0) {
            document.getElementById('days').textContent = '0';
            document.getElementById('hours').textContent = '0';
            document.getElementById('minutes').textContent = '0';
            document.getElementById('seconds').textContent = '0';
            return;
        }

        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);

        document.getElementById('days').textContent = days;
        document.getElementById('hours').textContent = String(hours).padStart(2, '0');
        document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
        document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
    }

    updateCountdown();
    setInterval(updateCountdown, 1000);

    // ===== SCROLL REVEAL =====
    function handleReveal() {
        const reveals = document.querySelectorAll('.reveal');
        reveals.forEach(el => {
            const windowHeight = window.innerHeight;
            const elTop = el.getBoundingClientRect().top;
            if (elTop < windowHeight - 100) {
                el.classList.add('visible');
            }
        });
    }

    window.addEventListener('scroll', handleReveal);
    window.addEventListener('load', handleReveal);

    // ===== SMOOTH SCROLL =====
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // ===== FILTER =====
    function filterGifts(city, btn) {
        const items = document.querySelectorAll('.gift-item');
        const tabs = document.querySelectorAll('.filter-tab');

        tabs.forEach(t => t.classList.remove('active'));
        btn.classList.add('active');

        items.forEach(item => {
            if (city === 'todos' || item.dataset.city === city) {
                item.style.display = 'block';
                item.style.animation = 'fadeInUp 0.5s ease forwards';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // ===== SELECT GIFT =====
    function selectGift(giftId, giftName, giftPrice) {
        @auth
            Swal.fire({
                title: 'Confirmar Presente?',
                html: `Deseja realmente confirmar a escolha deste presente?<br><br><b>${giftName}</b> (R$ ${giftPrice})<br><br><small>Você poderá efetuar o pagamento via Cartão ou PIX na próxima tela.</small>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#c9a87c',
                cancelButtonColor: '#a07d50',
                confirmButtonText: 'Sim, quero presentear!',
                cancelButtonText: 'Voltar',
                background: '#faf8f5',
                color: '#3d3427',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return fetch(`/presente/${giftId}/bloquear`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            throw new Error(data.message || 'Erro ao bloquear presente');
                        }
                        return data;
                    })
                    .catch(error => {
                        Swal.showValidationMessage(`Oops: ${error.message}`);
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/presente/' + giftId;
                }
            });
        @else
            // Save intended gift in session/localStorage and redirect to login
            localStorage.setItem('intended_gift', giftId);
            localStorage.setItem('intended_gift_name', giftName);
            localStorage.setItem('intended_gift_price', giftPrice);
            
            Swal.fire({
                title: 'Faça login para presentear',
                text: 'Você precisa criar uma conta ou fazer login para comprar um presente.',
                icon: 'info',
                iconColor: '#c9a87c',
                confirmButtonText: 'Entrar',
                confirmButtonColor: '#c9a87c',
                showCancelButton: true,
                cancelButtonText: 'Criar Conta',
                cancelButtonColor: '#a07d50',
                background: '#faf8f5',
                color: '#3d3427',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route("login") }}';
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    window.location.href = '{{ route("register") }}';
                }
            });
        @endauth
    }

    // Check if user came back from login with an intended gift
    document.addEventListener('DOMContentLoaded', function() {
        @auth
            const intendedGift = localStorage.getItem('intended_gift');
            const intendedGiftName = localStorage.getItem('intended_gift_name');
            const intendedGiftPrice = localStorage.getItem('intended_gift_price');
            
            if (intendedGift && intendedGiftName && intendedGiftPrice) {
                // Remove from storage to prevent multiple triggers
                localStorage.removeItem('intended_gift');
                localStorage.removeItem('intended_gift_name');
                localStorage.removeItem('intended_gift_price');
                
                // Small delay to ensure smooth UI transition
                setTimeout(() => {
                    selectGift(intendedGift, intendedGiftName, intendedGiftPrice);
                }, 500);
            }
        @endauth
    });
</script>
@endpush