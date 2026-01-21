# Dokumentasi Teknis Aplikasi Tier List Game Gacha

Dokumen ini menjelaskan secara rinci dan teknis tentang arsitektur, komponen, dan alur kerja aplikasi Tier List Game Gacha yang dibangun menggunakan Laravel. Penjelasan mencakup frontend, backend, database, controller, middleware, dan berbagai aspek teknis lainnya.

---

## 1. Frontend Daftar Game (Halaman Utama)

### 1.1 Deskripsi dan Fungsi

Halaman daftar game merupakan halaman utama yang menampilkan seluruh game yang tersedia dalam sistem. Halaman ini berfungsi sebagai gateway utama bagi pengguna untuk memilih game mana yang ingin dilihat tier list-nya. Desain halaman ini dibuat menarik dengan menggunakan efek hover animasi, gradient yang konsisten, dan tata letak responsif yang menyesuaikan dengan berbagai ukuran layar.

Halaman ini juga menyediakan informasi tambahan untuk setiap game seperti jumlah tier list yang tersedia, deskripsi singkat, dan ikon game. Pengguna dapat langsung mengklik kartu game untuk menuju ke halaman tier list spesifik game tersebut. Sistem juga menyediakan akses ke panel admin bagi pengguna yang memiliki otoritas untuk mengelola data.

### 1.2 File yang Terlibat

Halaman ini di-render menggunakan file `resources/views/frontend/index.blade.php` yang merupakan bagian dari Blade templating system Laravel. File ini me-extend layout dasar `base.blade.php` yang berisi struktur HTML umum seperti navbar, CSS global, dan footer. Berikut adalah struktur folder yang relevan untuk komponen frontend:

```
resources/views/
├── base.blade.php                    # Layout utama aplikasi
├── frontend/
│   ├── index.blade.php              # Halaman daftar game (utama)
│   └── tier-list.blade.php          # Halaman tier list per game
└── admin/                           # View untuk panel admin
```

### 1.3 Route yang Digunakan

```php
// routes/web.php
Route::get('/', function () {
    return view('frontend.index');
})->name('home');

Route::get('/games', [TierListController::class, 'viewTierList'])->name('games.index');
Route::get('/game/{gameSlug}/{categoryId?}', [TierListController::class, 'viewTierList'])->name('game.tier-list');
```

Route pertama (`/`) menampilkan halaman utama dengan daftar game. Route kedua (`/games`) juga menampilkan daftar game melalui controller. Route ketiga (`/game/{gameSlug}`) digunakan untuk menampilkan tier list spesifik suatu game berdasarkan slug URL, dengan parameter opsional `categoryId` untuk memilih kategori tier list tertentu.

### 1.4 Model yang Digunakan

```php
// app/Models/Game.php
class Game extends Model
{
    protected $table = 'games';
    protected $fillable = ['game_name', 'slug', 'icon_url', 'description'];

    public function tierCategories(): HasMany
    {
        return $this->hasMany(TierCategory::class);
    }
}
```

Model `Game` merepresentasikan entitas game dalam database. Atribut `fillable` mendefinisikan kolom yang dapat diisi secara massal (mass assignment). Relasi `tierCategories` menghubungkan game dengan kategori-kategori tier list yang dimilikinya, yang digunakan untuk menghitung jumlah tier list per game.

### 1.5 Cuplikan Kode View (Frontend Index)

```blade
@extends('base')

@section('title', 'Daftar Game - Tier List')

@section('content')
<div style="min-height: 100vh;">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <i class="bi bi-controller me-2"></i>Gacha Tier List
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="/">
                            <i class="bi bi-house-door me-1"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/login">
                            <i class="bi bi-shield-lock me-1"></i> Admin
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container position-relative" style="z-index: 1;">
            <h1 class="gradient-text">Gacha Tier List</h1>
            <p class="text-muted">Ranking karakter game gacha favorit Anda</p>
        </div>
    </div>

    <!-- Games Section -->
    <div class="container py-5" id="games">
        <div class="text-center mb-5">
            <h2 class="section-title gradient-text">Pilih Game</h2>
        </div>

        <div class="row g-4 justify-content-center">
            @forelse($games ?? \App\Models\Game::all() as $game)
                <div class="col-md-4 col-lg-3">
                    <a href="{{ route('game.tier-list', ['gameSlug' => $game->slug]) }}" style="text-decoration: none;">
                        <div class="card game-card h-100" style="min-height: 320px;">
                            <div style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.3) 0%, rgba(118, 75, 162, 0.3) 100%); height: 160px; display: flex; align-items: center; justify-content: center;">
                                @if($game->icon_url)
                                    <img src="{{ $game->icon_url }}" alt="{{ $game->game_name }}"
                                         class="game-card-icon"
                                         style="width: 100px; height: 100px; object-fit: contain;">
                                @else
                                    <i class="bi bi-controller game-card-icon" style="font-size: 4rem; color: white;"></i>
                                @endif
                                <div class="game-card-overlay">
                                    <span class="text-white"><i class="bi bi-eye-fill me-2"></i>Lihat Tier List</span>
                                </div>
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-title mb-3">{{ $game->game_name }}</h5>
                                <p class="card-text text-muted mb-3">
                                    {{ Str::limit($game->description, 70) }}
                                </p>
                                <div class="stats-badge">
                                    <i class="bi bi-bar-chart-steps"></i>
                                    <span>{{ \App\Models\TierCategory::where('game_id', $game->id)->count() }} Tier List</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <h4 class="text-muted">Belum ada game tersedia</h4>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
```

### 1.6 Alur Kerja Halaman Daftar Game

Alur kerja halaman daftar game dimulai dari request GET ke route `/`. Controller tidak secara eksplisit diperlukan karena view ini bisa langsung dirender, namun dalam praktiknya biasanya ada controller yang mengambil data game dari database. Data game diambil melalui Eloquent ORM dengan query `Game::all()` yang mengembalikan collection semua game. View kemudian melakukan iterasi pada collection tersebut untuk menampilkan kartu-kartu game.

