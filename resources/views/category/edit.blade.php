@extends('layouts.admin')

@section('admin_content')

<div class="container">

    <h3 class="mb-4">Sửa danh mục</h3>

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

        {{-- TÊN --}}
        <div class="mb-3">
            <label class="fw-bold">Tên danh mục</label>
            <input 
                type="text" 
                name="name" 
                class="form-control"
                value="{{ old('name', $category->name) }}"
                required
            >
        </div>

        {{-- LOẠI --}}
        <div class="mb-3">
            <label class="fw-bold">Loại</label>
           <select name="type" class="form-control" onchange="togglePrice(this.value)">
    <option value="room" {{ old('type', $category->type) == 'room' ? 'selected' : '' }}>
        Phòng / Hội trường
    </option>
    <option value="sport" {{ old('type', $category->type) == 'sport' ? 'selected' : '' }}>
        Sân thể thao
    </option>
</select>
        </div>

        {{-- ===== GIÁ THEO BUỔI ===== --}}
        <div id="price-session">

            <div class="mb-3">
                <label>Giá sáng (7h - 11h)</label>
                <input 
                    type="number" 
                    name="price_morning" 
                    class="form-control"
                    value="{{ old('price_morning', $category->price_morning) }}"
                    min="0"
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
                >
            </div>

        </div>

        {{-- ===== GIÁ THEO GIỜ ===== --}}
        <div id="price-hour">

            <div class="mb-3">
                <label>Giá theo giờ</label>
                <input 
                    type="number" 
                    name="price_hour" 
                    class="form-control"
                    value="{{ old('price_hour', $category->price_hour) }}"
                    min="0"
                >
            </div>

        </div>

        {{-- BUTTON --}}
        <button class="btn btn-primary">
            Cập nhật
        </button>

        <a href="{{ route('admin.categories') }}" class="btn btn-secondary">
            Quay lại
        </a>

    </form>

</div>

{{-- JS --}}
<script>
function togglePrice(type){
    if(type === 'sport'){
        document.getElementById('price-session').style.display = 'none';
        document.getElementById('price-hour').style.display = 'block';
    }else{
        document.getElementById('price-session').style.display = 'block';
        document.getElementById('price-hour').style.display = 'none';
    }
}

// 🔥 load lần đầu
document.addEventListener("DOMContentLoaded", function(){
    togglePrice("{{ $category->type }}");
});
</script>

@endsection