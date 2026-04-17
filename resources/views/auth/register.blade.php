<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng ký hệ thống</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* Đồng bộ background với trang Login */
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

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* Card thiết kế Glassmorphism */
        .register-card {
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(10px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            padding: 35px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.4s ease;
            width: 100%;
            max-width: 450px;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Tinh chỉnh Input hiện đại */
        .form-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 6px;
        }

        .form-control {
            border-radius: 12px;
            padding: 10px 15px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            transition: 0.3s;
        }

        .form-control:focus {
            background: #fff;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            border-color: #3b82f6;
        }

        /* Button Gradient */
        .btn-register {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            color: white;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn-register:hover {
            filter: brightness(1.1);
            transform: scale(1.02);
            color: white;
        }

        .text-primary { color: #2563eb !important; }
        
        /* Hiệu ứng link */
        .login-link {
            transition: 0.2s;
            color: #2563eb;
        }
        .login-link:hover {
            color: #1d4ed8;
            text-decoration: underline !important;
        }
    </style>
</head>

<body>

    <div class="bg-blur"></div>
    <div class="bg-overlay"></div>

    <div class="register-card">
        <h3 class="text-center mb-2 fw-bold text-primary">ĐĂNG KÝ TÀI KHOẢN</h3>
        <p class="text-center text-muted small mb-4">Tham gia cùng chúng tôi ngay hôm nay</p>

        {{-- Thành công --}}
        @if(session('success'))
            <div class="alert alert-success border-0 small shadow-sm mb-4" style="border-radius: 10px;">
                <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            {{-- Họ tên --}}
            <div class="mb-3">
                <label class="form-label">Họ và tên</label>
                <input type="text" name="ho_ten" class="form-control" placeholder="Nguyễn Văn A" value="{{ old('ho_ten') }}">
                @error('ho_ten')
                    <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label class="form-label">Địa chỉ Email</label>
                <input type="email" name="email" class="form-control" placeholder="name@company.com" value="{{ old('email') }}">
                @error('email')
                    <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                {{-- Password --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Mật khẩu</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••">
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- CONFIRM PASSWORD --}}
                <div class="col-md-6 mb-4">
                    <label class="form-label">Xác nhận</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••">
                </div>
            </div>

            {{-- BUTTON --}}
            <div class="d-grid">
                <button type="submit" class="btn btn-register shadow-sm">
                    Đăng ký tài khoản
                </button>
            </div>

            {{-- LINK LOGIN --}}
            <div class="text-center mt-4">
                <span class="text-muted small">Đã có tài khoản?</span>
                <a href="{{ route('login') }}" class="small fw-bold text-decoration-none login-link ms-1">
                    Đăng nhập
                </a>
            </div>
        </form>
    </div>

</body>
</html>