Setiap kartu game memiliki link yang mengarahkan ke route `game.tier-list` dengan parameter `gameSlug`. Slug digunakan sebagai identifier yang friendly untuk URL, berbeda dengan ID numerik. Ketika pengguna mengklik kartu, browser akan melakukan redirect ke halaman tier list game tersebut. Sistem juga menampilkan hitung jumlah tier list yang dimiliki setiap game sebagai informasi tambahan bagi pengguna.

---

## 2. Frontend Tier List

### 2.1 Deskripsi dan Fungsi

Halaman tier list menampilkan ranking karakter berdasarkan tier atau rank tertentu. Halaman ini merupakan inti dari aplikasi di mana pengguna dapat melihat karakter mana yang termasuk dalam rank SS, S, A, B, C, atau D. Setiap rank memiliki warna yang berbeda untuk memudahkan identifikasi visual. Halaman ini juga mendukung multiple kategori tier list untuk satu game, misalnya PvE dan PvP bisa memiliki ranking yang berbeda.

Desain halaman tier list menampilkan karakter-karakter dalam kartu yang berisi gambar karakter, nama, elemen, role, dan catatan ranking. Navigasi antar kategori tier list menggunakan tabs yang responsive. Sistem juga menampilkan header game dengan ikon dan deskripsi untuk memberikan konteks kepada pengguna tentang game mana yang sedang mereka lihat.

### 2.2 Controller yang Digunakan

```php
// app/Http/Controllers/TierListController.php

public function viewTierList($gameSlug, $categoryId = null)
{
    // Cari game berdasarkan slug
    $game = Game::where('slug', $gameSlug)->firstOrFail();

    // Ambil semua kategori tier list untuk game ini
    $categories = TierCategory::where('game_id', $game->id)->get();

    // Tentukan kategori yang akan ditampilkan
    if ($categoryId) {
        $category = TierCategory::findOrFail($categoryId);
    } else {
        $category = $categories->first();
    }

    // Ambil data tier dengan sorting berdasarkan rank
    $tierData = TierData::where('tier_category_id', $category->id)
        ->with('character')
        ->orderByRaw("CASE rank
            WHEN 'SS' THEN 1
            WHEN 'S' THEN 2
            WHEN 'A' THEN 3
            WHEN 'B' THEN 4
            WHEN 'C' THEN 5
            WHEN 'D' THEN 6
            ELSE 7 END")
        ->orderBy('sort_order')
        ->get()
        ->groupBy('rank');

    return view('frontend.tier-list', compact('game', 'category', 'categories', 'tierData'));
}
```

Controller ini menangani permintaan untuk menampilkan tier list. Method `viewTierList` menerima parameter `gameSlug` untuk mengidentifikasi game dan `categoryId` opsional untuk memilih kategori spesifik. Query menggunakan `firstOrFail()` yang akan melempar exception 404 jika game tidak ditemukan, sehingga memberikan response yang proper kepada pengguna.

Sorting menggunakan `orderByRaw` dengan CASE statement SQL untuk mengurutkan rank dalam urutan yang benar (SS, S, A, B, C, D). Data kemudian di-group berdasarkan rank menggunakan method `groupBy()` dari Laravel collection, yang menghasilkan array asosiatif dengan rank sebagai key dan collection karakter sebagai value.

### 2.3 Cuplikan Kode View (Tier List)

```blade
@extends('base')

@section('title', $game->game_name . ' - Tier List')

@section('content')
<div style="min-height: 100vh;">
    <!-- Game Header -->
    <div class="game-header" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.4) 0%, rgba(118, 75, 162, 0.4) 100%); padding: 50px 20px;">
        <div class="container">
            <div class="d-flex align-items-center gap-4 flex-wrap">
                @if($game->icon_url)
                    <img src="{{ $game->icon_url }}" alt="{{ $game->game_name }}"
                         class="game-header-icon"
                         style="width: 90px; height: 90px; object-fit: contain;">
                @else
                    <div class="game-header-icon" style="width: 90px; height: 90px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-controller" style="font-size: 3rem;"></i>
                    </div>
                @endif
                <div>
                    <h1 style="margin: 0; font-size: 2.2rem; font-weight: 800;">{{ $game->game_name }}</h1>
                    <p style="margin: 5px 0 0 0; color: rgba(255, 255, 255, 0.7);">{{ $game->description }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Tabs -->
    @if($categories->count() > 1)
        <div style="background: rgba(0, 0, 0, 0.2); backdrop-filter: blur(20px);">
            <div class="container">
                <div class="category-tabs d-flex gap-2 py-3">
                    @foreach($categories as $cat)
                        <a href="{{ route('game.tier-list', ['gameSlug' => $game->slug, 'categoryId' => $cat->id]) }}"
                           class="category-tab {{ $category->id == $cat->id ? 'active' : '' }}">
                            <span>{{ $cat->category_name }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Tier List Display -->
    <div class="container py-5">
        @php
            $rankOrder = ['SS', 'S', 'A', 'B', 'C', 'D'];
            $rankColors = [
                'SS' => ['bg' => 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)'],
                'S' => ['bg' => 'linear-gradient(135deg, #f97316 0%, #ea580c 100%)'],
                'A' => ['bg' => 'linear-gradient(135deg, #eab308 0%, #ca8a04 100%)'],
                'B' => ['bg' => 'linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%)'],
                'C' => ['bg' => 'linear-gradient(135deg, #10b981 0%, #059669 100%)'],
                'D' => ['bg' => 'linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%)']
            ];
        @endphp

        @foreach($rankOrder as $rank)
            @if(isset($tierData[$rank]) && $tierData[$rank]->count() > 0)
                <div class="mb-4">
                    <div class="tier-rank d-flex align-items-center gap-3"
                         style="background: {{ $rankColors[$rank]['bg'] }}; padding: 18px 24px; border-radius: 16px 16px 0 0;">
                        <span style="font-size: 1.5rem; font-weight: 800;">RANK {{ $rank }}</span>
                        <span class="count-badge bg-white bg-opacity-25">
                            {{ $tierData[$rank]->count() }} karakter
                        </span>
                    </div>
                    <div style="background: rgba(255, 255, 255, 0.05); padding: 25px; border-radius: 0 0 16px 16px;">
                        <div class="row g-3">
                            @foreach($tierData[$rank] as $tier)
                                <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                                    <div class="card character-card h-100">
                                        @if($tier->character->image_url)
                                            <img src="{{ $tier->character->image_url }}"
                                                 alt="{{ $tier->character->name }}"
                                                 class="character-image"
                                                 style="height: 150px; object-fit: cover; width: 100%;">
                                        @else
                                            <div style="background: linear-gradient(135deg, #667eea, #764ba2); height: 150px; display: flex; align-items: center; justify-content: center;">
                                                <span style="font-size: 2.5rem; font-weight: 800; color: white;">
                                                    {{ Str::substr($tier->character->name, 0, 2) }}
                                                </span>
                                            </div>
                                        @endif
                                        <div class="card-body" style="padding: 15px;">
                                            <h6 class="character-name mb-3">{{ $tier->character->name }}</h6>
                                            <div class="d-flex flex-wrap gap-1 mb-2">
                                                @if($tier->character->element)
                                                    <span class="tag-element">
                                                        <i class="bi bi-lightning me-1"></i>{{ $tier->character->element->element_name }}
                                                    </span>
                                                @endif
                                                @if($tier->character->role)
                                                    <span class="tag-role">
                                                        <i class="bi bi-shield me-1"></i>{{ $tier->character->role->role_name }}
                                                    </span>
                                                @endif
                                            </div>
                                            @if($tier->note)
                                                <p class="tier-note mb-0" style="font-size: 0.75rem;">
                                                    "{{ $tier->note }}"
                                                </p>
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
    </div>
</div>
@endsection
```

