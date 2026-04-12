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
        background: linear-gradient(160deg, #faf8f5 0%, #f5f0e8 30%, #e8d5b7 100%);
        overflow: hidden;
    }

    .hero::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
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
        font-size: 0.75rem;
        letter-spacing: 6px;
        text-transform: uppercase;
        color: var(--rose);
        margin-bottom: 1.5rem;
        opacity: 0;
        animation: fadeInUp 1s ease 0.2s forwards;
    }

    .hero-names {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2.5rem, 8vw, 5rem);
        font-weight: 400;
        color: var(--text-dark);
        line-height: 1.1;
        margin-bottom: 1rem;
        opacity: 0;
        animation: fadeInUp 1s ease 0.5s forwards;
    }

    .hero-names .amp {
        font-style: italic;
        color: var(--rose);
        font-size: 0.7em;
        display: inline-block;
        margin: 0 0.3em;
    }

    .hero-date {
        font-family: 'Lato', sans-serif;
        font-size: 1rem;
        letter-spacing: 3px;
        color: var(--text-light);
        margin-bottom: 2rem;
        opacity: 0;
        animation: fadeInUp 1s ease 0.8s forwards;
    }

    .hero-divider {
        width: 60px;
        height: 1px;
        background: var(--rose);
        margin: 0 auto 2rem;
        opacity: 0;
        animation: fadeInUp 1s ease 0.9s forwards;
    }

    /* Countdown */
    .countdown {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin-bottom: 2.5rem;
        opacity: 0;
        animation: fadeInUp 1s ease 1s forwards;
    }

    .countdown-item {
        text-align: center;
    }

    .countdown-number {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        font-weight: 600;
        color: var(--text-dark);
        line-height: 1;
    }

    .countdown-label {
        font-size: 0.65rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--text-light);
        margin-top: 0.3rem;
    }

    .hero-cta {
        opacity: 0;
        animation: fadeInUp 1s ease 1.2s forwards;
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
    }

    .btn-elegant:hover {
        background: var(--rose);
        color: var(--white);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(201,168,124,0.3);
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

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .countdown {
            gap: 1rem;
        }
        .countdown-number {
            font-size: 1.8rem;
        }
        .event-card {
            margin-bottom: 1rem;
        }
        .gift-image {
            height: 160px;
        }
    }

    @media (max-width: 576px) {
        .hero {
            min-height: 90vh;
        }
        .countdown {
            gap: 0.8rem;
        }
        .countdown-number {
            font-size: 1.5rem;
        }
        .filter-tabs {
            gap: 0.3rem;
        }
        .filter-tab {
            padding: 0.4rem 1rem;
            font-size: 0.7rem;
        }
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
                <a href="#presentes" class="btn-elegant">Ver Presentes</a>
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
        </div>
    </section>

    <!-- ===== GIFTS ===== -->
    <section class="gifts-section" id="presentes">
        <div class="container">
            <div class="reveal">
                <p class="section-subtitle">Presentes para a Lua de Mel</p>
                <h2 class="section-title">Nos Ajude a Realizar esse Sonho</h2>
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

                {{-- ===== CURITIBA ===== --}}
                <div class="col-lg-4 col-md-6 gift-item" data-city="curitiba">
                    <div class="gift-card" onclick="selectGift(1)">
                        <div class="gift-image-wrapper">
                            <span class="gift-city-badge">Curitiba</span>
                            <img src="https://images.unsplash.com/photo-1585320806297-9794b3e4eeae?w=600" class="gift-image" alt="Jardim Botânico">
                        </div>
                        <div class="gift-body">
                            <h4 class="gift-name">Jardim Botânico</h4>
                            <p class="gift-description">Visita ao icônico Jardim Botânico de Curitiba, com sua estufa de vidro Art Nouveau e jardins impecáveis.</p>
                            <div class="gift-price">R$ 150,00</div>
                            <span class="btn-gift">Presentear</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 gift-item" data-city="curitiba">
                    <div class="gift-card" onclick="selectGift(2)">
                        <div class="gift-image-wrapper">
                            <span class="gift-city-badge">Curitiba</span>
                            <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=600" class="gift-image" alt="Tour Gastronômico">
                        </div>
                        <div class="gift-body">
                            <h4 class="gift-name">Tour Gastronômico</h4>
                            <p class="gift-description">Roteiro pelos melhores restaurantes e cafés da cidade, degustando pratos típicos paranaenses.</p>
                            <div class="gift-price">R$ 200,00</div>
                            <span class="btn-gift">Presentear</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 gift-item" data-city="curitiba">
                    <div class="gift-card" onclick="selectGift(3)">
                        <div class="gift-image-wrapper">
                            <span class="gift-city-badge">Curitiba</span>
                            <img src="https://images.unsplash.com/photo-1474487548417-781cb71495f3?w=600" class="gift-image" alt="Trem Serra do Mar">
                        </div>
                        <div class="gift-body">
                            <h4 class="gift-name">Passeio de Trem – Serra do Mar</h4>
                            <p class="gift-description">Viagem de trem pela Serra do Mar, com vistas deslumbrantes da Mata Atlântica e pontes históricas.</p>
                            <div class="gift-price">R$ 350,00</div>
                            <span class="btn-gift">Presentear</span>
                        </div>
                    </div>
                </div>

                {{-- ===== BALNEÁRIO CAMBORIÚ ===== --}}
                <div class="col-lg-4 col-md-6 gift-item" data-city="balneario">
                    <div class="gift-card" onclick="selectGift(4)">
                        <div class="gift-image-wrapper">
                            <span class="gift-city-badge">Balneário Camboriú</span>
                            <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=600" class="gift-image" alt="Teleférico">
                        </div>
                        <div class="gift-body">
                            <h4 class="gift-name">Teleférico + Cristo Luz</h4>
                            <p class="gift-description">Passeio de teleférico com vista panorâmica e visita ao mirante do Cristo Luz ao entardecer.</p>
                            <div class="gift-price">R$ 250,00</div>
                            <span class="btn-gift">Presentear</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 gift-item" data-city="balneario">
                    <div class="gift-card" onclick="selectGift(5)">
                        <div class="gift-image-wrapper">
                            <span class="gift-city-badge">Balneário Camboriú</span>
                            <img src="https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=600" class="gift-image" alt="Barco Pirata">
                        </div>
                        <div class="gift-body">
                            <h4 class="gift-name">Passeio de Barco Pirata</h4>
                            <p class="gift-description">Diversão a bordo do famoso Barco Pirata, navegando pela costa com música e animação.</p>
                            <div class="gift-price">R$ 180,00</div>
                            <span class="btn-gift">Presentear</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 gift-item" data-city="balneario">
                    <div class="gift-card" onclick="selectGift(6)">
                        <div class="gift-image-wrapper">
                            <span class="gift-city-badge">Balneário Camboriú</span>
                            <img src="https://images.unsplash.com/photo-1540541338287-41700207dee6?w=600" class="gift-image" alt="Beach Club">
                        </div>
                        <div class="gift-body">
                            <h4 class="gift-name">Day Use Beach Club</h4>
                            <p class="gift-description">Um dia inteiro em um beach club exclusivo com piscina, drinks e vista para o mar.</p>
                            <div class="gift-price">R$ 300,00</div>
                            <span class="btn-gift">Presentear</span>
                        </div>
                    </div>
                </div>

                {{-- ===== FLORIANÓPOLIS ===== --}}
                <div class="col-lg-4 col-md-6 gift-item" data-city="florianopolis">
                    <div class="gift-card" onclick="selectGift(7)">
                        <div class="gift-image-wrapper">
                            <span class="gift-city-badge">Florianópolis</span>
                            <img src="https://images.unsplash.com/photo-1551632811-561732d1e306?w=600" class="gift-image" alt="Trilha">
                        </div>
                        <div class="gift-body">
                            <h4 class="gift-name">Trilha da Lagoinha do Leste</h4>
                            <p class="gift-description">Trilha até uma das praias mais bonitas do Brasil, com paisagens de tirar o fôlego.</p>
                            <div class="gift-price">R$ 200,00</div>
                            <span class="btn-gift">Presentear</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 gift-item" data-city="florianopolis">
                    <div class="gift-card" onclick="selectGift(8)">
                        <div class="gift-image-wrapper">
                            <span class="gift-city-badge">Florianópolis</span>
                            <img src="https://images.unsplash.com/photo-1500930287596-c1ecaa210c04?w=600" class="gift-image" alt="Escuna">
                        </div>
                        <div class="gift-body">
                            <h4 class="gift-name">Passeio de Escuna</h4>
                            <p class="gift-description">Navegação pela baía de Florianópolis, passando por ilhas paradisíacas e paradas para mergulho.</p>
                            <div class="gift-price">R$ 280,00</div>
                            <span class="btn-gift">Presentear</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 gift-item" data-city="florianopolis">
                    <div class="gift-card" onclick="selectGift(9)">
                        <div class="gift-image-wrapper">
                            <span class="gift-city-badge">Florianópolis</span>
                            <img src="https://images.unsplash.com/photo-1559339352-11d035aa65de?w=600" class="gift-image" alt="Jantar Romântico">
                        </div>
                        <div class="gift-body">
                            <h4 class="gift-name">Jantar Romântico à Beira-Mar</h4>
                            <p class="gift-description">Noite especial em um restaurante à beira-mar com menu degustação e vista para o pôr do sol.</p>
                            <div class="gift-price">R$ 400,00</div>
                            <span class="btn-gift">Presentear</span>
                        </div>
                    </div>
                </div>

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
    function selectGift(giftId) {
        @auth
            window.location.href = '/presente/' + giftId;
        @else
            // Save intended gift in session/localStorage and redirect to login
            localStorage.setItem('intended_gift', giftId);
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
</script>
@endpush