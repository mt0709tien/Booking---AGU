@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;800&display=swap" rel="stylesheet">

<style>
    body { 
        background: #f0f2f5; 
        font-family: 'Lexend', sans-serif; 
    }

    /* Chữ nhiều màu (Gradient Text) */
    .text-gradient-primary {
        background: linear-gradient(to right, #00dbde, #fc00ff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        display: inline-block;
    }

    .text-gradient-danger {
        background: linear-gradient(to right, #f83600, #fe8c00);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        display: inline-block;
    }

    /* Khung màu sắc (Colored Cards) */
    .card-profile {
        border: none;
        border-top: 6px solid;
        border-image: linear-gradient(to right, #00dbde, #fc00ff) 1;
        border-radius: 15px;
        box-shadow: 0 15px 35px rgba(0, 219, 222, 0.1);
        background: #fff;
    }

    .card-password {
        border: none;
        border-top: 6px solid;
        border-image: linear-gradient(to right, #f83600, #fe8c00) 1;
        border-radius: 15px;
        box-shadow: 0 15px 35px rgba(248, 54, 0, 0.1);
        background: #fff;
    }

    .card-header {
        background: transparent;
        border: none;
        padding: 1.5rem 1.5rem 0.5rem;
        font-weight: 800;
        font-size: 1.2rem;
    }

    /* Avatar loang màu */
    .profile-avatar { 
        width: 100px; height: 100px; 
        background: linear-gradient(45deg, #00dbde, #fc00ff); 
        border-radius: 50%; 
        display: flex; align-items: center; justify-content: center; 
        margin: 0 auto 1.5rem; 
        font-size: 2.5rem; color: #fff; 
        box-shadow: 0 8px 20px rgba(252, 0, 255, 0.3);
    }

    /* Input & Badge */
    .form-control {
        border-radius: 12px;
        border: 2px solid #eee;
        padding: 0.7rem 1rem;
    }
    .form-control:focus {
        border-color: #00dbde;
        box-shadow: none;
    }

    .badge-rainbow {
        background: linear-gradient(45deg, #ff0000, #ff7f00, #ffff00, #00ff00, #0000ff, #4b0082, #8b00ff);
        background-size: 200% auto;
        color: #fff;
        animation: rainbow 3s linear infinite;
        border: none;
        border-radius: 30px;
        padding: 0.5em 1.2em;
    }

    @keyframes rainbow {
        0% { background-position: 0% 50%; }
        100% { background-position: 200% 50%; }
    }

    /* Buttons */
    .btn-gradient-save {
        background: linear-gradient(to right, #00dbde, #fc00ff);
        color: white; border: none; font-weight: 600;
    }
    .btn-gradient-pass {
        background: linear-gradient(to right, #f83600, #fe8c00);
        color: white; border: none; font-weight: 600;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h2 class="fw-800 mb-5 text-center">
                <span class="text-gradient-primary">✨ KHÔNG GIAN</span> 
                <span class="text-gradient-danger">CÁ NHÂN ✨</span>
            </h2>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 text-center fw-bold">
                    <i class="fa-solid fa-face-grin-stars me-2"></i> {{ session('success') }}
                </div>
            @endif

            <div class="row g-4">
                {{-- KHUNG THÔNG TIN --}}
                <div class="col-md-6">
                    <div class="card card-profile h-100 p-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span class="text-gradient-primary">🆔 Thông tin của bạn</span>
                            <button class="btn btn-sm btn-outline-info rounded-pill px-3" id="btnEditToggle" onclick="toggleEdit()">
                                <b>Sửa</b>
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="viewMode" class="text-center">
                                <div class="profile-avatar">
                                    <i class="fa-solid fa-crown"></i>
                                </div>
                                <div class="p-3 rounded-4" style="background: rgba(0,219,222,0.05)">
                                    <p class="mb-2 text-muted">Họ tên thành viên</p>
                                    <h4 class="fw-bold text-dark mb-4">{{ auth()->user()->ho_ten }}</h4>
                                    
                                    <p class="mb-2 text-muted">Hòm thư điện tử</p>
                                    <h5 class="fw-bold text-secondary mb-4">{{ auth()->user()->email }}</h5>

                                    <p class="mb-1 text-muted">Quyền hạn</p>
                                    <span class="badge badge-rainbow">{{ auth()->user()->vai_tro }}</span>
                                </div>
                            </div>

                            <form id="editMode" method="POST" action="{{ route('profile.update') }}" style="display:none;">
                                @csrf
                                <div class="mb-4">
                                    <label class="form-label text-gradient-primary">Cập nhật họ tên mới</label>
                                    <input type="text" name="ho_ten" value="{{ auth()->user()->ho_ten }}" class="form-control shadow-sm">
                                </div>
                                <button type="submit" class="btn btn-gradient-save w-100 btn-lg rounded-pill shadow">
                                    Cập nhật ngay <i class="fa-solid fa-paper-plane ms-2"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- KHUNG MẬT KHẨU --}}
                <div class="col-md-6">
                    <div class="card card-password h-100 p-3">
                        <div class="card-header">
                            <span class="text-gradient-danger">🔐 Bảo mật mật khẩu</span>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('profile.password') }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Mật khẩu hiện tại</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-warning"><i class="fa-solid fa-shield"></i></span>
                                        <input type="password" name="old_password" class="form-control border-start-0" placeholder="••••••••">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Mật khẩu mới</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-danger"><i class="fa-solid fa-fire"></i></span>
                                        <input type="password" name="new_password" class="form-control border-start-0" placeholder="Nhập mã mới">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Xác nhận lại mã</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-success"><i class="fa-solid fa-check-double"></i></span>
                                        <input type="password" name="new_password_confirmation" class="form-control border-start-0" placeholder="Gõ lại mã trên">
                                    </div>
                                </div>

                                <button class="btn btn-gradient-pass w-100 btn-lg rounded-pill shadow">
                                    Đổi mật khẩu <i class="fa-solid fa-key ms-2"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleEdit() {
    const view = document.getElementById('viewMode');
    const edit = document.getElementById('editMode');
    const btn = document.getElementById('btnEditToggle');

    if (view.style.display === "none") {
        view.style.display = "block";
        edit.style.display = "none";
        btn.innerHTML = '<b>Sửa</b>';
        btn.className = "btn btn-sm btn-outline-info rounded-pill px-3";
    } else {
        view.style.display = "none";
        edit.style.display = "block";
        btn.innerHTML = '<b>Hủy</b>';
        btn.className = "btn btn-sm btn-outline-danger rounded-pill px-3";
    }
}
</script>
@endsection