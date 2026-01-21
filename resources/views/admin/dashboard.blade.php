@extends('base')

@section('title', 'Admin Dashboard')

@section('extra-css')
<style>
    .stats-card {
        position: relative;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .stats-card:hover::before {
        opacity: 1;
    }

    .stats-card-icon {
        font-size: 3rem;
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .stats-card:hover .stats-card-icon {
        transform: scale(1.2) rotate(10deg);
    }

    .stats-card-value {
        font-size: 2.8rem;
        font-weight: 800;
        line-height: 1;
    }

    .stats-card-label {
        font-size: 0.95rem;
        opacity: 0.7;
        margin-top: 8px;
    }

    .stats-card-action {
        position: absolute;
        bottom: 15px;
        right: 15px;
        font-size: 0.85rem;
        opacity: 0;
        transform: translateX(-10px);
        transition: all 0.3s ease;
    }

    .stats-card:hover .stats-card-action {
        opacity: 1;
        transform: translateX(0);
    }

    .welcome-banner {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.3) 0%, rgba(118, 75, 162, 0.3) 100%);
        border-radius: 24px;
        padding: 50px;
        position: relative;
        overflow: hidden;
        margin-bottom: 35px;
    }

    .welcome-banner::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 350px;
        height: 350px;
        background: radial-gradient(circle, rgba(102, 126, 234, 0.3) 0%, transparent 70%);
        animation: float 6s ease-in-out infinite;
    }

    .welcome-banner::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -5%;
        width: 250px;
        height: 250px;
        background: radial-gradient(circle, rgba(240, 147, 251, 0.2) 0%, transparent 70%);
        animation: float 8s ease-in-out infinite reverse;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }

    .welcome-title {
        font-size: 2.2rem;
        font-weight: 800;
        margin-bottom: 12px;
        position: relative;
        z-index: 1;
    }

    .welcome-subtitle {
        opacity: 0.85;
        font-size: 1.15rem;
        position: relative;
        z-index: 1;
    }

    .welcome-subtitle span {
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 600;
    }

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 18px;
        margin-top: 30px;
        position: relative;
        z-index: 1;
    }

    .quick-action-btn {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 18px 24px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        color: white;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .quick-action-btn:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: translateX(8px) translateY(-3px);
        color: white;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        border-color: rgba(102, 126, 234, 0.3);
    }

    .quick-action-btn i {
        font-size: 1.4rem;
    }

    .recent-activity {
        margin-top: 35px;
    }

    .activity-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 14px;
        margin-bottom: 12px;
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .activity-item:hover {
        background: rgba(255, 255, 255, 0.08);
        transform: translateX(5px);
        border-color: rgba(102, 126, 234, 0.2);
    }

    .activity-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        font-size: 1.3rem;
        flex-shrink: 0;
    }

    .activity-content {
        flex: 1;
    }

    .activity-title {
        font-weight: 600;
        margin-bottom: 4px;
        font-size: 0.95rem;
    }

    .activity-time {
        font-size: 0.8rem;
        opacity: 0.5;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-in {
        animation: fadeInUp 0.6s ease forwards;
    }

    .user-role-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        background: rgba(102, 126, 234, 0.2);
        border: 1px solid rgba(102, 126, 234, 0.4);
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: capitalize;
        margin-left: 10px;
    }

    .user-role-badge i {
        font-size: 0.9rem;
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
            <a href="{{ route('admin.dashboard') }}" class="active">
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

    <!-- Main Content -->
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

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" style="border-radius: 12px;">
                    <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Welcome Banner -->
            <div class="welcome-banner animate-in">
                <h1 class="welcome-title">
                    <i class="bi bi-emoji-smile me-3"></i>Selamat Datang, {{ auth()->user()->name }}
                    @php
                        $roleIcons = [
                            'admin' => 'bi-shield-check',
                            'staff_editor' => 'bi-pencil-square',
                            'staff_viewer' => 'bi-eye',
                        ];
                    @endphp
                    <span class="user-role-badge">
                        <i class="bi {{ $roleIcons[auth()->user()->role] ?? 'bi-person' }}"></i>
                        {{ str_replace('_', ' ', auth()->user()->role) }}
                    </span>
                </h1>
                <p class="welcome-subtitle">Kelola tier list game gacha favorit Anda dengan mudah dan cepat.</p>
                
                <div class="quick-actions">
                    <a href="{{ route('admin.games.create') }}" class="quick-action-btn">
                        <i class="bi bi-plus-circle" style="color: #667eea;"></i>
                        <span>Tambah Game</span>
                    </a>
                    <a href="{{ route('admin.characters.create') }}" class="quick-action-btn">
                        <i class="bi bi-person-plus" style="color: #f97316;"></i>
                        <span>Tambah Karakter</span>
                    </a>
                    <a href="{{ route('admin.tier-lists.create') }}" class="quick-action-btn">
                        <i class="bi bi-bar-chart-plus" style="color: #10b981;"></i>
                        <span>Buat Tier List</span>
                    </a>
                    <a href="{{ route('home') }}" target="_blank" class="quick-action-btn">
                        <i class="bi bi-eye" style="color: #f093fb;"></i>
                        <span>Lihat Website</span>
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-3 col-sm-6">
                    <div class="card stats-card h-100" style="padding: 28px;">
                        <div style="position: relative; z-index: 1;">
                            <div class="stats-card-icon mb-3" style="background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                                <i class="bi bi-controller"></i>
                            </div>
                            <div class="stats-card-value gradient-text">{{ \App\Models\Game::count() }}</div>
                            <div class="stats-card-label">Total Game</div>
                        </div>
                        <div class="stats-card-action">
                            <i class="bi bi-arrow-right"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card stats-card h-100" style="padding: 28px;">
                        <div style="position: relative; z-index: 1;">
                            <div class="stats-card-icon mb-3" style="background: var(--secondary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="stats-card-value" style="background: var(--secondary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                                {{ \App\Models\Character::count() }}
                            </div>
                            <div class="stats-card-label">Total Karakter</div>
                        </div>
                        <div class="stats-card-action">
                            <i class="bi bi-arrow-right"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card stats-card h-100" style="padding: 28px;">
                        <div style="position: relative; z-index: 1;">
                            <div class="stats-card-icon mb-3" style="background: var(--success-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                                <i class="bi bi-bar-chart-steps"></i>
                            </div>
                            <div class="stats-card-value" style="background: var(--success-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                                {{ \App\Models\TierCategory::count() }}
                            </div>
                            <div class="stats-card-label">Tier List</div>
                        </div>
                        <div class="stats-card-action">
                            <i class="bi bi-arrow-right"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card stats-card h-100" style="padding: 28px;">
                        <div style="position: relative; z-index: 1;">
                            <div class="stats-card-icon mb-3" style="background: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                                <i class="bi bi-lightning"></i>
                            </div>
                            <div class="stats-card-value" style="background: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                                {{ \App\Models\Element::count() }}
                            </div>
                            <div class="stats-card-label">Element</div>
                        </div>
                        <div class="stats-card-action">
                            <i class="bi bi-arrow-right"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Access Cards -->
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100" style="padding: 30px;">
                        <h5 class="mb-4" style="font-weight: 700; font-size: 1.2rem;">
                            <i class="bi bi-grid-3x3-gap me-2"></i>Akses Cepat
                        </h5>
                        <div class="row g-3">
                            <div class="col-6">
                                <a href="{{ route('admin.games.index') }}" class="quick-action-btn" style="padding: 20px;">
                                    <i class="bi bi-controller" style="color: #667eea;"></i>
                                    <span>Kelola Game</span>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('admin.characters.index') }}" class="quick-action-btn" style="padding: 20px;">
                                    <i class="bi bi-people" style="color: #f97316;"></i>
                                    <span>Kelola Karakter</span>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('admin.tier-lists.index') }}" class="quick-action-btn" style="padding: 20px;">
                                    <i class="bi bi-bar-chart-steps" style="color: #10b981;"></i>
                                    <span>Kelola Tier</span>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('home') }}" target="_blank" class="quick-action-btn" style="padding: 20px;">
                                    <i class="bi bi-eye-fill" style="color: #f093fb;"></i>
                                    <span>Lihat Web</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card h-100" style="padding: 30px;">
                        <h5 class="mb-4" style="font-weight: 700; font-size: 1.2rem;">
                            <i class="bi bi-info-circle me-2"></i>Info Sistem
                        </h5>
                        <div class="activity-item">
                            <div class="activity-icon" style="background: rgba(102, 126, 234, 0.2); color: #667eea;">
                                <i class="bi bi-database"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Total Role</div>
                                <div class="activity-time">{{ \App\Models\Role::count() }} role tersedia</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon" style="background: rgba(240, 147, 251, 0.2); color: #f093fb;">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Total Admin</div>
                                <div class="activity-time">{{ \App\Models\Admin::count() }} admin aktif</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon" style="background: rgba(16, 185, 129, 0.2); color: #10b981;">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Tier Data</div>
                                <div class="activity-time">{{ \App\Models\TierData::count() }} data ranking</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