### 2.4 Alur Kerja Halaman Tier List

Alur kerja dimulai ketika pengguna mengakses URL `/game/{gameSlug}`. Request ini ditangkap oleh route `game.tier-list` yang kemudian memanggil method `viewTierList` pada `TierListController`. Controller mencari game berdasarkan slug yang diberikan, dan jika tidak ditemukan akan menampilkan halaman 404.

Setelah game ditemukan, controller mengambil semua kategori tier list yang terkait dengan game tersebut. Jika parameter `categoryId` tidak diberikan, sistem akan menampilkan kategori pertama secara default. Data tier list kemudian diambil dengan eager loading relasi `character` untuk menghindari N+1 query problem.

Di sisi view, Blade template menampilkan header game dengan ikon dan deskripsi. Jika game memiliki lebih dari satu kategori, tabs navigasi akan ditampilkan untuk memungkinkan pengguna berpindah antar kategori. Tier list ditampilkan dalam section-section berdasarkan rank, dengan setiap rank memiliki warna background yang berbeda sesuai konfigurasi dalam array `$rankColors`.

---

## 3. Frontend Admin (Halaman Login Admin)

### 3.1 Deskripsi dan Fungsi

Halaman login admin merupakan pintu masuk untuk panel administrasi. Halaman ini dilengkapi dengan form autentikasi yang meminta email dan password dari pengguna. Sistem menggunakan session-based authentication yang disediakan oleh Laravel. Halaman ini juga menyediakan akun-akun test yang bisa digunakan untuk mencoba berbagai role (Admin, Editor, Viewer).

Validasi input dilakukan baik di sisi client maupun server. Pesan error yang jelas ditampilkan jika login gagal, baik karena format email yang salah maupun kredensial yang tidak matching. Desain halaman menggunakan glassmorphism dengan background blur dan efek animasi yang memberikan kesan modern dan profesional.

### 3.2 Controller yang Digunakan

```php
// app/Http/Controllers/AuthController.php

public function showLogin()
{
    // Log untuk debugging
    Log::info('showLogin called, Auth::check()=' . (Auth::check() ? 'true' : 'false'));

    // Jika sudah login, redirect ke dashboard
    if (Auth::check()) {
        Log::info('User already authenticated, redirecting to dashboard');
        return redirect('/admin/dashboard');
    }

    return view('admin.auth.login');
}

public function login(Request $request)
{
    // Validasi input
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string|min:6',
    ], [
        'email.required' => 'Email harus diisi',
        'email.email' => 'Format email tidak valid',
        'password.required' => 'Password harus diisi',
        'password.min' => 'Password minimal 6 karakter',
    ]);

    // Attempt login dengan kredensial
    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended('/admin/dashboard');
    }

    // Login gagal, kembali dengan error
    return back()
        ->withErrors(['email' => 'Email atau password salah'])
        ->withInput($request->only('email'));
}

public function dashboard()
{
    return view('admin.dashboard');
}
```

Method `showLogin()` menangani tampilan halaman login. Sebelum menampilkan view, method ini memeriksa apakah pengguna sudah login. Jika sudah, langsung redirect ke dashboard untuk menghindari akses tidak langsung ke halaman login. Method `login()` menerima request POST dengan kredensial, memvalidasi input, dan mencoba autentikasi menggunakan `Auth::attempt()`.

Jika autentikasi berhasil, session diregenerate untuk keamanan (mencegah session fixation) dan pengguna diarahkan ke dashboard atau halaman yang dituju sebelumnya (`intended()`). Jika gagal, kembali ke halaman login dengan menampilkan pesan error dan mempertahankan input email yang telah dimasukkan. Option `remember` memungkinkan sesi bertahan lebih lama menggunakan cookie remember.

### 3.3 Cuplikan Kode View (Login)

