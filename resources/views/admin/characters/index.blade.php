@extends('base')

@section('title', 'Kelola Karakter')

@section('extra-css')
<style>
    .character-image {
        width: 55px;
        height: 55px;
        object-fit: cover;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.05);
        padding: 3px;
    }

    .rarity-stars {
        color: #fbbf24;
        letter-spacing: 2px;
    }

    .element-badge, .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .element-badge {
        background: rgba(102, 126, 234, 0.2);
        border: 1px solid rgba(102, 126, 234, 0.5);
        color: #93c5fd;
    }

    .role-badge {
        background: rgba(240, 147, 251, 0.2);
        border: 1px solid rgba(240, 147, 251, 0.5);
        color: #f0abfc;
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
            <a href="{{ route('admin.characters.index') }}" class="active">
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
                    <i class="bi bi-people me-2"></i>Kelola Karakter
                </h1>
                <a href="{{ route('admin.characters.create') }}" class="btn btn-primary">
                    <i class="bi bi-person-plus me-2"></i>Tambah Karakter
                </a>
            </div>

            <!-- Search and Filter -->
            <form action="{{ route('admin.characters.index') }}" method="GET" id="filterForm">
                <div class="row mb-4 g-3">
                    <div class="col-md-4">
                        <div class="search-box">
                            <i class="bi bi-search"></i>
                            <input type="text" class="form-control" name="keyword" 
                                   placeholder="Cari karakter..." 
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
                        <select class="form-select filter-select" name="rarity">
                            <option value="">Rarity</option>
                            @for($i = 5; $i >= 3; $i--)
                                <option value="{{ $i }}" {{ request('rarity') == $i ? 'selected' : '' }}>{{ $i }}⭐</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-funnel me-1"></i>Filter
                        </button>
                    </div>
                    <div class="col-md-1">
                        <a href="{{ route('admin.characters.index') }}" class="btn btn-secondary w-100" title="Reset">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 80px;"><i class="bi bi-image me-1"></i>Image</th>
                            <th><i class="bi bi-person me-1"></i>Nama</th>
                            <th><i class="bi bi-controller me-1"></i>Game</th>
                            <th><i class="bi bi-star me-1"></i>Rarity</th>
                            <th><i class="bi bi-lightning me-1"></i>Elemen</th>
                            <th><i class="bi bi-shield me-1"></i>Role</th>
                            <th style="width: 120px;"><i class="bi bi-gear me-1"></i>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($characters as $character)
                            <tr>
                                <td>
                                    @if($character->image_url)
                                        <img src="{{ $character->image_url }}" alt="{{ $character->name }}" class="character-image">
                                    @else
                                        <div class="character-image d-flex align-items-center justify-content-center" style="background: rgba(255,255,255,0.05);">
                                            <i class="bi bi-person text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $character->name }}</strong>
                                </td>
                                <td>
                                    <span class="game-badge">{{ $character->game->game_name }}</span>
                                </td>
                                <td>
                                    <span class="rarity-stars">
                                        @for($i = 0; $i < $character->rarity; $i++)
                                            <i class="bi bi-star-fill"></i>
                                        @endfor
                                    </span>
                                </td>
                                <td>
                                    @if($character->element)
                                        <span class="element-badge">
                                            <i class="bi bi-lightning"></i>{{ $character->element->element_name }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($character->role)
                                        <span class="role-badge">
                                            <i class="bi bi-shield"></i>{{ $character->role->role_name }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.characters.edit', $character) }}" class="btn btn-sm btn-warning action-btn">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.characters.destroy', $character) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger action-btn" 
                                                    onclick="return confirm('Hapus karakter {{ $character->name }}?');">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="bi bi-inbox"></i>
                                        @if(request()->hasAny(['keyword', 'game_id', 'rarity']))
                                            <h5 class="text-muted mb-3">Tidak ada karakter yang cocok dengan filter</h5>
                                            <a href="{{ route('admin.characters.index') }}" class="btn btn-primary">
                                                <i class="bi bi-arrow-clockwise me-2"></i>Reset Filter
                                            </a>
                                        @else
                                            <h5 class="text-muted mb-3">Belum ada karakter</h5>
                                            <p class="text-muted mb-3">Tambahkan karakter pertama untuk memulai</p>
                                            <a href="{{ route('admin.characters.create') }}" class="btn btn-primary">
                                                <i class="bi bi-person-plus me-2"></i>Tambah Karakter
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($characters->hasPages())
                <div class="mt-4">
                    {{ $characters->appends(request()->all())->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    // Auto-submit form when filters change
    document.querySelectorAll('.filter-select').forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });
</script>
@endsection

