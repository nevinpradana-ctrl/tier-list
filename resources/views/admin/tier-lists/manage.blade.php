@extends('base')

@section('title', 'Kelola Tier: ' . $tierCategory->category_name)

@section('extra-css')
<style>
    .tier-section {
        margin-bottom: 30px;
        border-radius: 20px;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .tier-header {
        padding: 20px 25px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .tier-header-rank {
        font-size: 1.5rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .tier-count {
        background: rgba(255, 255, 255, 0.2);
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.9rem;
    }

    .tier-body {
        padding: 25px;
        background: rgba(255, 255, 255, 0.02);
    }

    .character-manage-card {
        position: relative;
        transition: all 0.3s ease;
    }

    .character-manage-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }

    .character-manage-card:hover {
        transform: translateY(-5px);
    }

    .character-manage-card .card-img-top {
        height: 140px;
        object-fit: cover;
    }

    .character-manage-card .card-body {
        padding: 15px;
    }

    .character-manage-card .card-title {
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 10px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .character-manage-card .remove-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
        transition: transform 0.2s ease;
    }

    .character-manage-card .remove-btn:hover {
        transform: scale(1.1);
    }

    .add-character-form {
        background: rgba(102, 126, 234, 0.1);
        border: 1px solid rgba(102, 126, 234, 0.3);
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 30px;
    }

    .add-character-form h5 {
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .add-character-form h5 i {
        color: #10b981;
    }

    .form-select, .form-control {
        background: rgba(255, 255, 255, 0.05);
        border: 2px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 12px 15px;
        color: white;
        transition: all 0.3s ease;
    }

    .form-select:focus, .form-control:focus {
        background: rgba(255, 255, 255, 0.1);
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.2);
        color: white;
    }

    .form-select option {
        background: #1a1a2e;
        color: white;
    }

    .empty-tier {
        text-align: center;
        padding: 40px;
        color: rgba(255, 255, 255, 0.4);
    }

    .empty-tier i {
        font-size: 3rem;
        margin-bottom: 15px;
        display: block;
    }

    .game-info {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.2) 0%, rgba(118, 75, 162, 0.2) 100%);
        border-radius: 16px;
        padding: 20px 25px;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
    }

    .game-info-left {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .game-info-icon {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        object-fit: contain;
        background: rgba(255, 255, 255, 0.1);
        padding: 5px;
    }

    .game-info h4 {
        margin: 0;
        font-weight: 700;
    }

    .game-info p {
        margin: 0;
        opacity: 0.7;
        font-size: 0.9rem;
    }

    .rank-ss { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
    .rank-s { background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); }
    .rank-a { background: linear-gradient(135deg, #eab308 0%, #ca8a04 100%); color: black; }
    .rank-b { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); }
    .rank-c { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .rank-d { background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%); }

    .character-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-bottom: 10px;
    }

    .tag {
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .tag-element {
        background: rgba(102, 126, 234, 0.3);
        border: 1px solid rgba(102, 126, 234, 0.5);
    }

    .tag-role {
        background: rgba(240, 147, 251, 0.3);
        border: 1px solid rgba(240, 147, 251, 0.5);
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

            <!-- Game Info -->
            <div class="game-info">
                <div class="game-info-left">
                    @if($tierCategory->game->icon_url)
                        <img src="{{ $tierCategory->game->icon_url }}" alt="" class="game-info-icon">
                    @else
                        <div class="game-info-icon d-flex align-items-center justify-content-center">
                            <i class="bi bi-controller" style="font-size: 1.5rem;"></i>
                        </div>
                    @endif
                    <div>
                        <h4>{{ $tierCategory->game->game_name }}</h4>
                        <p>{{ $tierCategory->category_name }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.tier-lists.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>

            <!-- Add Character Form -->
            <div class="add-character-form">
                <h5>
                    <i class="bi bi-plus-circle-fill"></i>
                    Tambahkan Karakter ke Tier List
                </h5>
                <form action="{{ route('admin.tier-lists.assign', $tierCategory) }}" method="POST" class="row g-3">
                    @csrf
                    
                    <div class="col-lg-4 col-md-6">
                        <select class="form-select" name="character_id" required>
                            <option value="">-- Pilih Karakter --</option>
                            @foreach($characters as $char)
                                @php
                                    $isInList = $tierData->flatten()->pluck('character_id')->contains($char->id);
                                @endphp
                                @if(!$isInList)
                                    <option value="{{ $char->id }}">{{ $char->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <select class="form-select" name="rank" required>
                            <option value="">-- Rank --</option>
                            <option value="SS">SS</option>
                            <option value="S">S</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-8">
                        <input type="text" class="form-control" name="note" placeholder="Catatan (opsional)">
                    </div>

                    <div class="col-lg-2 col-md-4">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-plus-lg me-1"></i>Tambah
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tier List Display -->
            @php
                $rankOrder = ['SS', 'S', 'A', 'B', 'C', 'D'];
                $rankColors = ['SS' => 'rank-ss', 'S' => 'rank-s', 'A' => 'rank-a', 'B' => 'rank-b', 'C' => 'rank-c', 'D' => 'rank-d'];
            @endphp

            @foreach($rankOrder as $rank)
                @if(isset($tierData[$rank]) && $tierData[$rank]->count() > 0)
                    <div class="tier-section">
                        <div class="tier-header {{ $rankColors[$rank] }}">
                            <div class="tier-header-rank">
                                <span>RANK {{ $rank }}</span>
                                <span class="tier-count">{{ $tierData[$rank]->count() }} karakter</span>
                            </div>
                        </div>
                        <div class="tier-body">
                            <div class="row g-3">
                                @foreach($tierData[$rank] as $tier)
                                    <div class="col-md-3 col-sm-6">
                                        <div class="card character-manage-card h-100" style="border: none;">
                                            <button type="button" class="remove-btn btn btn-danger"
                                                    onclick="if(confirm('Hapus {{ $tier->character->name }} dari tier list?')) { document.getElementById('remove-form-{{ $tier->id }}').submit(); }">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            <form id="remove-form-{{ $tier->id }}" 
                                                  action="{{ route('admin.tier-lists.remove', [$tierCategory, $tier]) }}" 
                                                  method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            
                                            @if($tier->character->image_url)
                                                <img src="{{ $tier->character->image_url }}" 
                                                     alt="{{ $tier->character->name }}" 
                                                     class="card-img-top">
                                            @else
                                                <div class="card-img-top d-flex align-items-center justify-content-center" 
                                                     style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.3) 0%, rgba(118, 75, 162, 0.3) 100%);">
                                                    <span style="font-size: 2.5rem; font-weight: 800; color: white;">
                                                        {{ Str::substr($tier->character->name, 0, 2) }}
                                                    </span>
                                                </div>
                                            @endif
                                            <div class="card-body">
                                                <h6 class="card-title">{{ $tier->character->name }}</h6>
                                                <div class="character-tags">
                                                    @if($tier->character->element)
                                                        <span class="tag tag-element">
                                                            <i class="bi bi-lightning"></i>{{ $tier->character->element->element_name }}
                                                        </span>
                                                    @endif
                                                    @if($tier->character->role)
                                                        <span class="tag tag-role">
                                                            <i class="bi bi-shield"></i>{{ $tier->character->role->role_name }}
                                                        </span>
                                                    @endif
                                                </div>
                                                @if($tier->note)
                                                    <small style="color: rgba(255, 255, 255, 0.6); font-style: italic;">
                                                        "{{ $tier->note }}"
                                                    </small>
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
                <div class="tier-section">
                    <div class="tier-body">
                        <div class="empty-tier">
                            <i class="bi bi-inbox"></i>
                            <h4 class="mb-3">Tier list masih kosong</h4>
                            <p>Tambahkan karakter menggunakan form di atas untuk memulai.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

