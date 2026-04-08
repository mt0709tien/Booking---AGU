@extends('layouts.admin')

@section('admin_content')

<div class="container py-4">

<h3>Sửa cơ sở vật chất</h3>

<form action="{{ route('facilities.update',$facility->id) }}" method="POST" enctype="multipart/form-data">

@csrf

<div class="mb-3">

<label>Tên</label>

<input type="text"
name="name"
value="{{ $facility->name }}"
class="form-control">

</div>


<div class="mb-3">

<label>Danh mục</label>

<select name="category_id" class="form-control">

@foreach($categories as $cat)

<option value="{{ $cat->id }}"
{{ $facility->category_id == $cat->id ? 'selected' : '' }}>

{{ $cat->name }}

</option>

@endforeach

</select>

</div>


<div class="mb-3">

<label>Mô tả</label>

<textarea name="description"
class="form-control">{{ $facility->description }}</textarea>

</div>


<div class="mb-3">

<label>Hình ảnh</label>

<input type="file" name="image" class="form-control">

@if($facility->image)

<br>

<img src="{{ asset('storage/'.$facility->image) }}" width="120">

@endif

</div>


<button class="btn btn-primary">
Cập nhật
</button>

<a href="{{ route('admin.facilities') }}"
class="btn btn-secondary">
Quay lại
</a>

</form>

</div>

@endsection