<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quên mật khẩu</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #e6f2ff, #ffffff);
        }

        .card {
            border-radius: 16px;
        }

        .icon-box {
            width: 70px;
            height: 70px;
            background: #e7f1ff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: auto;
            font-size: 30px;
        }

        .btn-main {
            background: #0d6efd;
            border: none;
            font-weight: 600;
        }

        .btn-main:hover {
            background: #0b5ed7;
        }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center vh-100">

<div class="col-md-5">

    <div class="card shadow text-center p-4">

        {{-- ICON --}}
        <div class="icon-box mb-3">
            🔐
        </div>

        {{-- TITLE --}}
        <h4 class="fw-bold text-primary mb-3">
            Quên mật khẩu
        </h4>

        {{-- TEXT --}}
        <p class="text-muted">
            Vui lòng liên hệ <strong>Admin</strong> để được cấp lại mật khẩu.
        </p>

        {{-- CONTACT --}}
        <div class="bg-light rounded p-3 mb-4">
            <div class="mb-2">
                📞 <strong>0373276510</strong>
            </div>
            <div>
                📧 <strong>mytien6510@gmail.com</strong>
            </div>
        </div>

        {{-- BUTTON --}}
        <div class="d-grid">
            <a href="{{ route('login') }}" class="btn btn-main py-2">
                ← Quay lại đăng nhập
            </a>
        </div>

    </div>

</div>

</body>
</html>