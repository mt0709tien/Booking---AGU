@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm border-0 rounded-4 mx-auto" style="max-width:700px;">
        <div class="card-body p-4">

            <h3 class="text-center text-success mb-4">
                Đánh giá cơ sở vật chất
            </h3>

            <form action="{{ route('booking.review.submit', $booking->id) }}" method="POST">
                @csrf

                <div class="mb-4 text-center">
                    <label class="fw-bold d-block mb-3">Chọn số sao</label>

                    <div class="d-flex justify-content-center gap-3">
                        @for($i = 1; $i <= 5; $i++)
                            <label>
                                <input type="radio" name="rating" value="{{ $i }}">
                                ⭐ {{ $i }}
                            </label>
                        @endfor
                    </div>
                </div>

                <div class="mb-4">
                    <label class="fw-bold">Bình luận</label>
                    <textarea name="comment"
                              class="form-control"
                              rows="4"
                              placeholder="Nhập nhận xét..."></textarea>
                </div>

                <div class="text-center">
                    <button class="btn btn-success px-4">
                        Gửi đánh giá
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection