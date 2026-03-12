@extends('layouts.app')

@section('content')

<div class="container">

<h3 class="mb-4">Quản lý danh mục</h3>

<a href="{{ route('admin.categories.create') }}" 
class="btn btn-success mb-3">
Thêm danh mục
</a>

<table class="table table-bordered">

<tr>
<th>ID</th>
<th>Tên</th>
<th>Giá</th>
<th>Hành động</th>
</tr>

@foreach($categories as $category)

<tr>

<td>{{ $category->id }}</td>

<td>{{ $category->name }}</td>

<td>{{ number_format($category->price) }} VNĐ</td>

<td>

<a href="{{ route('admin.categories.edit',$category->id) }}" 
class="btn btn-warning btn-sm">
Sửa
</a>

<a href="{{ route('admin.categories.delete',$category->id) }}" 
class="btn btn-danger btn-sm">
Xóa
</a>

</td>

</tr>

@endforeach

</table>

</div>

@endsection