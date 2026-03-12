@extends('layouts.app')

@section('content')

<div class="container">

    <!-- Tiêu đề -->
    <div class="text-center mb-5">
        <h1 class="fw-bold text-danger">GIỚI THIỆU CƠ SỞ VẬT CHẤT</h1>
        <p class="lead text-muted">
            Trường Đại học An Giang cung cấp nhiều loại cơ sở vật chất hiện đại 
            phục vụ cho việc học tập, tổ chức sự kiện và hoạt động thể thao.
            Hệ thống được xây dựng nhằm giúp việc quản lý và đăng ký sử dụng
            cơ sở vật chất trở nên dễ dàng, nhanh chóng và minh bạch.
        </p>
    </div>


    <!-- Hội trường -->
    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            <img src="https://images.unsplash.com/photo-1587825140708-dfaf72ae4b04"
                 class="img-fluid rounded shadow">
        </div>

        <div class="col-md-6">
            <h3 class="text-danger fw-bold">Hội trường</h3>
            <p>
                Hội trường được trang bị đầy đủ hệ thống âm thanh, máy chiếu,
                máy lạnh và các thiết bị hỗ trợ hội nghị hiện đại.
            </p>

            <ul>
                <li>Sức chứa: 600 chỗ, 300 chỗ, 150 chỗ</li>
                <li>Trang bị máy chiếu, âm thanh hội nghị</li>
                <li>Phù hợp tổ chức hội thảo, sự kiện, hội nghị</li>
            </ul>
        </div>
    </div>


    <!-- Phòng học -->
    <div class="row align-items-center mb-5">

        <div class="col-md-6">
            <h3 class="text-danger fw-bold">Phòng học & Phòng thực hành</h3>
            <p>
                Các phòng học được thiết kế rộng rãi, thoáng mát và được
                trang bị đầy đủ thiết bị phục vụ cho việc giảng dạy.
            </p>

            <ul>
                <li>Sức chứa từ 50 - 100 sinh viên</li>
                <li>Trang bị máy chiếu và hệ thống âm thanh</li>
                <li>Phòng máy tính phục vụ thực hành</li>
            </ul>
        </div>

        <div class="col-md-6">
            <img src="https://images.unsplash.com/photo-1580582932707-520aed937b7b"
                 class="img-fluid rounded shadow">
        </div>

    </div>


    <!-- Sân thể thao -->
    <div class="row align-items-center mb-5">

        <div class="col-md-6">
            <img src="https://images.unsplash.com/photo-1505842465776-3d90f6163100"
                 class="img-fluid rounded shadow">
        </div>

        <div class="col-md-6">
            <h3 class="text-danger fw-bold">Sân thể thao</h3>
            <p>
                Trường có nhiều sân thể thao phục vụ cho hoạt động rèn luyện
                sức khỏe của sinh viên và các giải thi đấu thể thao.
            </p>

            <ul>
                <li>Sân bóng đá</li>
                <li>Sân bóng chuyền</li>
                <li>Sân bóng rổ</li>
                <li>Sân tennis</li>
            </ul>
        </div>

    </div>


    <!-- Sân tổ chức sự kiện -->
    <div class="row align-items-center mb-5">

        <div class="col-md-6">
            <h3 class="text-danger fw-bold">Sân tổ chức sự kiện</h3>
            <p>
                Khu vực sân bãi rộng rãi phù hợp tổ chức các chương trình
                văn nghệ, hội chợ, hoạt động ngoại khóa và sự kiện lớn.
            </p>

            <ul>
                <li>Không gian rộng</li>
                <li>Phù hợp tổ chức sự kiện ngoài trời</li>
                <li>Đáp ứng nhu cầu thuê tổ chức chương trình</li>
            </ul>
        </div>

        <div class="col-md-6">
            <img src="https://images.unsplash.com/photo-1511578314322-379afb476865"
                 class="img-fluid rounded shadow">
        </div>

    </div>


    <!-- Công nghệ -->
    <div class="text-center mt-5">
        <h4 class="fw-bold">Công nghệ sử dụng trong hệ thống</h4>
        <p class="text-muted">
            Website được xây dựng nhằm hỗ trợ quản lý và đăng ký thuê cơ sở vật chất trực tuyến.
        </p>

        <div class="row mt-4">

            <div class="col-md-3">
                <div class="card shadow">
                    <div class="card-body">
                        <h5>Laravel</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow">
                    <div class="card-body">
                        <h5>MySQL</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow">
                    <div class="card-body">
                        <h5>Bootstrap 5</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow">
                    <div class="card-body">
                        <h5>XAMPP</h5>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

@endsection