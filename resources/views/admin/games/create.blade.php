@extends('base')

@section('title', 'Tambah Game')

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

    .form-control {
        background: rgba(255, 255, 255, 0.05);
        border: 2px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 14px 18px;
        color: white;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        background: rgba(255, 255, 255, 0.1);
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.2);
        color: white;
    }

    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.4);
    }

    .form-text {
        color: rgba(255, 255, 255, 0.5);
        font-size: 0.85rem;
        margin-top: 8px;
    }

    .form-text i {
        color: #667eea;
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
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
        border-color: #667eea;
        background: rgba(102, 126, 234, 0.1);
    }

    .file-upload-area i {
        font-size: 3rem;
        background: var(--primary-gradient);
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

    .input-hint {
        background: rgba(102, 126, 234, 0.1);
        border-left: 3px solid #667eea;
        padding: 12px 15px;
        border-radius: 0 10px 10px 0;
        margin-bottom: 20px;
    }

    .input-hint i {
        color: #667eea;
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
                <i class="bi bi-plus-circle me-2"></i>Tambah Game
            </h1>

            <div class="form-panel">
                <form action="{{ route('admin.games.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label for="game_name" class="form-label">
                            <i class="bi bi-controller me-2"></i>Nama Game <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('game_name') is-invalid @enderror" 
                               id="game_name" name="game_name" required value="{{ old('game_name') }}"
                               placeholder="Contoh: Genshin Impact">
                        @error('game_name')
                            <div class="text-danger mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="slug" class="form-label">
                            <i class="bi bi-link-45deg me-2"></i>Slug URL <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                               id="slug" name="slug" required value="{{ old('slug') }}"
                               placeholder="Contoh: genshin-impact">
                        <small class="form-text">
                            <i class="bi bi-info-circle me-1"></i>Gunakan huruf kecil dan dash untuk memisahkan kata
                        </small>
                        @error('slug')
                            <div class="text-danger mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">
                            <i class="bi bi-image me-2"></i>Icon Game
                        </label>
                        <div class="file-upload">
                            <div class="file-upload-area">
                                <i class="bi bi-cloud-upload"></i>
                                <p class="mb-2" style="font-weight: 600;">Klik atau tarik gambar ke sini</p>
                                <p class="text-muted small mb-0">Format: JPG, PNG | Max: 2MB | Rekomendasi: 400×400px</p>
                            </div>
                            <input type="file" class="form-control @error('icon_url') is-invalid @enderror" 
                                   id="icon_url" name="icon_url" accept="image/*">
                        </div>
                        @error('icon_url')
                            <div class="text-danger mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label">
                            <i class="bi bi-text-paragraph me-2"></i>Deskripsi
                        </label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4"
                                  placeholder="Deskripsi singkat tentang game...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-danger mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary" style="flex: 1;">
                            <i class="bi bi-check-lg me-2"></i>Simpan Game
                        </button>
                        <a href="{{ route('admin.games.index') }}" class="btn btn-cancel" style="flex: 1;">
                            <i class="bi bi-x-lg me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-generate slug from game name
        const gameNameInput = document.getElementById('game_name');
        const slugInput = document.getElementById('slug');

        gameNameInput.addEventListener('input', function() {
            const slug = this.value
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim();
            
            // Only update if slug field is empty or was auto-generated
            if (slugInput.value === '' || slugInput.dataset.autoGenerated === 'true') {
                slugInput.value = slug;
                slugInput.dataset.autoGenerated = 'true';
            }
        });

        slugInput.addEventListener('input', function() {
            this.dataset.autoGenerated = 'false';
        });
    });
</script>
@endsection

