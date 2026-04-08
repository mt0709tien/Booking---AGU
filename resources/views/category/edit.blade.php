@extends('layouts.admin')

@section('admin_content')

<div class="container">

    <h3>Sửa danh mục</h3>

    {{-- Hiển thị lỗi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Tên danh mục</label>
            <input 
                type="text" 
                name="name" 
                class="form-control"
                value="{{ old('name', $category->name) }}"
                required
            >
        </div>

        <div class="mb-3">
            <label>Giá sáng (7h - 11h)</label>
            <input 
                type="number" 
                name="price_morning" 
                class="form-control"
                value="{{ old('price_morning', $category->price_morning) }}"
                min="0"
                required
            >
        </div>

        <div class="mb-3">
            <label>Giá chiều (13h - 17h)</label>
            <input 
                type="number" 
                name="price_afternoon" 
                class="form-control"
                value="{{ old('price_afternoon', $category->price_afternoon) }}"
                min="0"
                required
            >
        </div>

        <div class="mb-3">
            <label>Giá tối (17h - 21h)</label>
            <input 
                type="number" 
                name="price_evening" 
                class="form-control"
                value="{{ old('price_evening', $category->price_evening) }}"
                min="0"
                required
            >
        </div>

        <button class="btn btn-primary">
            Cập nhật
        </button>

        <a href="{{ route('admin.categories') }}" class="btn btn-secondary">
            Quay lại
        </a>

    </form>

</div>

@endsection