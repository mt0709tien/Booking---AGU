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

        /* bỏ dấu chấm menu */
        .navbar-nav{
            list-style:none;
        }

        .navbar-nav .nav-link{
            font-weight:600;
        }

        .navbar-nav .nav-link:hover{
            color:#ff4da6;
        }

        /* navbar hồng pastel */
        .navbar-pink{
            background-color:#ffd6e7;
        }

    </style>

</head>
<body>


<!-- HEADER -->
<div class="shadow sticky-top">

<!-- PHẦN TRÊN (LOGO + TÊN + USER) -->
<div style="background-color:#8FB9E6;">
<div class="container d-flex justify-content-between align-items-center py-2">

    <!-- BÊN TRÁI: LOGO + TÊN -->
    <div class="d-flex align-items-center">
        <img src="{{ asset('images/logo-agu.png') }}"
             style="height:65px; margin-right:12px;">

        <div class="lh-sm">
            <div style="font-size:18px; font-weight:700;">
                TRƯỜNG ĐẠI HỌC AN GIANG
            </div>

            <div style="font-size:26px; font-weight:800; color:red;">
                HỆ THỐNG QUẢN LÝ VÀ CHO THUÊ CƠ SỞ VẬT CHẤT
            </div>
        </div>
    </div>

    <!-- BÊN PHẢI: USER -->
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
<ul class="navbar-nav">

@auth

@if(Auth::user()->vai_tro === 'admin')

<li class="nav-item">
<a class="nav-link" href="{{ route('admin.dashboard') }}">
Trang quản lý
</a>
</li>

@else

<li class="nav-item">
<a class="nav-link" href="{{ route('booking.home') }}">
Trang đặt lịch
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

</body>
</html>