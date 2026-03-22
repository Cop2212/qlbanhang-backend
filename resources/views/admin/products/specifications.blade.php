@extends('admin.layouts.app')

@section('content')

<h2>Thông số sản phẩm: {{ $product->name }}</h2>

<hr>

{{-- Form thêm thông số --}}
<form action="{{ route('admin.products.specifications.store', $product->id) }}" method="POST">
    @csrf

    <div class="row mb-3">

        <div class="col-4">
            <label>Tên thông số</label>
            <select name="template_id" class="form-control" required>
                <option value="">-- Chọn thông số --</option>
                @foreach($templates as $tpl)
                <option value="{{ $tpl->id }}">{{ $tpl->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-4">
            <label>Giá trị</label>
            <input type="text" name="value" class="form-control" required>
        </div>

        <div class="col-2 d-flex align-items-end">
            <button class="btn btn-primary w-100">Thêm</button>
        </div>

    </div>
</form>

<hr>

{{-- Danh sách thông số --}}
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Tên</th>
            <th>Giá trị</th>
            <th width="120">Thứ tự</th>
            <th width="100">Xóa</th>
        </tr>
    </thead>

    <tbody>
        @foreach($specifications as $spec)
        <tr>
            <td>{{ $spec->template->name }}</td>
            <td>{{ $spec->value }}</td>

            <td>
                <div class="d-flex gap-1">

                    <!-- UP -->
                    <form method="POST"
                        action="{{ route('admin.products.specifications.up', [$product->id, $spec->id]) }}">
                        @csrf
                        <button class="btn btn-sm btn-secondary">↑</button>
                    </form>

                    <!-- DOWN -->
                    <form method="POST"
                        action="{{ route('admin.products.specifications.down', [$product->id, $spec->id]) }}">
                        @csrf
                        <button class="btn btn-sm btn-secondary">↓</button>
                    </form>

                </div>
            </td>

            <td>
                <form method="POST"
                    action="{{ route('admin.products.specifications.destroy', [$product->id, $spec->id]) }}">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection