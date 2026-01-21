@extends('base')

@section('title', 'Daftar Game - Tier List')

@section('extra-css')
<style>
    /* Game Card Styling */
    .game-card {
        position: relative;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .game-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: var(--primary-gradient);
        opacity: 0;
        transition: opacity 0.4s ease;
        z-index: 0;
    }

    .game-card:hover::before {
        opacity: 0.1;
    }

    .game-card > * {
        position: relative;
        z-index: 1;
    }

    .game-card-icon {
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), filter 0.4s ease;
    }

    .game-card:hover .game-card-icon {
        transform: scale(1.2) rotate(5deg);
        filter: brightness(1.2);
    }

    .game-card-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, 
            rgba(102, 126, 234, 0.95) 0%, 
            rgba(118, 75, 162, 0.95) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.4s ease;
        z-index: 2;
    }

    .game-card:hover .game-card-overlay {
        opacity: 1;
    }

    .game-card-overlay span {
        font-size: 3rem;
        animation: bounce 1s ease infinite;
    }

    /* Section Title */
    .section-title {
        position: relative;
        display: inline-block;
        margin-bottom: 50px;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -15px;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 5px;
        background: var(--primary-gradient);
        border-radius: 3px;
        box-shadow: 0 0 20px rgba(102, 126, 234, 0.5);
    }

    /* Stats Badge */
    .stats-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        font-size: 0.8rem;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
    }

    .game-card:hover .stats-badge {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.3);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 24px;
        border: 2px dashed rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
    }

    .empty-state:hover {
        border-color: rgba(102, 126, 234, 0.3);
        background: rgba(255, 255, 255, 0.05);
    }

    .empty-state i {
        font-size: 5rem;
        margin-bottom: 25px;
        opacity: 0.5;
        animation: float 3s ease-in-out infinite;
    }

    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    /* Loading skeleton */
    .loading-skeleton {
        background: linear-gradient(90deg, 
            rgba(255,255,255,0.05) 25%, 
            rgba(255,255,255,0.1) 50%, 
            rgba(255,255,255,0.05) 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        border-radius: 20px;
    }

    /* Card body hover effect */
    .game-card .card-body {
        position: relative;
        overflow: hidden;
    }

    .game-card .card-body::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.4s ease;
        pointer-events: none;
    }

    .game-card:hover .card-body::before {
        opacity: 1;
    }

    /* Feature cards */
    .feature-card {
        padding: 40px 30px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
    }

    .feature-card i {
        transition: transform 0.4s ease;
    }

    .feature-card:hover i {
        transform: scale(1.2) rotate(10deg);
    }

    /* Card title styling */
    .game-card .card-title {
        position: relative;
        display: inline-block;
    }

    .game-card:hover .card-title::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        width: 100%;
        height: 2px;
        background: var(--primary-gradient);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .hero-section h1 {
            font-size: 2.5rem !important;
        }
        
        .game-card-overlay span {
            font-size: 2rem !important;
        }
    }
</style>
@endsection

