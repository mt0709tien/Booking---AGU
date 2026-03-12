<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng nhập hệ thống</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            height: 100vh;
        }

        .login-card {
            border-radius: 15px;
        }

        .eye-btn {
            cursor: pointer;
        }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-md-5">

                <div class="card login-card shadow-lg">

                    <div class="card-body p-4">

                        <h3 class="text-center mb-4">
                            ĐĂNG NHẬP HỆ THỐNG
                        </h3>

                        {{-- Hiển thị lỗi --}}
                        @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                        @endif


                        <form method="POST" action="{{ route('login') }}">

                            @csrf


                            <!-- Email -->
                            <div class="mb-3">

                                <label class="form-label">
                                    Email
                                </label>

                                <input type="email"
                                    name="email"
                                    class="form-control"
                                    placeholder="Nhập email"
                                    required>

                            </div>


                            <!-- Password -->
                            <div class="mb-2">

                                <label class="form-label">
                                    Mật khẩu
                                </label>

                                <div class="input-group">

                                    <input type="password"
                                        name="password"
                                        id="password"
                                        class="form-control"
                                        placeholder="Nhập mật khẩu"
                                        required>

                                    <button type="button"
                                        class="btn btn-outline-secondary eye-btn"
                                        onclick="togglePassword()">

                                        <i id="eyeIcon" class="bi bi-eye"></i>

                                    </button>

                                </div>

                            </div>


                           


                            <!-- Button đăng nhập -->
                            <div class="d-grid mb-3">

                                <button type="submit"
                                    class="btn btn-primary">

                                    Đăng nhập

                                </button>

                            </div>


                            <!-- Button đăng ký -->
                            <div class="d-grid">

                                <a href="{{ route('register') }}"
                                    class="btn btn-outline-success">

                                    Đăng ký tài khoản mới

                                </a>

                            </div>

                        </form>

                    </div>

                </div>


                <p class="text-center text-white mt-3">

                    © {{ date('Y') }} Hệ thống quản lý cơ sở vật chất

                </p>

            </div>

        </div>

    </div>


    <script>

        function togglePassword() {

            let password = document.getElementById("password");
            let icon = document.getElementById("eyeIcon");

            if (password.type === "password") {

                password.type = "text";

                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");

            } else {

                password.type = "password";

                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");

            }

        }
        @if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

    </script>

</body>

</html>