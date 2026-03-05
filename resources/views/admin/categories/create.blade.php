@extends('admin.layouts.app')

@section('content')

<h3 class="mb-4">Thêm loại sản phẩm</h3>

<div class="card shadow-sm p-4">

    <form method="POST" action="{{ route('admin.categories.store') }}">
        @csrf

        <div class="mb-3">
            <label>Tên loại</label>
            <input type="text" name="name" class="form-control">
        </div>

        <div class="mb-3">
            <label>Trạng thái</label>
            <select name="is_active" class="form-select">
                <option value="1">Hiển thị</option>
                <option value="0">Ẩn</option>
            </select>
        </div>

        <button class="btn btn-primary">Lưu</button>

    </form>

</div>

@endsection