@extends('layouts.app')

@section('content')

<div class="container">

    <!-- Tiêu đề -->
    <div class="text-center mb-5">
        <h1 class="fw-bold text-danger">GIỚI THIỆU CƠ SỞ VẬT CHẤT</h1>
        <p class="lead text-muted">
            Trường Đại học An Giang cung cấp nhiều loại cơ sở vật chất hiện đại 
            phục vụ cho việc học tập, tổ chức sự kiện và hoạt động thể thao.
        </p>
    </div>

    <div class="row mb-5">
    <div class="col-12">
        <div class="card bg-light border-0 shadow-sm">
            <div class="card-body p-4">
                <h4 class="fw-bold text-dark mb-4">
                    <i class="fas fa-file-signature text-danger me-2"></i> Văn bản & Quy định tài chính
                </h4>
                <div class="row g-3">
                    
                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 bg-white rounded border shadow-sm h-100">
                            <div class="flex-shrink-0">
                                <i class="fas fa-file-pdf fa-2x text-danger"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-1 fw-bold small">Thông báo niêm yết giá cho thuê tài sản công (Trực tiếp)</h6>
                                <p class="small text-muted mb-2">Số: 11/TB-ĐHAG</p>
                                <a href="{{ asset('images/11-TB-DHAG Thong bao v-v niem yet gia cho thue tai san cong tai Truong DHAG theo hinh thuc cho thue truc tiep.pdf') }}" 
                                   target="_blank" 
                                   class="btn btn-sm btn-outline-danger fw-bold">
                                    <i class="fas fa-eye me-1"></i> Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 bg-white rounded border shadow-sm h-100">
                            <div class="flex-shrink-0">
                                <i class="fas fa-file-pdf fa-2x text-primary"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-1 fw-bold small">Thông báo giá thuê sân Bóng đá, Bóng chuyền, Bóng rổ</h6>
                                <p class="small text-muted mb-2">Văn bản đã ký duyệt (.signed)</p>
                                <a href="{{ asset('images/TB GIÁ THUÊ SÂN BÓNG ĐÁ, BÓNG CHUYỀN, BÓNG RỔ.signed_0.pdf') }}" 
                                   target="_blank" 
                                   class="btn btn-sm btn-outline-primary fw-bold">
                                    <i class="fas fa-eye me-1"></i> Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
    <!-- Hội trường -->
    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            <img src="images/HT.jpg"
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
            <img src="images/PM.jpg"
                 class="img-fluid rounded shadow">
        </div>

    </div>


    <!-- Sân thể thao -->
    <div class="row align-items-center mb-5">

        <div class="col-md-6">
            <img src="images/TT.jpg"
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
    <hr class="my-5">



</div>

@endsection