@extends('layouts.app')

@section('content')

@php
    $hideFooter = true;
@endphp

<style>
:root{
    --header-height: 140px;
}

/* SIDEBAR FIXED */
.sidebar{
    position: fixed;
    top: var(--header-height);
    left: 0;
    width: 16.6667%;
    height: calc(100vh - var(--header-height));
    background: linear-gradient(180deg,#1e3c72,#2a5298);
    padding:20px 15px;
    color:white;
    overflow-y: auto;
    z-index: 1000;
}

.sidebar h5{
    font-weight:700;
    margin-bottom:20px;
}

/* MENU */
.menu-link{
    display:flex;
    align-items:center;
    gap:10px;
    padding:10px 12px;
    border-radius:8px;
    color:#dbeafe;
    text-decoration:none;
    margin-bottom:6px;
    transition:0.25s;
}

.menu-link:hover{
    background:rgba(255,255,255,0.1);
    color:#fff;
    transform:translateX(3px);
}

/* ACTIVE */
.menu-link.active{
    background:white;
    color:#1e3c72;
    font-weight:600;
}

/* MAIN CONTENT */
.main-content{
    margin-left: 16.6667%;
    margin-top: var(--header-height);
    width: calc(100% - 16.6667%);
}

/* CONTENT */
.content-wrapper{
    padding:25px;
}

.content-card{
    background:white;
    border-radius:12px;
    padding:20px;
    box-shadow:0 4px 15px rgba(0,0,0,0.05);
}
</style>

<!-- SIDEBAR -->
<div class="sidebar">

    <h5 class="text-center">⚙️ ADMIN</h5>

    <a href="{{ route('admin.categories') }}" class="menu-link">📂 Danh mục</a>
    <a href="{{ route('admin.facilities') }}" class="menu-link">🏟 Cơ sở</a>
    <a href="{{ route('admin.invoices') }}" class="menu-link">🧾 Hóa đơn</a>
    <a href="{{ route('admin.users') }}" class="menu-link">👤 Người dùng</a>
    <a href="{{ route('admin.bookings') }}" class="menu-link">📅 Đặt lịch</a>
    <a href="{{ route('admin.stats') }}" class="menu-link">📊 Thống kê</a>
    <a href="{{ route('admin.report') }}" class="menu-link">💰 Báo cáo</a>

</div>

<!-- MAIN -->
<div class="main-content">
    <div class="content-wrapper">

        <div class="content-card" id="content">
            @yield('admin_content')
        </div>

    </div>
</div>

<script>
/* ACTIVE MENU */
document.querySelectorAll('.menu-link').forEach(link=>{
    let current = window.location.pathname;
    let url = new URL(link.href).pathname;

    if(current === url){
        link.classList.add('active');
    }
});
</script>

@endsection