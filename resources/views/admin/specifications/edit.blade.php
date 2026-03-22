@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Sửa thông số</h3>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.specifications.update', $specification->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Tên thông số</label>
                <input type="text" name="name" class="form-control" value="{{ $specification->name }}" required>
            </div>

            <div class="mb-3">
                <label>Mô tả</label>
                <input type="text" name="description" class="form-control" value="{{ $specification->description }}">
            </div>

            <button class="btn btn-primary">Cập nhật</button>
        </form>
    </div>
</div>
@endsection