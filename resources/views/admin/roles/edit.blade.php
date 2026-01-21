@extends('base')

@section('title', 'Edit Role')

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

    .current-icon {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 12px;
        margin-bottom: 15px;
    }

    .current-icon img {
        width: 80px;
        height: 80px;
        object-fit: contain;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.05);
        padding: 5px;
    }

    .file-upload {
        position: relative;
        overflow: hidden;
    }

    .file-upload input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .file-upload-area {
        background: rgba(255, 255, 255, 0.03);
        border: 2px dashed rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        padding: 40px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .file-upload-area:hover {
        border-color: #f093fb;
        background: rgba(240, 147, 251, 0.1);
    }

    .file-upload-area i {
        font-size: 3rem;
        background: var(--secondary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 15px;
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
            <a href="{{ route('admin.roles.index') }}" class="active">
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
                <i class="bi bi-pencil-square me-2"></i>Edit Role
            </h1>

            <div class="form-panel">
                <form action="{{ route('admin.roles.update', $role->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="game_id" class="form-label">
                            <i class="bi bi-controller me-2"></i>Game <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('game_id') is-invalid @endretry('game_id') is-invalid @enderror" 
                                id="game_id" name="game_id" required>
                            <option value="">-- Pilih Game --</option>
                            @foreach($games as $game)
                                <option value="{{ $game->id }}" {{ $role->game_id == $game->id ? 'selected' : '' }}>
                                    {{ $game->game_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('game_id')
                            <div class="text-danger mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="role_name" class="form-label">
                            <i class="bi bi-shield me-2"></i>Nama Role <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('role_name') is-invalid @endretry('role_name') is-invalid @enderror" 
                               id="role_name" name="role_name" value="{{ $role->role_name }}" required>
                        @error('role_name')
                            <div class="text-danger mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">
                            <i class="bi bi-image me-2"></i>Icon Role
                        </label>
                        @if($role->icon_url)
                            <div class="current-icon">
                                @if(strpos($role->icon_url, '/storage/') === 0 || strpos($role->icon_url, '://') !== false)
                                    <img src="{{ $role->icon_url }}" alt="{{ $role->role_name }}">
                                @else
                                    <img src="{{ asset($role->icon_url) }}" alt="{{ $role->role_name }}">
                                @endif
                                <div>
                                    <strong>Icon Saat Ini</strong>
                                    <small class="d-block text-muted">Klik area di bawah untuk mengubah icon</small>
                                </div>
                            </div>
                        @endif
                        <div class="file-upload">
                            <div class="file-upload-area">
                                <i class="bi bi-cloud-upload"></i>
                                <p class="mb-2" style="font-weight: 600;">Klik atau tarik gambar untuk mengubah</p>
                                <p class="text-muted small mb-0">Format: JPG, PNG | Max: 2MB | Rekomendasi: 150×150px</p>
                            </div>
                            <input type="file" class="form-control @error('icon_url') is-invalid @endretry('icon_url') is-invalid @enderror" 
                                   id="icon_url" name="icon_url" accept="image/*">
                        </div>
                    </div>

                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary" style="flex: 1;">
                            <i class="bi bi-check-lg me-2"></i>Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-cancel" style="flex: 1;">
                            <i class="bi bi-x-lg me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

