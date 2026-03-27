@extends('layouts.app')

@section('content')

<div class="container py-4">

    <h3 class="text-center mb-4">Trang cá nhân</h3>

    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">

        {{-- INFO --}}
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Thông tin cá nhân</span>
                    <button class="btn btn-sm btn-warning" onclick="toggleEdit()">
                        Sửa
                    </button>
                </div>

                <div class="card-body">

                    {{-- VIEW MODE --}}
                    <div id="viewMode">
                        <p><strong>Họ tên:</strong> {{ auth()->user()->ho_ten }}</p>
                        <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                        <p>
                            <strong>Vai trò:</strong> 
                            <span class="badge bg-info">
                                {{ auth()->user()->vai_tro }}
                            </span>
                        </p>
                    </div>

                    {{-- EDIT MODE --}}
                    <form id="editMode"
                          method="POST"
                          action="{{ route('profile.update') }}"
                          style="display:none;">
                        @csrf

                        <div class="mb-3">
                            <label>Họ tên</label>
                            <input type="text"
                                   name="ho_ten"
                                   value="{{ auth()->user()->ho_ten }}"
                                   class="form-control">
                        </div>

                        <div class="d-flex gap-2">
                            <button class="btn btn-primary w-100">
                                Lưu thay đổi
                            </button>

                            <button type="button"
                                    class="btn btn-secondary w-100"
                                    onclick="toggleEdit()">
                                Hủy
                            </button>
                        </div>

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
                            <input type="password"
                                   name="old_password"
                                   class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Mật khẩu mới</label>
                            <input type="password"
                                   name="new_password"
                                   class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Nhập lại mật khẩu</label>
                            <input type="password"
                                   name="new_password_confirmation"
                                   class="form-control">
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

{{-- SCRIPT --}}
<script>
function toggleEdit() {
    let view = document.getElementById('viewMode');
    let edit = document.getElementById('editMode');

    if (view.style.display === "none") {
        view.style.display = "block";
        edit.style.display = "none";
    } else {
        view.style.display = "none";
        edit.style.display = "block";
    }
}
</script>

@endsection