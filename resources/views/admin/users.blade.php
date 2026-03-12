@extends('layouts.app')

@section('content')

<h3 class="mb-4">Quản lý người dùng</h3>

<!-- Nút thêm -->
<a href="{{ route('users.create') }}" class="btn btn-primary mb-3">
    Thêm người dùng
</a>

<table class="table table-bordered">

<tr>
<th>ID</th>
<th>Tên</th>
<th>Email</th>
<th>Vai trò</th>
<th>Hành động</th>
</tr>

@foreach($users as $user)

<tr>
<td>{{ $user->id }}</td>
<td>{{ $user->ho_ten }}</td>
<td>{{ $user->email }}</td>
<td>{{ $user->vai_tro }}</td>

<td>
    <!-- Nút sửa -->
    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">
        Sửa
    </a>

    <!-- Nút xóa -->
    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')

        <button type="submit" class="btn btn-danger btn-sm"
        onclick="return confirm('Bạn có chắc muốn xóa?')">
            Xóa
        </button>
    </form>
</td>

</tr>

@endforeach

</table>

@endsection