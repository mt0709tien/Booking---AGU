@extends('layouts.app')

@section('content')

<div class="text-center mb-5">
    <h1 class="fw-bold text-danger">HỆ THỐNG QUẢN LÝ VÀ CHO THUÊ CƠ SỞ VẬT CHẤT TẠI TRƯỜNG ĐẠI HỌC AN GIANG</h1>
    <p class="lead">Quản lý và đặt lịch sử dụng các khu vực trong trường</p>
</div>

<div class="row g-4">

    <div class="col-md-4">
        <div class="card shadow h-100">
            <div class="card-body text-center">
                <h5 class="card-title">Khu thể thao</h5>
                <p class="card-text">
                    Bao gồm sân bóng đá, bóng chuyền, cầu lông, tennis.
                </p>
                <a href="#" class="btn btn-danger">Xem chi tiết</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow h-100">
            <div class="card-body text-center">
                <h5 class="card-title">Phòng học & phòng máy</h5>
                <p class="card-text">
                    Đặt lịch sử dụng phòng học và phòng máy.
                </p>
                <a href="#" class="btn btn-danger">Xem chi tiết</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow h-100">
            <div class="card-body text-center">
                <h5 class="card-title">Hội trường</h5>
                <p class="card-text">
                    Quản lý và đăng ký sử dụng hội trường.
                </p>
                <a href="#" class="btn btn-danger">Xem chi tiết</a>
            </div>
        </div>
    </div>

</div>

@endsection