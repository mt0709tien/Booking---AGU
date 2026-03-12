@extends('layouts.app')

@section('content')

<div class="container py-4">

<h3 class="mb-4">Thêm cơ sở vật chất</h3>

<form action="{{ route('facilities.store') }}" method="POST" enctype="multipart/form-data">

@csrf

<div class="mb-3">
<label class="form-label">Tên cơ sở</label>
<input type="text" name="name" class="form-control" required>
</div>


<div class="mb-3">
<label class="form-label">Danh mục</label>

<select name="category_id" class="form-control" required>

@foreach($categories as $cat)

<option value="{{ $cat->id }}">
{{ $cat->name }}
(Sáng: {{ number_format($cat->price_morning) }} |
Chiều: {{ number_format($cat->price_afternoon) }} |
Tối: {{ number_format($cat->price_evening) }})
</option>

@endforeach

</select>

</div>


<div class="mb-3">
<label class="form-label">Mô tả</label>
<textarea name="description" class="form-control" rows="3"></textarea>
</div>


<div class="mb-3">
<label class="form-label">Hình ảnh</label>
<input type="file" name="image" class="form-control" accept="image/*">
</div>


<button class="btn btn-success">
Thêm
</button>

<a href="{{ route('admin.facilities') }}"
class="btn btn-secondary">
Quay lại
</a>

</form>

</div>

@endsection