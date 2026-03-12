@extends('layouts.app')

@section('content')

<h3 class="mb-4">Thống kê hệ thống</h3>

<div class="row">

<div class="col-md-4">

<div class="card text-center shadow">

<div class="card-body">

<h4>{{ $totalUsers }}</h4>

<p>Tổng người dùng</p>

</div>

</div>

</div>


<div class="col-md-4">

<div class="card text-center shadow">

<div class="card-body">

<h4>{{ $totalBookings }}</h4>

<p>Tổng đặt lịch</p>

</div>

</div>

</div>


<div class="col-md-4">

<div class="card text-center shadow">

<div class="card-body">

<h4>{{ $totalFacilities }}</h4>

<p>Tổng cơ sở vật chất</p>

</div>

</div>

</div>

</div>

@endsection