```blade
@extends('base')

@section('title', 'Admin Login')

@section('content')
<div class="login-wrapper">
    <div class="login-card">
        <i class="bi bi-shield-lock login-icon"></i>
        <h1 class="login-title text-center">Admin Login</h1>
        <p class="login-subtitle text-center">Masuk ke panel administrasi</p>

        @if ($errors->any())
            <div class="alert alert-danger" style="border-radius: 12px;">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li><i class="bi bi-exclamation-circle me-2"></i>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf

            <div class="form-floating">
                <input type="email" class="form-control"
                       id="email" name="email" required autofocus
                       value="{{ old('email') }}" placeholder="Email">
                <label for="email"><i class="bi bi-envelope me-2"></i>Email</label>
            </div>

            <div class="form-floating">
                <input type="password" class="form-control"
                       id="password" name="password" required placeholder="Password">
                <label for="password"><i class="bi bi-key me-2"></i>Password</label>
            </div>

            <div class="remember-me">
                <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1">
                <label class="form-check-label" for="remember">Ingat saya</label>
            </div>

            <button type="submit" class="btn btn-primary btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i>Login
            </button>
        </form>

        <div class="divider">
            <span>atau gunakan akun test</span>
        </div>

        <div class="test-accounts">
            <h6><i class="bi bi-people me-2"></i>Akun Test</h6>
            <div class="account-item">
                <span class="account-role role-admin">Admin</span>
                <span class="account-email">nevin@localhost</span>
            </div>
            <div class="account-item">
                <span class="account-role role-admin">Admin</span>
                <span class="account-email">radith@localhost</span>
            </div>
            <div class="account-item">
                <span class="account-role role-viewer">Viewer</span>
                <span class="account-email">adit@localhost</span>
            </div>
        </div>
    </div>
</div>
@endsection
```

### 3.4 Middleware yang Digunakan

```php
// app/Http/Middleware/AdminMiddleware.php

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect('/admin/login')->with('error', 'Anda harus login terlebih dahulu');
        }

        return $next($request);
    }
}
```

Middleware `AdminMiddleware` melindungi route-route admin dengan memastikan pengguna sudah ter-autentikasi sebelum mengakses resource. Jika pengguna belum login, mereka akan di-redirect ke halaman login dengan pesan error. Middleware ini di-assign ke group route admin untuk memberikan proteksi menyeluruh.

---

## 4. Backend Dashboard Admin

### 4.1 Deskripsi dan Fungsi

Dashboard admin merupakan halaman utama setelah login yang menampilkan overview statistik sistem dan akses cepat ke berbagai fitur. Halaman ini menampilkan jumlah total game, karakter, tier list, dan element yang ada dalam sistem. Quick actions buttons memungkinkan admin untuk langsung mengakses fitur-fitur utama seperti tambah game, tambah karakter, dan buat tier list.

Desain dashboard menggunakan cards dengan animasi hover dan gradient yang konsisten. Sidebar navigasi di sebelah kiri memberikan akses ke semua fitur manajemen. Halaman ini juga menampilkan welcome message dengan nama user yang sedang login, memberikan kesan personal kepada pengguna.

### 4.2 Cuplikan Kode View (Dashboard)

```blade
@extends('base')

@section('title', 'Admin Dashboard')

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
            <!-- Welcome Banner -->
            <div class="welcome-banner animate-in">
                <h1 class="welcome-title">
                    <i class="bi bi-emoji-smile me-3"></i>Selamat Datang, {{ auth()->user()->name }}!
                </h1>
                <p class="welcome-subtitle">Kelola tier list game gacha favorit Anda dengan mudah.</p>

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
                    <div class="card stats-card h-100" style="padding: 25px;">
                        <div class="stats-card-icon mb-3" style="background: var(--primary-gradient); -webkit-background-clip: text;">
                            <i class="bi bi-controller"></i>
                        </div>
                        <div class="stats-card-value gradient-text">{{ \App\Models\Game::count() }}</div>
                        <div class="stats-card-label">Total Game</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card stats-card h-100" style="padding: 25px;">
                        <div class="stats-card-icon mb-3" style="background: var(--secondary-gradient); -webkit-background-clip: text;">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="stats-card-value" style="background: var(--secondary-gradient); -webkit-background-clip: text;">
                            {{ \App\Models\Character::count() }}
                        </div>
                        <div class="stats-card-label">Total Karakter</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card stats-card h-100" style="padding: 25px;">
                        <div class="stats-card-icon mb-3" style="background: var(--success-gradient); -webkit-background-clip: text;">
                            <i class="bi bi-bar-chart-steps"></i>
                        </div>
                        <div class="stats-card-value" style="background: var(--success-gradient); -webkit-background-clip: text;">
                            {{ \App\Models\TierCategory::count() }}
                        </div>
                        <div class="stats-card-label">Tier List</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card stats-card h-100" style="padding: 25px;">
                        <div class="stats-card-icon mb-3" style="background: linear-gradient(135deg, #f2994a, #f2c94c); -webkit-background-clip: text;">
                            <i class="bi bi-lightning"></i>
                        </div>
                        <div class="stats-card-value" style="background: linear-gradient(135deg, #f2994a, #f2c94c); -webkit-background-clip: text;">
                            {{ \App\Models\Element::count() }}
                        </div>
                        <div class="stats-card-label">Element</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```

### 4.3 Route Dashboard Admin

```php
// routes/web.php

Route::middleware('admin')->group(function () {
    Route::get('/admin/dashboard', [AuthController::class, 'dashboard'])->name('admin.dashboard');

    // Route-route manajemen lainnya...
});
```

Dashboard dilindungi oleh middleware `admin` yang memastikan hanya pengguna terautentikasi yang dapat mengakses. Route-group digunakan untuk mengelompokkan route-route admin dengan middleware yang sama, membuat konfigurasi route lebih bersih dan terorganisir.

---

## 5. Backend Kelola Game dan CRUD

### 5.1 Deskripsi dan Fungsi

Fitur Kelola Game memungkinkan admin untuk menambah, melihat, mengedit, dan menghapus game dari sistem. Setiap game memiliki nama, slug URL, ikon, dan deskripsi. Slug digunakan untuk membuat URL yang friendly dan SEO-friendly. Sistem juga menangani upload gambar ikon game dengan validasi format dan ukuran file.

