@extends('admin.layouts.app')

@section('content')

<h3 class="mb-4">Dashboard</h3>

<div class="row">

    <div class="col-md-4">
        <div class="card card-dashboard shadow-sm p-4">
            <h5>Tổng sản phẩm</h5>
            <h2 class="text-primary">120</h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-dashboard shadow-sm p-4">
            <h5>Tổng đơn hàng</h5>
            <h2 class="text-success">85</h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-dashboard shadow-sm p-4">
            <h5>Doanh thu</h5>
            <h2 class="text-danger">45,000,000 đ</h2>
        </div>
    </div>

</div>

@endsection