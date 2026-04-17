@extends('layouts.admin')

@section('admin_content')

<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">📂 Quản lý danh mục</h3>

        <a href="{{ route('admin.categories.create') }}" class="btn btn-success">
            + Thêm danh mục
        </a>
    </div>

    <!-- ALERT -->
    @if(session('success'))
        <div id="alert-box" class="alert alert-success alert-dismissible fade show shadow-sm d-flex align-items-center">
            <span class="me-2">✅</span>
            {{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- SEARCH -->
    <div class="card mb-3 shadow-sm border-0">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.categories') }}">
                <div class="row g-2">

                    <div class="col-md-4">
                        <input type="text"
                               name="keyword"
                               class="form-control"
                               placeholder="🔍 Tìm tên danh mục..."
                               value="{{ request('keyword') }}">
                    </div>

                    <!-- 🔥 FILTER TYPE -->
                    <div class="col-md-3">
                        <select name="type" class="form-control">
                            <option value="">-- Tất cả --</option>
                            <option value="room" {{ request('type')=='room' ? 'selected' : '' }}>Phòng</option>
                            <option value="sport" {{ request('type')=='sport' ? 'selected' : '' }}>Sân</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <button class="btn btn-primary">Tìm kiếm</button>
                        <a href="{{ route('admin.categories') }}" class="btn btn-secondary">Reset</a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-dark text-center">
                    <tr>
                        <th>ID</th>
                        <th class="text-start">Tên</th>
                        <th>Loại</th>
                        <th>Giá</th>
                        <th width="180">Hành động</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($categories as $category)

                    <tr>
                        <td class="text-center">{{ $category->id }}</td>

                        <td class="fw-semibold">
                            {{ $category->name }}
                        </td>

                        <!-- 🔥 TYPE -->
                        <td class="text-center">
                            @if($category->type == 'sport')
                                <span class="badge bg-info">Sân</span>
                            @else
                                <span class="badge bg-primary">Phòng</span>
                            @endif
                        </td>

                        <!-- 🔥 GIÁ -->
                        <td class="text-center">

                            @if($category->type == 'sport')

                                <span class="text-success fw-bold">
                                    {{ number_format($category->price_hour) }}đ / giờ
                                </span>

                            @else

                                <div class="text-success fw-bold">
                                    Sáng: {{ number_format($category->price_morning) }}đ
                                </div>

                                <div class="text-primary fw-bold">
                                    Chiều: {{ number_format($category->price_afternoon) }}đ
                                </div>

                                <div class="text-danger fw-bold">
                                    Tối: {{ number_format($category->price_evening) }}đ
                                </div>

                            @endif

                        </td>

                        <td class="text-center">

                            <a href="{{ route('admin.categories.edit',$category->id) }}"
                               class="btn btn-warning btn-sm me-1">
                                ✏️ Sửa
                            </a>

                            <form action="{{ route('admin.categories.destroy', $category->id) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Bạn có chắc muốn xoá?')"
                                  style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">
                                    🗑 Xóa
                                </button>
                            </form>

                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            Không có dữ liệu
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>
    </div>

</div>

<!-- AUTO HIDE -->
<script>
setTimeout(() => {
    let alert = document.getElementById('alert-box');
    if (alert) {
        alert.style.transition = "opacity 0.5s";
        alert.style.opacity = "0";
        setTimeout(() => alert.remove(), 500);
    }
}, 3000);
</script>

@endsection