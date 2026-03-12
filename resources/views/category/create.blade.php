@extends('layouts.app')

@section('content')

<div class="container">

<h3>Thêm danh mục</h3>

<form action="{{ route('admin.categories.store') }}" method="POST">

@csrf

<div class="mb-3">
<label>Tên danh mục</label>
<input type="text" name="name" class="form-control">
</div>

<div class="mb-3">
<label>Giá</label>
<input type="number" name="price" class="form-control">
</div>

<button class="btn btn-primary">
Thêm
</button>

</form>

</div>

@endsection