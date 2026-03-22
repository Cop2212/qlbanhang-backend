@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3>Danh sách thông số sản phẩm</h3>
        <a href="{{ route('admin.specifications.create') }}" class="btn btn-primary">+ Thêm mới</a>
    </div>

    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên thông số</th>
                    <th>Mô tả</th>
                    <th width="120">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($templates as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->description }}</td>
                    <td>
                        <a href="{{ route('admin.specifications.edit', $item->id) }}" class="btn btn-sm btn-warning">Sửa</a>

                        <form action="{{ route('admin.specifications.destroy', $item->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Xóa?')">Xóa</button>
                        </form>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $templates->links() }}
    </div>
</div>
@endsection