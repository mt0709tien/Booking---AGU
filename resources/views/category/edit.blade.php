@extends('layouts.app')

@section('content')

<div class="container">

<h3>Sửa danh mục</h3>

<form action="{{ route('admin.categories.update',$category->id) }}" method="POST">

@csrf

<div class="mb-3">
<label>Tên danh mục</label>
<input type="text" name="name" class="form-control"
value="{{ $category->name }}">
</div>

<div class="mb-3">
<label>Giá</label>
<input type="number" name="price" class="form-control"
value="{{ $category->price }}">
</div>

<button class="btn btn-primary">
Cập nhật
</button>

</form>

</div>

@endsection