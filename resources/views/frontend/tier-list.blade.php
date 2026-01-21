@extends('base')

@section('title', $game->game_name . ' - Tier List')

@section('extra-css')
<style>
    /* Tier Header Effects */
    .tier-header {
        position: relative;
        overflow: hidden;
    }

    .tier-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: inherit;
        filter: blur(30px);
        opacity: 0.5;
        z-index: 0;
    }

    /* Tier Rank Shimmer Effect */
    .tier-rank {
        position: relative;
        overflow: hidden;
    }

    .tier-rank::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
        transform: skewX(-15deg) translateX(100px);
        animation: shimmer 3s infinite;
    }

    @keyframes shimmer {
        0% { transform: skewX(-15deg) translateX(-200px); }
        100% { transform: skewX(-15deg) translateX(400px); }
    }

    /* Character Card Hover Effects */
    .character-card {
        position: relative;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .character-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
    }

    .character-card:hover::before {
        opacity: 1;
    }

    .character-image {
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .character-card:hover .character-image {
        transform: scale(1.15);
    }

    .character-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4);
        border-color: rgba(102, 126, 234, 0.5);
    }

    .character-name {
        font-weight: 700;
        transition: all 0.3s ease;
    }

    .character-card:hover .character-name {
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Tags Styling */
    .tag-element, .tag-role {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
    }

    .tag-element {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.3), rgba(118, 75, 162, 0.3));
        border: 1px solid rgba(102, 126, 234, 0.5);
        color: #a5b4fc;
    }

    .tag-role {
        background: linear-gradient(135deg, rgba(240, 147, 251, 0.3), rgba(245, 87, 108, 0.3));
        border: 1px solid rgba(240, 147, 251, 0.5);
        color: #f0abfc;
    }

    .tag-element:hover, .tag-role:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    /* Tier Note */
    .tier-note {
        font-style: italic;
        opacity: 0.7;
        transition: all 0.3s ease;
        border-left: 2px solid rgba(255, 255, 255, 0.2);
        padding-left: 10px;
    }

    .character-card:hover .tier-note {
        opacity: 1;
    }

    /* Category Tabs */
    .category-tabs {
        position: relative;
        overflow-x: auto;
        padding-bottom: 10px;
    }

    .category-tabs::-webkit-scrollbar {
        height: 4px;
    }

    .category-tabs::-webkit-scrollbar-thumb {
        background: var(--primary-gradient);
        border-radius: 4px;
    }

    .category-tab {
        position: relative;
        padding: 14px 28px;
        border-radius: 14px;
        color: rgba(255, 255, 255, 0.6);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        white-space: nowrap;
    }

    .category-tab::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: var(--primary-gradient);
        opacity: 0;
        border-radius: 14px;
        transition: opacity 0.3s ease;
        z-index: 0;
    }

    .category-tab:hover::before,
    .category-tab.active::before {
        opacity: 1;
    }

    .category-tab span {
        position: relative;
        z-index: 1;
    }

    .category-tab.active {
        color: white;
        box-shadow: 0 8px 30px rgba(102, 126, 234, 0.5);
    }

    .category-tab:hover {
        color: white;
        transform: translateY(-3px);
    }

    /* Game Header */
    .game-header {
        position: relative;
        overflow: hidden;
    }

    .game-header-icon {
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .game-header:hover .game-header-icon {
        transform: scale(1.15) rotate(5deg);
    }

    /* Count Badge */
    .count-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 28px;
        height: 28px;
        padding: 0 12px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 14px;
        font-size: 0.85rem;
        font-weight: 700;
        backdrop-filter: blur(10px);
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

    .tier-empty-icon {
        font-size: 5rem;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 25px;
        animation: bounce 2s ease infinite;
    }

    /* Glow Animation */
    @keyframes glow {
        0%, 100% { box-shadow: 0 0 20px rgba(102, 126, 234, 0.3); }
        50% { box-shadow: 0 0 40px rgba(102, 126, 234, 0.6); }
    }

    .glow-animation {
        animation: glow 2s ease-in-out infinite;
    }

    /* Section Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .tier-section {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.6s ease forwards;
    }

    .tier-section:nth-child(1) { animation-delay: 0.1s; }
    .tier-section:nth-child(2) { animation-delay: 0.2s; }
    .tier-section:nth-child(3) { animation-delay: 0.3s; }
    .tier-section:nth-child(4) { animation-delay: 0.4s; }
    .tier-section:nth-child(5) { animation-delay: 0.5s; }
    .tier-section:nth-child(6) { animation-delay: 0.6s; }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .game-header-icon {
            width: 70px !important;
            height: 70px !important;
        }
        
        .character-card:hover {
            transform: translateY(-5px) scale(1.01);
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
                        <a class="nav-link" href="/">
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

    <!-- Game Header -->
    <div class="game-header" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.4) 0%, rgba(118, 75, 162, 0.4) 100%); padding: 50px 20px; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
        <div class="container">
            <div class="d-flex align-items-center gap-4 flex-wrap">
                @if($game->icon_url)
                    <img src="{{ $game->icon_url }}" alt="{{ $game->game_name }}" 
                         class="game-header-icon"
                         style="width: 90px; height: 90px; object-fit: contain; background: rgba(255,255,255,0.1); padding: 12px; border-radius: 20px;">
                @else
                    <div class="game-header-icon" style="width: 90px; height: 90px; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.1); border-radius: 20px;">
                        <i class="bi bi-controller" style="font-size: 3rem;"></i>
                    </div>
                @endif
                <div>
                    <h1 style="margin: 0; font-size: 2.2rem; font-weight: 800;">{{ $game->game_name }}</h1>
                    <p style="margin: 8px 0 0 0; color: rgba(255, 255, 255, 0.7); font-size: 1rem;">{{ $game->description }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Tabs -->
    @if($categories->count() > 1)
        <div style="background: rgba(0, 0, 0, 0.3); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
            <div class="container">
                <div class="category-tabs d-flex gap-2 py-3">
                    @foreach($categories as $cat)
                        <a href="{{ route('game.tier-list', ['gameSlug' => $game->slug, 'categoryId' => $cat->id]) }}"
                           class="category-tab {{ $category->id == $cat->id ? 'active' : '' }}">
                            <span><i class="bi bi-bar-chart-steps me-2"></i>{{ $cat->category_name }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Tier List Display -->
    <div class="container py-5">
        <div class="d-flex align-items-center justify-content-between mb-5 flex-wrap gap-3">
            <h2 class="gradient-text mb-0" style="font-size: 1.8rem;">
                <i class="bi bi-bar-chart-steps me-2"></i>{{ $category->category_name }}
            </h2>
            <div class="count-badge glow-animation">
                <i class="bi bi-people me-2"></i>
                {{ $tierData->flatten()->count() }} Karakter
            </div>
        </div>

        @php
            $rankOrder = ['SS', 'S', 'A', 'B', 'C', 'D'];
            $rankColors = [
                'SS' => ['bg' => 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)', 'glow' => 'rgba(239, 68, 68, 0.5)'],
                'S' => ['bg' => 'linear-gradient(135deg, #f97316 0%, #ea580c 100%)', 'glow' => 'rgba(249, 115, 22, 0.5)'],
                'A' => ['bg' => 'linear-gradient(135deg, #eab308 0%, #ca8a04 100%)', 'glow' => 'rgba(234, 179, 8, 0.5)'],
                'B' => ['bg' => 'linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%)', 'glow' => 'rgba(59, 130, 246, 0.5)'],
                'C' => ['bg' => 'linear-gradient(135deg, #10b981 0%, #059669 100%)', 'glow' => 'rgba(16, 185, 129, 0.5)'],
                'D' => ['bg' => 'linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%)', 'glow' => 'rgba(139, 92, 246, 0.5)']
            ];
        @endphp

        @foreach($rankOrder as $index => $rank)
            @if(isset($tierData[$rank]) && $tierData[$rank]->count() > 0)
                <div class="tier-section mb-4">
                    <div class="tier-rank d-flex align-items-center gap-3" 
                         style="background: {{ $rankColors[$rank]['bg'] }}; padding: 20px 28px; border-radius: 16px 16px 0 0; box-shadow: 0 8px 30px {{ $rankColors[$rank]['glow'] }};">
                        <span style="font-size: 1.6rem; font-weight: 800; letter-spacing: 2px;">RANK {{ $rank }}</span>
                        <span class="count-badge bg-white bg-opacity-25">
                            <i class="bi bi-people-fill me-1"></i>
                            {{ $tierData[$rank]->count() }} karakter
                        </span>
                    </div>
                    <div style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); padding: 30px; border-radius: 0 0 16px 16px; border: 1px solid rgba(255, 255, 255, 0.1); border-top: none;">
                        <div class="row g-4">
                            @foreach($tierData[$rank] as $tier)
                                <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                                    <div class="card character-card h-100" 
                                         style="border: 2px solid transparent; background: rgba(255, 255, 255, 0.05);">
                                        @if($tier->character->image_url)
                                            <img src="{{ $tier->character->image_url }}" 
                                                 alt="{{ $tier->character->name }}" 
                                                 class="character-image"
                                                 style="height: 160px; object-fit: cover; width: 100%;">
                                        @else
                                            <div style="background: linear-gradient(135deg, {{ str_replace(['linear-gradient(135deg, ', ')'], ['', ''], $rankColors[$rank]['bg']) }}; height: 160px; display: flex; align-items: center; justify-content: center;">
                                                <span style="font-size: 2.8rem; font-weight: 800; color: white; text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                                                    {{ Str::substr($tier->character->name, 0, 2) }}
                                                </span>
                                            </div>
                                        @endif
                                        <div class="card-body" style="padding: 18px;">
                                            <h6 class="character-name mb-3" style="font-size: 0.95rem; color: white; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                                                title="{{ $tier->character->name }}">
                                                {{ $tier->character->name }}
                                            </h6>
                                            <div class="d-flex flex-wrap gap-2 mb-3">
                                                @if($tier->character->element)
                                                    <span class="tag-element">
                                                        <i class="bi bi-lightning me-1"></i>{{ $tier->character->element->element_name }}
                                                    </span>
                                                @endif
                                                @if($tier->character->role)
                                                    <span class="tag-role">
                                                        <i class="bi bi-shield me-1"></i>{{ $tier->character->role->role_name }}
                                                    </span>
                                                @endif
                                            </div>
                                            @if($tier->note)
                                                <p class="tier-note mb-0" style="font-size: 0.8rem; color: rgba(255, 255, 255, 0.5);">
                                                    "{{ $tier->note }}"
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

        @if($tierData->isEmpty())
            <div class="empty-state">
                <i class="bi bi-inbox tier-empty-icon"></i>
                <h4 class="mb-3" style="font-weight: 700;">Tier list masih kosong</h4>
                <p class="text-muted mb-4">Belum ada karakter yang ditambahkan ke kategori ini.</p>
                <a href="/admin/login" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Karakter
                </a>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p class="mb-2">&copy; 2025 Gacha Tier List. All rights reserved.</p>
            <p class="mb-0">
                <a href="/admin/login"><i class="bi bi-shield-lock me-1"></i>Admin Login</a>
                <span class="text-muted mx-3">|</span>
                <a href="/"><i class="bi bi-house me-1"></i>Kembali ke Home</a>
            </p>
        </div>
    </footer>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add stagger animation to character cards
        const cards = document.querySelectorAll('.character-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 50);
        });

        // Smooth scroll for category tabs
        document.querySelectorAll('.category-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                // Add ripple effect
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        });
    });
</script>
@endsection

