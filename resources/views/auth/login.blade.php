@extends('layouts.app')

@section('content')
    <div class="login-container">
        <div class="card-header">{{ __('Đăng Nhập') }}</div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">{{ __('Email') }}</label>
                <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email"
                    value="{{ old('email') }}" autocomplete="email" autofocus>
                @error('email')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">{{ __('Mật khẩu') }}</label>
                <div class="password-wrapper">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" autocomplete="current-password">
                    <span class="toggle-password" onclick="togglePassword()">👁️</span>
                </div>
                @error('password')
                    <div class="invalid-feedback d-block">
                        <strong>{{ $message }}</strong>
                    </div>
                @enderror
            </div>


            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                    {{ __('Nhớ mật khẩu') }}
                </label>
            </div>

            <button type="submit" class="btn btn-primary mt-3">
                {{ __('Đăng nhập') }}
            </button>

            <div class="auth-links text-center ">
                <a href="/forgot-password" class="btn-link">Quên mật khẩu</a> |
                <a href="{{ route('register') }}" class="btn-link">Đăng ký ngay</a>
            </div>
        </form>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.querySelector('.toggle-password');
            passwordField.type = passwordField.type === 'password' ? 'text' : 'password';
            toggleIcon.textContent = passwordField.type === 'password' ? '👁️' : '🙈';
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

        .login-container {
            background: #fff;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            max-width: 100% !important;
            width: 500px;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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
            background: #667eea;
            margin-bottom: 0.5rem;
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