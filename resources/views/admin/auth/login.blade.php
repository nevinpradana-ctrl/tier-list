@extends('base')

@section('title', 'Admin Login')

@section('extra-css')
<style>
    .login-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        position: relative;
    }

    .login-wrapper::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(102, 126, 234, 0.3) 0%, transparent 70%);
        animation: pulse 4s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 0.5; }
        50% { transform: translate(-50%, -50%) scale(1.1); opacity: 0.8; }
    }

    .login-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(30px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        padding: 50px 40px;
        width: 100%;
        max-width: 450px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
        position: relative;
        z-index: 1;
    }

    .login-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--primary-gradient);
        border-radius: 24px 24px 0 0;
    }

    .login-icon {
        font-size: 4rem;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 20px;
        display: block;
    }

    .login-title {
        font-size: 1.8rem;
        font-weight: 800;
        margin-bottom: 10px;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .login-subtitle {
        color: rgba(255, 255, 255, 0.6);
        margin-bottom: 30px;
    }

    .form-floating {
        margin-bottom: 20px;
    }

    .form-floating > .form-control {
        background: rgba(255, 255, 255, 0.05);
        border: 2px solid rgba(255, 255, 255, 0.1);
        border-radius: 14px;
        padding: 20px 15px 10px;
        height: 60px;
        color: white;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-floating > .form-control:focus {
        background: rgba(255, 255, 255, 0.1);
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.2);
    }

    .form-floating > .form-control::placeholder {
        color: rgba(255, 255, 255, 0.3);
    }

    .form-floating > label {
        color: rgba(255, 255, 255, 0.6);
        padding: 15px 15px;
    }

    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
        color: #667eea;
        font-size: 0.85rem;
    }

    .remember-me {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 25px;
    }

    .remember-me .form-check-input {
        width: 20px;
        height: 20px;
        border-radius: 6px;
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid rgba(255, 255, 255, 0.2);
    }

    .remember-me .form-check-input:checked {
        background: var(--primary-gradient);
        border-color: transparent;
    }

    .remember-me label {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.9rem;
    }

    .btn-login {
        width: 100%;
        padding: 16px;
        font-size: 1rem;
        font-weight: 700;
        letter-spacing: 1px;
    }

    .divider {
        display: flex;
        align-items: center;
        margin: 30px 0;
        color: rgba(255, 255, 255, 0.4);
    }

    .divider::before,
    .divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: rgba(255, 255, 255, 0.1);
    }

    .divider span {
        padding: 0 15px;
        font-size: 0.85rem;
    }

    .test-accounts {
        background: rgba(255, 255, 255, 0.03);
        border-radius: 16px;
        padding: 20px;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .test-accounts h6 {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 15px;
    }

    .account-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .account-item:last-child {
        border-bottom: none;
    }

    .account-role {
        padding: 3px 10px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        min-width: 60px;
        text-align: center;
    }

    .role-admin { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
    .role-editor { background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); }
    .role-viewer { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); }

    .account-email {
        font-family: monospace;
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.7);
    }

    .back-home {
        text-align: center;
        margin-top: 25px;
    }

    .back-home a {
        color: rgba(255, 255, 255, 0.6);
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .back-home a:hover {
        color: white;
    }
</style>
@endsection

@section('content')
<div class="login-wrapper">
    <div class="login-card">
        <i class="bi bi-shield-lock login-icon"></i>
        <h1 class="login-title text-center">Admin Login</h1>
        <p class="login-subtitle text-center">Masuk ke panel administrasi</p>
        
        @if ($errors->any())
            <div class="alert alert-danger" style="border-radius: 12px; margin-bottom: 25px;">
                <ul class="mb-0" style="list-style: none; padding: 0;">
                    @foreach ($errors->all() as $error)
                        <li><i class="bi bi-exclamation-circle me-2"></i>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            
            <div class="form-floating">
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                       id="email" name="email" required autofocus value="{{ old('email') }}" 
                       placeholder="Email">
                <label for="email"><i class="bi bi-envelope me-2"></i>Email</label>
            </div>

            <div class="form-floating">
                <input type="password" class="form-control @error('password') is-invalid @enderror" 
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

        <div class="back-home">
            <a href="/">
                <i class="bi bi-arrow-left"></i>Kembali ke Home
            </a>
        </div>
    </div>
</div>
@endsection

