@extends('layouts.admin')

@section('admin_content')

<div class="container py-4">

<h3 class="mb-4">Thêm cơ sở vật chất</h3>

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

<form action="{{ route('facilities.store') }}" method="POST" enctype="multipart/form-data">

@csrf

{{-- TÊN --}}
<div class="mb-3">
    <label class="form-label">Tên cơ sở</label>
    <input type="text" 
           name="name" 
           class="form-control" 
           value="{{ old('name') }}"
           required>
</div>

{{-- DANH MỤC --}}
<div class="mb-3">
    <label class="form-label">Danh mục</label>

    <select name="category_id" class="form-control" required>

        <option value="">-- Chọn danh mục --</option>

        @foreach($categories as $cat)

        <option value="{{ $cat->id }}"
            data-type="{{ $cat->type }}"
            data-morning="{{ $cat->price_morning }}"
            data-afternoon="{{ $cat->price_afternoon }}"
            data-evening="{{ $cat->price_evening }}"
            data-hour="{{ $cat->price_hour }}"
            {{ old('category_id') == $cat->id ? 'selected' : '' }}>
            
            {{ $cat->name }}

            @if($cat->type == 'sport')
                ({{ number_format($cat->price_hour) }}đ / giờ)
            @else
                (Sáng: {{ number_format($cat->price_morning) }} |
                 Chiều: {{ number_format($cat->price_afternoon) }} |
                 Tối: {{ number_format($cat->price_evening) }})
            @endif
        
        </option>

        @endforeach

    </select>
</div>

{{-- HIỂN THỊ GIÁ --}}
<div class="mb-3">
    <label class="form-label">Thông tin giá</label>

    <div id="price-info" class="p-3 bg-light rounded text-muted">
        Chọn danh mục để xem giá
    </div>
</div>

{{-- MÔ TẢ --}}
<div class="mb-3">
    <label class="form-label">Mô tả</label>
    <textarea name="description" 
              class="form-control" 
              rows="3">{{ old('description') }}</textarea>
</div>

{{-- ẢNH --}}
<div class="mb-3">
    <label class="form-label">Hình ảnh</label>
    <input type="file" 
           name="image" 
           class="form-control" 
           accept="image/*">
</div>

{{-- BUTTON --}}
<button class="btn btn-success">
    Thêm
</button>

<a href="{{ route('admin.facilities') }}"
   class="btn btn-secondary">
   Quay lại
</a>

</form>

</div>

{{-- JS --}}
<script>
document.querySelector('select[name="category_id"]').addEventListener('change', function(){

    let option = this.options[this.selectedIndex];

    let type = option.dataset.type;

    let box = document.getElementById('price-info');

    if(!type){
        box.innerHTML = "Chọn danh mục để xem giá";
        return;
    }

    if(type === 'sport'){
        box.innerHTML = `
            <strong>⚽ Giá theo giờ:</strong><br>
            ${Number(option.dataset.hour).toLocaleString()} VNĐ / giờ
        `;
    }else{
        box.innerHTML = `
            <strong>🏫 Giá theo buổi:</strong><br>
            Sáng: ${Number(option.dataset.morning).toLocaleString()}<br>
            Chiều: ${Number(option.dataset.afternoon).toLocaleString()}<br>
            Tối: ${Number(option.dataset.evening).toLocaleString()}
        `;
    }

});
</script>

@endsection