CRUD (Create, Read, Update, Delete) diimplementasikan dengan pattern resource controller yang standar di Laravel. Setiap operasi memiliki route, method di controller, dan view tersendiri. Validasi input dilakukan di controller untuk memastikan data yang masuk memenuhi kriteria yang ditetapkan.

### 5.2 Migration Database

```php
// database/migrations/2025_12_11_000002_create_games_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('game_name', 100);
            $table->string('slug', 100)->unique();
            $table->string('icon_url', 255)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
```

Migration mendefinisikan struktur tabel `games`. Kolom `id` adalah primary key auto-increment. `game_name` membatasi panjang 100 karakter untuk nama game. `slug` juga dibatasi 100 karakter dan diberi constraint `unique()` untuk memastikan tidak ada dua game dengan slug yang sama. `icon_url` nullable karena tidak semua game harus memiliki ikon. `timestamps()` menambahkan kolom `created_at` dan `updated_at` secara otomatis.

### 5.3 Model Game

```php
// app/Models/Game.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    protected $table = 'games';
    protected $fillable = ['game_name', 'slug', 'icon_url', 'description'];

    public function elements(): HasMany
    {
        return $this->hasMany(Element::class);
    }

    public function roles(): HasMany
    {
        return $this->hasMany(Role::class);
    }

    public function characters(): HasMany
    {
        return $this->hasMany(Character::class);
    }

    public function tierCategories(): HasMany
    {
        return $this->hasMany(TierCategory::class);
    }
}
```

Model mendefinisikan relasi one-to-many dengan Element, Role, Character, dan TierCategory. Relasi ini memungkinkan akses mudah ke data terkait, misalnya `$game->characters` akan mengembalikan semua karakter dalam game tersebut.

### 5.4 Controller Game (CRUD)

```php
// app/Http/Controllers/GameController.php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GameController extends Controller
{
    // READ - Menampilkan daftar game dengan pagination
    public function index()
    {
        $games = Game::paginate(10);
        return view('admin.games.index', compact('games'));
    }

    // CREATE - Menampilkan form tambah game
    public function create()
    {
        return view('admin.games.create');
    }

    // CREATE - Menyimpan game baru
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'game_name' => 'required|string|max:100',
            'slug' => 'required|string|max:100|unique:games',
            'icon_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
        ]);

        // Handle file upload
        if ($request->hasFile('icon_url')) {
            $file = $request->file('icon_url');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = Storage::disk('public')->putFileAs('games', $file, $filename);
            $validated['icon_url'] = '/storage/' . $path;
        }

        Game::create($validated);
        return redirect('/admin/games')->with('success', 'Game berhasil ditambahkan!');
    }

    // UPDATE - Menampilkan form edit game
    public function edit(Game $game)
    {
        return view('admin.games.edit', compact('game'));
    }

    // UPDATE - Menyimpan perubahan game
    public function update(Request $request, Game $game)
    {
        $validated = $request->validate([
            'game_name' => 'required|string|max:100',
            'slug' => 'required|string|max:100|unique:games,slug,' . $game->id,
            'icon_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
        ]);

        // Handle file upload
        if ($request->hasFile('icon_url')) {
            // Delete old file if exists
            if ($game->icon_url && strpos($game->icon_url, '/storage/') === 0) {
                $oldPath = str_replace('/storage/', '', $game->icon_url);
                Storage::disk('public')->delete($oldPath);
            }
            $file = $request->file('icon_url');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = Storage::disk('public')->putFileAs('games', $file, $filename);
            $validated['icon_url'] = '/storage/' . $path;
        }

        $game->update($validated);
        return redirect('/admin/games')->with('success', 'Game berhasil diperbarui!');
    }

    // DELETE - Menghapus game
    public function destroy(Game $game)
    {
        $game->delete();
        return redirect('/admin/games')->with('success', 'Game berhasil dihapus!');
    }
}

---

## 6. Backend Kelola Karakter dan CRUD

### 6.1 Deskripsi dan Fungsi

Fitur Kelola Karakter memungkinkan admin untuk menambah, melihat, mengedit, dan menghapus karakter dari sistem. Setiap karakter memiliki nama, gambar, elemen, dan role. Karakter dikaitkan dengan game tertentu melalui foreign key `game_id`. Sistem menangani upload gambar karakter dengan validasi format dan ukuran file.

CRUD untuk karakter menggunakan pattern yang sama dengan game. Validasi input memastikan nama karakter tidak kosong dan gambar yang diupload sesuai format yang diperbolehkan. Relasi dengan game, elemen, dan role di-handle melalui dropdown select di form.

### 6.2 Migration Database

```php
// database/migrations/xxxx_xx_xx_create_characters_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            $table->string('name', 100);
            $table->string('image_url', 255)->nullable();
            $table->foreignId('element_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('role_id')->nullable()->constrained()->nullOnDelete();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('characters');
    }
};
```

Migration mendefinisikan struktur tabel `characters` dengan relasi ke tabel games, elements, dan roles. Foreign key menggunakan `constrained()` yang会自动创建约束，`onDelete('cascade')` memastikan jika game dihapus, semua karakter terkait juga akan dihapus. `nullOnDelete()` pada element dan role memungkinkan karakter tetap ada meskipun element/role terkait dihapus.

### 6.3 Model Character

```php
// app/Models/Character.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Character extends Model
{
    protected $table = 'characters';
    protected $fillable = ['game_id', 'name', 'image_url', 'element_id', 'role_id', 'description'];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function element(): BelongsTo
    {
        return $this->belongsTo(Element::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
```

Model Character mendefinisikan relasi belongsTo dengan Game, Element, dan Role. Relasi ini memungkinkan akses mudah ke data terkait, misalnya `$character->element->element_name` untuk mendapatkan nama elemen karakter.

### 6.4 Controller Character (CRUD)

```php
// app/Http/Controllers/CharacterController.php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Game;
use App\Models\Element;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CharacterController extends Controller
{
    // READ - Menampilkan daftar karakter
    public function index(Request $request)
    {
        $query = Character::with(['game', 'element', 'role']);

        if ($request->has('game_id')) {
            $query->where('game_id', $request->game_id);
        }

        $characters = $query->paginate(10);
        $games = Game::all();
        return view('admin.characters.index', compact('characters', 'games'));
    }

    // CREATE - Menampilkan form tambah karakter
    public function create(Request $request)
    {
        $games = Game::all();
        $elements = collect();
        $roles = collect();

        if ($request->has('game_id')) {
            $gameId = $request->game_id;
            $elements = Element::where('game_id', $gameId)->get();
            $roles = Role::where('game_id', $gameId)->get();
        }

        return view('admin.characters.create', compact('games', 'elements', 'roles'));
    }

    // CREATE - Menyimpan karakter baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'name' => 'required|string|max:100',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'element_id' => 'nullable|exists:elements,id',
            'role_id' => 'nullable|exists:roles,id',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image_url')) {
            $file = $request->file('image_url');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = Storage::disk('public')->putFileAs('characters', $file, $filename);
            $validated['image_url'] = '/storage/' . $path;
        }

        Character::create($validated);
        return redirect('/admin/characters')->with('success', 'Karakter berhasil ditambahkan!');
    }

    // UPDATE - Menampilkan form edit karakter
    public function edit(Character $character)
    {
        $games = Game::all();
        $elements = Element::where('game_id', $character->game_id)->get();
        $roles = Role::where('game_id', $character->game_id)->get();
        return view('admin.characters.edit', compact('character', 'games', 'elements', 'roles'));
    }

    // UPDATE - Menyimpan perubahan karakter
    public function update(Request $request, Character $character)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'name' => 'required|string|max:100',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'element_id' => 'nullable|exists:elements,id',
            'role_id' => 'nullable|exists:roles,id',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image_url')) {
            if ($character->image_url && strpos($character->image_url, '/storage/') === 0) {
                $oldPath = str_replace('/storage/', '', $character->image_url);
                Storage::disk('public')->delete($oldPath);
            }
            $file = $request->file('image_url');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = Storage::disk('public')->putFileAs('characters', $file, $filename);
            $validated['image_url'] = '/storage/' . $path;
        }

        $character->update($validated);
        return redirect('/admin/characters')->with('success', 'Karakter berhasil diperbarui!');
    }

    // DELETE - Menghapus karakter
    public function destroy(Character $character)
    {
        $character->delete();
        return redirect('/admin/characters')->with('success', 'Karakter berhasil dihapus!');
    }
}
```

---

## 7. Backend Kelola Element dan CRUD

### 7.1 Deskripsi dan Fungsi

Fitur Kelola Element memungkinkan admin untuk menambah, melihat, mengedit, dan menghapus elemen karakter. Element memberikan klasifikasi tambahan pada karakter seperti Fire, Water, Earth, Wind, dan lainnya. Setiap elemen terkait dengan game tertentu karena penamaan elemen bisa berbeda antar game.

### 7.2 Migration Database

```php
// database/migrations/xxxx_xx_xx_create_elements_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('elements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            $table->string('element_name', 50);
            $table->string('icon', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('elements');
    }
};
```

### 7.3 Model Element

```php
// app/Models/Element.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Element extends Model
{
    protected $table = 'elements';
    protected $fillable = ['game_id', 'element_name', 'icon'];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function characters(): HasMany
    {
        return $this->hasMany(Character::class);
    }
}
```

### 7.4 Controller Element (CRUD)

```php
// app/Http/Controllers/ElementController.php

