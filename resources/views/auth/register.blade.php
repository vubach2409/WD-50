@extends('layouts.app')

@section('content')
<div class="register-container">
    <div class="card-header">{{ __('Đăng Ký') }}</div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label for="name">{{ __('Tên') }}</label>
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">{{ __('Email') }}</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">{{ __('Mật khẩu') }}</label>
            <div class="password-wrapper">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                <span class="toggle-password" onclick="togglePassword('password')">👁️</span>
            </div>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password-confirm">{{ __('Xác nhận mật khẩu') }}</label>
            <div class="password-wrapper">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                <span class="toggle-password" onclick="togglePassword('password-confirm')">👁️</span>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">
                {{ __('Đăng ký') }}
            </button>
            <div class="auth-links text-center">
                Đã có tài khoản?<a href="{{ route('login') }}" class="btn-link"> Đăng nhập</a>
            </div>
        </div>
    </form>
</div>

<script>
    function togglePassword(fieldId) {
        const passwordField = document.getElementById(fieldId);
        const toggleIcon = passwordField.nextElementSibling;
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.textContent = '🙈';
        } else {
            passwordField.type = 'password';
            toggleIcon.textContent = '👁️';
        }
    }
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
    max-width: 100% !important;
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
    text-decoration: none;
    color: #764ba2;
}

.form-check {
    margin-top: 0.5rem;
}

.form-check-label {
    font-size: 0.9rem;
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