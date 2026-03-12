@extends('layouts.app')

@section('content')

<div class="container py-5">

<h3 class="mb-4">Quản lý cơ sở vật chất</h3>

<a href="#" class="btn btn-success mb-3">
Thêm cơ sở
</a>

<table class="table table-bordered">

<thead>
<tr>
<th>ID</th>
<th>Tên</th>
<th>Danh mục</th>
<th>Mô tả</th>
<th>Hình ảnh</th>
<th width="180">Hành động</th>
</tr>
</thead>

<tbody>

@foreach($facilities as $facility)

<tr>

<td>{{ $facility->id }}</td>

<td>{{ $facility->name }}</td>

<td>{{ $facility->category->name }}</td>

<td>{{ $facility->description }}</td>

<td>

<a href="#" class="btn btn-warning btn-sm">
Sửa
</a>

<a href="#" class="btn btn-danger btn-sm">
Xóa
</a>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

@endsection 