namespace App\Http\Controllers;

use App\Models\Element;
use App\Models\Game;
use Illuminate\Http\Request;

class ElementController extends Controller
{
    public function index()
    {
        $elements = Element::with('game')->paginate(10);
        return view('admin.elements.index', compact('elements'));
    }

    public function create()
    {
        $games = Game::all();
        return view('admin.elements.create', compact('games'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'element_name' => 'required|string|max:50',
            'icon' => 'nullable|string|max:50',
        ]);

        Element::create($validated);
        return redirect('/admin/elements')->with('success', 'Element berhasil ditambahkan!');
    }

    public function edit(Element $element)
    {
        $games = Game::all();
        return view('admin.elements.edit', compact('element', 'games'));
    }

    public function update(Request $request, Element $element)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'element_name' => 'required|string|max:50',
            'icon' => 'nullable|string|max:50',
        ]);

        $element->update($validated);
        return redirect('/admin/elements')->with('success', 'Element berhasil diperbarui!');
    }

    public function destroy(Element $element)
    {
        $element->delete();
        return redirect('/admin/elements')->with('success', 'Element berhasil dihapus!');
    }
}
```

---

## 8. Backend Kelola Role dan CRUD

### 8.1 Deskripsi dan Fungsi

Fitur Kelola Role memungkinkan admin untuk menambah, melihat, mengedit, dan menghapus role karakter. Role memberikan klasifikasi peran karakter seperti Tank, DPS, Support, dan lainnya. Setiap role terkait dengan game tertentu karena penamaan dan jumlah role bisa berbeda antar game.

### 8.2 Migration Database

```php
// database/migrations/xxxx_xx_xx_create_roles_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            $table->string('role_name', 50);
            $table->string('icon', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
```

### 8.3 Model Role

```php
// app/Models/Role.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = ['game_id', 'role_name', 'icon'];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function characters(): HasMany
    {
        return $this->hasMany(Character::class);
    }
}
```

### 8.4 Controller Role (CRUD)

```php
// app/Http/Controllers/RoleController.php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Game;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('game')->paginate(10);
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $games = Game::all();
        return view('admin.roles.create', compact('games'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'role_name' => 'required|string|max:50',
            'icon' => 'nullable|string|max:50',
        ]);

        Role::create($validated);
        return redirect('/admin/roles')->with('success', 'Role berhasil ditambahkan!');
    }

    public function edit(Role $role)
    {
        $games = Game::all();
        return view('admin.roles.edit', compact('role', 'games'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'role_name' => 'required|string|max:50',
            'icon' => 'nullable|string|max:50',
        ]);

        $role->update($validated);
        return redirect('/admin/roles')->with('success', 'Role berhasil diperbarui!');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect('/admin/roles')->with('success', 'Role berhasil dihapus!');
    }
}
```

---

## 9. Backend Kelola Tier List dan CRUD

### 9.1 Deskripsi dan Fungsi

Fitur Kelola Tier List memungkinkan admin untuk membuat dan mengelola kategori tier list serta menempatkan karakter ke dalam tier yang sesuai. Sistem menggunakan ranking SS, S, A, B, C, D untuk mengklasifikasikan kekuatan karakter. Setiap tier list terdiri dari kategori (misalnya PvE, PvP) dan data tier yang berisi karakter-karakter dengan rankingnya.

### 9.2 Migration Database

```php
// database/migrations/xxxx_xx_xx_create_tier_categories_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tier_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            $table->string('category_name', 100);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('tier_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tier_category_id')->constrained()->onDelete('cascade');
            $table->foreignId('character_id')->constrained()->onDelete('cascade');
            $table->enum('rank', ['SS', 'S', 'A', 'B', 'C', 'D']);
            $table->integer('sort_order')->default(0);
            $table->text('note')->nullable();
            $table->timestamps();

            $table->unique(['tier_category_id', 'character_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tier_data');
        Schema::dropIfExists('tier_categories');
    }
};
```

### 9.3 Model Tier Category dan Tier Data

```php
// app/Models/TierCategory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TierCategory extends Model
{
    protected $table = 'tier_categories';
    protected $fillable = ['game_id', 'category_name', 'description'];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function tierData(): HasMany
    {
        return $this->hasMany(TierData::class);
    }
}

