@extends('layouts.app')

@section('content')
<div class="reset-container">
    <div class="card-header">{{ __('Đặt lại mật khẩu') }}</div>

    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group">
                <label for="email">{{ __('Địa chỉ Email') }}</label>
                <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" 
                    name="email" value="{{ old('email') }}" autocomplete="email" autofocus>
                @error('email')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    {{ __('Xác nhận quên mật khẩu') }}
                </button>
            </div>
        </form>

        <div class="text-center">
            Đã nhớ mật khẩu? <a href="{{ route('login') }}" class="btn-link"> Đăng nhập</a>
        </div>
    </div>
</div>

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
    .reset-container {
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
        color: #333;
        margin-bottom: 1.5rem;
    }
    .card-body {
        font-size: 1rem;
        color: #666;
    }
    .alert-success {
        background: #d4edda;
        color: #155724;
        padding: 1rem;
        border-radius: 8px;
        font-size: 0.9rem;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .form-group {
        margin-bottom: 0.5rem;
    }
    label {
        font-weight: 500;
        color: #444;
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
    .invalid-feedback {
        font-size: 0.85rem;
        color: #dc3545;
    }
    .btn-primary {
        background: #667eea;
        border: none;
        margin-top: 0.5rem;
        padding: 0.75rem;
        border-radius: 8px;
        transition: background 0.3s ease;
        width: 100%;
    }
    .btn-primary:hover {
        background: #764ba2;
    }
    .btn-link {
        color: #667eea;
        text-decoration: none;
        font-weight: 500;
        display: inline-block;
    }
    .btn-link:hover {
        text-decoration: none;
        color: #764ba2;
    }
</style>
@endsection
