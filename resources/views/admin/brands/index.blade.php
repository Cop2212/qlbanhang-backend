@extends('admin.layouts.app')

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Quản lý hãng</h3>
    <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">
        ➕ Thêm hãng
    </a>
</div>

<div class="card shadow-sm p-4">
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Tên hãng</th>
                <th>Logo</th>
                <th>Trạng thái</th>
                <th width="150">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($brands as $key => $brand)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $brand->name }}</td>
                <td>
                    @if($brand->logo)
                    <img src="{{ str_replace('/upload/', '/upload/w_80,h_80,c_fill/', $brand->logo) }}" width="50">
                    @endif
                </td>
                <td>
                    @if($brand->is_active)
                    <span class="badge bg-success">Hiển thị</span>
                    @else
                    <span class="badge bg-danger">Ẩn</span>
                    @endif
                </td>
                <td>
                    <button class="btn btn-sm btn-warning">Sửa</button>
                    <button class="btn btn-sm btn-danger">Xóa</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection