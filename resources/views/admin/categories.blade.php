@extends('layouts.app')

@section('content')

<div class="container">

<h3 class="mb-4">Quản lý danh mục</h3>

<a href="{{ route('admin.categories.create') }}" class="btn btn-success mb-3">
+ Thêm danh mục
</a>

<form method="GET" action="{{ route('admin.categories') }}" class="mb-3">
    <div class="row">
        <div class="col-md-4">
            <input type="text"
                   name="keyword"
                   class="form-control"
                   placeholder="Tìm tên danh mục..."
                   value="{{ request('keyword') }}">
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary">Tìm kiếm</button>
            <a href="{{ route('admin.categories') }}" class="btn btn-secondary">
                Reset
            </a>
        </div>
    </div>
</form>

<table class="table table-bordered table-striped">

<thead>
<tr>
<th>ID</th>
<th>Tên danh mục</th>
<th>Giá sáng</th>
<th>Giá chiều</th>
<th>Giá tối</th>
<th width="200">Hành động</th>
</tr>
</thead>

<tbody>

@foreach($categories as $category)

<tr>

<td>{{ $category->id }}</td>

<td>{{ $category->name }}</td>

<td>{{ number_format($category->price_morning) }} VNĐ</td>

<td>{{ number_format($category->price_afternoon) }} VNĐ</td>

<td>{{ number_format($category->price_evening) }} VNĐ</td>

<td>

<a href="{{ route('admin.categories.edit',$category->id) }}"
class="btn btn-warning btn-sm">
Sửa
</a>

<a href="{{ route('admin.categories.delete',$category->id) }}"
class="btn btn-danger btn-sm"
onclick="return confirm('Bạn có chắc muốn xoá?')">
Xóa
</a>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

@endsection