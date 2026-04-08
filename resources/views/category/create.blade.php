@extends('layouts.admin')

@section('admin_content')

<div class="container">

    <h3>Thêm danh mục</h3>

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

    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Tên danh mục</label>
            <input 
                type="text" 
                name="name" 
                class="form-control"
                value="{{ old('name') }}"
                required
            >
        </div>

        {{-- Giá buổi sáng --}}
        <div class="mb-3">
            <label>Giá buổi sáng (7h - 11h)</label>
            <input 
                type="number" 
                name="price_morning" 
                class="form-control"
                value="{{ old('price_morning') }}"
                min="0"
                required
            >
        </div>

        {{-- Giá buổi chiều --}}
        <div class="mb-3">
            <label>Giá buổi chiều (13h - 17h)</label>
            <input 
                type="number" 
                name="price_afternoon" 
                class="form-control"
                value="{{ old('price_afternoon') }}"
                min="0"
                required
            >
        </div>

        {{-- Giá buổi tối --}}
        <div class="mb-3">
            <label>Giá buổi tối (17h - 21h)</label>
            <input 
                type="number" 
                name="price_evening" 
                class="form-control"
                value="{{ old('price_evening') }}"
                min="0"
                required
            >
        </div>

        <button class="btn btn-primary">
            Thêm
        </button>

        <a href="{{ route('admin.categories') }}" class="btn btn-secondary">
            Quay lại
        </a>

    </form>

</div>

@endsection