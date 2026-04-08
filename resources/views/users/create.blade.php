@extends('layouts.admin')

@section('admin_content')

<h3 class="mb-4">Thêm người dùng</h3>

<form action="{{ route('users.store') }}" method="POST">

@csrf

<div class="mb-3">
<label>Họ tên</label>

<input type="text"
name="ho_ten"
class="form-control"
value="{{ old('ho_ten') }}">

@error('ho_ten')
<div class="text-danger">{{ $message }}</div>
@enderror

</div>


<div class="mb-3">

<label>Email</label>

<input type="email"
name="email"
class="form-control"
value="{{ old('email') }}">

@error('email')
<div class="text-danger">{{ $message }}</div>
@enderror

</div>


<div class="mb-3">

<label>Mật khẩu</label>

<input type="password"
name="password"
class="form-control">

@error('password')
<div class="text-danger">{{ $message }}</div>
@enderror

</div>


<div class="mb-3">

<label>Vai trò</label>

<select name="vai_tro" class="form-control">

<option value="admin">Admin</option>
<option value="user">User</option>

</select>

</div>


<button type="submit" class="btn btn-success">
Thêm
</button>

<a href="{{ route('users.index') }}" class="btn btn-secondary">
Quay lại
</a>

</form>

@endsection