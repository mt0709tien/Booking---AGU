@extends('layouts.app')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
    /* Background mượt mà hơn */
    .bg-blur {
        position: fixed;
        inset: 0;
        background: url('{{ asset("images/AGU.jpg") }}') no-repeat center center/cover;
        z-index: -2;
        transform: scale(1.1); /* Tránh lộ mép khi blur */
    }

    .bg-overlay {
        position: fixed;
        inset: 0;
        backdrop-filter: blur(8px); /* Tăng độ blur để tập trung vào form */
        background: rgba(15, 23, 42, 0.45); /* Màu tối sang trọng hơn */
        z-index: -1;
    }

    /* Wrapper */
    .login-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    /* Card thiết kế Glassmorphism nhẹ */
    .login-card {
        border-radius: 24px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        padding: 40px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        width: 100%;
        max-width: 420px;
    }

    .login-card:hover {
        transform: translateY(-8px);
    }

    /* Tinh chỉnh Input */
    .input-group {
        border-radius: 12px;
        overflow: hidden;
        transition: 0.3s;
        border: 1px solid #e2e8f0;
    }

    .input-group:focus-within {
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }

    .input-group-text {
        background: #f8fafc;
        border: none;
        color: #64748b;
        padding-left: 15px;
    }

    .form-control {
        border: none;
        padding: 12px 15px;
        font-size: 0.95rem;
    }

    .form-control:focus {
        box-shadow: none;
    }

    /* Button hiện đại */
    .btn-login {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border: none;
        border-radius: 12px;
        padding: 12px;
        font-weight: 600;
        transition: 0.3s;
        color: white;
    }

    .btn-login:hover {
        filter: brightness(1.1);
        transform: scale(1.02);
    }

    .btn-google {
        border-radius: 12px;
        padding: 10px;
        font-weight: 500;
        background: white;
        border: 1px solid #e2e8f0;
        color: #475569;
        transition: 0.3s;
    }

    .btn-google:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    /* Divider */
    .divider {
        display: flex;
        align-items: center;
        text-align: center;
        margin: 25px 0;
        color: #94a3b8;
        font-size: 0.85rem;
    }

    .divider::before, .divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #e2e8f0;
    }

    .divider:not(:empty)::before { margin-right: .75em; }
    .divider:not(:empty)::after { margin-left: .75em; }

    /* Animation */
    .fade-in {
        animation: fadeIn 0.8s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="bg-blur"></div>
<div class="bg-overlay"></div>

<div class="login-wrapper">
    <div class="login-card fade-in">
        
        <div class="text-center mb-4">
            <h3 class="fw-bold text-dark mb-1">ĐĂNG NHẬP</h3>
            <p class="text-muted small">Vui lòng đăng nhập để tiếp tục</p>
        </div>

        {{-- Thông báo lỗi --}}
        @if ($errors->any() || session('error'))
            <div class="alert alert-danger border-0 small shadow-sm" style="border-radius: 10px;">
                <ul class="mb-0 list-unstyled">
                    @foreach ($errors->all() as $error)
                        <li><i class="bi bi-exclamation-circle me-2"></i>{{ $error }}</li>
                    @endforeach
                    @if(session('error'))
                        <li><i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}</li>
                    @endif
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <label class="form-label small fw-semibold text-secondary">Địa chỉ Email</label>
            <div class="mb-3 input-group">
                <span class="input-group-text">
                    <i class="bi bi-envelope"></i>
                </span>
                <input type="email" name="email" value="{{ old('email') }}" 
                       class="form-control" placeholder="name@company.com" required>
            </div>

            <div class="d-flex justify-content-between">
                <label class="form-label small fw-semibold text-secondary">Mật khẩu</label>
                <a href="{{ url('/forgot-password') }}" class="small text-primary fw-medium">Quên mật khẩu?</a>
            </div>
            <div class="mb-4 input-group">
                <span class="input-group-text">
                    <i class="bi bi-lock"></i>
                </span>
                <input type="password" name="password" id="password" 
                       class="form-control" placeholder="••••••••" required>
                <button type="button" class="btn bg-white border-0 py-0" onclick="togglePassword()">
                    <i id="eyeIcon" class="bi bi-eye text-muted"></i>
                </button>
            </div>

            <button class="btn btn-login w-100 shadow-sm mb-2">
                Đăng nhập
            </button>

            <div class="divider">Hoặc</div>

            <a href="{{ route('google.login') }}" class="btn btn-google w-100 d-flex align-items-center justify-content-center gap-2 mb-3">
                <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" width="18" height="18" alt="Google">
                Đăng nhập với Google
            </a>

            <div class="text-center mt-4">
                <p class="text-muted small mb-0">Chưa có tài khoản? 
                    <a href="{{ route('register') }}" class="fw-bold text-primary">Đăng ký ngay</a>
                </p>
            </div>
        </form>
    </div>
</div>

<script>
function togglePassword() {
    let password = document.getElementById("password");
    let icon = document.getElementById("eyeIcon");

    if (password.type === "password") {
        password.type = "text";
        icon.classList.replace("bi-eye", "bi-eye-slash");
    } else {
        password.type = "password";
        icon.classList.replace("bi-eye-slash", "bi-eye");
    }
}
</script>

@endsection