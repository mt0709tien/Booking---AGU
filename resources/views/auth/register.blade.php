<!DOCTYPE html>
<html lang="vi">

<head>

<meta charset="UTF-8">

<title>Đăng ký</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>


<body class="bg-light d-flex align-items-center justify-content-center vh-100">

<div class="col-md-5">

<div class="card shadow">

<div class="card-body p-4">

<h3 class="text-center mb-4">Đăng ký tài khoản</h3>


{{-- Thông báo thành công --}}
@if(session('success'))
<div class="alert alert-success">
{{ session('success') }}
</div>
@endif


<form method="POST" action="{{ route('register.store') }}">

@csrf


{{-- Họ tên --}}
<div class="mb-3">

<label class="form-label">Họ và tên</label>

<input 
type="text" 
name="ho_ten" 
class="form-control"
placeholder="Nhập họ tên"
value="{{ old('ho_ten') }}"
>

@error('ho_ten')
<div class="text-danger">
{{ $message }}
</div>
@enderror

</div>



{{-- Email --}}
<div class="mb-3">

<label class="form-label">Email</label>

<input 
type="email" 
name="email" 
class="form-control"
placeholder="Nhập email"
value="{{ old('email') }}"
>

@error('email')
<div class="text-danger">
{{ $message }}
</div>
@enderror

</div>



{{-- Password --}}
<div class="mb-3">

<label class="form-label">Mật khẩu</label>

<input 
type="password" 
name="password" 
class="form-control"
placeholder="Nhập mật khẩu"
>

@error('password')
<div class="text-danger">
{{ $message }}
</div>
@enderror

</div>



<div class="d-grid">

<button type="submit" class="btn btn-success">

Đăng ký

</button>

</div>



<div class="text-center mt-3">

<a href="{{ route('login') }}">

Quay lại đăng nhập

</a>

</div>

</form>

</div>

</div>

</div>

</body>

</html>