// app/Models/TierData.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TierData extends Model
{
    protected $table = 'tier_data';
    protected $fillable = ['tier_category_id', 'character_id', 'rank', 'sort_order', 'note'];

    public function tierCategory(): BelongsTo
    {
        return $this->belongsTo(TierCategory::class);
    }

    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class);
    }
}
```

### 9.4 Controller Tier List (CRUD)

```php
// app/Http/Controllers/TierListController.php

namespace App\Http\Controllers;

use App\Models\TierCategory;
use App\Models\TierData;
use App\Models\Game;
use App\Models\Character;
use Illuminate\Http\Request;

class TierListController extends Controller
{
    public function index()
    {
        $categories = TierCategory::with('game')->paginate(10);
        return view('admin.tier-lists.index', compact('categories'));
    }

    public function create()
    {
        $games = Game::all();
        return view('admin.tier-lists.create', compact('games'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'category_name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        TierCategory::create($validated);
        return redirect('/admin/tier-lists')->with('success', 'Tier list berhasil ditambahkan!');
    }

    public function edit(TierCategory $tierList)
    {
        $games = Game::all();
        $characters = Character::where('game_id', $tierList->game_id)
            ->with(['element', 'role'])
            ->get();
        $tierData = TierData::where('tier_category_id', $tierList->id)
            ->with('character')
            ->orderByRaw("CASE rank
                WHEN 'SS' THEN 1
                WHEN 'S' THEN 2
                WHEN 'A' THEN 3
                WHEN 'B' THEN 4
                WHEN 'C' THEN 5
                WHEN 'D' THEN 6
                ELSE 7 END")
            ->orderBy('sort_order')
            ->get()
            ->groupBy('rank');

        return view('admin.tier-lists.edit', compact('tierList', 'games', 'characters', 'tierData'));
    }

    public function update(Request $request, TierCategory $tierList)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'category_name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        $tierList->update($validated);
        return redirect('/admin/tier-lists')->with('success', 'Tier list berhasil diperbarui!');
    }

    public function destroy(TierCategory $tierList)
    {
        $tierList->delete();
        return redirect('/admin/tier-lists')->with('success', 'Tier list berhasil dihapus!');
    }

    // Manage tier data (add/remove characters from tiers)
    public function manage(Request $request, TierCategory $tierList)
    {
        $action = $request->action;

        if ($action === 'add') {
            $validated = $request->validate([
                'character_id' => 'required|exists:characters,id',
                'rank' => 'required|in:SS,S,A,B,C,D',
            ]);

            TierData::create([
                'tier_category_id' => $tierList->id,
                'character_id' => $validated['character_id'],
                'rank' => $validated['rank'],
            ]);
        } elseif ($action === 'update') {
            $validated = $request->validate([
                'tier_data_id' => 'required|exists:tier_data,id',
                'rank' => 'required|in:SS,S,A,B,C,D',
                'sort_order' => 'nullable|integer',
                'note' => 'nullable|string',
            ]);

            $tierData = TierData::findOrFail($validated['tier_data_id']);
            $tierData->update([
                'rank' => $validated['rank'],
                'sort_order' => $validated['sort_order'] ?? 0,
                'note' => $validated['note'] ?? null,
            ]);
        } elseif ($action === 'remove') {
            $request->validate([
                'tier_data_id' => 'required|exists:tier_data,id',
            ]);

            TierData::where('id', $request->tier_data_id)->delete();
        }

        return back();
    }
}
```

---

## 10. Database dan Struktur Tabel

### 10.1 ERD (Entity Relationship Diagram)

```
+----------------+       +-------------------+       +------------------+
|     users      |       |      games        |       |   characters     |
+----------------+       +-------------------+       +------------------+
| id             |<----->| id                |<------| id               |
| name           |       | game_name         |       | game_id (FK)     |
| email          |       | slug              |       | name             |
| password       |       | icon_url          |       | image_url        |
| role           |       | description       |       | element_id (FK)  |
| created_at     |       | created_at        |       | role_id (FK)     |
| updated_at     |       | updated_at        |       | description      |
+----------------+       +-------------------+       | created_at       |
        ^                                                 | updated_at       |
        |                                                 +------------------+
        |
        v
+----------------+       +-------------------+       +------------------+
|   elements     |<------|      roles        |       |  tier_categories |
+----------------+       +-------------------+       +------------------+
| id             |<------| id                |<------| id               |
| game_id (FK)   |       | game_id (FK)      |       | game_id (FK)     |
| element_name   |       | role_name         |       | category_name    |
| icon           |       | icon              |       | description      |
| created_at     |       | created_at        |       | created_at       |
| updated_at     |       | updated_at        |       | updated_at       |
+----------------+       +-------------------+       +------------------+
                                                           |
                                                           v
                                                  +------------------+
                                                  |    tier_data     |
                                                  +------------------+
                                                  | id               |
                                                  | tier_category_id |
                                                  | character_id     |
                                                  | rank (SS,S,A,B,C,D)|
                                                  | sort_order       |
                                                  | note             |
                                                  | created_at       |
                                                  | updated_at       |
                                                  +------------------+
```

### 10.2 Hubungan Antar Tabel

- **users**: Tabel untuk autentikasi admin. Kolom `role` menentukan level akses (Admin, Editor, Viewer).
- **games**: Tabel utama yang merepresentasikan game gacha. Satu game bisa memiliki banyak karakter, elemen, role, dan kategori tier list.
- **characters**: Tabel yang merepresentasikan karakter dalam game. Setiap karakter terkait dengan satu game dan bisa memiliki satu element dan satu role.
- **elements**: Tabel klasifikasi elemen karakter (Fire, Water, dll). Setiap element terkait dengan satu game.
- **roles**: Tabel klasifikasi peran karakter (Tank, DPS, Support, dll). Setiap role terkait dengan satu game.
- **tier_categories**: Tabel kategori tier list (PvE, PvP, dll). Setiap kategori terkait dengan satu game.
- **tier_data**: Tabel pivot yang menghubungkan karakter dengan tier. Satu karakter bisa berada di satu kategori tier list dengan satu rank tertentu.

---

## 11. Fitur Autentikasi dan Otorisasi

### 11.1 Sistem Autentikasi

Aplikasi menggunakan Laravel Sanctum untuk autentikasi berbasis session. Middleware `admin` memastikan hanya pengguna yang sudah login yang dapat mengakses panel admin.

```php
// app/Http/Kernel.php

protected $middlewareAliases = [
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
    'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
];
```

### 11.2 Middleware Admin

```php
// app/Http/Middleware/AdminMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('/admin/login')
                ->with('error', 'Anda harus login terlebih dahulu');
        }

