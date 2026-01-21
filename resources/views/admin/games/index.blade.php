 +@extends('base')

@section('title', 'Kelola Game')

@section('extra-css')
<style>
    .table-icon {
        width: 50px;
        height: 50px;
        object-fit: contain;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.05);
        padding: 5px;
    }

    .action-btn {
        padding: 8px 16px;
        border-radius: 10px;
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }

    .action-btn:hover {
        transform: translateY(-2px);
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

    .slug-badge {
        font-family: monospace;
        background: rgba(102, 126, 234, 0.2);
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.85rem;
        color: #93c5fd;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state i {
        font-size: 4rem;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 20px;
        display: block;
    }

    .table-responsive {
        border-radius: 16px;
        overflow: hidden;
    }

    .table {
        margin-bottom: 0;
    }

    .table tbody td {
        vertical-align: middle;
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

    .filter-form {
        transition: all 0.3s ease;
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
            <a href="{{ route('admin.games.index') }}" class="active">
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
            <a href="{{ route('admin.tier-lists.index') }}">
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
                    <i class="bi bi-controller me-2"></i>Kelola Game
                </h1>
                <a href="{{ route('admin.games.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Game
                </a>
            </div>

            <!-- Search Box with Filter -->
            <form action="{{ route('admin.games.index') }}" method="GET" class="filter-form mb-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="search-box">
                            <i class="bi bi-search"></i>
                            <input type="text" class="form-control" name="keyword" 
                                   placeholder="Cari game..." 
                                   value="{{ request('keyword', '') }}"
                                   id="searchInput">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-1"></i>Cari
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('admin.games.index') }}" class="btn btn-secondary w-100">
                            <i class="bi bi-arrow-clockwise me-1"></i>Reset
                        </a>
                    </div>
                    <div class="col-md-2 d-flex align-items-center">
                        <div class="loading-spinner" id="loadingSpinner"></div>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 80px;"><i class="bi bi-image me-1"></i>Icon</th>
                            <th><i class="bi bi-controller me-1"></i>Nama Game</th>
                            <th><i class="bi bi-hash me-1"></i>Slug</th>
                            <th><i class="bi bi-text-paragraph me-1"></i>Deskripsi</th>
                            <th style="width: 150px;"><i class="bi bi-gear me-1"></i>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="gamesTableBody">
                        @forelse($games as $game)
                            <tr>
                                <td>
                                    @if($game->icon_url)
                                        <img src="{{ $game->icon_url }}" alt="{{ $game->game_name }}" class="table-icon">
                                    @else
                                        <div class="table-icon d-flex align-items-center justify-content-center" style="background: rgba(255,255,255,0.05);">
                                            <i class="bi bi-controller text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong style="font-size: 1.05rem;">{{ $game->game_name }}</strong>
                                    <div class="text-muted small">
                                        <i class="bi bi-bar-chart-steps me-1"></i>
                                        {{ \App\Models\TierCategory::where('game_id', $game->id)->count() }} tier list
                                    </div>
                                </td>
                                <td>
                                    <span class="slug-badge">{{ $game->slug }}</span>
                                </td>
                                <td>
                                    <span class="text-muted">{{ Str::limit($game->description, 60) }}</span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.games.edit', $game) }}" class="btn btn-sm btn-warning action-btn">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.games.destroy', $game) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger action-btn" 
                                                    onclick="return confirm('Hapus game {{ $game->game_name }}?');">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <i class="bi bi-inbox"></i>
                                        @if(request('keyword'))
                                            <h5 class="text-muted mb-3">Tidak ada hasil untuk "{{ request('keyword') }}"</h5>
                                        @else
                                            <h5 class="text-muted mb-3">Belum ada game</h5>
                                        @endif
                                        <p class="text-muted mb-3">@if(request('keyword')) Coba kata kunci lain @else Tambah game pertama untuk memulai @endif</p>
                                        @if(!request('keyword'))
                                            <a href="{{ route('admin.games.create') }}" class="btn btn-primary">
                                                <i class="bi bi-plus-lg me-2"></i>Tambah Game
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($games->hasPages())
                <div class="mt-4">
                    {{ $games->appends(request()->all())->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    // Debounce function for search
    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    // Auto-submit on search with debounce
    const searchInput = document.getElementById('searchInput');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const filterForm = document.querySelector('.filter-form');

    if (searchInput) {
        searchInput.addEventListener('input', debounce(function() {
            // Show loading
            if (loadingSpinner) loadingSpinner.style.display = 'block';
            
            // Submit form
            filterForm.submit();
        }, 500));
    }

    // Show loading on form submit
    if (filterForm) {
        filterForm.addEventListener('submit', function() {
            if (loadingSpinner) loadingSpinner.style.display = 'block';
        });
    }
</script>
@endsection

