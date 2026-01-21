@extends('base')

@section('title', 'Kelola Element')

@section('extra-css')
<style>
    .element-card {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .element-card:hover {
        background: rgba(255, 255, 255, 0.08);
        transform: translateX(5px);
    }

    .element-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        object-fit: cover;
        background: rgba(255, 255, 255, 0.05);
        padding: 5px;
    }

    .element-name {
        font-weight: 600;
        font-size: 1.05rem;
    }

    .game-badge {
        background: rgba(16, 185, 129, 0.2);
        border: 1px solid rgba(16, 185, 129, 0.5);
        color: #6ee7b7;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.8rem;
    }

    .action-btn {
        padding: 8px 14px;
        border-radius: 10px;
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }

    .action-btn:hover {
        transform: translateY(-2px);
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
            <a href="{{ route('admin.elements.index') }}" class="active">
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

            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <h1 class="mb-0">
                    <i class="bi bi-lightning me-2"></i>Kelola Element
                </h1>
                <a href="{{ route('admin.elements.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Element
                </a>
            </div>

            <!-- Search -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" class="form-control" placeholder="Cari element...">
                    </div>
                </div>
            </div>

            @forelse($elements->groupBy('game_id') as $gameId => $gameElements)
                <div class="mb-4">
                    <h5 class="mb-3 d-flex align-items-center gap-2">
                        <span class="game-badge">{{ $gameElements->first()->game->game_name ?? 'N/A' }}</span>
                    </h5>
                    <div class="row g-3">
                        @foreach($gameElements as $element)
                            <div class="col-md-6 col-lg-4">
                                <div class="element-card h-100">
                                    @if($element->icon_url && strpos($element->icon_url, '/storage/') === 0)
                                        <img src="{{ $element->icon_url }}" alt="{{ $element->element_name }}" class="element-icon">
                                    @elseif($element->icon_url)
                                        <img src="{{ asset($element->icon_url) }}" alt="{{ $element->element_name }}" class="element-icon">
                                    @else
                                        <div class="element-icon d-flex align-items-center justify-content-center">
                                            <i class="bi bi-lightning text-muted" style="font-size: 1.5rem;"></i>
                                        </div>
                                    @endif
                                    <div style="flex: 1;">
                                        <div class="element-name">{{ $element->element_name }}</div>
                                        <small class="text-muted">
                                            {{ \App\Models\Character::where('element_id', $element->id)->count() }} karakter
                                        </small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.elements.edit', $element->id) }}" class="btn btn-sm btn-warning action-btn">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.elements.destroy', $element->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger action-btn" 
                                                    onclick="return confirm('Hapus element {{ $element->element_name }}?');">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h5 class="text-muted mb-3">Belum ada element</h5>
                    <p class="text-muted mb-3">Tambahkan element pertama untuk memulai</p>
                    <a href="{{ route('admin.elements.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-2"></i>Tambah Element
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

