@extends('layouts.app')

@section('content')

<div class="container py-5">

<h3 class="mb-4">Quản lý cơ sở vật chất</h3>

<a href="#" class="btn btn-success mb-3">
Thêm cơ sở
</a>

{{-- 🔍 LIVE SEARCH --}}
<input type="text" id="liveSearch"
    class="form-control mb-3"
    placeholder="🔍 Gõ để tìm...">

<table class="table table-bordered">

<thead>
<tr>
<th>ID</th>
<th>Tên</th>
<th>Danh mục</th>
<th>Mô tả</th>
<th>Hình ảnh</th>
<th>Đơn đặt</th>
<th width="180">Hành động</th>
</tr>
</thead>

<tbody id="facilityTable">

@foreach($facilities as $facility)

<tr>
<td>{{ $facility->id }}</td>
<td>{{ $facility->name }}</td>
<td>{{ $facility->category->name }}</td>
<td>{{ $facility->description }}</td>

<td></td>

<td>
    <a href="{{ route('admin.facility.bookings', $facility->id) }}"
       class="btn btn-info btn-sm">
        📋 Xem đơn
    </a>
</td>

<td>
    <a href="#" class="btn btn-warning btn-sm">Sửa</a>
    <a href="#" class="btn btn-danger btn-sm">Xóa</a>
</td>

</tr>

@endforeach

</tbody>

</table>

</div>

{{-- 🔥 SCRIPT LIVE SEARCH --}}

<script>
document.addEventListener('DOMContentLoaded', function () {

    let input = document.getElementById('liveSearch');
    let timeout;

    if (!input) {
        console.log("Không tìm thấy input ❌");
        return;
    }

    input.addEventListener('keyup', function () {

        clearTimeout(timeout);

        timeout = setTimeout(() => {

            let keyword = input.value;

            fetch(`{{ route('admin.facilities') }}?keyword=` + keyword, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest' // 🔥 QUAN TRỌNG
                }
            })
            .then(res => res.json())
            .then(data => {

                let html = '';

                if (data.length === 0) {
                    html = `
                        <tr>
                            <td colspan="7" class="text-center">
                                Không có dữ liệu
                            </td>
                        </tr>
                    `;
                } else {
                    data.forEach(f => {
                        html += `
                        <tr>
                            <td>${f.id}</td>
                            <td>${f.name}</td>
                            <td>${f.category ? f.category.name : ''}</td>
                            <td>${f.description ?? ''}</td>
                            <td></td>

                            <td>
                                <a href="/admin/facility/${f.id}/bookings"
                                   class="btn btn-info btn-sm">
                                   📋 Xem đơn
                                </a>
                            </td>

                            <td>
                                <a href="#" class="btn btn-warning btn-sm">Sửa</a>
                                <a href="#" class="btn btn-danger btn-sm">Xóa</a>
                            </td>
                        </tr>
                        `;
                    });
                }

                document.getElementById('facilityTable').innerHTML = html;

            })
            .catch(err => console.log("Lỗi:", err));

        }, 300); // delay cho mượt
    });

});
</script>

@endsection