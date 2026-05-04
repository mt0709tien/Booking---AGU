@extends('layouts.app')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
    .bg-blur {
        position: fixed;
        inset: 0;
        background: url('{{ asset("images/AGU.jpg") }}') no-repeat center center/cover;
        z-index: -2;
        transform: scale(1.1);
    }

    .bg-overlay {
        position: fixed;
        inset: 0;
        backdrop-filter: blur(8px);
        background: rgba(15, 23, 42, 0.45);
        z-index: -1;
    }

    .register-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .register-card {
        border-radius: 24px;
        background: rgba(255,255,255,0.92);
        backdrop-filter: blur(10px);
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
        padding: 35px;
        width: 100%;
        max-width: 450px;
        animation: fadeIn .8s ease-out;
    }

    @keyframes fadeIn {
        from {opacity:0; transform:translateY(20px);}
        to {opacity:1; transform:translateY(0);}
    }

    .form-label {
        font-size: .85rem;
        font-weight: 600;
        color: #475569;
    }

    .form-control {
        border-radius: 12px;
        padding: 10px 15px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
    }

    .form-control:focus {
        background: #fff;
        box-shadow: 0 0 0 4px rgba(59,130,246,.1);
        border-color: #3b82f6;
    }

    .btn-register {
        background: linear-gradient(135deg,#3b82f6,#2563eb);
        border: none;
        border-radius: 12px;
        padding: 12px;
        color: #fff;
        font-weight: 600;
        transition: .3s;
    }

    .btn-register:hover {
        transform: scale(1.02);
        filter: brightness(1.05);
        color: #fff;
    }

    .login-link:hover {
        text-decoration: underline;
    }
</style>

<div class="bg-blur"></div>
<div class="bg-overlay"></div>

<div class="register-wrapper">
    <div class="register-card">

        <h3 class="text-center mb-2 fw-bold text-primary">ĐĂNG KÝ TÀI KHOẢN</h3>
        <p class="text-center text-muted small mb-4">Tham gia cùng chúng tôi ngay hôm nay</p>

        @if(session('success'))
            <div class="alert alert-success border-0 small shadow-sm">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Họ và tên</label>
                <input type="text" name="ho_ten" class="form-control" value="{{ old('ho_ten') }}" placeholder="Nguyễn Văn A">
                @error('ho_ten')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Địa chỉ Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="name@company.com">
                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Mật khẩu</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••">
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Xác nhận</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••">
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-register">
                    Đăng ký tài khoản
                </button>
            </div>

            <div class="text-center mt-4">
                <span class="text-muted small">Đã có tài khoản?</span>
                <a href="{{ route('login') }}" class="small fw-bold text-primary text-decoration-none login-link ms-1">
                    Đăng nhập
                </a>
            </div>
        </form>

    </div>
</div>

@endsection