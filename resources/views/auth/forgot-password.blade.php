<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quên mật khẩu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

<div class="col-md-4">
    <div class="card shadow">
        <div class="card-body p-4">
            <h4 class="text-center mb-4">Khôi phục mật khẩu</h4>

            <!-- THÔNG BÁO -->
            @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <!-- FORM -->
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-3">
                    <input 
                        type="email" 
                        name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="Nhập email của bạn"
                        value="{{ old('email') }}"
                        required
                    >

                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="d-grid">
                    <button class="btn btn-warning">
                        Gửi yêu cầu
                    </button>
                </div>

                <div class="text-center mt-3">
                    <a href="{{ route('login') }}">Quay lại đăng nhập</a>
                </div>
            </form>

        </div>
    </div>
</div>

</body>
</html>