@section('content')
<div style="min-height: 100vh;">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <i class="bi bi-controller me-2"></i>Gacha Tier List
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="/">
                            <i class="bi bi-house-door me-1"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/login">
                            <i class="bi bi-shield-lock me-1"></i> Admin
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container position-relative" style="z-index: 1;">
            <div class="animate-float mb-4">
                <i class="bi bi-controller" style="font-size: 5rem;"></i>
            </div>
            <h1 class="gradient-text">Gacha Tier List</h1>
            <p class="text-muted" style="font-size: 1.2rem; max-width: 600px; margin: 0 auto;">Ranking karakter game gacha favorit Anda dengan tampilan yang menarik dan mudah digunakan</p>
            <div class="mt-5 d-flex justify-content-center gap-3 flex-wrap">
                <a href="#games" class="btn btn-primary btn-lg" style="padding: 15px 35px;">
                    <i class="bi bi-play-fill me-2"></i>Explore Games
                </a>
                <a href="/admin/login" class="btn btn-outline-light btn-lg" style="padding: 15px 35px;">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Admin Panel
                </a>
            </div>
        </div>
    </div>

    <!-- Games Section -->
    <div class="container py-6" id="games">
        <div class="text-center mb-6">
            <h2 class="section-title gradient-text" style="font-size: 2.2rem; font-weight: 800;">Pilih Game</h2>
            <p class="text-muted mt-4" style="font-size: 1.1rem;">Pilih game favorit Anda untuk melihat tier list karakter</p>
        </div>
        
        <div class="row g-4 justify-content-center">
            @forelse($games ?? \App\Models\Game::all() as $game)
                <div class="col-md-4 col-lg-3">
                    <a href="{{ route('game.tier-list', ['gameSlug' => $game->slug]) }}" style="text-decoration: none;">
                        <div class="card game-card h-100" style="min-height: 340px;">
                            <div style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.3) 0%, rgba(118, 75, 162, 0.3) 100%); height: 170px; display: flex; align-items: center; justify-content: center; position: relative;">
                                @if($game->icon_url)
                                    <img src="{{ $game->icon_url }}" alt="{{ $game->game_name }}" 
                                         class="game-card-icon"
                                         style="width: 110px; height: 110px; object-fit: contain;">
                                @else
                                    <i class="bi bi-controller game-card-icon" style="font-size: 4.5rem; color: white;"></i>
                                @endif
                                <div class="game-card-overlay">
                                    <span class="text-white"><i class="bi bi-eye-fill me-2"></i>Lihat Tier List</span>
                                </div>
                            </div>
                            <div class="card-body text-center" style="background: rgba(255, 255, 255, 0.03);">
                                <h5 class="card-title mb-3" style="font-size: 1.4rem; color: white;">{{ $game->game_name }}</h5>
                                <p class="card-text text-muted mb-3" style="font-size: 0.95rem;">
                                    {{ Str::limit($game->description, 80) }}
                                </p>
                                <div class="stats-badge">
                                    <i class="bi bi-bar-chart-steps"></i>
                                    <span>{{ \App\Models\TierCategory::where('game_id', $game->id)->count() }} Tier List</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <h4 class="text-muted mb-3" style="font-weight: 600;">Belum ada game tersedia</h4>
                        <p class="text-muted mb-4">Silakan hubungi admin untuk menambahkan game.</p>
                        <a href="/admin/login" class="btn btn-primary btn-lg mt-3">
                            <i class="bi bi-shield-lock me-2"></i>Masuk sebagai Admin
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Features Section -->
    <div class="container py-6">
        <div class="text-center mb-6">
            <h2 class="section-title gradient-text" style="font-size: 2rem; font-weight: 800;">Fitur Unggulan</h2>
            <p class="text-muted mt-3" style="font-size: 1.1rem;">Nikmati berbagai fitur menarik untuk mengelola tier list</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card feature-card h-100 text-center">
                    <div class="mb-4">
                        <i class="bi bi-star-fill" style="font-size: 3.5rem; background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                    </div>
                    <h5 class="card-title mb-3" style="font-size: 1.3rem; font-weight: 700;">Tier Ranking</h5>
                    <p class="card-text text-muted">Sistem ranking SS, S, A, B, C, D untuk mengkategorikan kekuatan karakter dengan visual yang menarik</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card h-100 text-center">
                    <div class="mb-4">
                        <i class="bi bi-diagram-3" style="font-size: 3.5rem; background: var(--secondary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                    </div>
                    <h5 class="card-title mb-3" style="font-size: 1.3rem; font-weight: 700;">Multi Kategori</h5>
                    <p class="card-text text-muted">Bisa memiliki multiple tier list untuk satu game (PvE, PvP, Event, dll) dengan mudah</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card h-100 text-center">
                    <div class="mb-4">
                        <i class="bi bi-people" style="font-size: 3.5rem; background: var(--success-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                    </div>
                    <h5 class="card-title mb-3" style="font-size: 1.3rem; font-weight: 700;">Banyak Karakter</h5>
                    <p class="card-text text-muted">Ratusan karakter dengan sistem elemen dan role yang lengkap dan terorganisir</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p class="mb-2">&copy; 2025 Gacha Tier List. All rights reserved.</p>
            <p class="mb-0">
                <a href="/admin/login"><i class="bi bi-shield-lock me-1"></i>Admin Login</a>
            </p>
        </div>
    </footer>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add stagger animation to game cards
        const cards = document.querySelectorAll('.game-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            setTimeout(() => {
                card.style.transition = 'all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 150);
        });

        // Add stagger animation to feature cards
        const featureCards = document.querySelectorAll('.feature-card');
        featureCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 300 + (index * 150));
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    });
</script>
@endsection

