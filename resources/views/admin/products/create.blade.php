@extends('admin.layouts.app')

@section('content')

<h3 class="mb-4">Thêm sản phẩm</h3>

<div class="card shadow-sm p-4">

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @csrf

        <div class="row">

            <div class="col-md-6 mb-3">
                <label>Tên sản phẩm</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>SKU</label>
                <input type="text" name="sku" class="form-control" value="{{ old('sku') }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label d-block">Danh mục</label>
                <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto; background-color: #f8f9fa;">
                    @foreach($categories as $category)
                    <div class="form-check mb-2">
                        <input class="form-check-input" 
                               type="checkbox" 
                               name="category_ids[]" 
                               value="{{ $category->id }}" 
                               id="category_{{ $category->id }}"
                               {{ in_array($category->id, old('category_ids', [])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="category_{{ $category->id }}">
                            {{ $category->name }}
                        </label>
                    </div>
                    @endforeach
                </div>
                @error('category_ids')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label>Thương hiệu</label>
                <select name="brand_id" class="form-select" required>

                    <option value="">Chọn thương hiệu</option>

                    @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                    @endforeach

                </select>
            </div>

            <div class="col-md-12 mb-3">
                <label>Mô tả ngắn</label>
                <textarea name="short_description" class="form-control">{{ old('short_description') }}</textarea>
            </div>

            <div class="col-md-12 mb-3">
                <label>Mô tả chi tiết</label>
                <textarea name="description" class="form-control" rows="5">{{ old('description') }}</textarea>
            </div>

            <div class="col-md-6 mb-3">
                <label>Ảnh đại diện</label>
                <input type="file" name="thumbnail" class="form-control" accept="image/*">
            </div>

            <div class="col-md-12 mb-3">

                <label>Ảnh chi tiết</label>

                <div class="d-flex gap-2">

                    <input type="file"
                        id="image-input"
                        name="images[]"
                        class="form-control"
                        accept="image/*"
                        multiple
                        style="display:none">

                    <button type="button"
                        class="btn btn-success"
                        onclick="document.getElementById('image-input').click()">

                        ➕ Thêm ảnh

                    </button>

                </div>

            </div>

            <div class="row mt-3" id="preview-images"></div>

            <div class="mb-3">
                <label class="form-label">Màu sắc</label>
                <input type="text"
                    name="color"
                    class="form-control"
                    placeholder="Ví dụ: Đen, Trắng, Đen - Vàng"
                    value="{{ old('color') }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Giá</label>
                <input type="number" name="price" class="form-control" value="{{ old('price') }}" required>
            </div>

            <div class="col-md-4 mb-3">
                <label>Giá khuyến mãi</label>
                <input type="number" name="sale_price" class="form-control" value="{{ old('sale_price') }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Tồn kho</label>
                <input type="number" name="stock" class="form-control" value="{{ old('stock') }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Trạng thái</label>
                <select name="is_active" class="form-select">
                    <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Hiển thị</option>
                    <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Ẩn</option>
                </select>
            </div>

        </div>

        <button type="submit" class="btn btn-primary mt-3">
            Lưu sản phẩm
        </button>

    </form>

    <script>
        const imageInput = document.getElementById('image-input');
        const previewContainer = document.getElementById('preview-images');

        let fileStore = new DataTransfer();

        imageInput.addEventListener('change', function() {

            const files = this.files;

            for (let i = 0; i < files.length; i++) {

                const file = files[i];

                const index = fileStore.items.length;
                fileStore.items.add(file);

                const reader = new FileReader();

                reader.onload = function(e) {

                    const col = document.createElement('div');
                    col.classList.add('col-md-2', 'mb-3');
                    col.dataset.index = index;

                    col.innerHTML = `
                <div class="position-relative">
                    <img src="${e.target.result}" class="img-fluid rounded border">
                    <button type="button"
                        class="btn btn-danger btn-sm position-absolute top-0 end-0 remove-image">
                        ✕
                    </button>
                </div>
            `;

                    previewContainer.appendChild(col);

                    col.querySelector('.remove-image').onclick = function() {
                        fileStore.items.remove(col.dataset.index);
                        imageInput.files = fileStore.files;
                        col.remove();
                    };
                };

                reader.readAsDataURL(file);
            }

            imageInput.files = fileStore.files;
        });
    </script>

</div>

@endsection