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
    <form method="GET" action="{{ route('admin.products.index') }}">
        <div class="row mb-3">

            <div class="col-md-4">
                <input
                    type="text"
                    name="keyword"
                    class="form-control"
                    placeholder="Tìm theo tên hoặc SKU..."
                    value="{{ request('keyword') }}">
            </div>

            <div class="col-md-3">
                <select name="category_id" class="form-select">
                    <option value="">-- Danh mục --</option>

                    @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach

                </select>
            </div>

            <div class="col-md-3">
                <select name="brand_id" class="form-select">
                    <option value="">-- Thương hiệu --</option>

                    @foreach($brands as $brand)
                    <option value="{{ $brand->id }}"
                        {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                    @endforeach

                </select>
            </div>

            <div class="col-md-2 d-flex gap-2">

                <button class="btn btn-primary w-100">
                    🔍 Tìm
                </button>

                <a href="{{ route('admin.products.index') }}"
                    class="btn btn-secondary">
                    Reset
                </a>

            </div>

            <div class="col-md-2">

                <select name="per_page"
                    class="form-select"
                    onchange="this.form.submit()">

                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>
                        10 / trang
                    </option>

                    <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>
                        20 / trang
                    </option>

                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>
                        50 / trang
                    </option>

                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>
                        100 / trang
                    </option>

                </select>

            </div>

        </div>
    </form>

    <form action="{{ route('admin.products.deleteMultiple') }}" method="POST">
        @csrf

        <button type="submit"
            id="deleteSelectedBtn"
            class="btn btn-danger mb-3"
            style="display:none"
            onclick="return confirm('Xóa các sản phẩm đã chọn?')">
            🗑 Xóa đã chọn
        </button>

        <button type="submit"
            formaction="{{ route('admin.products.updateStatusMultiple') }}"
            class="btn btn-success mb-3">
            💾 Lưu trạng thái
        </button>

        <div class="mb-3 d-flex gap-2 flex-wrap">

            <!-- trạng thái -->
            <button type="button" class="btn btn-success btn-sm" onclick="setAllStatus(1)">
                👁 Hiện tất cả
            </button>

            <button type="button" class="btn btn-secondary btn-sm" onclick="setAllStatus(0)">
                🚫 Ẩn tất cả
            </button>

            <!-- nổi bật -->
            <button type="button" class="btn btn-warning btn-sm" onclick="setAllFeatured(1)">
                ⭐ Nổi bật tất cả
            </button>

            <button type="button" class="btn btn-outline-warning btn-sm" onclick="setAllFeatured(0)">
                Bình thường tất cả
            </button>

            <!-- bán chạy -->
            <button type="button" class="btn btn-danger btn-sm" onclick="setAllBestSeller(1)">
                🔥 Bán chạy tất cả
            </button>

            <button type="button" class="btn btn-outline-danger btn-sm" onclick="setAllBestSeller(0)">
                Bình thường tất cả
            </button>

        </div>

        <!-- Bảng sản phẩm -->
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="40">
                            <input type="checkbox" id="checkAll">
                        </th>
                        <th width="60">STT</th>
                        <th>Ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Thương hiệu</th>
                        <th>Màu sắc</th>
                        <th>Giá thấp nhất</th>
                        <th>Kho</th>
                        <th style="min-width:120px">Trạng thái</th>
                        <th style="min-width:150px">⭐ Nổi bật</th>
                        <th style="min-width:150px">🔥 Bán chạy</th>
                        <th width="150">Hành động</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse($products as $product)
                    <tr>
                        <td>
                            <input type="checkbox"
                                name="ids[]"
                                value="{{ $product->id }}"
                                class="product-checkbox">
                        </td>

                        <td>
                            {{ $products->firstItem() + $loop->index }}
                        </td>

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

                        <td>
                            {{ $product->color ?? '---' }}
                        </td>

                        <td class="text-danger">
                            {{ number_format($product->price) }} đ
                        </td>

                        <td>
                            {{ $product->stock }}
                        </td>

                        <td>
                            <select name="status[{{ $product->id }}]" class="form-select form-select-sm status-select">

                                <option value="1" {{ $product->is_active ? 'selected' : '' }}>
                                    Hiển thị
                                </option>

                                <option value="0" {{ !$product->is_active ? 'selected' : '' }}>
                                    Ẩn
                                </option>

                            </select>
                        </td>

                        <td>
                            <select name="featured[{{ $product->id }}]" class="form-select form-select-sm featured-select">

                                <option value="1" {{ $product->is_featured ? 'selected' : '' }}>
                                    ⭐ Nổi bật
                                </option>

                                <option value="0" {{ !$product->is_featured ? 'selected' : '' }}>
                                    Bình thường
                                </option>

                            </select>
                        </td>

                        <td>
                            <select name="best_seller[{{ $product->id }}]" class="form-select form-select-sm best-select">

                                <option value="1" {{ $product->is_best_seller ? 'selected' : '' }}>
                                    🔥 Bán chạy
                                </option>

                                <option value="0" {{ !$product->is_best_seller ? 'selected' : '' }}>
                                    Bình thường
                                </option>

                            </select>
                        </td>

                        <td>
                            <a href="{{ route('admin.products.edit',$product->id) }}"
                                class="btn btn-sm btn-warning">
                                Sửa
                            </a>
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
    </form>

    <!-- Phân trang -->
    <div class="mt-3">
        <nav>
            <div class="mt-3">
                {{ $products->links() }}
            </div>
        </nav>
    </div>

</div>

<script>
    document.getElementById('checkAll').addEventListener('click', function() {

        let checkboxes = document.querySelectorAll('.product-checkbox');

        checkboxes.forEach(cb => {
            cb.checked = this.checked;
        });

    });

    const checkAll = document.getElementById('checkAll');
    const checkboxes = document.querySelectorAll('.product-checkbox');
    const deleteBtn = document.getElementById('deleteSelectedBtn');

    function toggleDeleteButton() {

        let checked = document.querySelectorAll('.product-checkbox:checked');

        if (checked.length > 0) {
            deleteBtn.style.display = 'inline-block';
        } else {
            deleteBtn.style.display = 'none';
        }

    }

    // tick tất cả
    checkAll.addEventListener('change', function() {

        checkboxes.forEach(cb => {
            cb.checked = this.checked;
        });

        toggleDeleteButton();

    });

    // tick từng cái
    checkboxes.forEach(cb => {

        cb.addEventListener('change', toggleDeleteButton);

    });

    function setAllStatus(value) {

        document.querySelectorAll('.status-select')
            .forEach(el => el.value = value);

    }

    function setAllFeatured(value) {

        document.querySelectorAll('.featured-select')
            .forEach(el => el.value = value);

    }

    function setAllBestSeller(value) {

        document.querySelectorAll('.best-select')
            .forEach(el => el.value = value);

    }
</script>

@endsection