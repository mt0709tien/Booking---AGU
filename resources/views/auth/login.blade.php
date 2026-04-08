@extends('layouts.app')

@section('content')

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
    /* Background */
    .bg-blur {
        position: fixed;
        inset: 0;
        background: url('{{ asset("images/AGU.jpg") }}') no-repeat center center/cover;
        z-index: -2;
    }

    .bg-overlay {
        position: fixed;
        inset: 0;
        backdrop-filter: blur(3px);
        background: rgba(0, 0, 0, 0.5);
        z-index: -1;
    }

    /* Wrapper */
    .login-wrapper {
        min-height: 100vh;
    }

    /* Card */
    .login-card {
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.95);
        box-shadow: 0 20px 50px rgba(0,0,0,0.4);
        padding: 30px;
        transition: 0.3s;
    }

    .login-card:hover {
        transform: translateY(-5px);
    }

    /* Input */
    .input-group-text {
        background: #f1f1f1;
    }

    /* Button */
    .btn-primary {
        border-radius: 10px;
        font-weight: bold;
    }

    /* Link */
    a {
        text-decoration: none;
    }
</style>

<!-- Background layers -->
<div class="bg-blur"></div>
<div class="bg-overlay"></div>

<div class="login-wrapper d-flex align-items-center justify-content-center">

    <div class="col-md-4">

        <div class="login-card">

            <h3 class="text-center mb-4 fw-bold text-primary">
                🔐 Đăng nhập hệ thống
            </h3>

            {{-- lỗi validate --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            {{-- lỗi khác --}}
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-3 input-group">
                    <span class="input-group-text">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-control"
                        placeholder="Email"
                        required>
                </div>

                <!-- Password -->
                <div class="mb-3 input-group">
                    <span class="input-group-text">
                        <i class="bi bi-lock"></i>
                    </span>

                    <input type="password"
                        name="password"
                        id="password"
                        class="form-control"
                        placeholder="Mật khẩu"
                        required>

                    <button type="button"
                        class="btn btn-outline-secondary"
                        onclick="togglePassword()">
                        <i id="eyeIcon" class="bi bi-eye"></i>
                    </button>
                </div>

                <!-- Quên mật khẩu -->
                <div class="text-end mb-3">
                    <a href="{{ url('/forgot-password') }}" class="text-primary">
                        Quên mật khẩu?
                    </a>
                </div>

                <!-- Submit -->
                <button class="btn btn-primary w-100">
                    Đăng nhập
                </button>

                <!-- Register -->
                <div class="text-center mt-3">
                    <span>Chưa có tài khoản?</span>
                    <a href="{{ route('register') }}" class="fw-bold text-primary">
                        Đăng ký ngay
                    </a>
                </div>

            </form>

        </div>

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