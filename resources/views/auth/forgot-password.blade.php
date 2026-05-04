@extends('layouts.app')

@section('content')

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

    .forgot-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .forgot-card {
        width: 100%;
        max-width: 450px;
        background: rgba(255,255,255,0.92);
        backdrop-filter: blur(10px);
        border-radius: 24px;
        padding: 35px;
        box-shadow: 0 25px 50px rgba(0,0,0,0.25);
        text-align: center;
        transition: 0.3s;
    }

    .forgot-card:hover {
        transform: translateY(-6px);
    }

    .icon-box {
        width: 80px;
        height: 80px;
        margin: auto;
        border-radius: 50%;
        background: #e7f1ff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 34px;
    }

    .btn-main {
        background: linear-gradient(135deg,#3b82f6,#2563eb);
        border: none;
        border-radius: 12px;
        color: white;
        font-weight: 600;
        padding: 12px;
        transition: 0.3s;
    }

    .btn-main:hover {
        transform: scale(1.02);
        filter: brightness(1.05);
    }
</style>

<div class="bg-blur"></div>
<div class="bg-overlay"></div>

<div class="forgot-wrapper">
    <div class="forgot-card">
        
        <div class="icon-box mb-4">
            🔐
        </div>

        <h4 class="fw-bold text-primary mb-3">
            Quên mật khẩu
        </h4>

        <p class="text-muted mb-4">
            Vui lòng liên hệ <strong>Admin</strong> để được cấp lại mật khẩu.
        </p>

        <div class="bg-light rounded p-3 mb-4">
            <div class="mb-2">
                📞 <strong>0373276510</strong>
            </div>
            <div>
                📧 <strong>mytien6510@gmail.com</strong>
            </div>
        </div>

        <div class="d-grid">
            <a href="{{ route('login') }}" class="btn btn-main">
                ← Quay lại đăng nhập
            </a>
        </div>

    </div>
</div>

@endsection