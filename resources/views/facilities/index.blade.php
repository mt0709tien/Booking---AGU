@extends('layouts.app')

@section('content')

<div class="container py-4">

<h3 class="mb-3">Quản lý cơ sở vật chất</h3>

@if(session('success'))
<div class="alert alert-success">
{{ session('success') }}
</div>
@endif

<a href="{{ route('facilities.create') }}"
class="btn btn-success mb-3">
Thêm cơ sở
</a>

<table class="table table-bordered">

<thead>

<tr>
<th>ID</th>
<th>Tên</th>
<th>Hình ảnh</th>
<th>Danh mục</th>
<th>Mô tả</th>
<th width="200">Hành động</th>
</tr>

</thead>

<tbody>

@foreach($facilities as $facility)

<tr>

<td>{{ $facility->id }}</td>

<td>{{ $facility->name }}</td>

<td>
@if($facility->image)
<img src="{{ asset('images/'.$facility->image) }}" width="80">
@endif
</td>

<td>{{ $facility->category->name }}</td>

<td>{{ $facility->description }}</td>

<td>

<a href="{{ route('facilities.edit',$facility->id) }}"
class="btn btn-warning btn-sm">
Sửa
</a>

<a href="{{ route('facilities.delete',$facility->id) }}"
class="btn btn-danger btn-sm"
onclick="return confirm('Xóa cơ sở này?')">
Xóa
</a>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

@endsection