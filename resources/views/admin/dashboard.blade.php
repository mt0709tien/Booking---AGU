@extends('layouts.app')

@section('content')

<div class="container py-5">

    <div class="text-center mb-5">
        <h2 class="text-danger fw-bold">TRANG QUẢN LÝ (ADMIN)</h2>
        <p class="lead text-muted">Hệ thống quản lý cơ sở vật chất</p>
    </div>

    <div class="row g-4">


        {{-- Quản lý danh mục --}}
        <div class="col-md-4">
            <div class="card shadow h-100 border-danger">
                <div class="card-body text-center d-flex flex-column">

                    <h5 class="card-title fw-bold mb-3">
                        Quản lý danh mục
                    </h5>

                    <p class="text-muted mb-4">
                        Thêm, sửa, xoá danh mục như
                        sân bóng, phòng học, hội trường...
                    </p>

                    <a href="{{ route('admin.categories') }}"
                       class="btn btn-danger mt-auto">
                       Quản lý
                    </a>

                </div>
            </div>
        </div>


        {{-- Quản lý cơ sở vật chất --}}
        <div class="col-md-4">
            <div class="card shadow h-100 border-danger">
                <div class="card-body text-center d-flex flex-column">

                    <h5 class="card-title fw-bold mb-3">
                        Quản lý cơ sở vật chất
                    </h5>

                    <p class="text-muted mb-4">
                        Thêm, sửa, xoá các cơ sở vật chất
                        như sân bóng, phòng học, phòng máy...
                    </p>

                    <a href="{{ route('admin.facilities') }}"
                       class="btn btn-danger mt-auto">
                       Quản lý
                    </a>

                </div>
            </div>
        </div>


        {{-- Quản lý người dùng --}}
        <div class="col-md-4">
            <div class="card shadow h-100 border-danger">
                <div class="card-body text-center d-flex flex-column">

                    <h5 class="card-title fw-bold mb-3">
                        Quản lý người dùng
                    </h5>

                    <p class="text-muted mb-4">
                        Xem danh sách, chỉnh sửa hoặc xoá
                        tài khoản người dùng.
                    </p>

                    <a href="{{ route('admin.users') }}"
                       class="btn btn-danger mt-auto">
                       Quản lý
                    </a>

                </div>
            </div>
        </div>


        {{-- Danh sách đặt lịch --}}
        <div class="col-md-6">
            <div class="card shadow h-100 border-danger">
                <div class="card-body text-center d-flex flex-column">

                    <h5 class="card-title fw-bold mb-3">
                        Danh sách đặt lịch
                    </h5>

                    <p class="text-muted mb-4">
                        Xem toàn bộ lịch đặt
                        của người dùng.
                    </p>

                    <a href="{{ route('admin.bookings') }}"
                       class="btn btn-danger mt-auto">
                       Xem danh sách
                    </a>

                </div>
            </div>
        </div>


        {{-- Thống kê --}}
        <div class="col-md-6">
            <div class="card shadow h-100 border-danger">
                <div class="card-body text-center d-flex flex-column">

                    <h5 class="card-title fw-bold mb-3">
                        Thống kê
                    </h5>

                    <p class="text-muted mb-4">
                        Xem báo cáo và thống kê
                        số lượt sử dụng.
                    </p>

                    <a href="{{ route('admin.stats') }}"
                       class="btn btn-danger mt-auto">
                       Xem thống kê
                    </a>

                </div>
            </div>
        </div>


    </div>

</div>

@endsection