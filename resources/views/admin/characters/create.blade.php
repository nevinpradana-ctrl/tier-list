@extends('base')

@section('title', 'Tambah Karakter')

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

    .form-text {
        color: rgba(255, 255, 255, 0.5);
        font-size: 0.85rem;
        margin-top: 8px;
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

    .rarity-option {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 15px;
        background: rgba(255, 255, 255, 0.03);
        border: 2px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .rarity-option:hover, .rarity-option.selected {
        border-color: #fbbf24;
        background: rgba(251, 191, 36, 0.1);
    }

    .rarity-option input {
        display: none;
    }

    .rarity-stars {
        color: #fbbf24;
        font-size: 1.2rem;
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
                <i class="bi bi-person-plus me-2"></i>Tambah Karakter
            </h1>

            <div class="form-panel">
                <form action="{{ route('admin.characters.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label for="game_id" class="form-label">
                            <i class="bi bi-controller me-2"></i>Game <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('game_id') is-invalid @enderror" 
                                id="game_id" name="game_id" required onchange="updateElements()">
                            <option value="">-- Pilih Game --</option>
                            @foreach($games as $game)
                                <option value="{{ $game->id }}" {{ old('game_id') == $game->id ? 'selected' : '' }}>
                                    {{ $game->game_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="name" class="form-label">
                            <i class="bi bi-person me-2"></i>Nama Karakter <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" required value="{{ old('name') }}"
                               placeholder="Contoh: Hu Tao">
                        @error('name')
                            <div class="text-danger mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">
                            <i class="bi bi-star me-2"></i>Rarity <span class="text-danger">*</span>
                        </label>
                        <div class="d-flex gap-3">
                            @for($i = 5; $i >= 3; $i--)
                                <label class="rarity-option {{ old('rarity') == $i ? 'selected' : '' }}">
                                    <input type="radio" name="rarity" value="{{ $i }}" {{ old('rarity') == $i ? 'checked' : '' }}>
                                    <span class="rarity-stars">
                                        @for($j = 0; $j < $i; $j++)
                                            <i class="bi bi-star-fill"></i>
                                        @endfor
                                    </span>
                                </label>
                            @endfor
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="element_id" class="form-label">
                            <i class="bi bi-lightning me-2"></i>Elemen
                        </label>
                        <select class="form-select @error('element_id') is-invalid @enderror" 
                                id="element_id" name="element_id">
                            <option value="">-- Pilih Elemen --</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="role_id" class="form-label">
                            <i class="bi bi-shield me-2"></i>Role
                        </label>
                        <select class="form-select @error('role_id') is-invalid @enderror" 
                                id="role_id" name="role_id">
                            <option value="">-- Pilih Role --</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">
                            <i class="bi bi-image me-2"></i>Gambar Karakter
                        </label>
                        <div class="file-upload">
                            <div class="file-upload-area">
                                <i class="bi bi-cloud-upload"></i>
                                <p class="mb-2" style="font-weight: 600;">Klik atau tarik gambar ke sini</p>
                                <p class="text-muted small mb-0">Format: JPG, PNG | Max: 2MB | Rekomendasi: 300×400px</p>
                            </div>
                            <input type="file" class="form-control @error('image_url') is-invalid @enderror" 
                                   id="image_url" name="image_url" accept="image/*">
                        </div>
                        @error('image_url')
                            <div class="text-danger mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary" style="flex: 1;">
                            <i class="bi bi-check-lg me-2"></i>Simpan Karakter
                        </button>
                        <a href="{{ route('admin.characters.index') }}" class="btn btn-cancel" style="flex: 1;">
                            <i class="bi bi-x-lg me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('extra-js')
<script>
function updateElements() {
    const gameId = document.getElementById('game_id').value;
    
    fetch(`/api/elements/${gameId}`)
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('element_id');
            select.innerHTML = '<option value="">-- Pilih Elemen --</option>';
            data.forEach(element => {
                const option = document.createElement('option');
                option.value = element.id;
                option.textContent = element.element_name;
                select.appendChild(option);
            });
        });

    fetch(`/api/roles/${gameId}`)
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('role_id');
            select.innerHTML = '<option value="">-- Pilih Role --</option>';
            data.forEach(role => {
                const option = document.createElement('option');
                option.value = role.id;
                option.textContent = role.role_name;
                select.appendChild(option);
            });
        });
}

// Rarity option selection
document.querySelectorAll('.rarity-option').forEach(option => {
    option.addEventListener('click', function() {
        document.querySelectorAll('.rarity-option').forEach(o => o.classList.remove('selected'));
        this.classList.add('selected');
        this.querySelector('input').checked = true;
    });
});

// Initialize on page load if game already selected
if (document.getElementById('game_id').value) {
    updateElements();
}
</script>
@endsection

