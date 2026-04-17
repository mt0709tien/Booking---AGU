@extends('layouts.admin')

@section('admin_content')

<div class="container">

    <h3 class="mb-4">Thêm danh mục</h3>

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

        {{-- TÊN --}}
        <div class="mb-3">
            <label class="fw-bold">Tên danh mục</label>
            <input 
                type="text" 
                name="name" 
                class="form-control"
                value="{{ old('name') }}"
                required
            >
        </div>

        {{-- LOẠI --}}
        <div class="mb-3">
            <label class="fw-bold">Loại</label>
            <select name="type" class="form-control" onchange="togglePrice(this.value)">
                <option value="room" {{ old('type') == 'room' ? 'selected' : '' }}>Phòng / Hội trường</option>
                <option value="sport" {{ old('type') == 'sport' ? 'selected' : '' }}>Sân thể thao</option>
            </select>
        </div>

        {{-- ===== GIÁ THEO BUỔI ===== --}}
        <div id="price-session">

            <div class="mb-3">
                <label>Giá buổi sáng (7h - 11h)</label>
                <input 
                    type="number" 
                    name="price_morning" 
                    class="form-control"
                    value="{{ old('price_morning') }}"
                    min="0"
                >
            </div>

            <div class="mb-3">
                <label>Giá buổi chiều (13h - 17h)</label>
                <input 
                    type="number" 
                    name="price_afternoon" 
                    class="form-control"
                    value="{{ old('price_afternoon') }}"
                    min="0"
                >
            </div>

            <div class="mb-3">
                <label>Giá buổi tối (17h - 21h)</label>
                <input 
                    type="number" 
                    name="price_evening" 
                    class="form-control"
                    value="{{ old('price_evening') }}"
                    min="0"
                >
            </div>

        </div>

        {{-- ===== GIÁ THEO GIỜ ===== --}}
        <div id="price-hour" style="display:none;">
            <div class="mb-3">
                <label>Giá theo giờ</label>
                <input 
                    type="number" 
                    name="price_hour" 
                    class="form-control"
                    value="{{ old('price_hour') }}"
                    min="0"
                >
            </div>
        </div>

        {{-- BUTTON --}}
        <button class="btn btn-primary">
            Thêm
        </button>

        <a href="{{ route('admin.categories') }}" class="btn btn-secondary">
            Quay lại
        </a>

    </form>

</div>

{{-- JS --}}
<script>
function togglePrice(type){
    let session = document.getElementById('price-session');
    let hour = document.getElementById('price-hour');

    if(type === 'sport'){
        session.style.display = 'none';
        hour.style.display = 'block';

        // disable input session
        session.querySelectorAll('input').forEach(i => i.disabled = true);
        hour.querySelectorAll('input').forEach(i => i.disabled = false);

    }else{
        session.style.display = 'block';
        hour.style.display = 'none';


    }
}

// 🔥 FIX reload form (giữ trạng thái cũ)
document.addEventListener("DOMContentLoaded", function() {
    let type = "{{ old('type', 'room') }}";
    togglePrice(type);
});
</script>

@endsection