@extends('layouts.app')

@section('content')

<h3 class="mb-4">Quản lý người dùng</h3>

<!-- ➕ Thêm -->
<a href="{{ route('users.create') }}" class="btn btn-success mb-3">
    + Thêm người dùng
</a>

<!-- 🔍 Tìm kiếm -->
<form method="GET" class="mb-3 d-flex" style="gap:10px;">
    <input 
        type="text" 
        name="keyword" 
        class="form-control"
        placeholder="Tìm theo tên hoặc email..."
        value="{{ request('keyword') }}"
    >

    <button class="btn btn-primary">Tìm</button>

    <a href="{{ route('users.index') }}" class="btn btn-secondary">
        Reset
    </a>
</form>


<!-- 📊 Table -->
<table class="table table-bordered table-hover align-middle">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Email</th>
            <th>Vai trò</th>
            <th width="180">Hành động</th>
        </tr>
    </thead>

    <tbody>
        @forelse($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->ho_ten }}</td>
            <td>{{ $user->email }}</td>
            <td>
                @if($user->vai_tro == 'admin')
                    <span class="badge bg-danger">Admin</span>
                @else
                    <span class="badge bg-secondary">User</span>
                @endif
            </td>

            <td>
                <a href="{{ route('users.edit', $user->id) }}" 
                   class="btn btn-warning btn-sm">
                    Sửa
                </a>

                <form action="{{ route('users.destroy', $user->id) }}" 
                      method="POST" 
                      style="display:inline;">
                    @csrf
                    @method('DELETE')

                    <button type="submit" 
                        class="btn btn-danger btn-sm"
                        onclick="return confirm('Bạn có chắc muốn xóa?')">
                        Xóa
                    </button>
                </form>
            </td>
        </tr>

        @empty
        <tr>
            <td colspan="5" class="text-center text-muted">
                Không tìm thấy người dùng
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<!-- 📄 Phân trang -->
<div class="mt-3">
    {{ $users->appends(request()->query())->links() }}
</div>

@endsection