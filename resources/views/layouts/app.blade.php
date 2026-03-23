<!DOCTYPE html>
<html>
<head>
    <title>Quản lý cơ sở vật chất</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>

        body{
            margin:0;
        }

        .navbar-nav{
            list-style:none;
        }

        .navbar-nav .nav-link{
            font-weight:600;
        }

        .navbar-nav .nav-link:hover{
            color:#ff4da6;
        }

        .navbar-pink{
            background-color:#ffd6e7;
        }

    </style>

</head>
<body>


<!-- HEADER -->
<div class="shadow sticky-top">

<!-- TOP BAR -->
<div style="background-color:#8FB9E6;">
<div class="container d-flex justify-content-between align-items-center py-2">

    <!-- LOGO -->
    <div class="d-flex align-items-center">

        <img 
            src="{{ asset('images/logo-agu.png') }}"
            style="height:65px; margin-right:12px;"
        >

        <div class="lh-sm">

            <div style="font-size:18px; font-weight:700;">
                TRƯỜNG ĐẠI HỌC AN GIANG
            </div>

            <div style="font-size:26px; font-weight:800; color:red;">
                HỆ THỐNG QUẢN LÝ VÀ CHO THUÊ CƠ SỞ VẬT CHẤT
            </div>

        </div>

    </div>

    <!-- USER -->
    <div style="font-size:14px;">

        @auth

            <span class="me-2">
                Xin chào, <strong>{{ Auth::user()->ho_ten }}</strong>
            </span>

            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button class="btn btn-sm btn-light">
                    Đăng xuất
                </button>
            </form>

        @endauth

        @guest

            <a href="{{ route('login') }}" class="btn btn-sm btn-light">
                Đăng nhập
            </a>

        @endguest

    </div>

</div>
</div>


<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light navbar-pink border-top border-bottom">

<div class="container">

<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
    <span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="navbarMain">

<!-- MENU TRÁI -->
<ul class="navbar-nav me-auto">

    <li class="nav-item">
        <a class="nav-link" href="{{ route('booking.home') }}">
            Trang chủ
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('gioithieu') }}">
            Giới thiệu
        </a>
    </li>

    <li class="nav-item dropdown">

        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
            Danh mục
        </a>

        <ul class="dropdown-menu">

            @foreach($categories as $category)

                <li>
                    <a class="dropdown-item" href="{{ route('category.show',$category->id) }}">
                        {{ $category->name }}
                    </a>
                </li>

            @endforeach

        </ul>

    </li>

</ul>


<!-- MENU PHẢI -->
<ul class="navbar-nav align-items-center">

    @auth

        {{-- ✅ CHỈ USER MỚI THẤY --}}
        @if(Auth::user()->vai_tro === 'user')
            <li class="nav-item">
                <a href="{{ route('booking.my') }}" class="nav-link">
                    📋 Lịch của tôi
                </a>
            </li>
        @endif

        <!-- CHUÔNG THÔNG BÁO -->
        <li class="nav-item dropdown">

            <button 
                class="btn nav-link position-relative"
                data-bs-toggle="dropdown"
                style="border:none; background:none;"
            >
                🔔

                @if(Auth::user()->unreadNotifications->count() > 0)
                    <span 
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                        style="font-size:10px;"
                    >
                        {{ Auth::user()->unreadNotifications->count() }}
                    </span>
                @endif
            </button>

            <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="width:300px;">

                <li class="p-2 border-bottom fw-bold">
                    Thông báo
                </li>

                @forelse(Auth::user()->unreadNotifications as $notification)

                    <li>
                        <a 
                            class="dropdown-item p-3"
                            href="{{ url('/notification/read/'.$notification->id) }}"
                        >
                            <small class="fw-bold d-block">
                                {{ $notification->data['title'] }}
                            </small>

                            <small class="text-muted">
                                {{ $notification->data['message'] }}
                            </small>
                        </a>
                    </li>

                @empty

                    <li class="p-3 text-center text-muted">
                        Không có thông báo
                    </li>

                @endforelse

            </ul>

        </li>


        <!-- ADMIN / USER -->
        @if(Auth::user()->vai_tro === 'admin')

            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    ⚙️ Quản lý
                </a>
            </li>

        @else

            <li class="nav-item">
                <a class="nav-link" href="{{ route('booking.home') }}">
                    🛒 Đặt sân
                </a>
            </li>

        @endif

    @endauth


    @guest

        <li class="nav-item">
            <a class="nav-link" href="{{ route('login') }}">
                Đăng nhập
            </a>
        </li>

    @endguest

</ul>

</div>
</div>

</nav>

</div>


<!-- CONTENT -->
<div class="container mt-4">

    @yield('content')

</div>


<!-- FOOTER -->
<footer class="bg-light text-center py-3 mt-5 border-top">

    © {{ date('Y') }} - Hệ thống quản lý cơ sở vật chất

</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo/dist/echo.iife.js"></script>

<script>
    const echo = new Echo({
        broadcaster: 'pusher',
        key: 'd2ede6aab99762078bbc', // 👈 SỬA DÒNG NÀY
        cluster: 'mt1',
        forceTLS: true
    });

    echo.channel('booking-channel')
        .listen('BookingNotification', (e) => {
            alert(e.message);
            location.reload();
        });
</script>
</body>
</html>