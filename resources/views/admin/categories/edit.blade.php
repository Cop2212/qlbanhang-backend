@extends('admin.layouts.app')

@section('content')

<h3 class="mb-4">Chỉnh sửa loại sản phẩm</h3>

<div class="card shadow-sm p-4">

    <form method="POST" action="{{ route('admin.categories.update', $category->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Tên loại</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}">
        </div>

        <div class="mb-3">
            <label>Trạng thái</label>
            <select name="is_active" class="form-select">
                <option value="1" {{ $category->is_active == 1 ? 'selected' : '' }}>Hiển thị</option>
                <option value="0" {{ $category->is_active == 0 ? 'selected' : '' }}>Ẩn</option>
            </select>
        </div>

        <button class="btn btn-primary">Lưu thay đổi</button>

    </form>

</div>

@endsection
