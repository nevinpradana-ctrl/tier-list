@extends('base')

@section('title', 'Kelola Tier List')

@section('extra-css')
<style>
    .tier-card {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 20px;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 16px;
        transition: all 0.3s ease;
        position: relative;
        overflow: visible;
    }

    .tier-card:hover {
        background: rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .tier-card:hover .action-buttons {
        opacity: 1;
        visibility: visible;
        transform: translateX(0);
    }

    .tier-icon {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        background: var(--primary-gradient);
        flex-shrink: 0;
        position: relative;
        z-index: 1;
    }

    .tier-icon img:hover {
        transform: none;
        box-shadow: none;
    }

    .action-buttons {
        flex-shrink: 0;
        position: relative;
        z-index: 2;
    }

    .tier-info {
        flex: 1;
    }

    .tier-name {
        font-weight: 700;
        font-size: 1.15rem;
        margin-bottom: 5px;
    }

    .game-badge {
        background: rgba(102, 126, 234, 0.2);
        border: 1px solid rgba(102, 126, 234, 0.5);
        color: #93c5fd;
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 0.85rem;
        display: inline-block;
        margin-bottom: 8px;
    }

    .character-count {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(16, 185, 129, 0.2);
        border: 1px solid rgba(16, 185, 129, 0.5);
        color: #6ee7b7;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .last-updated {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.5);
    }

    .action-btn {
        padding: 10px 16px;
        border-radius: 12px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .action-btn:hover {
        transform: translateY(-2px);
    }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
    }

    .empty-state i {
        font-size: 5rem;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 25px;
        display: block;
    }

    .search-box {
        position: relative;
    }

    .search-box input {
        background: rgba(255, 255, 255, 0.05);
        border: 2px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 12px 20px 12px 45px;
        color: white;
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        background: rgba(255, 255, 255, 0.1);
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.2);
        color: white;
    }

    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(255, 255, 255, 0.4);
    }

    .filter-select {
        background: rgba(255, 255, 255, 0.05);
        border: 2px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 12px 15px;
        color: white;
        cursor: pointer;
    }

    .filter-select:focus {
        border-color: #667eea;
        outline: none;
    }

    .filter-select option {
        background: #1a1a2e;
        color: white;
    }

    /* Loading spinner */
    .loading-spinner {
        display: none;
        width: 20px;
        height: 20px;
        border: 2px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top-color: #667eea;
        animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>
@endsection

@section('content')
<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar col-md-3">
        <div class="p-3 border-bottom">
            <h5 class="text-warning mb-0">
                <i class="bi bi-shield-check me-2"></i>ADMIN PANEL
            </h5>
        </div>
        <nav>
            <a href="{{ route('admin.dashboard') }}">
                <i class="bi bi-speedometer2"></i>Dashboard
            </a>
            <a href="{{ route('admin.games.index') }}">
                <i class="bi bi-controller"></i>Kelola Game
            </a>
            <a href="{{ route('admin.characters.index') }}">
                <i class="bi bi-people"></i>Kelola Karakter
            </a>
            <a href="{{ route('admin.elements.index') }}">
                <i class="bi bi-lightning"></i>Kelola Element
            </a>
            <a href="{{ route('admin.roles.index') }}">
                <i class="bi bi-shield"></i>Kelola Role
            </a>
            <a href="{{ route('admin.tier-lists.index') }}" class="active">
                <i class="bi bi-bar-chart-steps"></i>Kelola Tier List
            </a>
            <hr class="bg-secondary">
            <form action="{{ route('admin.logout') }}" method="POST" style="padding: 0 20px;">
                @csrf
                <button type="submit" class="btn btn-danger w-100">
                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                </button>
            </form>
        </nav>
    </div>

    <div class="col-md-9">
        <div class="content-wrapper">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" style="border-radius: 12px;">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('warning'))
                <div class="alert alert-warning alert-dismissible fade show mb-4" style="border-radius: 12px;">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <h1 class="mb-0">
                    <i class="bi bi-bar-chart-steps me-2"></i>Kelola Tier List
                </h1>
                <a href="{{ route('admin.tier-lists.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>Buat Kategori
                </a>
            </div>

            <!-- Search and Filter -->
            <form action="{{ route('admin.tier-lists.index') }}" method="GET" id="filterForm">
                <div class="row mb-4 g-3">
                    <div class="col-md-4">
                        <div class="search-box">
                            <i class="bi bi-search"></i>
                            <input type="text" class="form-control" name="keyword" 
                                   placeholder="Cari tier list..." 
                                   value="{{ request('keyword', '') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select filter-select" name="game_id">
                            <option value="">Filter by Game</option>
                            @foreach($games as $game)
                                <option value="{{ $game->id }}" {{ request('game_id') == $game->id ? 'selected' : '' }}>
                                    {{ $game->game_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-funnel me-1"></i>Filter
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('admin.tier-lists.index') }}" class="btn btn-secondary w-100">
                            <i class="bi bi-arrow-clockwise me-1"></i>Reset
                        </a>
                    </div>
                    <div class="col-md-1 d-flex align-items-center">
                        <div class="loading-spinner" id="loadingSpinner"></div>
                    </div>
                </div>
            </form>

            @forelse($tierCategories->groupBy('game_id') as $gameId => $categories)
                <div class="mb-4">
                    <h5 class="mb-3 d-flex align-items-center gap-2">
                        @if($categories->first()->game->icon_url)
                            <img src="{{ $categories->first()->game->icon_url }}" alt="" style="width: 30px; height: 30px; object-fit: contain;">
                        @else
                            <i class="bi bi-controller"></i>
                        @endif
                        <span>{{ $categories->first()->game->game_name ?? 'N/A' }}</span>
                    </h5>
                    <div class="row g-3">
                        @foreach($categories as $category)
                            <div class="col-lg-6">
                                <div class="tier-card">
                                    <div class="tier-icon">
                                        <i class="bi bi-bar-chart-steps"></i>
                                    </div>
                                    <div class="tier-info">
                                        <span class="game-badge">{{ $category->game->game_name }}</span>
                                        <div class="tier-name">{{ $category->category_name }}</div>
                                        <div class="d-flex align-items-center gap-3 flex-wrap">
                                            <span class="character-count">
                                                <i class="bi bi-people-fill"></i>
                                                {{ $category->tierData->count() }} karakter
                                            </span>
                                            <span class="last-updated">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ $category->updated_at?->format('d M Y') ?? 'Belum update' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="action-buttons d-flex flex-column gap-2">
                                        <a href="{{ route('admin.tier-lists.manage', $category) }}" class="btn btn-sm btn-info action-btn">
                                            <i class="bi bi-gear me-1"></i>Kelola
                                        </a>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.tier-lists.edit', $category) }}" class="btn btn-sm btn-warning action-btn">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.tier-lists.destroy', $category) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger action-btn" 
                                                        onclick="return confirm('Hapus kategori {{ $category->category_name }}?');">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    @if(request()->hasAny(['keyword', 'game_id']))
                        <h5 class="text-muted mb-3">Tidak ada tier list yang cocok dengan filter</h5>
                        <a href="{{ route('admin.tier-lists.index') }}" class="btn btn-primary">
                            <i class="bi bi-arrow-clockwise me-2"></i>Reset Filter
                        </a>
                    @else
                        <h4 class="mb-3">Belum ada tier list</h4>
                        <p class="text-muted mb-4">Buat kategori tier list pertama untuk memulai</p>
                        <a href="{{ route('admin.tier-lists.create') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-plus-lg me-2"></i>Buat Tier List
                        </a>
                    @endif
                </div>
            @endforelse

            @if($tierCategories->hasPages())
                <div class="mt-4">
                    {{ $tierCategories->appends(request()->all())->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    // Auto-submit form when filter changes
    document.querySelector('.filter-select')?.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });

    // Show loading on form submit
    const filterForm = document.getElementById('filterForm');
    const loadingSpinner = document.getElementById('loadingSpinner');
    
    if (filterForm && loadingSpinner) {
        filterForm.addEventListener('submit', function() {
            loadingSpinner.style.display = 'block';
        });
    }
</script>
@endsection

