@extends('layouts.app')

@section('content')

<div class="container py-4">

<h3>Thêm cơ sở vật chất</h3>

<form action="{{ route('facilities.store') }}" method="POST" enctype="multipart/form-data">

@csrf

<div class="mb-3">
<label>Tên cơ sở</label>
<input type="text" name="name" class="form-control">
</div>

<div class="mb-3">
<label>Danh mục</label>

<select name="category_id" class="form-control">

@foreach($categories as $cat)

<option value="{{ $cat->id }}">
{{ $cat->name }}
</option>

@endforeach

</select>

</div>

<div class="mb-3">
<label>Mô tả</label>
<textarea name="description" class="form-control"></textarea>
</div>

<div class="mb-3">
<label>Hình ảnh</label>
<input type="file" name="image" class="form-control">
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