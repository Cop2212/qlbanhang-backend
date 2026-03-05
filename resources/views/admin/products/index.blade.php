@extends('admin.layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Quản lý sản phẩm</h3>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        ➕ Thêm sản phẩm
    </a>
</div>

<div class="card shadow-sm p-4">

    <!-- Bộ lọc + Search -->
    <div class="row mb-3">
        <div class="col-md-4">
            <input type="text" class="form-control" placeholder="Tìm kiếm sản phẩm...">
        </div>

        <div class="col-md-3">
            <select name="category_id" class="form-select">
                <option value="">-- Danh mục --</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}">
                    {{ $category->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <select name="brand_id" class="form-select">
                <option value="">-- Thương hiệu --</option>
                @foreach($brands as $brand)
                <option value="{{ $brand->id }}">
                    {{ $brand->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <button class="btn btn-secondary w-100">Lọc</button>
        </div>
    </div>

    <!-- Bảng sản phẩm -->
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Thương hiệu</th>
                    <th>Giá thấp nhất</th>
                    <th>Kho</th>
                    <th>Trạng thái</th>
                    <th width="150">Hành động</th>
                </tr>
            </thead>
            <tbody>

                @forelse($products as $product)
                <tr>

                    <td>{{ $product->id }}</td>

                    <td>
                        @if($product->thumbnail)
                        <img src="{{ $product->thumbnail }}" width="60">
                        @else
                        <img src="https://via.placeholder.com/60" width="60">
                        @endif
                    </td>

                    <td>
                        {{ $product->name }}
                        <br>
                        <small class="text-muted">SKU: {{ $product->sku }}</small>
                    </td>

                    <td>
                        {{ $product->category->name ?? '---' }}
                    </td>

                    <td>
                        {{ $product->brand->name ?? '---' }}
                    </td>

                    <td class="text-danger">
                        {{ number_format($product->price) }} đ
                    </td>

                    <td>
                        {{ $product->stock }}
                    </td>

                    <td>
                        @if($product->is_active)
                        <span class="badge bg-success">Hiển thị</span>
                        @else
                        <span class="badge bg-secondary">Ẩn</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('admin.products.edit',$product->id) }}"
                            class="btn btn-sm btn-warning">
                            Sửa
                        </a>

                        <form action="{{ route('admin.products.destroy',$product->id) }}"
                            method="POST"
                            style="display:inline-block">

                            @csrf
                            @method('DELETE')

                            <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Xóa sản phẩm này?')">
                                Xoá
                            </button>

                        </form>
                    </td>

                </tr>

                @empty
                <tr>
                    <td colspan="9" class="text-center">
                        Chưa có sản phẩm nào
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>
    </div>

    <!-- Phân trang -->
    <div class="mt-3">
        <nav>
            <ul class="pagination">
                <li class="page-item disabled"><a class="page-link">«</a></li>
                <li class="page-item active"><a class="page-link">1</a></li>
                <li class="page-item"><a class="page-link">2</a></li>
                <li class="page-item"><a class="page-link">»</a></li>
            </ul>
        </nav>
    </div>

</div>

@endsection