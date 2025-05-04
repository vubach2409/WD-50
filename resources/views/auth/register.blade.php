@extends('layouts.app')

@php
    $confirmError = session('errors')?->get('password') 
                    ? collect(session('errors')->get('password'))->first(fn($msg) => str_contains($msg, 'khớp'))
                    : null;
@endphp

@section('content')
<div class="register-container">
    <div class="card-header">{{ __('Đăng Ký') }}</div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Tên --}}
        <div class="form-group">
            <label for="name">{{ __('Tên') }}</label>
            <input id="name" type="text"
                   class="form-control @error('name') is-invalid @enderror"
                   name="name" value="{{ old('name') }}" autocomplete="name" autofocus>
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Email --}}
        <div class="form-group">
            <label for="email">{{ __('Email') }}</label>
            <input id="email" type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   name="email" value="{{ old('email') }}" autocomplete="email">
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Mật khẩu --}}
        <div class="form-group">
            <label for="password">{{ __('Mật khẩu') }}</label>
            <div class="password-wrapper">
                <input id="password" type="password"
                       class="form-control @error('password') is-invalid @enderror"
                       name="password" autocomplete="new-password">
                <span class="toggle-password" data-target="password">👁️</span>
            </div>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Xác nhận mật khẩu --}}
        <div class="form-group">
            <label for="password-confirm">{{ __('Xác nhận mật khẩu') }}</label>
            <div class="password-wrapper">
                <input id="password-confirm" type="password"
                       class="form-control @if($confirmError) is-invalid @endif"
                       name="password_confirmation" autocomplete="new-password">
                <span class="toggle-password" data-target="password-confirm">👁️</span>
            </div>
            @if ($confirmError)
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $confirmError }}</strong>
                </span>
            @endif
        </div>

        {{-- Submit --}}
        <div class="form-group">
            <button type="submit" class="btn btn-primary">
                {{ __('Đăng ký') }}
            </button>
            <div class="auth-links text-center">
                Đã có tài khoản? <a href="{{ route('login') }}" class="btn-link">Đăng nhập</a>
            </div>
        </div>
    </form>
</div>

{{-- Toggle --}}
<script>
    document.querySelectorAll('.toggle-password').forEach(icon => {
        icon.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            if (input.type === 'password') {
                input.type = 'text';
                this.textContent = '🙈';
            } else {
                input.type = 'password';
                this.textContent = '👁️';
            }
        });
    });
</script>

<style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #667eea, #764ba2);
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0;
}

.register-container {
    background: #fff;
    padding: 2.5rem;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    width: 500px;
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

.card-header {
    font-size: 1.5rem;
    font-weight: 600;
    text-align: center;
    margin-bottom: 1.5rem;
}
.form-group {
    margin-bottom: 0.5rem;
}
.form-control {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 0.75rem;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 5px rgba(102, 126, 234, 0.5);
    outline: none;
}

.btn-primary {
    margin-top: 0.5rem;
    margin-bottom: 0.5rem;
    background: #667eea;
    border: none;
    padding: 0.75rem;
    border-radius: 8px;
    width: 100%;
    transition: background 0.3s ease;
}

.btn-primary:hover {
    background: #764ba2;
}

.btn-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
}

.btn-link:hover {
    color: #764ba2;
}

.invalid-feedback {
    font-size: 0.85rem;
    color: #dc3545;
}

.password-wrapper {
    position: relative;
}

.password-wrapper .form-control {
    padding-right: 2.5rem;
}

.toggle-password {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #666;
}
</style>
@endsection
