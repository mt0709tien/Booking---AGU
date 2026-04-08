@extends('layouts.admin')

@section('admin_content')
<h3 class="mb-4">Sửa người dùng</h3>

<form action="{{ route('users.update',$user->id) }}" method="POST">

@csrf
@method('PUT')

<div class="mb-3">
<label>Họ tên</label>
<input type="text" name="ho_ten" class="form-control"
value="{{ $user->ho_ten }}">
</div>

<div class="mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control"
value="{{ $user->email }}">
</div>

<div class="mb-3">
<label>Vai trò</label>
<select name="vai_tro" class="form-control">

<option value="admin"
@if($user->vai_tro=='admin') selected @endif>
Admin
</option>

<option value="user"
@if($user->vai_tro=='user') selected @endif>
User
</option>

</select>
</div>

<button type="submit" class="btn btn-primary">
Cập nhật
</button>

<a href="{{ route('users.index') }}" class="btn btn-secondary">
Quay lại
</a>

</form>

@endsection