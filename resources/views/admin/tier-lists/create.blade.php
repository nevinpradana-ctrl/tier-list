@extends('base')

@section('title', 'Buat Tier List')

@section('extra-css')
<style>
    .form-panel {
        background: rgba(255, 255, 255, 0.03);
        border-radius: 20px;
        padding: 35px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .form-label {
        color: rgba(255, 255, 255, 0.9);
        font-weight: 600;
        margin-bottom: 10px;
    }

    .form-control, .form-select {
        background: rgba(255, 255, 255, 0.05);
        border: 2px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 14px 18px;
        color: white;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        background: rgba(255, 255, 255, 0.1);
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.2);
        color: white;
    }

    .form-select option {
        background: #1a1a2e;
        color: white;
    }

    .info-box {
        background: rgba(102, 126, 234, 0.1);
        border: 1px solid rgba(102, 126, 234, 0.3);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
    }

    .info-box i {
        color: #667eea;
        font-size: 1.5rem;
        margin-bottom: 10px;
        display: block;
    }

    .btn-group {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }

    .btn-cancel {
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid rgba(255, 255, 255, 0.2);
        color: white;
    }

    .btn-cancel:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: white;
        color: white;
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
            @if ($errors->any())
                <div class="alert alert-danger" style="border-radius: 12px;">
                    <ul class="mb-0" style="list-style: none; padding: 0;">
                        @foreach ($errors->all() as $error)
                            <li><i class="bi bi-exclamation-circle me-2"></i>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <h1 class="mb-4">
                <i class="bi bi-plus-circle me-2"></i>Buat Kategori Tier List
            </h1>

            <div class="form-panel">
                <div class="info-box">
                    <i class="bi bi-info-circle-fill"></i>
                    <strong>Tips Pembuatan Tier List</strong>
                    <p class="mb-0 mt-2" style="opacity: 0.8;">
                        Buat kategori tier list untuk game tertentu. Contoh: "Overall Tier List", "PvP Meta", 
                        "Abyss Guide", atau "Support Characters Only".
                    </p>
                </div>

                <form action="{{ route('admin.tier-lists.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="game_id" class="form-label">
                            <i class="bi bi-controller me-2"></i>Game <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('game_id') is-invalid @endretry('game_id') is-invalid @enderror" 
                                id="game_id" name="game_id" required>
                            <option value="">-- Pilih Game --</option>
                            @foreach($games as $game)
                                <option value="{{ $game->id }}" {{ old('game_id') == $game->id ? 'selected' : '' }}>
                                    {{ $game->game_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('game_id')
                            <div class="text-danger mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="category_name" class="form-label">
                            <i class="bi bi-bar-chart-steps me-2"></i>Nama Kategori <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('category_name') is-invalid @endretry('category_name') is-invalid @enderror" 
                               id="category_name" name="category_name" required value="{{ old('category_name') }}"
                               placeholder="Contoh: Overall Tier List, PvP Meta, Abyss Guide">
                        @error('category_name')
                            <div class="text-danger mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary" style="flex: 1;">
                            <i class="bi bi-check-lg me-2"></i>Buat Tier List
                        </button>
                        <a href="{{ route('admin.tier-lists.index') }}" class="btn btn-cancel" style="flex: 1;">
                            <i class="bi bi-x-lg me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

