@extends('layouts.app')

@section('content')

<div class="container py-4">

    <h3 class="text-center mb-4">Trang cá nhân</h3>

    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger text-center">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">

        {{-- INFO --}}
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header">Thông tin cá nhân</div>
                <div class="card-body">

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf

                        <div class="mb-3">
                            <label>Tên</label>
                            <input type="text" name="name"
                            value="{{ auth()->user()->name }}"
                            class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>SĐT</label>
                            <input type="text" name="phone"
                            value="{{ auth()->user()->phone }}"
                            class="form-control">
                        </div>

                        <button class="btn btn-primary w-100">
                            Cập nhật
                        </button>

                    </form>

                </div>
            </div>
        </div>

        {{-- PASSWORD --}}
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header">Đổi mật khẩu</div>
                <div class="card-body">

                    <form method="POST" action="{{ route('profile.password') }}">
                        @csrf

                        <div class="mb-3">
                            <label>Mật khẩu cũ</label>
                            <input type="password" name="old_password" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Mật khẩu mới</label>
                            <input type="password" name="new_password" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Nhập lại mật khẩu</label>
                            <input type="password" name="new_password_confirmation" class="form-control">
                        </div>

                        <button class="btn btn-danger w-100">
                            Đổi mật khẩu
                        </button>

                    </form>

                </div>
            </div>
        </div>

    </div>

</div>

@endsection