        return $next($request);
    }
}
```

### 11.3 Akun Test

Berikut adalah akun-akun test yang tersedia di sistem:

| Email | Password | Role |
|-------|----------|------|
| nevin@localhost | password | Admin |
| radith@localhost | password | Admin |
| adit@localhost | password | Viewer |

---

## 12. Instalasi dan Konfigurasi

### 12.1 Persyaratan Sistem

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL / MariaDB
- Laravel 10.x

### 12.2 Langkah Instalasi

```bash
# Clone repository
git clone <repository-url>
cd tier-list

# Install dependencies
composer install
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env file
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=tier_list
# DB_USERNAME=root
# DB_PASSWORD=

# Run migrations
php artisan migrate --seed

# Create storage link
php artisan storage:link

# Build assets
npm run build

# Start development server
php artisan serve
```

### 12.3 Konfigurasi Penting

```env
# .env

APP_NAME="Gacha Tier List"
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tier_list
DB_USERNAME=root
DB_PASSWORD=

# Session
SESSION_DRIVER=database

# File Storage
FILESYSTEM_DISK=public
```

---

## 13. API Routes

### 13.1 Route Publik

```php
// routes/web.php

// Halaman utama
Route::get('/', function () {
    return view('frontend.index');
})->name('home');

// Halaman tier list publik
Route::get('/game/{gameSlug}', [TierListController::class, 'viewTierList'])
    ->name('game.tier-list');
Route::get('/game/{gameSlug}/{categoryId}', [TierListController::class, 'viewTierList'])
    ->name('game.tier-list.category');
```

### 13.2 Route Admin

```php
// routes/web.php

// Authentication
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Admin middleware group
Route::middleware('admin')->group(function () {
    // Dashboard
    Route::get('/admin/dashboard', [AuthController::class, 'dashboard'])->name('admin.dashboard');

    // Games
    Route::resource('/admin/games', GameController::class);

    // Characters
    Route::resource('/admin/characters', CharacterController::class);

    // Elements
    Route::resource('/admin/elements', ElementController::class);

    // Roles
    Route::resource('/admin/roles', RoleController::class);

    // Tier Lists
    Route::resource('/admin/tier-lists', TierListController::class);
    Route::post('/admin/tier-lists/{tierList}/manage', [TierListController::class, 'manage'])
        ->name('tier-lists.manage');
});
```

---

## 14. Kesimpulan

Aplikasi Tier List Game Gacha ini dibangun menggunakan Laravel 10 dengan arsitektur MVC yang clean dan terorganisir. Fitur-fitur utama meliputi:

1. **Frontend Publik**: Menampilkan daftar game dan tier list dengan desain modern dan responsif.
2. **Panel Admin**: CRUD lengkap untuk Game, Karakter, Element, Role, dan Tier List.
3. **Autentikasi**: Sistem login berbasis session dengan dukungan multiple role.
4. **Database**: Struktur relasional yang mendukung game, karakter, dan ranking tier.
5. **Upload File**: Sistem upload gambar untuk ikon game dan karakter dengan validasi.

Dengan arsitektur yang modular, aplikasi ini mudah untuk dikembangkan lebih lanjut dengan fitur-fitur tambahan seperti sistem voting, sharing tier list, atau integrasi dengan API game resmi.
