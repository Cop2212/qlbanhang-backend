@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Thêm thông số mới</h3>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.specifications.store') }}">
            @csrf

            <div class="mb-3">
                <label>Tên thông số</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Mô tả (không bắt buộc)</label>
                <input type="text" name="description" class="form-control">
            </div>

            <button class="btn btn-primary">Lưu</button>
        </form>
    </div>
</div>
@endsection